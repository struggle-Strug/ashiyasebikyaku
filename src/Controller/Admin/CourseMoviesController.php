<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\AppController;
use Cake\I18n\Time;
use Cake\ORM\Query;
use Cake\Core\Configure;
use Cake\Filesystem\Folder;
use Cake\Filesystem\File;

/**
 * CourseMovies Controller
 *
 * @property \App\Model\Table\CourseMoviesTable $courseMovies
 * @method \App\Model\Entity\information[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class CourseMoviesController extends AppController
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
                    $courseMovie = $this->CourseMovies->get($id);
                    $courseMovie->sort = $sort;
                    $this->CourseMovies->save($courseMovie);
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
    public function index($courseId = null)
    {
        $course = $this->CourseMovies->Courses->find('all')
        ->where([
            'id' => $courseId,
            'is_deleted' => 0
        ])
        ->first();

        if (!$course) {
            return $this->redirect(['controller' => 'Courses', 'action' => 'index']);
        }

        $courseMovies = $this->CourseMovies->find('all')
        ->contain([
            'Courses'
        ])
        ->where([
            'CourseMovies.course_id' => $courseId,
            'CourseMovies.is_deleted' => 0
        ])
        ->order([
            'CourseMovies.sort' => 'asc'
        ])
        ->all();

        $this->set(compact('course', 'courseMovies'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add($courseId = null)
    {
        $course = $this->CourseMovies->Courses->find('all')
        ->where([
            'id' => $courseId,
            'is_deleted' => 0
        ])
        ->first();

        if (!$course) {
            return $this->redirect(['controller' => 'Courses', 'action' => 'index']);
        }

        $courseMovie = $this->CourseMovies->newEmptyEntity();
        if ($this->request->is('post')) {
            $data = $this->request->getData();

            $data['course_id'] = $courseId;

            // 表示順
            $data['sort'] = 1;
            $lastData = $this->CourseMovies->find('all')
            ->where([
                'course_id' => $courseId,
                'is_deleted' => 0
            ])
            ->order([
                'sort' => 'desc'
            ])
            ->first();
            if ($lastData) {
                $data['sort'] = $lastData->sort + 1;
            }

            $imageFile = $this->request->getData('image_file');

            if ($imageFile->getError() === 0) {
                if ($imageFile->getClientMediaType() == 'image/jpeg') {
                    $typeProfile = '.jpg';
                } elseif ($imageFile->getClientMediaType() == 'image/png') {
                    $typeProfile = '.png';
                }

                // check MIME-TYPE
                $finfo  = finfo_open(FILEINFO_MIME_TYPE);
                $mimetype = finfo_file($finfo, $_FILES['image_file']['tmp_name']);
                finfo_close($finfo);
                if ($mimetype != 'image/jpeg' && $mimetype != 'image/png') {
                    $this->Flash->error('画像はJPEGまたはPNGファイルのみ登録できます。');
                    $this->set(compact('course', 'CourseMovie'));
                    return;
                }
                // check file size
                if ($imageFile->getSize() > 4000000) {
                    $this->Flash->error('画像の容量は4MB以下のものを指定してください。');
                    $this->set(compact('course', 'CourseMovie'));
                    return;
                }
                // temp filename
                $tmpImage = sha1_file($imageFile->getStream()->getMetadata('uri')) . strtotime(date('Y-m-d H:i:s')) . $typeProfile;
            }

            $courseMovie = $this->CourseMovies->patchEntity($courseMovie, $data);
            if ($this->CourseMovies->save($courseMovie)) {
                // 画像の保存
                $toMoveFile = null;
                $fromMoveFile = null;
                if ($imageFile->getError() === 0) {
                    $fromMoveFile = $imageFile->getStream()->getMetadata('uri');
                    $toMoveFile = 'uploads/course_movie/' . $courseMovie->id . '/' . $tmpImage;
                }
                // ファイルアップロード処理
                if ($toMoveFile && $fromMoveFile) {
                    // 古いファイルを削除
                    if ($courseMovie->image_url) {
                        unlink(WWW_ROOT . $courseMovie->image_url);
                    }

                    // アップロード先ディレクトリの確認と生成
                    $dir = new Folder(WWW_ROOT . 'uploads/course_movie/' . $courseMovie->id, true, 0777);

                    // ファイルの移動
                    if (!@move_uploaded_file($fromMoveFile, WWW_ROOT . $toMoveFile)){
                        $this->Flash->set('画像の保存に失敗しました。');
                    } else {
                        // データベースを更新
                        $courseMovie->image_url = '/' . $toMoveFile;
                        $this->CourseMovies->save($courseMovie);
                    }
                }

                $this->Flash->success(__('登録しました。'));

                return $this->redirect(['action' => 'index', $courseId]);
            }
            $this->Flash->error(__('データを登録できませんでした。入力内容を見直してください。'));
        }

        $this->set(compact('course', 'courseMovie'));
    }

    /**
     * Edit method
     *
     * @param string|null $id CourseMovie id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($courseId = null, $id = null)
    {
        $course = $this->CourseMovies->Courses->find('all')
        ->where([
            'id' => $courseId,
            'is_deleted' => 0
        ])
        ->first();

        if (!$course) {
            return $this->redirect(['controller' => 'Courses', 'action' => 'index']);
        }

        $courseMovie = $this->CourseMovies->find('all')
        ->where([
            'course_id' => $courseId,
            'id' => $id,
            'is_deleted' => 0
        ])
        ->first();

        if (!$courseMovie) {
            return $this->redirect(['action' => 'index', $courseId]);
        }

        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();
            $imageFile = $this->request->getData('image_file');

            if ($imageFile->getError() === 0) {
                if ($imageFile->getClientMediaType() == 'image/jpeg') {
                    $typeProfile = '.jpg';
                } elseif ($imageFile->getClientMediaType() == 'image/png') {
                    $typeProfile = '.png';
                }

                // check MIME-TYPE
                $finfo  = finfo_open(FILEINFO_MIME_TYPE);
                $mimetype = finfo_file($finfo, $_FILES['image_file']['tmp_name']);
                finfo_close($finfo);
                if ($mimetype != 'image/jpeg' && $mimetype != 'image/png') {
                    $this->Flash->error('画像はJPEGまたはPNGファイルのみ登録できます。');
                    $this->set(compact('course', 'CourseMovie'));
                    return;
                }
                // check file size
                if ($imageFile->getSize() > 4000000) {
                    $this->Flash->error('画像の容量は4MB以下のものを指定してください。');
                    $this->set(compact('course', 'CourseMovie'));
                    return;
                }
                // temp filename
                $tmpImage = sha1_file($imageFile->getStream()->getMetadata('uri')) . strtotime(date('Y-m-d H:i:s')) . $typeProfile;
            }

            $courseMovie = $this->CourseMovies->patchEntity($courseMovie, $data);

            if ($this->CourseMovies->save($courseMovie)) {
                // 画像の保存
                $toMoveFile = null;
                $fromMoveFile = null;
                if ($imageFile->getError() === 0) {
                    $fromMoveFile = $imageFile->getStream()->getMetadata('uri');
                    $toMoveFile = 'uploads/course_movie/' . $courseMovie->id . '/' . $tmpImage;
                }
                // ファイルアップロード処理
                if ($toMoveFile && $fromMoveFile) {
                    // 古いファイルを削除
                    if ($courseMovie->image_url) {
                        unlink(WWW_ROOT . $courseMovie->image_url);
                    }

                    // アップロード先ディレクトリの確認と生成
                    $dir = new Folder(WWW_ROOT . 'uploads/course_movie/' . $courseMovie->id, true, 0777);

                    // ファイルの移動
                    if (!@move_uploaded_file($fromMoveFile, WWW_ROOT . $toMoveFile)){
                        $this->Flash->set('画像の保存に失敗しました。');
                    } else {
                        // データベースを更新
                        $courseMovie->image_url = '/' . $toMoveFile;
                        $this->CourseMovies->save($courseMovie);
                    }
                }

                $this->Flash->success(__('更新しました。'));

                return $this->redirect(['action' => 'index', $courseId]);
            }
            $this->Flash->error(__('データを登録できませんでした。入力内容を見直してください。'));
        }

        $this->set(compact('course', 'courseMovie'));

        $this->render('add');
    }

    /**
     * Delete method
     *
     * @param string|null $id CourseMovie id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($courseId = null, $id = null)
    {
        $course = $this->CourseMovies->Courses->find('all')
        ->where([
            'id' => $courseId,
            'is_deleted' => 0
        ])
        ->first();

        if (!$course) {
            return $this->redirect(['controller' => 'Courses', 'action' => 'index']);
        }

        $courseMovie = $this->CourseMovies->find('all')
        ->where([
            'course_id' => $courseId,
            'id' => $id,
            'is_deleted' => 0
        ])
        ->first();

        if (!$courseMovie) {
            return $this->redirect(['action' => 'index', $courseId]);
        }

        // ファイルを削除
        if ($courseMovie->image_url) {
            unlink(WWW_ROOT . $courseMovie->image_url);
        }
        // ディレクトリを削除
        if (is_dir(WWW_ROOT . 'uploads/course_movie/' . $courseMovie->id)) {
            rmdir(WWW_ROOT . 'uploads/course_movie/' . $courseMovie->id);
        }

        $courseMovie->is_deleted = 1;
        if ($this->CourseMovies->save($courseMovie)) {
            $this->Flash->success(__('削除しました。'));
        } else {
            $this->Flash->error(__('エラーが発生しました。'));
        }

        return $this->redirect(['action' => 'index', $courseId]);
    }
}
