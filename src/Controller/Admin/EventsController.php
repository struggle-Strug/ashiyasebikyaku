<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\AppController;
use Cake\I18n\Time;
use Cake\ORM\Query;
use Cake\Core\Configure;

/**
 * Events Controller
 *
 * @property \App\Model\Table\EventsTable $events
 * @method \App\Model\Entity\event[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class EventsController extends AppController
{
    public function beforeFilter(\Cake\Event\EventInterface $event)
    {
        parent::beforeFilter($event);

        $user = $this->Authentication->getIdentity();
        if($user->role_id == 1) {
            $this->Flash->error('このページへのアクセス権がありません。');
            return $this->redirect(['controller' => 'UploadMovies', 'action' => 'index']);
        }
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $query = $this->Events->find('all')
        ->contain([
            'Staffs',
            'EventSchedules'
        ])
        ->where([
            'Events.is_deleted' => 0
        ])
        ->order([
            'Events.start_datetime' => 'desc'
        ]);
        $events = $this->paginate($query);

        // イベントタイプ
        $eventType = Configure::read("EVENT_TYPE");

        $this->set(compact('events', 'eventType'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $event = $this->Events->newEmptyEntity();
        if ($this->request->is('post')) {
            $data = $this->request->getData();

            $error = false;
            if (empty($data['event_date'])) {
                $error = true;
                $this->Flash->error(__('スケジュールを登録してください。'));
            }

            foreach ($data['event_date'] as $key => $value) {
                $dataSchedule[] = [
                    'event_date' => $data['event_date'][$key],
                    'start_time' => $data['start_time'][$key],
                    'end_time' => $data['end_time'][$key],
                    'capacity' => $data['capacity'][$key],
                    'cancel_datetime' => $data['cancel_datetime'][$key]
                ];
            }
            $data['event_schedules'] = $dataSchedule;

            if (!$error) {
                $event = $this->Events->patchEntity($event, $data);

                if ($this->Events->save($event)) {
                    $this->Flash->success(__('登録しました。'));

                    return $this->redirect(['action' => 'index']);
                }
                $this->Flash->error(__('データを登録できませんでした。入力内容を見直してください。'));
            }
        }

        // スタッフを配列で取得
        $staffs = $this->Events->Staffs->find('list', [
            'keyField' => 'id',
            'valueField' => 'name',
            'conditions' => [
                'is_deleted' => 0
            ]
        ])->toArray();

        // イベントタイプ
        $eventType = Configure::read("EVENT_TYPE");

        $this->set(compact('event', 'staffs', 'eventType'));
    }

    /**
     * View method
     *
     * @param string|null $id event id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $event = $this->Events->find('all')
        ->contain([
            'Staffs',
            'EventSchedules.EventEntries.Users',
            'EventSchedules' =>['sort' => ['EventSchedules.event_date' => 'asc', 'EventSchedules.start_time' => 'asc', 'EventSchedules.end_time' => 'asc']]
        ])
        ->where([
            'Events.id' => $id,
            'Events.is_deleted' => 0
        ])
        ->first();

        if (!$event) {
            return $this->redirect(['action' => 'index']);
        }

        // イベントタイプ
        $eventType = Configure::read("EVENT_TYPE");

        $this->set(compact('event', 'eventType'));
    }

    /**
     * Edit method
     *
     * @param string|null $id event id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $event = $this->Events->find('all')
        ->contain([
            'EventSchedules' =>['sort' => ['EventSchedules.event_date' => 'asc', 'EventSchedules.start_time' => 'asc', 'EventSchedules.end_time' => 'asc']]
        ])
        ->where([
            'Events.id' => $id,
            'Events.is_deleted' => 0
        ])
        ->first();

        if (!$event) {
            return $this->redirect(['action' => 'index']);
        }

        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();

            $error = false;
            if (empty($data['event_date'])) {
                $error = true;
                $this->Flash->error(__('スケジュールを登録してください。'));
            }

            foreach ($data['event_date'] as $key => $value) {
                $dataSchedule[] = [
                    'id' => empty($data['id_' . $key]) ? '' : $data['id_' . $key],
                    'event_date' => $data['event_date'][$key],
                    'start_time' => $data['start_time'][$key],
                    'end_time' => $data['end_time'][$key],
                    'capacity' => $data['capacity'][$key],
                    'cancel_datetime' => $data['cancel_datetime'][$key]
                ];
            }
            $data['event_schedules'] = $dataSchedule;

            if (!$error) {
                $event = $this->Events->patchEntity($event, $data);

                if ($this->Events->save($event)) {
                    $this->Flash->success(__('更新しました。'));

                    return $this->redirect(['action' => 'view', $event->id]);
                }
                $this->Flash->error(__('データを登録できませんでした。入力内容を見直してください。'));
            }
        }

        // スタッフを配列で取得
        $staffs = $this->Events->Staffs->find('list', [
            'keyField' => 'id',
            'valueField' => 'name',
            'conditions' => [
                'is_deleted' => 0
            ]
        ])->toArray();

        // イベントタイプ
        $eventType = Configure::read("EVENT_TYPE");

        $this->set(compact('event', 'staffs', 'eventType'));

        $this->render('add');
    }

    /**
     * Delete method
     *
     * @param string|null $id event id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $event = $this->Events->find('all')
        ->where([
            'id' => $id,
            'is_deleted' => 0
        ])
        ->first();

        if (!$event) {
            return $this->redirect(['action' => 'index']);
        }

        // 応募データを削除
        $this->Events->EventEntries->deleteAll([
            'event_id' => $id
        ], false);

        // スケジュールデータを削除
        $this->Events->EventSchedules->deleteAll([
            'event_id' => $id
        ], false);

        $event->is_deleted = 1;
        if ($this->Events->save($event)) {
            $this->Flash->success(__('削除しました。'));
        } else {
            $this->Flash->error(__('エラーが発生しました。'));
        }

        return $this->redirect(['action' => 'index']);
    }

    /**
     * DeleteSchedule method
     *
     * @param string|null $id event id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function deleteSchedule($id = null, $eventScheduleId = null)
    {
        $event = $this->Events->find('all')
        ->where([
            'id' => $id,
            'is_deleted' => 0
        ])
        ->first();

        if (!$event) {
            return $this->redirect(['action' => 'index']);
        }

        $eventSchedule = $this->Events->EventSchedules->find('all')
        ->where([
            'id' => $eventScheduleId
        ])
        ->first();

        if (!$eventSchedule) {
            return $this->redirect(['action' => 'view', $event->id]);
        }

        // 応募データを削除
        $this->Events->EventEntries->deleteAll([
            'event_schedule_id' => $eventScheduleId
        ], false);

        // スケジュールデータを削除
        if ($this->Events->EventSchedules->delete($eventSchedule)) {
            $this->Flash->success(__('削除しました。'));
        } else {
            $this->Flash->error(__('エラーが発生しました。'));
        }

        return $this->redirect(['action' => 'view', $event->id]);
    }
}
