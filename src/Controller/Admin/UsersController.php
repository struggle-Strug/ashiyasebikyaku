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
     *
     * @param string|null $id user id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function customerInformations($id = null){
        $user = $this->Users->find('all')
        ->where([
            'id' => $id,
            'is_deleted' => 0
        ])
        ->first();

        if (!$user) {
            return $this->redirect(['action' => 'index']);
        }

        $param = $this->request->getQuery();

        $this->set(compact('user', 'param'));
    }

    public function customerMain($id = null){
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

        $this->set(compact('user', 'staffs', 'prefectures', 'param', 'courses'));
    }

    public function habits($id = null){
        $user = $this->Users->find('all')
        ->where([
            'id' => $id,
            'is_deleted' => 0
        ])
        ->first();

        if (!$user) {
            return $this->redirect(['action' => 'index']);
        }

        $mockUserData = [
            'occupation' => '専業主婦 / 営業 / 販売業 / 看護師 等々',
            'eating_habits' => '朝食抜きが多い、夜に炭水化物を多く摂る',
            'sleep' => '平均5～6時間、入眠しにくいことがある',
            'stress_factors' => '仕事（事務職・残業あり）、肩こり・眼精疲労',
            'exercise_history' => '中学までバスケ、以降はほぼなし',
            'medical_history' => '腰痛経験あり（2018年）',
            'other_notes' => '月経前の浮腫・不調あり、冷え性、柔軟性低い'
        ];

        $param = $this->request->getQuery();

        $this->set(compact('user', 'param', 'mockUserData'));
    }

    public function purpose($id = null){
        $user = $this->Users->find('all')
        ->where([
            'id' => $id,
            'is_deleted' => 0
        ])
        ->first();

        if (!$user) {
            return $this->redirect(['action' => 'index']);
        }

        $mockUserData = [
            'email' => 'aaaaaa@bbbbb.co.jp',
            'name' => '富田敦子',
            'current_concerns_appearance' => '反腰、ガニ股(立つと左足軸で右足休めの姿勢に無意識になる)、膝裏曲っていてO脚、猫背、巻き肩、垂尻、お腹ポッコリ、二重顎、右脚指頭(人差し指・中指・くすり指)上部にタコ、左右足裏親指と人差し指の間にタコ、左右足小指側面、タコ、左右足くすり指と小指',
            'current_concerns_function' => '腰椎椎間板ヘルニア・変形・すべり・狭窄と頸椎狭窄による腰痛、背中痛、首・肩凝り、坐骨神経痛、脚痺れ、膝・股関節・骨盤痛、頭痛、めまい、耳鳴り、耳痛。不眠（目覚ましより先に起きる）、腰痛で仰向けで眠れない。又は無意識に膝立てて寝て眠り浅く1日中眠気ある。食い縛り(夜はマウスピース使用)、食事で座っていても、料理で立っていて、外出で歩いていても腰痛',
            'goal_appearance' => '真直ぐな足でまっすぐ歩いている。お尻が上がっている。足指が真直ぐになってきちんと開けるようになっている。肩が下がって、姿勢が真直ぐになっている。お腹真直ぐ、顎の周りがすっきりしている。程良い筋肉がある',
            'goal_function' => '全ての痛みが無い身体。疲れ知らずで体力有る。膝を立てなくても眠れる。食い縛り無くなってマウスピース不要になる。生き生きしてキラキラして勢いがあってとても元気。食いしばりがなく、眠れ',
            'future_self_image' => 'ハンドメイドでブランド立ち上げ。受注が多く入り、お客様に喜んでもらって私も嬉しく笑顔が絶えず、毎日楽しくて仕方がない。時間に追われる生活ではなく、ゆっくりとした自然と共存する心豊かなのんびりとした生活。',
            'next_two_weeks_work' => '脳バグ探し',
            'consulting_topics' => '6月いっぱいまで休職中で、朝起きて直ぐローラーとボールでストレッチして、仙骨のワークと足首のワーク（インスタでアップされているもの）をした後、理学療法士伝授のリハビリ体操(軽い筋トレ含む)を1時間ほどしない動けない状態です。また、時間は決まっていませんが、公園に行って裸足歩きをしたり、鉄棒で股関節の刺激をしたり、うんていにぶら下がって脇伸ばしをしたりしています。あと、週一でヨガ'
        ];
        

        $param = $this->request->getQuery();

        $this->set(compact('user', 'param', 'mockUserData'));
    }

    public function bodyData($id = null){
        $user = $this->Users->find('all')
        ->where([
            'id' => $id,
            'is_deleted' => 0
        ])
        ->first();

        if (!$user) {
            return $this->redirect(['action' => 'index']);
        }

        $mockUserData = [
            [
                'date' => '2025/05/01',
                'ankle' => '◯',
                'instep' => '◯',
                'vertical_arch' => '△',
                'horizontal_arch' => '✕',
                'toes' => '✕',
                'note' => ''
            ],
            [
                'date' => '2025/05/20',
                'ankle' => '◯',
                'instep' => '△',
                'vertical_arch' => '△',
                'horizontal_arch' => '✕',
                'toes' => '✕',
                'note' => ''
            ],
            [
                'date' => '2025/06/10',
                'ankle' => '◯',
                'instep' => '◯',
                'vertical_arch' => '△',
                'horizontal_arch' => '✕',
                'toes' => '✕',
                'note' => ''
            ],
            [
                'date' => '2025/06/30',
                'ankle' => '◯',
                'instep' => '◯',
                'vertical_arch' => '△',
                'horizontal_arch' => '△',
                'toes' => '✕',
                'note' => ''
            ],
            [
                'date' => '2025/07/20',
                'ankle' => '◯',
                'instep' => '◯',
                'vertical_arch' => '△',
                'horizontal_arch' => '△',
                'toes' => '✕',
                'note' => '寝指の改善が進まず'
            ],
            [
                'date' => '2025/08/10',
                'ankle' => '◎',
                'instep' => '◎',
                'vertical_arch' => '◯',
                'horizontal_arch' => '◯',
                'toes' => '△',
                'note' => ''
            ],
            [
                'date' => '2025/08/30',
                'ankle' => '◎',
                'instep' => '◎',
                'vertical_arch' => '◯',
                'horizontal_arch' => '◯',
                'toes' => '△',
                'note' => ''
            ],
        ];

        $param = $this->request->getQuery();

        $this->set(compact('user', 'param', 'mockUserData'));
    }

    public function addBodyData($id = null){
        $user = $this->Users->find('all')
        ->where([
            'id' => $id,
            'is_deleted' => 0
        ])
        ->first();

        if (!$user) {
            return $this->redirect(['action' => 'index']);
        }

        $param = $this->request->getQuery();

        $bodyDataOptions = Configure::read("BODYDATAOPTIONS");

        $this->set(compact('user', 'param', 'bodyDataOptions'));
    }

    public function mention($id = null){
        $user = $this->Users->find('all')
        ->where([
            'id' => $id,
            'is_deleted' => 0
        ])
        ->first();

        if (!$user) {
            return $this->redirect(['action' => 'index']);
        }

        $mockUserData = [
            'personality_tendency' => '真面目、完璧主義。失敗すると落ち込みやすい',
            'motivation_source' => '効果を数値で見るとやる気が出る。写真比較も◎',
            'dropout_risk' => '忙しさで通えなくなったときの自己嫌悪でフェードアウト傾向あり'
        ];
        

        $param = $this->request->getQuery();

        $this->set(compact('user', 'param', 'mockUserData'));
    }

    public function consultHistory($id = null){
        $user = $this->Users->find('all')
        ->where([
            'id' => $id,
            'is_deleted' => 0
        ])
        ->first();

        if (!$user) {
            return $this->redirect(['action' => 'index']);
        }

        $mockUserData = [
            [
                'date' => '2025/05/01',
                'category' => '実践レッスン',
                'theme' => '歩き方（仙骨ウォーク）',
                'instruction' => '足の踏み方指導',
                'note' => '親指を潰して踏むと膝が痛くなるが、足裏の正しい使い方がわかれば膝が痛くなることが体感できた',
                'next_step' => '足の踏み方が正しくできているか確認し、膝の痛みが緩和したか本人の体感を確認する',
                'instructor' => 'SHOCO'
            ],
            [
                'date' => '2025/05/15',
                'category' => '実践レッスン',
                'theme' => '下半身トレーニング（基礎）',
                'instruction' => '膝内側に入る癖あり。鏡で都度修正',
                'note' => '',
                'next_step' => '',
                'instructor' => 'FUKUCHAN'
            ],
            [
                'date' => '2025/06/01',
                'category' => '実践レッスン',
                'theme' => '食事アドバイスと振り返り面談',
                'instruction' => '食事記録アプリ導入済、前回とほぼ様子',
                'note' => '',
                'next_step' => '',
                'instructor' => 'SHOCO'
            ],
            [
                'date' => '2025/06/07',
                'category' => '脳バグレッスン',
                'theme' => '',
                'instruction' => '小指と美脚の関係性を学習',
                'note' => 'しっかり理解できた。膝痛で階段できない',
                'next_step' => '膝の関節ロックを解消',
                'instructor' => 'YUMICO'
            ],
            [
                'date' => '2025/06/16',
                'category' => '個人コンサル',
                'theme' => '',
                'instruction' => '写真診断の結果、ワークの変更を討議',
                'note' => '仮説通りの本人も成果を感じていないため、変更を合意',
                'next_step' => '写真診断から×××→×××にワークを変更指示',
                'instructor' => 'YUMICO'
            ]
        ];

        $param = $this->request->getQuery();

        $this->set(compact('user', 'param', 'mockUserData'));
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
            
            while (($data = fgetcsv($handle)) !== false) {
                $lineNumber++;
                
                if (count($data) !== count($headers)) {
                    $errorCount++;
                    $errors[] = "行{$lineNumber}: カラム数が一致しません。";
                    $hasErrors = true;
                    continue;
                }
                
                // データの検証と変換
                $userData = $this->validateAndTransformCsvData($data, $lineNumber);
                
                if (isset($userData['error'])) {
                    $errorCount++;
                    $errors[] = $userData['error'];
                    $hasErrors = true;
                    continue;
                }
                
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
    private function validateAndTransformCsvData($rowData, $lineNumber)
    {
        $userData = [];
        
        // メールアドレスの重複チェック
        $existingUser = $this->Users->find()
            ->where(['email' => $rowData[1]])
            ->first();
        if ($existingUser) {
            return ['error' => "行{$lineNumber}: メールアドレスが既に登録されています。"];
        }
        
        
        // データの変換
        $userData['member_number'] = $this->createManagementNumber();
        $userData['email'] = trim($rowData['1']);
        $userData['last_name'] = trim($rowData['2']);
        $userData['first_name'] = trim($rowData['2']);
        $userData['birthday'] = $this->parseDate($rowData['4']);
        $userData['tel'] = !empty($rowData['4']) ? trim($rowData['3']) : null;
        $userData['address'] = !empty($rowData[5]) ? trim($rowData[5]) : null;
        $userData['note'] = !empty($rowData[6]) ? trim($rowData[6]) : null;
        $userData['start_datetime'] = $this->parseDateTime($rowData[7]) ? $this->parseDateTime($rowData[7]) : new DateTime();
        $userData['password'] = !empty($rowData[8]) ? trim($rowData[8]) : $this->getNextMonthString();
        $userData['staff_id'] = !empty($rowData[9]) ? $this->getStaffId(trim($rowData[9])) : $this->getStaffId('SHOCO');

        
        // 日付の変換
        if (!empty($rowData['birthday'])) {
            $birthday = $this->parseDate($rowData['birthday']);
            if ($birthday) {
                $userData['birthday'] = $birthday;
            }
        }
        
        if (!empty($rowData['end_datetime'])) {
            $endDatetime = $this->parseDateTime($rowData['end_datetime']);
            if ($endDatetime) {
                $userData['end_datetime'] = $endDatetime;
            }
        }
        
        // 担当講師の設定
        if (!empty($rowData['staff_name'])) {
            $staff = $this->Users->Staffs->find()
                ->where(['name' => trim($rowData['staff_name']), 'is_deleted' => 0])
                ->first();
            if ($staff) {
                $userData['staff_id'] = $staff->id;
            }
        }
        
        // デフォルト値の設定
        $userData['uuid'] = substr(bin2hex(random_bytes(30)), 0, 30);
        $userData['password'] = 'password123'; // デフォルトパスワード
        $userData['is_continue'] = 1;
        $userData['light_plan'] = 0;
        $userData['staff_plan'] = 0;
        $userData['is_deleted'] = 0;
        $userData['is_light_plan_first_event'] = 0;
        
        return $userData;
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
