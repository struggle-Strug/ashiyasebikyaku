<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\AppController;
use Cake\I18n\FrozenDate;
use App\Mailer\AppMailer;
use Cake\Mailer\MailerAwareTrait;
use Cake\Core\Configure;
/**
 * Lessons Controller
 *
 * @property \App\Model\Table\AdminUsersTable $AdminUsers
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
        $listOptions = Configure::read('ADMINLESSONOPTIONS');

        $query = $this->EventSchedules->find()
            ->contain([
                'Events.Staffs',
                'EventEntries.Users'
            ])
            ->where([
                'Events.is_deleted' => 0
            ])
            ->order([
                'EventSchedules.event_date' => 'desc',
                'EventSchedules.start_time' => 'desc',
                'EventSchedules.end_time' => 'desc'
            ]);

        $eventType = Configure::read("EVENT_TYPE");

        $staffs = $this->Lessons->Staffs->find('list', [
            'keyField' => 'id',
            'valueField' => 'name',
            'conditions' => ['is_deleted' => 0]
        ])->toArray();

        $eventSchedules = $this->paginate($query, [
            'limit' => 20
        ]);

        $param = $this->request->getQuery();

        $this->set(compact('listOptions', 'staffs', 'param', 'eventSchedules', 'eventType'));
    }


    public function addConsult($userId)
    {
        $user = $this->Users->get($userId);

        $consults = $this->Consults->find()
            ->where(['user_id' => $userId])
            ->all()
            ->toList();
        $eventDate = $this->request->getQuery('event_date');
        $eventType = $this->request->getQuery('event_type');

        // ✅ Check if we need to create initial consult
        if ($eventDate && $eventType) {
            $newConsult = $this->Consults->newEmptyEntity();
            $newConsult->user_id = $userId;
            $newConsult->date = new FrozenDate($eventDate);
            $newConsult->category = $eventType;

            if ($this->Consults->save($newConsult)) {
                $this->Flash->success(__('新しいコンサルデータを作成しました。'));
            } else {
                $this->Flash->error(__('初期コンサルデータの作成に失敗しました。'));
            }

            // Refresh list after creating
            $consults = $this->Consults->find()
                ->where(['user_id' => $userId])
                ->all()
                ->toList();
        }

        $staffs = $this->Users->Staffs->find('list', [
            'keyField' => 'id',
            'valueField' => 'name',
            'conditions' => ['is_deleted' => 0]
        ])->toArray();

        $this->set(compact('user', 'consults', 'staffs'));
    }



    public function saveConsult($userId = null)
    {
        $user = $this->Users->find()
            ->where(['id' => $userId, 'is_deleted' => 0])
            ->first();

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
                        $consult->user_id = $userId;
                        $consult->date = new \Cake\I18n\FrozenDate();
                        $consult->category = 2; // 個別コンサルティング固定
                    }

                    // ✅ Only update fields present in $data — don't overwrite with ''
                    $fieldsToUpdate = [];
                    foreach (['theme', 'instruction', 'note', 'next_step', 'staff_id'] as $field) {
                        if (array_key_exists($field, $data)) {
                            $fieldsToUpdate[$field] = $data[$field];
                        }
                    }

                    $consult = $this->Consults->patchEntity($consult, $fieldsToUpdate);

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

            return $this->redirect(['action' => 'addConsult', $userId]);
        }

        $param = $this->request->getQuery();
        $this->set(compact('user', 'param'));
    }
}
