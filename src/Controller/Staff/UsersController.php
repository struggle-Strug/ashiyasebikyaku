<?php
declare(strict_types=1);

namespace App\Controller\Staff;

use App\Controller\AppController;
use Cake\I18n\Time;
use Cake\ORM\Query;
use Cake\Core\Configure;

use App\Mailer\AppMailer;
use Cake\Mailer\MailerAwareTrait;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $users
 * @method \App\Model\Entity\user[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class UsersController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    use MailerAwareTrait;
    public function index()
    {
        $query = $this->Users->find('all')
            ->contain([
                'Messages' => function (Query $q) {
                    return $q->where([
                        'Messages.staff_id' => $this->Authentication->getIdentity()->id,
                        'Messages.sender' => '1',
                        'Messages.is_read' => 0
                    ]);
                },
                'UserPoints' => function (Query $q) {
                    return $q->order([
                        'UserPoints.created' => 'desc'
                    ]);
                },
                'DailyReports' => function (Query $q) {
                    return $q->order([
                        'DailyReports.report_date' => 'desc'
                    ]);
                },
                'WeeklyReports' => function (Query $q) {
                    return $q->order([
                        'WeeklyReports.report_date' => 'desc'
                    ]);
                }
            ])
            ->where([
                'Users.staff_id' => $this->Authentication->getIdentity()->id,
                'Users.is_deleted' => 0
            ])
            ->order([
                'Users.id' => 'desc'
            ]);

        // 検索
        $param = $this->request->getQuery();

        // 会員番号
        if (!empty($param['member_number'])) {
            $query->where(['Users.member_number LIKE' => '%' . $param['member_number'] . '%']);
        } else {
            $param['member_number'] = '';
        }

        // 会員名
        if (!empty($param['name'])) {
            $query->where([
                'OR' => [
                    'Users.last_name LIKE' => '%' . $param['name'] . '%',
                    'Users.first_name LIKE' => '%' . $param['name'] . '%',
                    'Users.last_name_kana LIKE' => '%' . $param['name'] . '%',
                    'Users.first_name_kana LIKE' => '%' . $param['name'] . '%',
                ]
            ]);
        } else {
            $param['name'] = '';
        }

        $users = $this->paginate($query);

        $this->set(compact('users', 'param'));
    }

    /**
     * Edit method
     *
     * @param string|null $id user id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($userId = null)
    {
        // ユーザー情報の確認
        $this->loadModel('Staffs');
        $this->loadModel('StaffWeeklyMessages');
        $user = $this->Users->find('all')
            ->where([
                'Users.id' => $userId,
                'Users.staff_id' => $this->Authentication->getIdentity()->id,
                'Users.is_deleted' => 0
            ])
            ->first();
        $staff = $this->Staffs->find('all')
            ->where([
                'id' => $this->Authentication->getIdentity()->id,
            ])
            ->first();

        if (!$user) {
            return $this->redirect(['action' => 'index']);
        }

        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();
            $user = $this->Users->patchEntity($user, $data);

            $staffMessage = $this->StaffWeeklyMessages->newEmptyEntity();
            $staffMessage->user_id = $userId;
            $staffMessage->name = $user->last_name . $user->first_name;
            $staffMessage->staff_id = $this->Authentication->getIdentity()->id;
            $staffMessage->staff_name = $staff->name;
            $staffMessage->staff_week_message = $data["staff_week_message"];

            $staffMessage = $this->StaffWeeklyMessages->patchEntity($staffMessage, $data);
            if ($this->StaffWeeklyMessages->save($staffMessage)) {
                $this->Flash->success(__('今週のワーク履歴に追加しました。'));
            } else {
                $this->Flash->error(__('今週のワーク履歴にデータを登録できませんでした。'));
            }

            if ($this->Users->save($user)) {
                $this->Flash->success(__('更新しました。'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('データを登録できませんでした。入力内容を見直してください。'));
        }

        $param = $this->request->getQuery();

        $this->set(compact('user', 'param'));
    }

    /**
     * Point method
     *
     * @param string|null $id user id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function point($userId = null)
    {
        // ユーザー情報の確認
        $user = $this->Users->find('all')
            ->contain([
                'UserPoints' => function (Query $q) {
                    return $q->order([
                        'UserPoints.created' => 'desc'
                    ]);
                },
            ])
            ->where([
                'Users.id' => $userId,
                'Users.staff_id' => $this->Authentication->getIdentity()->id,
                'Users.is_deleted' => 0
            ])
            ->first();

        if (!$user) {
            return $this->redirect(['action' => 'index']);
        }

        $param = $this->request->getQuery();

        $this->set(compact('user', 'param'));
    }

    /**
     * PointAdd method
     *
     * @param string|null $id user id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function pointAdd($userId = null)
    {
        // ユーザー情報の確認
        $user = $this->Users->find('all')
            ->contain([
                'UserPoints' => function (Query $q) {
                    return $q->order([
                        'UserPoints.created' => 'desc'
                    ]);
                },
            ])
            ->where([
                'Users.id' => $userId,
                'Users.staff_id' => $this->Authentication->getIdentity()->id,
                'Users.is_deleted' => 0
            ])
            ->first();

        if (!$user) {
            return $this->redirect(['action' => 'index']);
        }

        $userPoint = $this->Users->UserPoints->newEmptyEntity();
        if ($this->request->is('post')) {
            $data = $this->request->getData();
            $data['user_id'] = $userId;

            $userPoint = $this->Users->UserPoints->patchEntity($userPoint, $data);
            if ($this->Users->UserPoints->save($userPoint)) {
                $this->Flash->success(__('登録しました。'));

                return $this->redirect(['action' => 'point', $userId]);
            }
            $this->Flash->error(__('データを登録できませんでした。入力内容を見直してください。'));
        }

        $param = $this->request->getQuery();

        $this->set(compact('user', 'userPoint', 'param'));
    }

    /**
     * PointEdit method
     *
     * @param string|null $id user id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function pointEdit($userId = null, $id = null)
    {
        // ユーザー情報の確認
        $user = $this->Users->find('all')
            ->contain([
                'UserPoints' => function (Query $q) {
                    return $q->order([
                        'UserPoints.created' => 'desc'
                    ]);
                },
            ])
            ->where([
                'Users.id' => $userId,
                'Users.staff_id' => $this->Authentication->getIdentity()->id,
                'Users.is_deleted' => 0
            ])
            ->first();

        if (!$user) {
            return $this->redirect(['action' => 'index']);
        }

        // ポイントを取得
        $userPoint = $this->Users->UserPoints->find('all')
            ->where([
                'user_id' => $userId,
                'id' => $id
            ])
            ->first();

        if (!$userPoint) {
            return $this->redirect(['action' => 'point', $userId]);
        }

        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();
            $userPoint = $this->Users->UserPoints->patchEntity($userPoint, $data);
            if ($this->Users->UserPoints->save($userPoint)) {
                $this->Flash->success(__('登録しました。'));

                return $this->redirect(['action' => 'point', $userId]);
            }
            $this->Flash->error(__('データを登録できませんでした。入力内容を見直してください。'));
        }

        $param = $this->request->getQuery();

        $this->set(compact('user', 'userPoint', 'param'));

        $this->render('point_add');
    }

    /**
     * PointDelete method
     *
     * @param string|null $id user id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function pointDelete($id = null)
    {
        // ユーザー情報の確認
        $user = $this->Users->find('all')
            ->contain([
                'UserPoints' => function (Query $q) {
                    return $q->order([
                        'UserPoints.created' => 'desc'
                    ]);
                },
            ])
            ->where([
                'Users.id' => $userId,
                'Users.staff_id' => $this->Authentication->getIdentity()->id,
                'Users.is_deleted' => 0
            ])
            ->first();

        if (!$user) {
            return $this->redirect(['action' => 'index']);
        }

        // ポイントを取得
        $userPoint = $this->Users->UserPoints->find('all')
            ->where([
                'user_id' => $userId,
                'id' => $id
            ])
            ->first();

        if (!$userPoint) {
            return $this->redirect(['action' => 'point', $userId]);
        }

        if ($this->Users->UserPoints->delete($userPoint)) {
            $this->Flash->success(__('削除しました。'));
        } else {
            $this->Flash->error(__('エラーが発生しました。'));
        }

        return $this->redirect(['action' => 'point', $userId]);
    }

    /**
     * Message method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function message($userId = null)
    {
        // ユーザー情報の確認
        $user = $this->Users->find('all')
            ->where([
                'Users.id' => $userId,
                'Users.staff_id' => $this->Authentication->getIdentity()->id,
                'Users.is_deleted' => 0
            ])
            ->first();

        if (!$user) {
            return $this->redirect(['action' => 'index']);
        }

        // 既読にする
        $this->Users->Messages->updateAll(
            [
                'is_read' => 1
            ],
            [
                'staff_id' => $this->Authentication->getIdentity()->id,
                'user_id' => $user->id,
                'sender' => '1',
                'is_read' => 0
            ]
        );

        // メッセージを取得
        $messages = $this->Users->Messages->find('all')
            ->contain([
                'Users',
                'Staffs'
            ])
            ->where([
                'Messages.staff_id' => $this->Authentication->getIdentity()->id,
                'Messages.user_id' => $user->id,
                'Messages.is_deleted' => 0
            ])
            ->all();

        $param = $this->request->getQuery();

        $this->set(compact('messages', 'user', 'param'));
    }


    /**
     * messageSend method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function messageSend()
    {
        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();

            $user = $this->Users->find('all')
                ->where([
                    'Users.id' => $data['user_id'],
                    'Users.staff_id' => $this->Authentication->getIdentity()->id,
                    'Users.is_deleted' => 0
                ])
                ->first();

            $data['staff_id'] = $this->Authentication->getIdentity()->id;
            $data['sender'] = '2'; // 講師

            $host = $this->request->getUri()->getScheme() . '://' . $this->request->getUri()->getHost();
            $path = '/mypage/messages';
            $adminPath = '/admin/users/message/';
            $port = $this->request->getUri()->getPort();
            $fullUrl = $host . ($port ? ":$port" : '') . $path;
            $adminFullUrl = $host . ($port ? ":$port" : '') . $adminPath  . $data['user_id'];

            $message = $this->Users->Messages->newEmptyEntity();
            $message = $this->Users->Messages->patchEntity($message, $data);

            $now = Time::now();
            $formattedDateTime = $now->i18nFormat('yyyy年MM月dd日 HH時mm分ss秒');
            if ($this->Users->Messages->save($message)) {
                $this->Flash->success(__('メッセージを送信しました。'));
                $this->getMailer('App')->send('sendGridEmail', ["【コミュニケーションツール】脚やせ美脚リバースエイジングプログラム", $user->email, "messages", ["name" => $this->Authentication->getIdentity()->name, "url" => $fullUrl , 'today' => $formattedDateTime]]);
                $this->getMailer('App')->send('sendGridEmail', ["【コミュニケーションツール】脚やせ美脚リバースエイジングプログラム", "ysonoda@someonelikeyou.co.jp", "messages", ["name" => $this->Authentication->getIdentity()->name, "url" => $adminFullUrl , 'today' => $formattedDateTime]]);
                // $this->getMailer('App')->send('sendGridEmail', ["【コミュニケーションツール】脚やせ美脚リバースエイジングプログラム", "ysonoda@someonelikeyou.co.jp", "messages", ["name" => $this->Authentication->getIdentity()->name, "url" => $adminFullUrl , 'today' => $formattedDateTime]]);
                return $this->redirect(['action' => 'message', $data['user_id']]);
            }
            $this->Flash->error(__('データを送信できませんでした。'));
        }
    }
}
