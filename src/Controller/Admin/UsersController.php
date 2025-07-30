<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\AppController;
use Cake\I18n\Time;
use Cake\ORM\Query;
use Cake\Core\Configure;
use DateTime;
use PHPUnit\TextUI\CliArguments\Configuration;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $users
 * @method \App\Model\Entity\user[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class UsersController extends AppController
{
    public function beforeFilter(\Cake\Event\EventInterface $event)
    {
        parent::beforeFilter($event);

        $user = $this->Authentication->getIdentity();
        if($user && $user->role_id == 1) {
            $this->Flash->error('このページへのアクセス権がありません。');
            return $this->redirect(['controller' => 'UploadMovies', 'action' => 'index']);
        }
    }

    public function initialize(): void
    {
        parent::initialize();

        $this->loadModel('ComparedImages');
        $this->loadModel('Habits');
        $this->loadModel('Purposes');
        $this->loadModel('BodyDatas');
        $this->loadModel('Mentions');
        $this->loadModel('SelfDatas');
        $this->loadModel('Differences');
        $this->loadModel('Consults');
    }

    
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $query = $this->Users->find('all')
        ->contain([
            'Messages' => function (Query $q) {
                    return $q->where([
                        'Messages.sender IN' => ['1', '2'],
                        'Messages.is_admin_read' => false
                    ]);
                },
            'Staffs',
            'ComparedImages' => function (Query $q) {
                return $q->where(['ComparedImages.active' => false]);
            },
        ])
        ->where([
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

        // 担当講師
        if (!empty($param['staff_id'])) {
            $query->where(['Users.staff_id' => $param['staff_id']]);
        } else {
            $param['staff_id'] = '';
        }

        $users = $this->paginate($query);
        // スタッフを配列で取得
        $staffs = $this->Users->Staffs->find('list', [
            'keyField' => 'id',
            'valueField' => 'name',
            'conditions' => [
                'is_deleted' => 0
            ]
        ])->toArray();

        $this->set(compact('users', 'staffs', 'param'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        // Get the user with the highest ID
        $lastUser = $this->Users->find()
        ->select(['id'])
        ->orderDesc('id')
        ->first();

        $nextMemberNumber = $lastUser ? $lastUser->id + 3000000 + 1 : 1;

        // Set default value into the new entity
        $user = $this->Users->newEntity([
            'member_number' => $nextMemberNumber
        ]);

        if ($this->request->is('post')) {
            $data = $this->request->getData();

            // UUIDの生成（30桁ランダム英数）
            $data['uuid'] = substr(bin2hex(random_bytes(30)), 0, 30);

            $user = $this->Users->patchEntity($user, $data);
            if ($this->Users->save($user)) {
                $this->Flash->success(__('登録しました。'));

                $param = $this->request->getQuery();

                return $this->redirect(['action' => 'index', '?' => $param]);
            }
            $this->Flash->error(__('データを登録できませんでした。入力内容を見直してください。'));
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

        $this->set(compact('user', 'staffs', 'prefectures', 'param', 'courses'));
    }

    /**
     * Edit method
     *
     * @param string|null $id user id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
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

        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();

            if ($data['new_password']) {
                $data['password'] = $data['new_password'];
            }

            $user = $this->Users->patchEntity($user, $data);

            if ($this->Users->save($user)) {
                $this->Flash->success(__('更新しました。'));

                $param = $this->request->getQuery();

                return $this->redirect(['action' => 'index', '?' => $param]);
            }
            $this->Flash->error(__('データを登録できませんでした。入力内容を見直してください。'));
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

        $this->set(compact('user', 'staffs', 'prefectures', 'param', 'courses'));

        $this->render('add');
    }

    /**
     * Delete method
     *
     * @param string|null $id user id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
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

        $user->is_deleted = 1;
        if ($this->Users->save($user)) {
            $this->Flash->success(__('削除しました。'));
        } else {
            $this->Flash->error(__('エラーが発生しました。'));
        }

        return $this->redirect(['action' => 'index']);
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

         // 既読にする
         $this->Users->Messages->updateAll(
            [
                'is_admin_read' => true
            ],
            [
                'user_id' => $user->id,
                'sender IN' => ['1', '2'],
                'is_admin_read' => false
            ]
        );

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

        $this->set(compact('messages', 'user'));
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

            $data['sender'] = '3'; // 管理者

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

            $message = $this->Users->Messages->newEmptyEntity();
            $message = $this->Users->Messages->patchEntity($message, $data);
            if ($this->Users->Messages->save($message)) {
                $this->Flash->success(__('メッセージを送信しました。'));

                return $this->redirect(['action' => 'message', $data['user_id']]);
            }
            $this->Flash->error(__('データを送信できませんでした。'));
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
     * CSV Import method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful import, renders view otherwise.
     */
    public function import()
    {
        if ($this->request->is('post')) {
            $file = $this->request->getData('csv_file');
            
            if ($file && $file->getError() === UPLOAD_ERR_OK) {
                $filename = $file->getClientFilename();
                $extension = pathinfo($filename, PATHINFO_EXTENSION);
                
                if (strtolower($extension) !== 'csv') {
                    $this->Flash->error(__('CSVファイルを選択してください。'));
                    return;
                }
                
                $result = $this->importCsvData($file);
                
                if ($result['success']) {
                    $this->Flash->success(__('CSVインポートが完了しました。成功: {0}件, エラー: {1}件', $result['success_count'], $result['error_count']));
                    return $this->redirect(['action' => 'index']);
                } else {
                    // エラー内容を詳細に表示
                    $errorMessage = 'CSVインポート中にエラーが発生しました。';
                    if (!empty($result['errors'])) {
                        $errorMessage .= '<br><br>エラー詳細:<br>';
                        foreach ($result['errors'] as $error) {
                            $errorMessage .= '・' . h($error) . '<br>';
                        }
                    }
                    $this->Flash->error($errorMessage, ['escape' => false]);
                }
            } else {
                $this->Flash->error(__('ファイルのアップロードに失敗しました。'));
            }
        }
        
        // スタッフを配列で取得
        $staffs = $this->Users->Staffs->find('list', [
            'keyField' => 'id',
            'valueField' => 'name',
            'conditions' => [
                'is_deleted' => 0
            ]
        ])->toArray();
        
        $param = $this->request->getQuery();
        $this->set(compact('staffs', 'param'));
    }

    /**
     * Download CSV template
     *
     * @return \Cake\Http\Response|null
     */
    public function downloadTemplate()
    {
        $filename = 'users_import_template.csv';
        $filepath = WWW_ROOT . 'uploads' . DS . $filename;
        
        // ファイルが存在するかチェック
        if (!file_exists($filepath)) {
            $this->Flash->error(__('テンプレートファイルが見つかりません。'));
            return $this->redirect(['action' => 'import']);
        }

        // レスポンスを設定
        $response = $this->response->withFile($filepath, [
            'download' => true,
            'name' => $filename,
        ]);

        return $response;
    }

    public function comparedImages($userId = null)
    {
        $this->loadModel('ComparedImages');

        $param = $this->request->getQuery();
        $active = $this->request->getQuery('active');

        $query = $this->ComparedImages->find()
            ->where(['user_id' => $userId]);

        if ($active === '0') {
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
        $comparedImage = $this->ComparedImages->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $comparedImage = $this->ComparedImages->patchEntity($comparedImage, $this->request->getData());
            if ($this->ComparedImages->save($comparedImage)) {
                $this->Flash->success(__('コメントを更新しました。'));

                return $this->redirect(['action' => 'comparedImages', $comparedImage->user_id, '?' => $param]);
            }
            $this->Flash->error(__('コメントを更新できませんでした。'));
        }
        $users = $this->ComparedImages->Users->find('list', ['limit' => 200])->all();
        $this->set(compact('comparedImage', 'users', 'param'));
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

    /**
     * 
     * @param string|null $id user id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function customerInformations($id = null){
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

    public function customerMain($id = null){
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

            $user = $this->Users->patchEntity($user, $data);

            if ($this->Users->save($user)) {
                $this->Flash->success(__('更新しました。'));

                $param = $this->request->getQuery();

                return $this->redirect(['action' => 'customerInformations', $user->id, '?' => $param]);
            }
            $this->Flash->error(__('データを登録できませんでした。入力内容を見直してください。'));
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
                foreach ((array)$dataList as $data) {
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
        return $this->redirect(['action' => 'customerInformations', $id]);
    }

    public function differences($userId = null)
    {
        $this->request->allowMethod(['post', 'put']);

        $data = $this->request->getData('differences');
        if (!$data) {
            $this->Flash->error('データが送信されませんでした。');
            return $this->redirect(['action' => 'customerInformations', $userId]);
        }

        foreach ((array)$data as $item) {
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
                'milestone', 'category', 'target1', 'target2',
                'evaluation1', 'evaluation2', 'comment1', 'comment2'
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
        return $this->redirect(['action' => 'customerInformations', $userId]);
    }




    /**
     * Import CSV data
     *
     * @param \Cake\Http\UploadedFile $file
     * @return array
     */
    private function importCsvData($file)
    {
        $successCount = 0;
        $errorCount = 0;
        $errors = [];

        $lastUser = $this->Users->find()
        ->select(['id'])
        ->orderDesc('id')
        ->first();

        $nextMemberNumber = $lastUser ? $lastUser->id + 3000000 + 1 : 1;
        
        // データベース接続を取得
        $connection = $this->Users->getConnection();
        
        // トランザクション開始
        $connection->begin();
        
        try {
            // CSVファイルを読み込み
            $handle = fopen($file->getStream()->getMetadata('uri'), 'r');
            if (!$handle) {
                $connection->rollback();
                return ['success' => false, 'success_count' => 0, 'error_count' => 0, 'errors' => ['ファイルを開けませんでした。']];
            }
            
            // BOMを除去
            $bom = fread($handle, 3);
            if ($bom !== "\xEF\xBB\xBF") {
                rewind($handle);
            }
            
            // ヘッダー行を読み込み
            $headers = fgetcsv($handle);
            if (!$headers) {
                fclose($handle);
                $connection->rollback();
                return ['success' => false, 'success_count' => 0, 'error_count' => 0, 'errors' => ['CSVファイルの形式が正しくありません。']];
            }
            
            $lineNumber = 1;
            $hasErrors = false;
            $memberNumber = $nextMemberNumber;
            
            while (($data = fgetcsv($handle)) !== false) {
                $lineNumber++;
                
                if (count($data) !== count($headers)) {
                    $errorCount++;
                    $errors[] = "行{$lineNumber}: カラム数が一致しません。";
                    $hasErrors = true;
                    continue;
                }
                
                // データの検証と変換
                $userData = $this->validateAndTransformCsvData($data, $lineNumber, $memberNumber);
                
                if (isset($userData['error'])) {
                    $errorCount++;
                    $errors[] = $userData['error'];
                    $hasErrors = true;
                    continue;
                }

                $memberNumber++;
                
                // ユーザーの保存
                try {
                    $user = $this->Users->newEmptyEntity();
                    $user = $this->Users->patchEntity($user, $userData);
                    
                    if ($this->Users->save($user)) {
                        $successCount++;
                    } else {
                        $errorCount++;
                        $errors[] = "行{$lineNumber}: " . $this->formatErrors($user->getErrors());
                        $hasErrors = true;
                    }
                } catch (\Exception $e) {
                    $errorCount++;
                    $errors[] = "行{$lineNumber}: " . $e->getMessage();
                    $hasErrors = true;
                }
            }
            
            fclose($handle);
            
            // エラーがある場合はロールバック
            if ($hasErrors) {
                $connection->rollback();
                return [
                    'success' => false,
                    'success_count' => 0,
                    'error_count' => $errorCount,
                    'errors' => $errors
                ];
            }
            
            // 成功時はコミット
            $connection->commit();
            
            return [
                'success' => true,
                'success_count' => $successCount,
                'error_count' => $errorCount,
                'errors' => $errors
            ];
            
        } catch (\Exception $e) {
            // 予期しないエラーが発生した場合もロールバック
            $connection->rollback();
            return [
                'success' => false,
                'success_count' => 0,
                'error_count' => 1,
                'errors' => ['インポート処理中にエラーが発生しました: ' . $e->getMessage()]
            ];
        }
    }

    /**
     * Validate and transform CSV data
     *
     * @param array $rowData
     * @param int $lineNumber
     * @return array
     */
    private function validateAndTransformCsvData($rowData, $lineNumber, $memberNumber)
    {
        $userData = [];
        
        // メールアドレスの重複チェック
        $existingUser = $this->Users->find()
            ->where(['email' => $rowData[1]])
            ->first();
        if ($existingUser) {
            return ['error' => "行{$lineNumber}: メールアドレスが既に登録されています。"];
        }

        if ($rowData[1] == '') {
            return ['error' => "行{$lineNumber}: メールアドレスが空です。"];
        }

        if ($rowData[2] == '') {
            return ['error' => "行{$lineNumber}: 姓が空です。"];
        }

        if ($rowData[4] == '') {
            return ['error' => "行{$lineNumber}: 名が空です。"];
        }


        
        // データの変換 - 新しいCSV構造に合わせて修正
        $userData['member_number'] = $memberNumber;
        $userData['email'] = trim($rowData[1]);
        $userData['last_name'] = trim($rowData[2]);
        $userData['last_name_kana'] = trim($rowData[3]);
        $userData['first_name'] = trim($rowData[4]);
        $userData['first_name_kana'] = trim($rowData[5]);
        $userData['tel'] = !empty($rowData[6]) ? trim($rowData[6]) : null;
        $userData['birthday'] = $this->parseDate($rowData[7]);
        $userData['zipcode'] = !empty($rowData[8]) ? trim($rowData[8]) : null;
        $userData['address'] = !empty($rowData[9]) ? trim($rowData[9]) : null;
        $userData['note'] = "";
        $userData['course_id'] = !empty($rowData[10]) ? $this->getCourseId(trim($rowData[10])) : null;
        // 申し込み日時（タイムスタンプから）
        $userData['start_datetime'] = $this->getFirstDayOfNextMonth();
        
        // デフォルト値の設定
        $userData['uuid'] = substr(bin2hex(random_bytes(30)), 0, 30);
        $userData['password'] = $this->getNextMonthString(); // 来月のYYYYMM形式をパスワードに設定
        $userData['is_continue'] = 1;
        $userData['light_plan'] = 0;
        $userData['staff_plan'] = 0;
        $userData['is_deleted'] = 0;
        $userData['is_light_plan_first_event'] = 0;
        
        // 担当講師のデフォルト設定
        $userData['staff_id'] = $this->getStaffId('SHOCO');
        
        return $userData;
    }

    private function getCourseId($courseName)
    {
        $courses = Configure::read("COURSE");
        foreach ($courses as $key => $value) {
            if ($value == $courseName) {
                return $key;
            }
        }
        return 2;
    }

    /**
     * Parse date string
     *
     * @param string $dateString
     * @return \Cake\I18n\FrozenDate|null
     */
    private function parseDate($dateString)
    {
        $formats = ['Y-m-d', 'Y/m/d', 'd/m/Y', 'm/d/Y'];
        
        foreach ($formats as $format) {
            $date = \DateTime::createFromFormat($format, $dateString);
            if ($date !== false) {
                return new \Cake\I18n\FrozenDate($date);
            }
        }
        
        return null;
    }

    private function createManagementNumber()
    {
        $lastUser = $this->Users->find('all')
        ->order(['CAST(member_number AS UNSIGNED)' => 'DESC'])
        ->first();

        if (!$lastUser) {
            return '1';
        }

        $lastNumber = (int)$lastUser->member_number;
        $newNumber = (string)($lastNumber + 1);
        
        return $newNumber;
    }

    private function getStaffId($staffName)
    {
        $staff = $this->Users->Staffs->find()
            ->where(['name' => $staffName, 'is_deleted' => 0])
            ->first();
        if (!$staff) {
            $staff = $this->Users->Staffs->find()
                ->where(['name' => 'SHOCO', 'is_deleted' => 0])
                ->first();
        }
        
        return $staff->id;
    }

    /**
     * Parse datetime string
     *
     * @param string $datetimeString
     * @return \Cake\I18n\FrozenTime|null
     */
    private function parseDateTime($datetimeString)
    {
        // nullまたは空文字の場合は本日の日時を返す
        if (empty($datetimeString)) {
            return new \Cake\I18n\FrozenTime();
        }
        
        $formats = ['Y-m-d H:i:s', 'Y-m-d H:i', 'Y/m/d H:i:s', 'Y/m/d H:i'];
        
        foreach ($formats as $format) {
            $datetime = \DateTime::createFromFormat($format, $datetimeString);
            if ($datetime !== false) {
                return new \Cake\I18n\FrozenTime($datetime);
            }
        }
        
        return null;
    }

    private function getNextMonthString()
    {
        $date = new \DateTime();
        $date->modify('+1 month');
        return $date->format('Ym');
    }

    /**
     * 来月の初日を取得
     * 
     * @return \Cake\I18n\FrozenTime
     */
    private function getFirstDayOfNextMonth()
    {
        $date = new \Cake\I18n\FrozenTime('first day of next month');
        return $date;
    }

    /**
     * Format validation errors for display
     *
     * @param array $errors
     * @return string
     */
    private function formatErrors($errors)
    {
        $formattedErrors = [];
        foreach ($errors as $field => $fieldErrors) {
            if (is_array($fieldErrors)) {
                foreach ($fieldErrors as $rule => $message) {
                    if (is_string($message)) {
                        $formattedErrors[] = $field . ': ' . $message;
                    } else {
                        $formattedErrors[] = $field . ': ' . json_encode($message);
                    }
                }
            } else {
                $formattedErrors[] = $field . ': ' . $fieldErrors;
            }
        }
        return implode(', ', $formattedErrors);
    }

}
