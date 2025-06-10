<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\AppController;

use App\Mailer\AppMailer;
use Cake\Mailer\MailerAwareTrait;
use Cake\Core\Configure;
/**
 * AdminUsers Controller
 *
 * @property \App\Model\Table\AdminUsersTable $AdminUsers
 * @method \App\Model\Entity\AdminUser[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class AdminUsersController extends AppController
{
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
        $this->viewBuilder()->setLayout('admin_login');

        $this->request->allowMethod(['get', 'post']);

        $result = $this->Authentication->getResult();

        // Login successful or Already logged in
        if ($result->isValid()) {
            $user = $this->Authentication->getIdentity();
            if($user->role_id == 0) {
                $redirect = $this->request->getQuery('redirect', [
                    'controller' => 'Users',
                    'action' => 'index',
                ]);
                return $this->redirect($redirect);
            } else {
                $redirect = $this->request->getQuery('redirect', [
                    'controller' => 'UploadMovies',
                    'action' => 'index',
                ]);
                return $this->redirect($redirect);
            }
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

    public function index()
    {
        $param = $this->request->getQuery();
        $query = $this->AdminUsers->find("all");

        if (!empty($param['name'])) {
            $query->where([
                'AdminUsers.name LIKE' => '%' . $param['name'] . '%',
            ]);
        } else {
            $param['name'] = '';
        }

        $adminUsers = $this->paginate($query);
        $this->set(compact('param', 'adminUsers'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Admin User id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $adminUser = $this->AdminUsers->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $adminUser = $this->AdminUsers->patchEntity($adminUser, $this->request->getData());
            if ($this->AdminUsers->save($adminUser)) {
                $this->Flash->success(__('パスワードを更新しました。'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('データを登録できませんでした。入力内容を見直してください。'));
        }
        $roles = Configure::read('ADMIN_ROLES');
        $this->set(compact('adminUser', 'roles'));
    }

    use MailerAwareTrait;

    public function add()
    {
        $adminUser = $this->AdminUsers->newEmptyEntity();
        if ($this->request->is('post')) {
            $adminUser = $this->AdminUsers->patchEntity($adminUser, $this->request->getData());
            if ($this->AdminUsers->save($adminUser)) {
                $this->Flash->success(__('管理者を追加しました。'));
                return $this->redirect(['controller' => 'AdminUsers', 'action' => 'index']);
            }
            $this->Flash->error(__('管理者を追加できませんでした。'));
        }
        $roles = Configure::read('ADMIN_ROLES');
        $this->set(compact('adminUser', 'roles'));
    }

    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']); // 安全のために POST または DELETE メソッドのみを許可

        $admin = $this->AdminUsers->get($id);
        if (!$admin) {
            throw new NotFoundException(__('Invalid admin'));
        }

        if ($this->AdminUsers->delete($admin)) {
            $this->Flash->success(__('管理者を削除しました。', h($id)));
        } else {
            $this->Flash->error(__('管理者を削除できませんでした。'));
        }

        return $this->redirect(['action' => 'index']);
    }

}
