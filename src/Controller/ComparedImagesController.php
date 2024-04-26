<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * ComparedImages Controller
 *
 * @property \App\Model\Table\ComparedImagesTable $ComparedImages
 * @method \App\Model\Entity\ComparedImage[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ComparedImagesController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Users'],
        ];
        $comparedImages = $this->paginate($this->ComparedImages);

        $this->set(compact('comparedImages'));
    }

    /**
     * View method
     *
     * @param string|null $id Compared Image id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $comparedImage = $this->ComparedImages->get($id, [
            'contain' => ['Users'],
        ]);

        $this->set(compact('comparedImage'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $comparedImage = $this->ComparedImages->newEmptyEntity();
        if ($this->request->is('post')) {
            $comparedImage = $this->ComparedImages->patchEntity($comparedImage, $this->request->getData());
            if ($this->ComparedImages->save($comparedImage)) {
                $this->Flash->success(__('The compared image has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The compared image could not be saved. Please, try again.'));
        }
        $users = $this->ComparedImages->Users->find('list', ['limit' => 200])->all();
        $this->set(compact('comparedImage', 'users'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Compared Image id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $comparedImage = $this->ComparedImages->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $comparedImage = $this->ComparedImages->patchEntity($comparedImage, $this->request->getData());
            if ($this->ComparedImages->save($comparedImage)) {
                $this->Flash->success(__('The compared image has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The compared image could not be saved. Please, try again.'));
        }
        $users = $this->ComparedImages->Users->find('list', ['limit' => 200])->all();
        $this->set(compact('comparedImage', 'users'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Compared Image id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $comparedImage = $this->ComparedImages->get($id);
        if ($this->ComparedImages->delete($comparedImage)) {
            $this->Flash->success(__('The compared image has been deleted.'));
        } else {
            $this->Flash->error(__('The compared image could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
