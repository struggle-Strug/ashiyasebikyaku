<?php
declare(strict_types=1);

namespace App\Controller\Staff;

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
    use MailerAwareTrait;

    public function initialize(): void
    {
        parent::initialize();

        $this->loadModel('Events');
        $this->loadModel('EventSchedules');
        $this->loadModel('EventEntries');
        $this->loadModel('Users');
        $this->loadModel('Consults');
        $this->loadModel('Staffs');
    }

    public function index()
    {
        $csvData = [];
        $csvErrors = [];
        $param = $this->request->getQuery();

        // CSV upload logic remains the same
        if ($this->request->is('post') && !empty($this->request->getData('csv_file'))) {
            $file = $this->request->getData('csv_file');
            $filename = $file->getClientFilename();
            $mediaType = $file->getClientMediaType();

            if (
                preg_match('/\.csv$/i', $filename) ||
                in_array($mediaType, ['text/csv', 'application/vnd.ms-excel', 'application/octet-stream'])
            ) {
                $savedName = 'uploaded_lessons.csv';
                $filePath = WWW_ROOT . 'files' . DS . $savedName;

                $folder = new Folder(WWW_ROOT . 'files', true, 0755);
                $file->moveTo($filePath);

                $csv = new \SplFileObject($filePath, 'r');
                $csv->setFlags(\SplFileObject::READ_CSV | \SplFileObject::SKIP_EMPTY);
                $csv->setCsvControl(',');

                $allUsers = $this->Lessons->Users->find()
                    ->select(['Users.id', 'Users.last_name', 'Users.first_name', 'Users.staff_id'])
                    ->contain([
                        'Staffs' => function ($q) {
                            return $q->select(['Staffs.id', 'Staffs.name']);
                        }
                    ])
                    ->where(['Users.is_deleted' => 0])
                    ->all();

                $userMap = [];
                foreach ($allUsers as $user) {
                    $fullName = preg_replace('/\s+/u', '', $user->last_name . $user->first_name);
                    $userMap[$fullName][] = $user;
                }

                $allStaffs = $this->Lessons->Staffs->find()
                    ->select(['id', 'name'])
                    ->where(['is_deleted' => 0])
                    ->all();

                $staffMap = [];
                foreach ($allStaffs as $staff) {
                    $staffMap[trim($staff->name) . '先生'] = $staff->id;
                }

                $headerSkipped = false;
                $tempData = [];
                $rowNum = 1;
                $sameNameCounters = [];
                
                while (!$csv->eof()) {
                    $line = $csv->fgets();
                    $rowNum++;
                    if (!$line) continue;

                    $line = mb_convert_encoding($line, 'UTF-8', 'SJIS-win');
                    $row = str_getcsv($line);

                    if (!$headerSkipped) {
                        $headerSkipped = true;
                        continue;
                    }

                    // if (empty($row[1]) || count($row) < 16) {
                    //     $this->Flash->error('ファイルが空または不正確です。');
                    //     return $this->redirect($this->referer());
                    // }

                    $eventDate = trim($row[1] ?? '');
                    $userName = trim($row[3] ?? '');
                    $staffName = trim($row[6] ?? '');
                    $category = isset($row[15]) ? trim($row[15]) : '';

                    if ($userName === '') {
                        $csvErrors[] = "行" . ($rowNum - 1) . ": 名前が空欄です。";
                    }
                    if ($category === '') {
                        $csvErrors[] = "行" . ($rowNum - 1) . ": 区分が空欄です。";
                    }

                    $normalizedUserName = preg_replace('/\s+/u', '', $userName);
                    $userId = null;
                    $validStaff = false;
                    $staffId = $staffMap[$staffName] ?? null;

                    if (!isset($userMap[$normalizedUserName])) {
                        $csvErrors[] = "行" . ($rowNum - 1) . ": ユーザー「{$userName}」はデータが存在しません。";
                    } else {
                        $matchedUsers = $userMap[$normalizedUserName];
                        foreach ($matchedUsers as $u) {
                            if (!empty($u->staff_id) &&
                                !empty($u->staff) &&
                                $u->staff->name . "先生" === $staffName) {
                                $validStaff = true;
                                $userId = $u->id;
                                break;
                            }
                        }

                        if (!$validStaff) {
                            $csvErrors[] = "行" . ($rowNum - 1) . ": 講師「{$staffName}」はユーザー「{$userName}」の担当講師ではありません。";
                        }
                    }

                    $normalizedDate = null;
                    if (!empty($eventDate)) {
                        $normalizedDate = FrozenDate::parseDate(str_replace('/', '-', $eventDate), 'yyyy-MM-dd');
                    }

                    $displayName = $userName;
                    if (isset($userMap[$normalizedUserName]) && count($userMap[$normalizedUserName]) > 1) {
                        $sameNameCounters[$normalizedUserName] = ($sameNameCounters[$normalizedUserName] ?? 0) + 1;
                        $displayName .= $sameNameCounters[$normalizedUserName];
                    }

                    $categoryMap = [
                        "実践レッスン" => 1,
                        "脳バグレッスン" => 2,
                        "個別コンサル" => 3,
                    ];
                    
                    $mappedCategory = $categoryMap[$category] ?? null; 

                    $tempData[] = [
                        'user_id' => $userId,
                        'start_date' => $normalizedDate,
                        'category' => $mappedCategory,
                        'staff_id' => $staffId,
                        'applicant_name' => $displayName,
                    ];
                }

                if (empty($csvErrors)) {
                    $connection = $this->Lessons->getConnection();
                    $connection->begin();
                    try {
                        foreach ($tempData as $data) {
                            if (empty($data['user_id']) || empty($data['staff_id'])) {
                                continue;
                            }
                            $lesson = $this->Lessons->newEntity($data);
                            $this->Lessons->saveOrFail($lesson);
                        }
                        $connection->commit();
                        $csvData = $tempData;
                        $this->Flash->success(sprintf(
                            'CSVを正常に読み込み、新しいレッスンデータを%d件登録しました。',
                            count($csvData)
                        ));
                    } catch (\Exception $e) {
                        $connection->rollback();
                        $this->Flash->error('データ保存中にエラーが発生しました: ' . $e->getMessage());
                    }
                }
            } else {
                $this->Flash->error('CSVファイルをアップロードしてください。');
            }
        }

        $staffs = $this->Lessons->Staffs->find('list', [
            'keyField' => 'id',
            'valueField' => 'name',
            'conditions' => ['is_deleted' => 0],
        ])->toArray();

        $listOptions = Configure::read('ADMINLESSONOPTIONS');

        $conditions = [];

        if (!empty($param['event_date'])) {
            $conditions['Lessons.start_date'] = FrozenDate::parseDate($param['event_date'], 'yyyy-MM-dd');
        }
        if (!empty($param['category'])) {
            $conditions['Lessons.category'] = $param['category'];
        }
        if (!empty($param['staff_id'])) {
            $conditions['Lessons.staff_id'] = $param['staff_id'];
        }

        $query = $this->Lessons->find('all')
            ->contain(['Users', 'Staffs'])
            ->where($conditions)
            ->order(['Lessons.start_date' => 'desc']);

        $this->paginate = [
            'limit' => 20,
            'order' => ['Lessons.start_date' => 'desc'],
        ];

        $lessons = $this->paginate($query);

        $this->set(compact('csvData', 'csvErrors', 'lessons', 'param', 'staffs', 'listOptions'));
    }

    private function sendEmail($user)
    {
        // 固定のメールアドレスに送信
        $this->getMailer('App')->send('sendGridEmail', ["【レッスン管理】美脚セルフデザインプログラム", "ysonoda@someonelikeyou.co.jp", "consults", ["user" => $user]]);
        $this->getMailer('App')->send('sendGridEmail', ["【レッスン管理】美脚セルフデザインプログラム", "ryota0246@gmail.com", "consults", ["user" => $user]]);
        
        // すべてのスタッフにメールを送信（メールアドレスが設定されている場合のみ）
        $allStaffs = $this->Staffs->find('all')
            ->where([
                'is_deleted' => 0,
                'email IS NOT' => null,
                'email !=' => ''
            ])
            ->all();
        
        foreach ($allStaffs as $staff) {
            if (!empty($staff->email)) {
                $this->getMailer('App')->send('sendGridEmail', ["【レッスン管理】美脚セルフデザインプログラム", $staff->email, "consults", ["user" => $user]]);
            }
        }
    }

    public function addConsult($id = null)
    {
        $name = $this->request->getQuery('name');   // CSVから来る: 例「山田太郎」
        $staffName = $this->request->getQuery('staff');
        $eventDate = $this->request->getQuery('event_date');
        $category = $this->request->getQuery('category');
        $user = null;
        $consults = [];

        // 1️⃣ 対象のレッスンがあれば is_open を true に更新
        if (!empty($id)) {
            $lesson = $this->Lessons->find()
                ->where(['id' => $id])
                ->first();

            if ($lesson) {
                $lesson->is_open = true;
                if ($this->Lessons->save($lesson)) {
                } else {
                    $this->Flash->error("レッスン ID {$id} の更新に失敗しました。");
                }
            }
        }

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
                $newConsult = $this->Consults->newEmptyEntity();
                $newConsult->user_id = $user->id;
                $newConsult->date = new FrozenDate($eventDate);
                $newConsult->category = $category;

                if ($this->Consults->save($newConsult)) {
                    $this->Flash->success("{$eventDate} のコンサルデータを作成しました。");
                    $this->sendEmail($user);
                    $consults[] = $newConsult;
                } else {
                    $this->Flash->error('初期コンサルデータの作成に失敗しました。');
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

        $param = $this->request->getQuery();

        $this->set(compact('user', 'consults', 'staffs', 'listOptions', 'eventDate', 'staffName', 'param'));
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
