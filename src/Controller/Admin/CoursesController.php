<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\AppController;
use Cake\I18n\Time;
use Cake\ORM\Query;
use Cake\Core\Configure;

/**
 * Courses Controller
 *
 * @property \App\Model\Table\CoursesTable $courses
 * @method \App\Model\Entity\Course[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class CoursesController extends AppController
{
    public function beforeFilter(\Cake\Event\EventInterface $event)
    {
        parent::beforeFilter($event);

        $user = $this->Authentication->getIdentity();
        if($user && $user->role_id == 1) {
            $this->Flash->error('このページへのアクセス権がありません。');
            return $this->redirect(['controller' => 'UploadMovies', 'action' => 'index']);
        }
    }

    /**
     * [ajax] 並び順を変更
     */
    public function changeSort()
    {
        $this->autoRender = false;
        if (!$this->request->is('ajax')) {
            return;
        }

        // データ更新
        if ($this->request->is(['post'])) {
            $data = $this->request->getData();

            if (!empty($data['list'])) {
                $sort = 1;
                foreach ($data['list'] as $id) {
                    $course = $this->Courses->get($id);
                    $course->sort = $sort;
                    $this->Courses->save($course);
                    $sort++;
                }
                echo 'SUCCESS';
                return;
            }
        }
        echo 'ERROR';
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $courses = $this->Courses->find('all')
        ->contain([
            'CourseMovies'
        ])
        ->where([
            'Courses.is_deleted' => 0
        ])
        ->order([
            'Courses.sort' => 'asc'
        ])
        ->all();

        // 講座区分情報
        $courseType = Configure::read("COURSE_TYPE");

        $this->set(compact('courses', 'courseType'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $course = $this->Courses->newEmptyEntity();
        if ($this->request->is('post')) {
            $data = $this->request->getData();

            // 表示順
            $data['sort'] = 1;
            $lastData = $this->Courses->find('all')
            ->where([
                'is_deleted' => 0
            ])
            ->order([
                'sort' => 'desc'
            ])
            ->first();
            if ($lastData) {
                $data['sort'] = $lastData->sort + 1;
            }

            $course = $this->Courses->patchEntity($course, $data);

            if ($this->Courses->save($course)) {
                $this->Flash->success(__('登録しました。'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('データを登録できませんでした。入力内容を見直してください。'));
        }

        // 講座区分情報
        $courseType = Configure::read("COURSE_TYPE");

        $this->set(compact('course', 'courseType'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Course id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $course = $this->Courses->find('all')
        ->where([
            'id' => $id,
            'is_deleted' => 0
        ])
        ->first();

        if (!$course) {
            return $this->redirect(['action' => 'index']);
        }

        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();

            $course = $this->Courses->patchEntity($course, $data);

            if ($this->Courses->save($course)) {
                $this->Flash->success(__('更新しました。'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('データを登録できませんでした。入力内容を見直してください。'));
        }

        // 講座区分情報
        $courseType = Configure::read("COURSE_TYPE");

        $this->set(compact('course', 'courseType'));

        $this->render('add');
    }

    /**
     * Delete method
     *
     * @param string|null $id Course id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $course = $this->Courses->find('all')
        ->where([
            'id' => $id,
            'is_deleted' => 0
        ])
        ->first();

        $course->is_deleted = 1;
        if ($this->Courses->save($course)) {
            $this->Flash->success(__('削除しました。'));
        } else {
            $this->Flash->error(__('エラーが発生しました。'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
