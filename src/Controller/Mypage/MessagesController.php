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
                        $this->getMailer('App')->send('sendGridEmail', ["【コミュニケーションツール】脚やせ美脚リバースエイジングプログラム", $staff->email, "messages_operation", ["name" => $this->Authentication->getIdentity()->last_name . $this->Authentication->getIdentity()->first_name, "url" => $adminFullUrl, 'today' => $formattedDateTime]]);
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
}
