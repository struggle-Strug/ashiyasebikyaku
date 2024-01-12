<?php
declare(strict_types=1);

namespace App\Controller\Mypage;

use App\Controller\AppController;
use Cake\ORM\Query;
use Cake\ORM\TableRegistry;

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

            $message = $this->Messages->newEmptyEntity();
            $message = $this->Messages->patchEntity($message, $data);
            if ($this->Messages->save($message)) {
                $this->Flash->success(__('メッセージを送信しました。'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('データを送信できませんでした。'));
        }
    }
}
