<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\AppController;
use Cake\I18n\FrozenDate;
use Cake\Datasource\Exception\RecordNotFoundException;
use App\Mailer\AppMailer;
use Cake\Mailer\MailerAwareTrait;
use Cake\Core\Configure;
use Cake\Filesystem\Folder;
use Cake\Filesystem\File;
/**
 * Lessons Controller
 *
 * @property \App\Model\Table\LessonTable
 * @method \App\Model\Entity\AdminUser[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class LessonsController extends AppController
{
    public function initialize(): void
    {
        parent::initialize();

        $this->loadModel('Events');
        $this->loadModel('EventSchedules');
        $this->loadModel('EventEntries');
        $this->loadModel('Users');
        $this->loadModel('Consults');
    }

    public function index()
    {
        $csvData = [];
        $param = $this->request->getQuery();

        if ($this->request->is('post') && !empty($this->request->getData('csv_file'))) {
            $file = $this->request->getData('csv_file');

            if ($file->getClientMediaType() === 'text/csv' || str_ends_with($file->getClientFilename(), '.csv')) {
                $filename = 'uploaded_lessons.csv';
                $filePath = WWW_ROOT . 'files' . DS . $filename;

                // 保存先作成
                $folder = new Folder(WWW_ROOT . 'files', true, 0755);
                $file->moveTo($filePath);

                // CSV読み込み (Shift_JIS対応)
                $csv = new \SplFileObject($filePath, 'r');
                $csv->setFlags(\SplFileObject::READ_CSV | \SplFileObject::SKIP_EMPTY);
                $csv->setCsvControl(',');

                $headerSkipped = false;

                while (!$csv->eof()) {
                    $line = $csv->fgets();
                    if (!$line) continue;

                    // Shift_JIS → UTF-8 変換
                    $line = mb_convert_encoding($line, 'UTF-8', 'SJIS-win');
                    $row = str_getcsv($line);

                    // ヘッダー行スキップ
                    if (!$headerSkipped) {
                        $headerSkipped = true;
                        continue;
                    }

                    if (empty($row[1]) || count($row) < 6) continue;

                    // 検索フィルタ適用
                    if (!empty($param['event_date']) && strpos($row[1], $param['event_date']) === false) {
                        continue;
                    }
                    if (!empty($param['staff_id']) && strpos($row[5], $param['staff_id']) === false) {
                        continue;
                    }

                    $csvData[] = [
                        'event_date' => $row[1] ?? '',
                        'category' => '',
                        'staff' => $row[6] ?? '',
                        'user' => $row[3] ?? '',
                    ];
                }

                $this->Flash->success('CSVを正常に読み込みました。');
            } else {
                $this->Flash->error('CSVファイルをアップロードしてください。');
            }
        }

        // 講師リスト（検索用）
        $staffs = $this->Lessons->Staffs->find('list', [
            'keyField' => 'name',
            'valueField' => 'name',
            'conditions' => ['is_deleted' => 0],
        ])->toArray();

        // 区分リスト（空でも一応渡す）
        $listOptions = Configure::read('ADMINLESSONOPTIONS');

        $this->set(compact('csvData', 'param', 'staffs', 'listOptions'));
    }


    public function addConsult()
    {
        $name = $this->request->getQuery('name');   // CSVから来る: 例「山田太郎」
        $staffName = $this->request->getQuery('staff');
        $eventDate = $this->request->getQuery('event_date');
        $user = null;
        $consults = [];

        // ユーザー検索（姓+名の結合で照合）
        if (!empty($name)) {
            $user = $this->Users->find()
                ->where(['CONCAT(last_name, first_name) LIKE' => $name])
                ->first();

            if (!$user) {
                $this->Flash->error("「{$name}」に該当するユーザーが見つかりません。");
                return $this->redirect($this->referer());
            }

            // 既存のConsult一覧
            $consults = $this->Consults->find()
                ->where(['user_id' => $user->id])
                ->all()
                ->toList();

            // 初期登録が必要な場合
            if (!empty($eventDate)) {
                $alreadyExists = $this->Consults->find()
                    ->where(['user_id' => $user->id, 'date' => $eventDate])
                    ->first();

                if (!$alreadyExists) {
                    $newConsult = $this->Consults->newEmptyEntity();
                    $newConsult->user_id = $user->id;
                    $newConsult->date = new FrozenDate($eventDate);

                    if ($this->Consults->save($newConsult)) {
                        $this->Flash->success("{$eventDate} のコンサルデータを作成しました。");
                        $consults[] = $newConsult;
                    } else {
                        $this->Flash->error('初期コンサルデータの作成に失敗しました。');
                    }
                }
            }
        }

        // 講師リスト
        $staffs = $this->Users->Staffs->find('list', [
            'keyField' => 'id',
            'valueField' => 'name',
            'conditions' => ['is_deleted' => 0]
        ])->toArray();

        // 区分リスト
        $listOptions = Configure::read('ADMINLESSONOPTIONS');

        $this->set(compact('user', 'consults', 'staffs', 'listOptions', 'eventDate', 'staffName'));
    }



    public function saveConsult()
    {
        $name = $this->request->getQuery('name');
        $user = null;

        // Resolve user from name
        if (!empty($name)) {
            $user = $this->Users->find()
                ->where(['CONCAT(last_name, first_name) LIKE' => $name])
                ->first();
        }

        if (!$user) {
            $this->Flash->error(__('ユーザーが見つかりませんでした。'));
            return $this->redirect(['action' => 'index']);
        }

        if ($this->request->is(['post', 'put', 'patch'])) {
            $dataList = $this->request->getData('consults');
            $connection = $this->Consults->getConnection();
            $connection->begin();

            try {
                foreach ($dataList as $data) {
                    if (!empty($data['id'])) {
                        $consult = $this->Consults->get($data['id']);
                    } else {
                        $consult = $this->Consults->newEmptyEntity();
                        $consult->user_id = $user->id;
                        $consult->date = !empty($data['date']) ? new \Cake\I18n\FrozenDate($data['date']) : new \Cake\I18n\FrozenDate();
                    }

                    $fieldsToUpdate = [];
                    foreach (['theme', 'instruction', 'note', 'next_step', 'staff_id', 'category'] as $field) {
                        if (array_key_exists($field, $data)) {
                            $fieldsToUpdate[$field] = $data[$field];
                        }
                    }

                    $this->Consults->patchEntity($consult, $fieldsToUpdate);

                    if (!$this->Consults->save($consult)) {
                        throw new \Exception(__('保存に失敗しました（テーマ: {0}）', $data['theme'] ?? '未入力'));
                    }
                }

                $connection->commit();
                $this->Flash->success(__('全てのコンサルデータを保存しました。'));
            } catch (\Exception $e) {
                $connection->rollback();
                $this->Flash->error($e->getMessage());
            }

            return $this->redirect(['action' => 'addConsult', '?' => ['name' => $name]]);
        }

        return $this->redirect(['action' => 'index']);
    }

}
