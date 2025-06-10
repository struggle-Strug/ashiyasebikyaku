<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\AppController;
use Cake\I18n\Time;
use Cake\ORM\Query;
use Cake\Core\Configure;

/**
 * informations Controller
 *
 * @property \App\Model\Table\informationsTable $informations
 * @method \App\Model\Entity\information[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class InformationsController extends AppController
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
        $query = $this->Informations->find('all')
        ->where([
            'is_deleted' => 0
        ])
        ->order([
            'start_datetime' => 'desc'
        ]);
        $informations = $this->paginate($query);

        // カテゴリー情報を取得してテンプレートに渡す
        $categories = Configure::read('INFORMATION_CATEGORY');
        $this->set(compact('informations', 'categories'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $information = $this->Informations->newEmptyEntity();
        if ($this->request->is('post')) {
            $data = $this->request->getData();
            $information = $this->Informations->patchEntity($information, $data);
            if ($this->Informations->save($information)) {
                $this->Flash->success(__('登録しました。'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('データを登録できませんでした。入力内容を見直してください。'));
        }

        // カテゴリー情報を取得してテンプレートに渡す
        $categories = Configure::read('INFORMATION_CATEGORY');
        $this->set(compact('information', 'categories'));
    }

    /**
     * Edit method
     *
     * @param string|null $id information id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $information = $this->Informations->find('all')
        ->where([
            'id' => $id,
            'is_deleted' => 0
        ])
        ->first();

        if (!$information) {
            return $this->redirect(['action' => 'index']);
        }

        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();
            $information = $this->Informations->patchEntity($information, $data);

            if ($this->Informations->save($information)) {
                $this->Flash->success(__('更新しました。'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('データを登録できませんでした。入力内容を見直してください。'));
        }

        // カテゴリー情報を取得してテンプレートに渡す
        $categories = Configure::read('INFORMATION_CATEGORY');
        $this->set(compact('information', 'categories'));

        $this->render('add');
    }

    /**
     * Delete method
     *
     * @param string|null $id information id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $information = $this->Informations->find('all')
        ->where([
            'id' => $id,
            'is_deleted' => 0
        ])
        ->first();

        if (!$information) {
            return $this->redirect(['action' => 'index']);
        }

        $information->is_deleted = 1;
        if ($this->Informations->save($information)) {
            $this->Flash->success(__('削除しました。'));
        } else {
            $this->Flash->error(__('エラーが発生しました。'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
