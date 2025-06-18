<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\AppController;
use Cake\I18n\Time;
use Cake\ORM\Query;
use Cake\Core\Configure;

/**
 * Events Controller
 *
 * @property \App\Model\Table\CategoriesTable $categories
 * @method \App\Model\Entity\category[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class CategoriesController extends AppController
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
        $query = $this->Categories->find('all')
        ->order([
            'Categories.name' => 'asc'
        ]);
        $param = $this->request->getQuery();

        if (!empty($param['name'])) {
            $query->where([
                'Categories.name LIKE' => '%' . $param['name'] . '%',
            ]);
        } else {
            $param['name'] = '';
        }

        $categories = $this->paginate($query);

        $this->set(compact('param', 'categories'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $category = $this->Categories->newEmptyEntity();
        $param = $this->request->getQuery();

        if ($this->request->is('post')) {
            $data = $this->request->getData();

            $error = false;
            if (empty($data['name'])) {
                $error = true;
                $this->Flash->error(__('カテゴリー名を入力してください。'));
            }

            if (!$error) {
                $category = $this->Categories->patchEntity($category, $data);

                if ($this->Categories->save($category)) {
                    $this->Flash->success(__('登録しました。'));

                    return $this->redirect(['action' => 'index', '?' => $param]);
                }
                $this->Flash->error(__('データを登録できませんでした。入力内容を見直してください。'));
            }
        }

        $this->set(compact('category', 'param'));
    }

    /**
     * Edit method
     *
     * @param string|null $id event id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $category = $this->Categories->find('all')
            ->where(['Categories.id' => $id])
            ->first();

        if (!$category) {
            return $this->redirect(['action' => 'index']);
        }

        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();
            $error = false;

            if (empty($data['name'])) {
                $error = true;
                $this->Flash->error(__('カテゴリー名を入力してください。'));
            }

            if (!$error) {
                $category = $this->Categories->patchEntity($category, $data);

                if ($this->Categories->save($category)) {
                    $this->Flash->success(__('更新しました。'));
                    return $this->redirect(['action' => 'index']);
                }

                $this->Flash->error(__('データを登録できませんでした。入力内容を見直してください。'));
            }
        }

        $param = $this->request->getQuery();

        $this->set(compact('category', 'param'));

        $this->render('add');
    }


    /**
     * Delete method
     *
     * @param string|null $id event id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $category = $this->Categories->find('all')
        ->where([
            'id' => $id,
        ])
        ->first();

        if (!$category) {
            return $this->redirect(['action' => 'index']);
        };

        $this->Categories->deleteAll([
            'id' => $id
        ], false);

        if ($this->Categories->save($category)) {
            $this->Flash->success(__('削除しました。'));
        } else {
            $this->Flash->error(__('エラーが発生しました。'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
