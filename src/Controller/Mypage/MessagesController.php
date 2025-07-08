<?php
declare(strict_types=1);

namespace App\Controller\Mypage;

use App\Controller\AppController;
use Cake\ORM\Query;
use Cake\ORM\TableRegistry;

use App\Mailer\AppMailer;
use Cake\Mailer\MailerAwareTrait;

use Cake\I18n\Time;

/**
 * Messages Controller
 *
 * @property \App\Model\Table\MessagesTable $Messages
 * @method \App\Model\Entity\Course[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class MessagesController extends AppController
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
    }

    public function index()
    {
        $messages = $this->Messages->find('all')
            ->where([
                'user_id' => $this->Authentication->getIdentity()->id,
                'is_deleted' => 0
            ])
            ->all();

        // 担当講師
        $staff = $this->Messages->Staffs->find('all')
            ->where([
                'id' => $this->Authentication->getIdentity()->staff_id,
                'is_deleted' => 0
            ])
            ->first();

        // 既読にする
        $this->Messages->updateAll(
            [
                'is_read' => 1
            ],
            [
                'user_id' => $this->Authentication->getIdentity()->id,
                'sender IN' => ['2', '3'],
                'is_read' => 0
            ]
        );

        $this->set(compact('messages', 'staff'));
    }

    /**
     * Send method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function send()
    {
        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();

            $data['user_id'] = $this->Authentication->getIdentity()->id;
            $data['staff_id'] = $this->Authentication->getIdentity()->staff_id;
            $data['sender'] = '1'; // 利用者

            // ファイルアップロード処理
            $uploadedFile = $this->request->getUploadedFile('attachment');
            if ($uploadedFile && $uploadedFile->getError() === UPLOAD_ERR_OK) {
                $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'video/mp4', 'video/mov', 'video/avi', 'video/webm', 'video/quicktime'];
                $maxFileSize = 10 * 1024 * 1024 * 1024; // 100MB

                if (!in_array($uploadedFile->getClientMediaType(), $allowedTypes)) {
                    $this->Flash->error(__('対応していないファイル形式です。'));
                    return $this->redirect(['action' => 'index']);
                }

                if ($uploadedFile->getSize() > $maxFileSize) {
                    $this->Flash->error(__('ファイルサイズが大きすぎます（最大100MB）。'));
                    return $this->redirect(['action' => 'index']);
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
            $path = '/staff/users/message/';
            $adminPath = '/admin/users/message/';
            $port = $this->request->getUri()->getPort();
            $fullUrl = $host . ($port ? ":$port" : '') . $path . $data['user_id'];
            $adminFullUrl = $host . ($port ? ":$port" : '') . $adminPath . $data['user_id'];

            $message = $this->Messages->newEmptyEntity();
            $message = $this->Messages->patchEntity($message, $data);

            $now = Time::now();

            
            $formattedDateTime = $now->i18nFormat('yyyy年MM月dd日 HH時mm分ss秒');

            if ($this->Messages->save($message)) {
                $this->Flash->success(__('メッセージを送信しました。'));
                $this->getMailer('App')->send('sendGridEmail', ["【コミュニケーションツール】脚やせ美脚リバースエイジングプログラム", "ysonoda@someonelikeyou.co.jp", "messages_operation", ["name" => $this->Authentication->getIdentity()->last_name . $this->Authentication->getIdentity()->first_name, "url" => $adminFullUrl, 'today' => $formattedDateTime]]);
                $staff = $this->Staffs->find('all')
                    ->where([
                        'id' => $this->Authentication->getIdentity()->staff_id,
                    ])
                    ->first();
                if ($staff) {
                    if ($staff->email) {
                        $this->getMailer('App')->send('sendGridEmail', ["【コミュニケーションツール】脚やせ美脚リバースエイジングプログラム", $staff->email, "messages_operation", ["name" => $this->Authentication->getIdentity()->last_name . $this->Authentication->getIdentity()->first_name, "url" => $fullUrl, 'today' => $formattedDateTime]]);
                    } else {
                        $this->Flash->error(__('講師のメールアドレスが設定されていません。'));
                    }
                } else {
                    $this->Flash->error(__('担当講師が設定されていません'));
                }
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('データを送信できませんでした。'));
        }
    }


     /**
     * Delete method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);

        $message = $this->Messages->get($id);
        $userId = $this->Authentication->getIdentity()->id;

        if ($message->user_id !== $userId || $message->sender !== '1') {
            throw new \Cake\Http\Exception\ForbiddenException('削除権限がありません。');
        }

        if ($this->Messages->delete($message)) {
            $this->Flash->success('メッセージを削除しました。');
        } else {
            $this->Flash->error('削除に失敗しました。');
        }

        return $this->redirect(['action' => 'index']);
    }

}
