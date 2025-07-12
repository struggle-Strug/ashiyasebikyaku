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
    }

    public function index()
    {
        $query = $this->Users->find('all')
            ->contain([
                'Messages' => function (Query $q) {
                    return $q->where([
                        'Messages.sender' => '1',
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
            AND sender = '1'
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
                $this->getMailer('App')->send('sendGridEmail', ["【コミュニケーションツール】脚やせ美脚リバースエイジングプログラム", $user->email, "messages", ["name" => $this->Authentication->getIdentity()->name, "url" => $fullUrl , 'today' => $formattedDateTime]]);
                $this->getMailer('App')->send('sendGridEmail', ["【コミュニケーションツール】脚やせ美脚リバースエイジングプログラム", "ysonoda@someonelikeyou.co.jp", "messages", ["name" => $this->Authentication->getIdentity()->name, "url" => $adminFullUrl , 'today' => $formattedDateTime]]);
                
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

        $comparedImages = $this->paginate($query);

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
        if ($this->request->is(['patch', 'post', 'put'])) {
            $comparedImage = $this->ComparedImages->patchEntity($comparedImage, $this->request->getData());
            if ($this->ComparedImages->save($comparedImage)) {
                $this->Flash->success(__('コメントを更新しました。'));

                return $this->redirect(['action' => 'comparedImages', $comparedImage->user_id,  '?' => $param]);
            }
            $this->Flash->error(__('コメントを更新できませんでした。'));
        }
        $users = $this->ComparedImages->Users->find('list', ['limit' => 200])->all();
        $this->set(compact('comparedImage', 'users', 'param', 'staff'));
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
            return $this->redirect(['action' => 'comparedImages', $comparedImage->user_id]);
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
            return $this->redirect(['action' => 'comparedImages', $comparedImage->user_id]);
        }

    }
}
