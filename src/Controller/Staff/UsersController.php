<?php
declare(strict_types=1);

namespace App\Controller\Staff;

use App\Controller\AppController;
use Cake\I18n\Time;
use Cake\Database\Expression\QueryExpression;
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

    public function initialize(): void
    {
        parent::initialize();
        $this->loadModel('Staffs');
        $this->loadModel('ComparedImages');
        $this->loadModel('Habits');
        $this->loadModel('Purposes');
        $this->loadModel('BodyDatas');
        $this->loadModel('Mentions');
        $this->loadModel('SelfDatas');
        $this->loadModel('Differences');
        $this->loadModel('Consults');
    }

    public function index()
    {
        $query = $this->Users->find('all')
            ->contain([
                'Messages' => function (Query $q) {
                    return $q->where([
                        'Messages.sender IN' => ['1', '3'],
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
                },
                'ComparedImages' => function (Query $q) {
                    return $q->where(['ComparedImages.active' => false]);
                },
            ])->where([
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

        if (!empty($param['in_charge']) && $param['in_charge'] == '1') {
            $query->where([
                'Users.staff_id' => $this->Authentication->getIdentity()->id,
            ]);
        } else if (!empty($param['in_charge']) && $param['in_charge'] == '2') {
            $query->where([
                'Users.staff_id !=' => $this->Authentication->getIdentity()->id,
            ]);
        } else {
            $param['in_charge'] = '';
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

        $staff = $this->Authentication->getIdentity();

        $this->set(compact('users', 'param', 'staff'));
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
                $param = $this->request->getQuery();

                return $this->redirect(['action' => 'index', '?' => $param]);
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
    public function pointDelete($userId = null, $id = null)
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
                'Users.is_deleted' => 0
            ])
            ->first();

        if (!$user) {
            return $this->redirect(['action' => 'index']);
        }

        $staffId = (int)$this->Authentication->getIdentity()->id;
        $staffIdJson = json_encode([$staffId]);

        $connection = $this->Users->getConnection();

        $expression = new QueryExpression("is_read = JSON_ARRAY_APPEND(is_read, '$', $staffId)");

        $connection->execute(
            "UPDATE messages 
             SET is_read = JSON_ARRAY_APPEND(is_read, '$', :staffId)
             WHERE user_id = :userId
               AND sender IN ('1', '3')
               AND (is_read IS NULL OR NOT JSON_CONTAINS(is_read, :staffIdJson))",
            [
                'staffId' => $staffId,
                'userId' => $user->id,
                'staffIdJson' => $staffIdJson
            ]
        );
        

        // メッセージを取得
        $messages = $this->Users->Messages->find('all')
            ->contain([
                'Users',
                'Staffs'
            ])
            ->where([
                'Messages.user_id' => $user->id,
                'Messages.is_deleted' => 0
            ])
            ->all();

        $param = $this->request->getQuery();

        $this->set(compact('messages', 'user', 'param', 'staffId'));
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
                    'Users.is_deleted' => 0
                ])
                ->first();

            $data['staff_id'] = $this->Authentication->getIdentity()->id;
            $data['sender'] = '2'; // 講師

            // ファイルアップロード処理
            $uploadedFile = $this->request->getUploadedFile('attachment');
            if ($uploadedFile && $uploadedFile->getError() === UPLOAD_ERR_OK) {
                $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'video/mp4', 'video/mov', 'video/avi', 'video/webm', 'video/quicktime'];
                $maxFileSize = 10 * 1024 * 1024 * 1024; // 100MB

                if (!in_array($uploadedFile->getClientMediaType(), $allowedTypes)) {
                    $this->Flash->error(__('対応していないファイル形式です。'));
                    return $this->redirect(['action' => 'message', $data['user_id']]);
                }

                if ($uploadedFile->getSize() > $maxFileSize) {
                    $this->Flash->error(__('ファイルサイズが大きすぎます（最大100MB）。'));
                    return $this->redirect(['action' => 'message', $data['user_id']]);
                }

                // ファイル保存
                $uploadDir = WWW_ROOT . 'uploads' . DS . 'messages';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }

                $fileName = time() . '_' . $uploadedFile->getClientFilename();
                $filePath = $uploadDir . DS . $fileName;
                $uploadedFile->moveTo($filePath);

                $data['file_path'] = 'uploads/messages/' . $fileName;
                $data['file_name'] = $uploadedFile->getClientFilename();
                $data['file_type'] = $uploadedFile->getClientMediaType();
                $data['file_size'] = $uploadedFile->getSize();
            }

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
                $this->getMailer('App')->send('sendGridEmail', ["【コミュニケーションツール】美脚セルフデザインプログラム", $user->email, "messages", ["name" => $this->Authentication->getIdentity()->name, "url" => $fullUrl , 'today' => $formattedDateTime]]);
                $this->getMailer('App')->send('sendGridEmail', ["【コミュニケーションツール】美脚セルフデザインプログラム", "ysonoda@someonelikeyou.co.jp", "messages", ["name" => $this->Authentication->getIdentity()->name, "url" => $adminFullUrl , 'today' => $formattedDateTime]]);
                // $this->getMailer('App')->send('sendGridEmail', ["【コミュニケーションツール】美脚セルフデザインプログラム", "ysonoda@someonelikeyou.co.jp", "messages", ["name" => $this->Authentication->getIdentity()->name, "url" => $adminFullUrl , 'today' => $formattedDateTime]]);
                
                return $this->redirect(['action' => 'message', $data['user_id']]);
            }
            $this->Flash->error(__('データを送信できませんでした。'));
        }
    }

    public function comparedImages($userId = null)
    {
        $this->loadModel('ComparedImages');

        $query = $this->ComparedImages->find()
            ->where(['user_id' => $userId]);

        // ✅ Safe access: avoid undefined index warning
        $param = $this->request->getQuery();
        $active = $this->request->getQuery('active');

        if ($active === '0') {
            // Show only inactive (未送信) items
            $query->where(['active' => false]);
        }

        $query->order(['created' => 'desc']);

        $comparedImages = $query->all();

        $this->set(compact('comparedImages', 'param'));
    }


    /**
     * EditComment method
     *
     * @param string|null $id Compared Image id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function editComment($id = null)
    {
        $param = $this->request->getQuery();
        $staff = $this->Authentication->getIdentity()->id;
        $comparedImage = $this->ComparedImages->get($id, [
            'contain' => [],
        ]);
        $user = $this->Users->find('all')
        ->where([
            'Users.id' => $comparedImage->user_id,
            'Users.is_deleted' => 0
        ])
        ->first();
        if ($this->request->is(['patch', 'post', 'put'])) {
            $comparedImage = $this->ComparedImages->patchEntity($comparedImage, $this->request->getData());
            if ($this->ComparedImages->save($comparedImage)) {
                $this->Flash->success(__('コメントを更新しました。'));

                return $this->redirect(['action' => 'comparedImages', $comparedImage->user_id,  '?' => $param]);
            }
            $this->Flash->error(__('コメントを更新できませんでした。'));
        }
        $users = $this->ComparedImages->Users->find('list', ['limit' => 200])->all();
        $this->set(compact('comparedImage', 'users', 'param', 'staff', 'user'));
    }
    

    /**
     * Delete method
     *
     * @param string|null $id Compared Image id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function deleteImage($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $comparedImage = $this->ComparedImages->get($id);
        $filePath = $comparedImage->image;
        $path = parse_url($filePath, PHP_URL_PATH);
        $filename = basename($path);
        if ($this->ComparedImages->delete($comparedImage)) {
            if ($filePath) {
                unlink(WWW_ROOT . "/uploads/" . $filename);
                $this->Flash->success(__('画像が削除されました。'));
            }
            $this->Flash->success(__('比較画像が画像が削除されました。'));
        } else {
            $this->Flash->error(__('比較画像が削除されませんでした。'));
        }
        return $this->redirect(['action' => 'comparedImages', $comparedImage->user_id]);
    }

    public function setActive($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $comparedImage = $this->ComparedImages->get($id, [
            'contain' => [],
        ]);
        $data = [];
        $data["active"] = 1;
        $comparedImage = $this->ComparedImages->patchEntity($comparedImage, $data);
        if ($this->ComparedImages->save($comparedImage)) {
            $this->Flash->success(__('送信完了いたしました。'));
            $param = $this->request->getQuery();
            return $this->redirect(['action' => 'comparedImages', $comparedImage->user_id, '?' => $param]);
        }
    }

    public function setNotActive($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $comparedImage = $this->ComparedImages->get($id, [
            'contain' => [],
        ]);
        $data = [];
        $data["active"] = 0;
        $comparedImage = $this->ComparedImages->patchEntity($comparedImage, $data);
        if ($this->ComparedImages->save($comparedImage)) {
            $this->Flash->success(__('送信取り消し完了いたしました。'));
            $param = $this->request->getQuery();
            return $this->redirect(['action' => 'comparedImages', $comparedImage->user_id, '?' => $param]);
        }

    }

     /**
     * Delete method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function deleteMessage($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);

        $this->loadModel('Messages');

        $message = $this->Messages->get($id);

        if ($this->Messages->delete($message)) {
            $this->Flash->success('メッセージを削除しました。');
        } else {
            $this->Flash->error('削除に失敗しました。');
        }

        return $this->redirect(['action' => 'message', $message['user_id']]);
    }

    /**
     * 
     * @param string|null $id user id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function customerInformations($id = null)
    {
        $user = $this->Users->find('all')
            ->contain(['Staffs'])
            ->where([
                'Users.id' => $id,
                'Users.is_deleted' => 0
            ])
            ->first();

        if (!$user) {
            return $this->redirect(['action' => 'customerInformations']);
        }

        $habit = $this->Habits->find('all')
            ->where([
                'user_id' => $id,
            ])
            ->first();

        if (!$habit) {
            $habit = $this->Habits->newEntity(['user_id' => $id]);
        }

        $purpose = $this->Purposes->find('all')
            ->where([
                'user_id' => $id,
            ])
            ->first();

        if (!$purpose) {
            $purpose = $this->Purposes->newEntity(['user_id' => $id]);
        }

        $bodyDatas = $this->BodyDatas->find('all')
            ->where(['user_id' => $id])
            ->order(['date' => 'ASC'])
            ->toArray();

        $mention = $this->Mentions->find('all')
            ->where([
                'user_id' => $id,
            ])
            ->first();

        if (!$mention) {
            $mention = $this->Mentions->newEntity(['user_id' => $id]);
        }

        $consults = $this->Consults->find()
            ->where(['user_id' => $id])
            ->all()
            ->toList();

        $selfdata = $this->SelfDatas->find('all')
            ->where(['user_id' => $id])
            ->order(['date' => 'ASC'])
            ->toArray();

        $differences = $this->Differences->find('all')
            ->where(['user_id' => $id])
            ->toArray();

        // スタッフを配列で取得
        $staffs = $this->Users->Staffs->find('list', [
            'keyField' => 'id',
            'valueField' => 'name',
            'conditions' => [
                'is_deleted' => 0
            ]
        ])->toArray();

        // 都道府県情報
        $prefectures = Configure::read("PREFECTURES");

        $param = $this->request->getQuery();

        $courses = Configure::read("COURSE");

        $bodyDataOptions = Configure::read("BODYDATAOPTIONS");

        $eventType = Configure::read("EVENT_TYPE");

        $this->set(compact('user', 'staffs', 'prefectures', 'param', 'courses', 'bodyDataOptions', 'eventType', 'habit', 'purpose', 'bodyDatas', 'mention', 'consults', 'selfdata', 'differences'));
    }

    private function sendEmail($user)
    {
        // 固定のメールアドレスに送信
        $this->getMailer('App')->send('sendGridEmail', ["【顧客カルテ】美脚セルフデザインプログラム", "ysonoda@someonelikeyou.co.jp", "karute", ["user" => $user]]);
        $this->getMailer('App')->send('sendGridEmail', ["【顧客カルテ】美脚セルフデザインプログラム", "ryota0246@gmail.com", "karute", ["user" => $user]]);
        
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
                $this->getMailer('App')->send('sendGridEmail', ["【顧客カルテ】美脚セルフデザインプログラム", $staff->email, "karute", ["user" => $user]]);
            }
        }
    }

    public function customerMain($id = null)
    {
        $user = $this->Users->find('all')
            ->contain(['Staffs'])
            ->where([
                'Users.id' => $id,
                'Users.is_deleted' => 0
            ])
            ->first();

        if (!$user) {
            return $this->redirect(['action' => 'index']);
        }

        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();

            if (isset($data['start_datetime']) && !empty($data['start_datetime'])) {
                if (is_string($data['start_datetime'])) {
                    $data['start_datetime'] = new \Cake\I18n\FrozenTime($data['start_datetime']);
                }
            } else {
                $data['start_datetime'] = new \Cake\I18n\FrozenTime();
            }

            $user = $this->Users->patchEntity($user, $data);

            if ($this->Users->save($user)) {
                $this->Flash->success(__('更新しました。'));
                $this->sendEmail($user);

                $param = $this->request->getQuery();

                return $this->redirect(['action' => 'customerInformations', $user->id, '?' => $param]);
            }

            // エラーの詳細を出力
            $errorMessage = 'データを登録できませんでした。';
            if ($user->getErrors()) {
                $errorMessage .= '<br><br>エラー詳細:<br>';
                foreach ($user->getErrors() as $field => $errors) {
                    if (is_array($errors)) {
                        foreach ($errors as $rule => $message) {
                            if (is_string($message)) {
                                $errorMessage .= '・' . h($field) . ': ' . h($message) . '<br>';
                            } else {
                                $errorMessage .= '・' . h($field) . ': ' . h(json_encode($message)) . '<br>';
                            }
                        }
                    } else {
                        $errorMessage .= '・' . h($field) . ': ' . h($errors) . '<br>';
                    }
                }
            }
            $this->Flash->error($errorMessage, ['escape' => false]);
        }

        // スタッフを配列で取得
        $staffs = $this->Users->Staffs->find('list', [
            'keyField' => 'id',
            'valueField' => 'name',
            'conditions' => [
                'is_deleted' => 0
            ]
        ])->toArray();

        // 都道府県情報
        $prefectures = Configure::read("PREFECTURES");

        $param = $this->request->getQuery();

        $courses = Configure::read("COURSE");

        $bodyDataOptions = Configure::read("BODYDATAOPTIONS");

        $this->set(compact('user', 'staffs', 'prefectures', 'param', 'courses'));
    }

    public function habits($id = null)
    {
        $habit = $this->Habits->find('all')
            ->where(['user_id' => $id])
            ->first();

        $user = $this->Users->find('all')
            ->where(['id' => $id, 'is_deleted' => 0])
            ->first();

        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();

            if (!$habit) {
                $habit = $this->Habits->newEmptyEntity();
                $data['user_id'] = $id;  // user_id をセットし忘れない
            }

            $habit = $this->Habits->patchEntity($habit, $data);

            $param = $this->request->getQuery();
            if ($this->Habits->save($habit)) {
                $this->Flash->success(__('更新しました。'));
                $this->sendEmail($user);


                return $this->redirect(['action' => 'customerInformations', $user->id, '?' => $param]);
            }
            $this->Flash->error(__('データを登録できませんでした。入力内容を見直してください。'));
            return $this->redirect(['action' => 'customerInformations', $user->id, '?' => $param]);
        }

        $param = $this->request->getQuery();

        $this->set(compact('user', 'habit', 'param'));
    }

    public function purpose($id = null)
    {
        $purpose = $this->Purposes->find('all')
            ->where(['user_id' => $id])
            ->first();

        $user = $this->Users->find('all')
            ->where(['id' => $id, 'is_deleted' => 0])
            ->first();

        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();

            if (!$purpose) {
                $purpose = $this->Purposes->newEmptyEntity();
                $data['user_id'] = $id;  // user_id をセットし忘れない
            }

            $purpose = $this->Purposes->patchEntity($purpose, $data);

            $param = $this->request->getQuery();
            if ($this->Purposes->save($purpose)) {
                $this->Flash->success(__('更新しました。'));
                $this->sendEmail($user);


                return $this->redirect(['action' => 'customerInformations', $user->id, '?' => $param]);
            }
            $this->Flash->error(__('データを登録できませんでした。入力内容を見直してください。'));
            return $this->redirect(['action' => 'customerInformations', $user->id, '?' => $param]);
        }

        $param = $this->request->getQuery();

        $this->set(compact('user', 'purpose', 'param'));
    }

    public function bodyData($id = null)
    {
        $user = $this->Users->find()
            ->where(['id' => $id, 'is_deleted' => 0])
            ->first();

        if (!$user) {
            return $this->redirect(['action' => 'index']);
        }

        if ($this->request->is(['post', 'put', 'patch'])) {
            $dataList = $this->request->getData('bodyData');

            foreach ($dataList as $data) {
                if (!empty($data['id'])) {
                    $bodyData = $this->BodyDatas->get($data['id']);
                    $bodyData = $this->BodyDatas->patchEntity($bodyData, $data);
                } else {
                    $bodyData = $this->BodyDatas->newEntity($data);
                    $bodyData->user_id = $id;
                }

                if (!$this->BodyDatas->save($bodyData)) {
                    $this->Flash->error(__('ボディデータの保存に失敗しました。'));
                    return $this->redirect(['action' => 'customerInformations', $id]);
                }
            }

            $this->Flash->success(__('ボディデータを保存しました。'));
            $this->sendEmail($user);
            return $this->redirect(['action' => 'customerInformations', $id]);
        }

        $param = $this->request->getQuery();
        $this->set(compact('user', 'param'));
    }


    public function mention($id = null)
    {
        $user = $this->Users->find('all')
            ->where([
                'id' => $id,
                'is_deleted' => 0
            ])
            ->first();

        if (!$user) {
            return $this->redirect(['action' => 'index']);
        }

        // Mentions テーブルから取得
        $mention = $this->Mentions->find('all')
            ->where([
                'user_id' => $id
            ])
            ->first();

        // なければ newEntity
        if (!$mention) {
            $mention = $this->Mentions->newEntity(['user_id' => $id]);
        }

        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();

            $mention = $this->Mentions->patchEntity($mention, $data);

            if ($this->Mentions->save($mention)) {
                $this->Flash->success(__('更新しました。'));
                $this->sendEmail($user);

                $param = $this->request->getQuery();

                return $this->redirect(['action' => 'customerInformations', $user->id, '?' => $param]);
            }

            $this->Flash->error(__('データを登録できませんでした。入力内容を見直してください。'));
        }

        $param = $this->request->getQuery();

        $this->set(compact('user', 'mention', 'param'));
    }

    public function consults($userId = null)
    {
        try {
            $user = $this->Users->get($userId, ['conditions' => ['is_deleted' => 0]]);
        } catch (\Cake\Datasource\Exception\RecordNotFoundException $e) {
            $this->Flash->error(__('ユーザーが見つかりませんでした。'));
            return $this->redirect(['action' => 'index']);
        }

        if ($this->request->is(['post', 'put', 'patch'])) {
            $dataList = $this->request->getData('consults');
            $connection = $this->Consults->getConnection();
            $connection->begin();

            try {
                foreach ((array) $dataList as $data) {
                    if (!empty($data['id'])) {
                        // --- Update existing consult ---
                        $consult = $this->Consults->get($data['id']);
                    } else {
                        // --- Insert new consult ---
                        $consult = $this->Consults->newEntity();
                        $consult->user_id = $userId;
                    }

                    $fields = [];
                    foreach (['theme', 'instruction', 'note', 'next_step', 'staff_id', 'date', 'category'] as $field) {
                        if (isset($data[$field]) && $data[$field] !== '') {
                            $fields[$field] = ($field === 'date')
                                ? new \Cake\I18n\FrozenDate($data[$field])
                                : $data[$field];
                        }
                    }

                    $this->Consults->patchEntity($consult, $fields);

                    if (!$this->Consults->save($consult)) {
                        throw new \Exception(__('保存に失敗しました（テーマ: {0}）', $data['theme'] ?? '未入力'));
                    }
                }

                $connection->commit();
                $this->sendEmail($user);
                $this->Flash->success(__('保存しました。'));
            } catch (\Exception $e) {
                $connection->rollback();
                $this->Flash->error($e->getMessage());
            }

            $param = $this->request->getQuery();
            return $this->redirect(['action' => 'customerInformations', $userId, '?' => $param]);
        }

        $param = $this->request->getQuery();
        $this->set(compact('user', 'param'));
    }

    public function selfData($id = null)
    {
        $this->request->allowMethod(['post', 'put']);

        $user = $this->Users->get($id);
        if (!$user) {
            $this->Flash->error('ユーザーが見つかりませんでした。');
            return $this->redirect(['action' => 'customerInformations']);
        }

        $selfDataTable = $this->getTableLocator()->get('SelfDatas');
        $data = $this->request->getData();

        // Define editable fields
        $editableFields = [
            'date',
            'good_changes',
            'work_review',
            'no_change_parts',
            'current_state',
            'next_two_weeks_work',
            'consult_topics',
            'consult_content'
        ];

        // Fetch existing selfData rows
        $existing = $selfDataTable->find()
            ->where(['user_id' => $id])
            ->order(['date' => 'ASC'])
            ->indexBy('id') // Allows quick lookup
            ->toArray();

        if (isset($data['selfData'])) {
            foreach ($data['selfData'] as $row) {
                if (empty($row['id']) || !isset($existing[$row['id']])) {
                    continue;
                }

                $entity = $existing[$row['id']];

                foreach ($editableFields as $field) {
                    if (array_key_exists($field, $row)) {
                        $entity->$field = $row[$field];
                    }
                }

                $selfDataTable->save($entity);
            }
        }

        $this->Flash->success('自己データを保存しました。');
        $this->sendEmail($user);
        return $this->redirect(['action' => 'customerInformations', $id]);
    }

    public function differences($userId = null)
    {
        $this->request->allowMethod(['post', 'put']);

        $data = $this->request->getData('differences');
        $user = $this->Users->get($userId);
        if (!$data) {
            $this->Flash->error('データが送信されませんでした。');
            return $this->redirect(['action' => 'customerInformations', $userId]);
        }

        foreach ((array) $data as $item) {
            if (!empty($item['id'])) {
                // --- Update existing difference ---
                $difference = $this->Differences->get($item['id']);
            } else {
                // --- Insert new difference ---
                $difference = $this->Differences->newEmptyEntity();
                $difference->user_id = $userId;
            }

            $fieldsToUpdate = [];
            foreach ([
                'milestone',
                'category',
                'target1',
                'target2',
                'evaluation1',
                'evaluation2',
                'comment1',
                'comment2'
            ] as $field) {
                if (array_key_exists($field, $item)) {
                    $fieldsToUpdate[$field] = $item[$field];
                }
            }

            $difference = $this->Differences->patchEntity($difference, $fieldsToUpdate);

            if (!$this->Differences->save($difference)) {
                $this->Flash->error('保存中にエラーが発生しました。');
                return $this->redirect(['action' => 'customerInformations', $userId]);
            }
        }

        $this->Flash->success('成果差分が保存されました。');
        $this->sendEmail($user);
        return $this->redirect(['action' => 'customerInformations', $userId]);
    }
}

