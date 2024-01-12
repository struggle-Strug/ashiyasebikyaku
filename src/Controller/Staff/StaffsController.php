<?php
declare(strict_types=1);

namespace App\Controller\Staff;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;

/**
 * Staffs Controller
 *
 * @property \App\Model\Table\StaffsTable $Staffs
 * @method \App\Model\Entity\Staff[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class StaffsController extends AppController
{
    public function initialize(): void
    {
        parent::initialize();
        $this->Informations = TableRegistry::get('Informations');
    }

    public function beforeFilter(\Cake\Event\EventInterface $event)
    {
        parent::beforeFilter($event);
        $this->Authentication->addUnauthenticatedActions(['login']);
    }

    /**
     * Login method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function login()
    {
        $this->viewBuilder()->setLayout('staff_login');

        $this->request->allowMethod(['get', 'post']);

        $result = $this->Authentication->getResult();

        // Login successful or Already logged in
        if ($result->isValid()) {
            $redirect = $this->request->getQuery('redirect', [
                'controller' => 'Staffs',
                'action' => 'top',
            ]);

            // 削除フラグをチェック
            if (!empty($this->Authentication->getIdentity()->is_deleted)) {
                $this->Authentication->logout();
                $this->Flash->error(__('このアカウントは削除されています。'));
                return $this->redirect(['action' => 'login']);
            }

            return $this->redirect($redirect);
        }

        // Login failure
        if ($this->request->is('post') && !$result->isValid()) {
            $this->Flash->error(__('ログインIDまたはパスワードが違います。'));
        }
    }

    /**
     * Logout method
     *
     * @return \Cake\Http\Response|null|void
     */
    public function logout()
    {
        $result = $this->Authentication->getResult();

        if ($result->isValid()) {
            $this->Authentication->logout();
            return $this->redirect(['action' => 'login']);
        }
    }

    /**
     * Top method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function top()
    {
        $staff = $this->Staffs->find('all')
        ->where([
            'id' => $this->Authentication->getIdentity()->id
        ])
        ->first();

        // お知らせ取得
        $informations = $this->Informations->find('all')
        ->where([
            'OR' => [
                ['target' => '1'], // 全員
                ['target' => '2'] // スタッフ
            ],
            'AND' => [
                'OR' => [
                    ['end_datetime Is' => null],
                    ['end_datetime >=' => date('Y-m-d H:i:s')]
                ]
            ],
            'start_datetime <=' => date('Y-m-d H:i:s'),
            'is_deleted' => 0
        ])
        ->order([
            'id' => 'desc'
        ])
        ->all();

        $this->set(compact('staff', 'informations'));
    }
}
