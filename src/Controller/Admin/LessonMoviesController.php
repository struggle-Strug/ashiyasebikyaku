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
 * LessonMovies Controller
 *
 * @property \App\Model\Table\LessonMoviesTable $lessonMovies
 * @method \App\Model\Entity\information[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class LessonMoviesController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */

    public function initialize(): void
    {
        parent::initialize();
        $this->loadModel('Categories');
    }

    public function index()
    {
        $query = $this->LessonMovies->find('all')
        ->where([
            'is_deleted' => 0
        ])
        ->order([
            'id' => 'desc'
        ]);
        $lessonMovies = $this->paginate($query);

        $categories = $this->Categories->find('all')->combine('id', 'name')->toArray();

        $this->set(compact('lessonMovies', 'categories'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $lessonMovie = $this->LessonMovies->newEmptyEntity();
        $categories = $this->Categories->find('all')->combine('id', 'name')->toArray();
        if ($this->request->is('post')) {
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
                    $this->set(compact('lessonMovie'));
                    return;
                }
                // check file size
                if ($imageFile->getSize() > 4000000) {
                    $this->Flash->error('画像の容量は4MB以下のものを指定してください。');
                    $this->set(compact('lessonMovie'));
                    return;
                }
                // temp filename
                $tmpImage = sha1_file($imageFile->getStream()->getMetadata('uri')) . strtotime(date('Y-m-d H:i:s')) . $typeProfile;
            }

            $lessonMovie = $this->LessonMovies->patchEntity($lessonMovie, $data);
            if ($this->LessonMovies->save($lessonMovie)) {
                // 画像の保存
                $toMoveFile = null;
                $fromMoveFile = null;
                if ($imageFile->getError() === 0) {
                    $fromMoveFile = $imageFile->getStream()->getMetadata('uri');
                    $toMoveFile = 'uploads/lesson_movie/' . $lessonMovie->id . '/' . $tmpImage;
                }
                // ファイルアップロード処理
                if ($toMoveFile && $fromMoveFile) {
                    // 古いファイルを削除
                    if ($lessonMovie->image_url) {
                        unlink(WWW_ROOT . $lessonMovie->image_url);
                    }

                    // アップロード先ディレクトリの確認と生成
                    $dir = new Folder(WWW_ROOT . 'uploads/lesson_movie/' . $lessonMovie->id, true, 0777);

                    // ファイルの移動
                    if (!@move_uploaded_file($fromMoveFile, WWW_ROOT . $toMoveFile)){
                        $this->Flash->set('画像の保存に失敗しました。');
                    } else {
                        // データベースを更新
                        $lessonMovie->image_url = '/' . $toMoveFile;
                        $this->LessonMovies->save($lessonMovie);
                    }
                }

                $this->Flash->success(__('登録しました。'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('データを登録できませんでした。入力内容を見直してください。'));
        }

        $categories = $this->Categories->find('all')->combine('id', 'name')->toArray();

        $this->set(compact('lessonMovie', 'categories'));
    }

    /**
     * Edit method
     *
     * @param string|null $id lessonMovie id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $lessonMovie = $this->LessonMovies->find('all')
        ->where([
            'id' => $id,
            'is_deleted' => 0
        ])
        ->first();

        $categories = $this->Categories->find('all')->combine('id', 'name')->toArray(); 

        if (!$lessonMovie) {
            return $this->redirect(['action' => 'index']);
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
                    $this->set(compact('lessonMovie'));
                    return;
                }
                // check file size
                if ($imageFile->getSize() > 4000000) {
                    $this->Flash->error('画像の容量は4MB以下のものを指定してください。');
                    $this->set(compact('lessonMovie'));
                    return;
                }
                // temp filename
                $tmpImage = sha1_file($imageFile->getStream()->getMetadata('uri')) . strtotime(date('Y-m-d H:i:s')) . $typeProfile;
            }

            $lessonMovie = $this->LessonMovies->patchEntity($lessonMovie, $data);

            if ($this->LessonMovies->save($lessonMovie)) {
                // 画像の保存
                $toMoveFile = null;
                $fromMoveFile = null;
                if ($imageFile->getError() === 0) {
                    $fromMoveFile = $imageFile->getStream()->getMetadata('uri');
                    $toMoveFile = 'uploads/lesson_movie/' . $lessonMovie->id . '/' . $tmpImage;
                }
                // ファイルアップロード処理
                if ($toMoveFile && $fromMoveFile) {
                    // 古いファイルを削除
                    if ($lessonMovie->image_url) {
                        unlink(WWW_ROOT . $lessonMovie->image_url);
                    }

                    // アップロード先ディレクトリの確認と生成
                    $dir = new Folder(WWW_ROOT . 'uploads/lesson_movie/' . $lessonMovie->id, true, 0777);

                    // ファイルの移動
                    if (!@move_uploaded_file($fromMoveFile, WWW_ROOT . $toMoveFile)){
                        $this->Flash->set('画像の保存に失敗しました。');
                    } else {
                        // データベースを更新
                        $lessonMovie->image_url = '/' . $toMoveFile;
                        $this->LessonMovies->save($lessonMovie);
                    }
                }

                $this->Flash->success(__('更新しました。'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('データを登録できませんでした。入力内容を見直してください。'));
        }

        $this->set(compact('lessonMovie', 'categories'));

        $this->render('add');
    }

    /**
     * Delete method
     *
     * @param string|null $id lessonMovie id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $lessonMovie = $this->LessonMovies->find('all')
        ->where([
            'id' => $id,
            'is_deleted' => 0
        ])
        ->first();

        if (!$lessonMovie) {
            return $this->redirect(['action' => 'index']);
        }

        // ファイルを削除
        if ($lessonMovie->image_url) {
            unlink(WWW_ROOT . $lessonMovie->image_url);
        }
        // ディレクトリを削除
        if (is_dir(WWW_ROOT . 'uploads/lesson_movie/' . $lessonMovie->id)) {
            rmdir(WWW_ROOT . 'uploads/lesson_movie/' . $lessonMovie->id);
        }

        $lessonMovie->is_deleted = 1;
        if ($this->LessonMovies->save($lessonMovie)) {
            $this->Flash->success(__('削除しました。'));
        } else {
            $this->Flash->error(__('エラーが発生しました。'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
