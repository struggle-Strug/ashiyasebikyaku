<?php
declare(strict_types=1);

namespace App\Controller\Mypage;

use App\Controller\AppController;
use Cake\I18n\Time;
use Cake\ORM\Query;
use Cake\Core\Configure;

use App\Mailer\AppMailer;
use Cake\Mailer\MailerAwareTrait;
use App\Model\Table\UsersTable;


/**
 * Events Controller
 *
 * @property \App\Model\Table\EventsTable $events
 * @method \App\Model\Entity\event[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class EventsController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */

    use MailerAwareTrait;

    public function initialize(): void
    {
        parent::initialize();
        $this->loadModel('Users'); // ユーザーテーブルを読み込む
    }


    public function index()
    {
        $query = $this->Events->find('all')
            ->contain([
                'Staffs',
                'EventSchedules' => ['sort' => ['EventSchedules.event_date' => 'asc', 'EventSchedules.start_time' => 'asc', 'EventSchedules.end_time' => 'asc']]
            ])
            ->where([
                'Events.start_datetime <=' => date('Y-m-d H:i:s'),
                'AND' => [
                    'OR' => [
                        ['Events.end_datetime Is' => null],
                        ['Events.end_datetime >=' => date('Y-m-d H:i:s')]
                    ]
                ],
                'AND' => [
                    'OR' => [
                        ['Events.staff_id Is' => null],
                        ['Events.staff_id' => $this->Authentication->getIdentity()->staff_id]
                    ]
                ],
                'Events.is_deleted' => 0
            ]);
        $user = $this->Users->find('all')
        ->where([
            'id' => $this->Authentication->getIdentity()->id,
            'is_deleted' => 0
        ])
        ->first();

        if ($this->Authentication->getIdentity()->light_plan) {
            $currentDate = Time::now();
            $interval = '45 days';
            $pastDate = Time::parse('-' . $interval);

            if ($this->Authentication->getIdentity()->created < $pastDate) {
                if ($user->is_light_plan_first_event) {
                    $query->where([
                        'Events.type !=' => 2,
                    ]);
                } else {
                    $query->where([
                        'OR' => [
                            ['Events.type !=' => 2],
                            [
                                'Events.type' => 2,
                                'Events.staff_id' =>  $this->Authentication->getIdentity()->staff_id
                            ]
                        ]
                    ]);
                }
            } else {
                $query->where([
                    'Events.type !=' => 2,
                ]);
            }
        }


        $events = $this->paginate($query);

        $eventType = Configure::read("EVENT_TYPE");

        $this->set(compact('events', 'eventType'));
    }

    /**
     * History method
     *
     * @param string|null $id event id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function history()
    {
        $query = $this->Events->EventEntries->find('all')
            ->contain([
                'Events',
                'EventSchedules'
            ])
            ->where([
                'EventEntries.user_id' => $this->Authentication->getIdentity()->id
            ])
            ->order([
                'EventSchedules.event_date' => 'asc'
            ]);

        $eventEntries = $this->paginate($query);

        // イベントタイプ
        $eventType = Configure::read("EVENT_TYPE");

        $this->set(compact('eventEntries', 'eventType'));
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
                'EventSchedules' => ['sort' => ['EventSchedules.event_date' => 'asc', 'EventSchedules.start_time' => 'asc', 'EventSchedules.end_time' => 'asc']]
            ])
            ->where([
                'Events.id' => $id,
                'Events.start_datetime <=' => date('Y-m-d H:i:s'),
                'AND' => [
                    'OR' => [
                        ['Events.end_datetime Is' => null],
                        ['Events.end_datetime >=' => date('Y-m-d H:i:s')]
                    ]
                ],
                'AND' => [
                    'OR' => [
                        ['Events.staff_id Is' => null],
                        ['Events.staff_id' => $this->Authentication->getIdentity()->staff_id]
                    ]
                ],
                'Events.is_deleted' => 0
            ])
            ->first();

        if (!$event) {
            $this->Flash->error(__('選択されたイベントは応募期間外か終了しました。'));
            return $this->redirect(['action' => 'index']);
        }

        // イベントタイプ
        $eventType = Configure::read("EVENT_TYPE");

        $this->set(compact('event', 'eventType'));
    }

    /**
     * Entry method
     *
     * @param string|null $id event id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */

    public function entry($id = null, $eventScheduleId = null)
    {
        $event = $this->Events->find('all')
            ->contain([
                'Staffs',
                'EventSchedules.EventEntries.Users'
            ])
            ->where([
                'Events.id' => $id,
                'Events.start_datetime <=' => date('Y-m-d H:i:s'),
                'AND' => [
                    'OR' => [
                        ['Events.end_datetime Is' => null],
                        ['Events.end_datetime >=' => date('Y-m-d H:i:s')]
                    ]
                ],
                'AND' => [
                    'OR' => [
                        ['Events.staff_id Is' => null],
                        ['Events.staff_id' => $this->Authentication->getIdentity()->staff_id]
                    ]
                ],
                'Events.is_deleted' => 0
            ])
            ->first();

        $types = [
            3 => 'グループレッスン',
            2 => '個別コンサル',
        ];

        if (!$event) {
            return $this->redirect(['action' => 'index']);
        }

        if ($event->type === "2") {
            if ($this->Authentication->getIdentity()->light_plan) {
                $user = $this->Users->find('all')
                    ->where([
                        'id' => $this->Authentication->getIdentity()->id,
                        'is_deleted' => 0
                    ])
                    ->first();
                $data = [
                    "is_light_plan_first_event" => true
                ];
                $user = $this->Users->patchEntity($user, $data);

                if ($this->Users->save($user)) {
                    $this->Flash->success(__('ライトプラン用の個別コンサルを応募しました。'));
                }
            }
        }

        // 定員チェック
        $isLimit = true;
        $eventScheduleDate = "";
        $start_time = "";
        $end_time = "";
        foreach ($event->event_schedules as $eventSchedule) {
            if ($eventSchedule->id == $eventScheduleId) {
                if ($eventSchedule->capacity > count($eventSchedule->event_entries)) {
                    $isLimit = false;
                    $eventScheduleDate = $eventSchedule->event_date;
                    $start_time = $eventSchedule->start_time;
                    $end_time = $eventSchedule->end_time;
                    break;
                }
            }
        }

        if ($isLimit) {
            $this->Flash->error(__('該当のスケジュールは既に定員に達しています。'));
            return $this->redirect(['action' => 'view', $event->id]);
        }

        // 応募
        $data = [
            'event_id' => $id,
            'event_schedule_id' => $eventScheduleId,
            'user_id' => $this->Authentication->getIdentity()->id,
        ];

        $eventEntry = $this->Events->EventEntries->newEmptyEntity();
        $eventEntry = $this->Events->EventEntries->patchEntity($eventEntry, $data);

        $now = Time::now();
        $formattedDateTime = $now->i18nFormat('yyyy年MM月dd日 HH時mm分ss秒');

        if ($this->Events->EventEntries->save($eventEntry)) {
            $this->getMailer('App')->send('sendGridEmail', ["【{$types[$event->type]}予約】脚やせ美脚リバースエイジングプログラム", $this->Authentication->getIdentity()->email, "events", ["type" => $types[$event->type], "datetime" => "{$eventScheduleDate} {$start_time} - {$end_time}", 'today' => $formattedDateTime]]);
            $this->getMailer('App')->send('sendGridEmail', ["【{$types[$event->type]}予約】脚やせ美脚リバースエイジングプログラム", "ysonoda@someonelikeyou.co.jp", "events_operation", ["type" => $types[$event->type], "datetime" => "{$eventScheduleDate} {$start_time} - {$end_time}", "name" => $this->Authentication->getIdentity()->last_name . $this->Authentication->getIdentity()->first_name, 'today' => $formattedDateTime]]);
            $this->getMailer('App')->send('sendGridEmail', ["【{$types[$event->type]}予約】脚やせ美脚リバースエイジングプログラム", "shocola.stayfit@gmail.com", "events_operation", ["type" => $types[$event->type], "datetime" => "{$eventScheduleDate} {$start_time} - {$end_time}", "name" => $this->Authentication->getIdentity()->last_name . $this->Authentication->getIdentity()->first_name, 'today' => $formattedDateTime]]);
            $this->Flash->success(__('応募しました。'));
        } else {
            $this->Flash->error(__('データを送信できませんでした。再度応募しなおしてください。'));
        }

        return $this->redirect(['action' => 'view', $event->id]);
    }

    /**
     * Cancel method
     *
     * @param string|null $id event id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function cancel($id = null)
    {
        $eventEntry = $this->Events->EventEntries->find('all')
            ->contain([
                'EventSchedules',
                'Events'
            ])
            ->where([
                'EventEntries.user_id' => $this->Authentication->getIdentity()->id,
                'EventEntries.id' => $id
            ])
            ->first();

        if (!$eventEntry) {
            return $this->redirect(['action' => 'history']);
        }

        if ($eventEntry->event->type === "2") {
            if ($this->Authentication->getIdentity()->light_plan) {
                $user = $this->Users->find('all')
                    ->where([
                        'id' => $this->Authentication->getIdentity()->id,
                        'is_deleted' => 0
                    ])
                    ->first();
                $data = [
                    "is_light_plan_first_event" => false
                ];
                $user = $this->Users->patchEntity($user, $data);

                if ($this->Users->save($user)) {
                    $this->Flash->success(__('ライトプラン用の個別コンサルをキャンセルしました。'));
                }
            }
        }

        $types = [
            3 => 'グループレッスン',
            2 => '個別コンサル',
        ];

        if ($eventEntry->event_schedule->cancel_datetime->format('YmdHi') < date('YmdHi')) {
            $this->Flash->error(__('キャンセル可能な時間を過ぎています。'));
            return $this->redirect(['action' => 'history']);
        }

        $now = Time::now();
        $formattedDateTime = $now->i18nFormat('yyyy年MM月dd日 HH時mm分ss秒');

        if ($this->Events->EventEntries->delete($eventEntry)) {
            $this->getMailer('App')->send('sendGridEmail', ["【{$types[$eventEntry->event->type]}キャンセル】脚やせ美脚リバースエイジングプログラム", $this->Authentication->getIdentity()->email, "events_cancel", ["type" => $types[$eventEntry->event->type], "datetime" => "{$eventEntry->event_schedule->event_date} {$eventEntry->event_schedule->start_time}", 'today' => $formattedDateTime]]);
            $this->getMailer('App')->send('sendGridEmail', ["【{$types[$eventEntry->event->type]}キャンセル】脚やせ美脚リバースエイジングプログラム", "ysonoda@someonelikeyou.co.jp", "events_cancel_operation", ["type" => $types[$eventEntry->event->type], "datetime" => "{$eventEntry->event_schedule->event_date} {$eventEntry->event_schedule->start_time}", "name" => $this->Authentication->getIdentity()->last_name . $this->Authentication->getIdentity()->fist_name, 'today' => $formattedDateTime]]);
            $this->getMailer('App')->send('sendGridEmail', ["【{$types[$eventEntry->event->type]}キャンセル】脚やせ美脚リバースエイジングプログラム", "shocola.stayfit@gmail.com", "events_cancel_operation", ["type" => $types[$eventEntry->event->type], "datetime" => "{$eventEntry->event_schedule->event_date} {$eventEntry->event_schedule->start_time}", "name" => $this->Authentication->getIdentity()->last_name . $this->Authentication->getIdentity()->fist_name, 'today' => $formattedDateTime]]);
            $this->Flash->success(__('キャンセルしました。'));
        } else {
            $this->Flash->error(__('エラーが発生しました。'));
        }

        return $this->redirect(['action' => 'history']);
    }
}
