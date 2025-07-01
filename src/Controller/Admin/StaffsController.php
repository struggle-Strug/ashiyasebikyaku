<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\AppController;
use Cake\I18n\Time;
use Cake\ORM\Query;
use Cake\Core\Configure;

/**
 * Staffs Controller
 *
 * @property \App\Model\Table\StaffsTable $staffs
 * @method \App\Model\Entity\staff[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class StaffsController extends AppController
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
        $staffs = $this->Staffs->find('all')
        ->contain(['ParentStaff'])
        ->where(['Staffs.is_deleted' => 0])
        ->all();

        $this->set(compact('staffs'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        // Get the user with the highest ID
        $lastStaff = $this->Staffs->find()
        ->select(['id'])
        ->orderDesc('id')
        ->first();

        $nextStaffNumber= $lastStaff ? $lastStaff->id + 3000000 + 1 : 1;
        $staff = $this->Staffs->newEmptyEntity([
            'loginid' => $nextStaffNumber
        ]);
        if ($this->request->is('post')) {
            
            $staff = $this->Staffs->patchEntity($staff, $this->request->getData());
            if ($this->Staffs->save($staff)) {
                $this->Flash->success(__('登録しました。'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('データを登録できませんでした。入力内容を見直してください。'));
        }

        $parentStaffs = $this->Staffs->find('list', [
            'keyField' => 'id',
            'valueField' => 'name',
            'conditions' => ['is_deleted' => 0]
        ])->toArray();

        $this->set(compact('staff', 'parentStaffs'));
    }

    /**
     * Edit method
     *
     * @param string|null $id staff id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $staff = $this->Staffs->find('all')
        ->where([
            'id' => $id,
            'is_deleted' => 0
        ])
        ->first();

        if (!$staff) {
            return $this->redirect(['action' => 'index']);
        }

        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();

            if ($data['new_password']) {
                $data['password'] = $data['new_password'];
            }

            $staff = $this->Staffs->patchEntity($staff, $data);

            if ($this->Staffs->save($staff)) {
                $this->Flash->success(__('更新しました。'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('データを登録できませんでした。入力内容を見直してください。'));
        }
        $parentStaffs = $this->Staffs->find('list', [
            'keyField' => 'id',
            'valueField' => 'name',
            'conditions' => ['is_deleted' => 0]
            ])->toArray();

        $this->set(compact('staff', 'parentStaffs'));

        $this->render('add');
    }

    /**
     * Delete method
     *
     * @param string|null $id staff id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $staff = $this->Staffs->find('all')
        ->where([
            'id' => $id,
            'is_deleted' => 0
        ])
        ->first();

        $staff->is_deleted = 1;
        if ($this->Staffs->save($staff)) {
            $this->Flash->success(__('削除しました。'));
        } else {
            $this->Flash->error(__('エラーが発生しました。'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
