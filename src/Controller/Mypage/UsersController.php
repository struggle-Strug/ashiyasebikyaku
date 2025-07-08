<?php
declare(strict_types=1);

namespace App\Controller\Mypage;

use App\Controller\AppController;
use Cake\ORM\Query;
use Cake\ORM\TableRegistry;
use Cake\I18n\Time;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class UsersController extends AppController
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
        $this->viewBuilder()->setLayout('mypage_login');

        $this->request->allowMethod(['get', 'post']);

        $result = $this->Authentication->getResult();

        if ($result->isValid()) {
            $user = $this->Authentication->getIdentity();

            // 削除フラグをチェック
            if (!empty($user->is_deleted)) {
                $this->Authentication->logout();
                $this->Flash->error(__('このアカウントは削除されています。'));
                return $this->redirect(['action' => 'login']);
            }

            // 利用停止（course_id = 3）
            if ($user->course_id === 3) {
            
                // ユーザーが利用停止期間を持っているかチェック（例: user に start_suspend, end_suspend がある）
                if (!empty($user->pause_start) && !empty($user->pause_end)) {
                    // 有効期限チェック
                    $startDatetime = $user->pause_start->i18nFormat('yyyy-MM-dd HH:mm:ss');
                    $endDatetime = $user->pause_end->i18nFormat('yyyy-MM-dd HH:mm:ss');
                    
                    if (time() >= strtotime($startDatetime) && time()<= strtotime($endDatetime)) {
                        $this->Authentication->logout();
                        $this->Flash->error(__('このコースのアカウントは現在ログインできません。'));
                        return $this->redirect(['action' => 'login']);
                    }
                }
            }
            

            // 有効期限チェック
            $startDatetime = $user->start_datetime->i18nFormat('yyyy-MM-dd HH:mm:ss');
            $endDatetime = ($user->end_datetime) ? $user->end_datetime->i18nFormat('yyyy-MM-dd HH:mm:ss') : '2999-12-31 23:59:59';

            if (strtotime($startDatetime) > time() || strtotime($endDatetime) < time()) {
                $this->Flash->error(__('このアカウントは現在ご利用いただけません。'));
                $this->Authentication->logout();
                return $this->redirect(['action' => 'login']);
            }

            $redirect = $this->request->getQuery('redirect', [
                'controller' => 'Users',
                'action' => 'top',
            ]);
            return $this->redirect($redirect);
        }

        if ($this->request->is('post') && !$result->isValid()) {
            $this->Flash->error(__('メールアドレスまたはパスワードが違います。'));
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
        $user = $this->Users->find('all')
        ->contain([
            'Messages' => function (Query $q) {
                return $q->where([
                    'Messages.user_id' => $this->Authentication->getIdentity()->id,
                    'Messages.sender IN' => ['2', '3'],
                    'Messages.is_read' => 0
                ]);
            },
            'UserPoints' => function (Query $q) {
                return $q->order([
                    'UserPoints.created' => 'desc'
                ]);
            },
        ])
        ->where([
            'Users.id' => $this->Authentication->getIdentity()->id
        ])
        ->first();

        // お知らせ取得
        $informations = $this->Informations->find('all')
        ->where([
            'OR' => [
                ['target' => '1'], // 全員
                ['target' => '3'] // 利用者
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
        ->where([
            'OR' => [
                ['category' => 10],
                ['category IS' => null]
            ]
        ])
        ->order([
            'id' => 'desc'
        ])
        ->all();

        $this->set(compact('user', 'informations'));
    }
}
