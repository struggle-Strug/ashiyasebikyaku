<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\AppController;
use Cake\I18n\Time;
use Cake\ORM\Query;
use Cake\Core\Configure;
use DateTime;

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
        $query = $this->Users->find('all')
        ->contain([
            'Messages' => function (Query $q) {
                    return $q->where([
                        'Messages.sender IN' => ['1', '2'],
                        'Messages.is_admin_read' => false
                    ]);
                },
            'Staffs'
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
        $user = $this->Users->newEmptyEntity();
        if ($this->request->is('post')) {
            $data = $this->request->getData();

            // UUIDの生成（30桁ランダム英数）
            $data['uuid'] = substr(bin2hex(random_bytes(30)), 0, 30);

            $user = $this->Users->patchEntity($user, $data);
            if ($this->Users->save($user)) {
                $this->Flash->success(__('登録しました。'));

                return $this->redirect(['action' => 'index']);
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

        $this->set(compact('user', 'staffs', 'prefectures', 'param'));
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

                return $this->redirect(['action' => 'index']);
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

        $this->set(compact('user', 'staffs', 'prefectures', 'param'));

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
            'Messages.staff_id' => $user->staff_id,
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
