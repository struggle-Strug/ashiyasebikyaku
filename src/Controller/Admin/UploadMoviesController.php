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
 * UploadMovies Controller
 *
 * @property \App\Model\Table\UploadMoviesTable $uploadMovies
 * @method \App\Model\Entity\information[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class UploadMoviesController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $query = $this->UploadMovies->find('all')
        ->where([
            'is_deleted' => 0
        ])
        ->order([
            'id' => 'desc'
        ]);
        $uploadMovies = $this->paginate($query);

        $this->set(compact('uploadMovies'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $uploadMovie = $this->UploadMovies->newEmptyEntity();
        if ($this->request->is('post')) {
            $data = $this->request->getData();
            $videoFile = $this->request->getData('video_file');

            if ($videoFile->getError() === 0) {
                $tmpVideo = sha1_file($videoFile->getStream()->getMetadata('uri')) . strtotime(date('Y-m-d H:i:s')) . $typeProfile;
            }

            $uploadMovie = $this->UploadMovies->patchEntity($uploadMovie, $data);
            if ($this->UploadMovies->save($uploadMovie)) {
                // 画像の保存
                $toMoveFile = null;
                $fromMoveFile = null;
                if ($videoFile->getError() === 0) {
                    $fromMoveFile = $videoFile->getStream()->getMetadata('uri');
                    $toMoveFile = 'uploads/upload_movie/' . $uploadMovie->id . '/' . $tmpVideo;
                }
                // ファイルアップロード処理
                if ($toMoveFile && $fromMoveFile) {
                    // 古いファイルを削除
                    if ($uploadMovie->video_url) {
                        unlink(WWW_ROOT . $uploadMovie->video_url);
                    }

                    // アップロード先ディレクトリの確認と生成
                    $dir = new Folder(WWW_ROOT . 'uploads/upload_movie/' . $uploadMovie->id, true, 0777);

                    // ファイルの移動
                    if (!@move_uploaded_file($fromMoveFile, WWW_ROOT . $toMoveFile)){
                        $this->Flash->set('動画の保存に失敗しました。');
                    } else {
                        // データベースを更新
                        $uploadMovie->video_url = '/' . $toMoveFile;
                        $this->UploadMovies->save($uploadMovie);
                    }
                }

                $this->Flash->success(__('登録しました。'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('データを登録できませんでした。入力内容を見直してください。'));
        }

        $this->set(compact('uploadMovie'));
    }


        /**
     * Delete method
     *
     * @param string|null $id UploadMovie id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $uploadMovie = $this->UploadMovies->find('all')
        ->where([
            'id' => $id,
            'is_deleted' => 0
        ])
        ->first();

        if (!$uploadMovie) {
            return $this->redirect(['action' => 'index']);
        }

        // ファイルを削除
        if ($uploadMovie->video_url) {
            unlink(WWW_ROOT . $uploadMovie->video_url);
        }
        // ディレクトリを削除
        if (is_dir(WWW_ROOT . 'uploads/upload_movie/' . $uploadMovie->id)) {
            rmdir(WWW_ROOT . 'uploads/upload_movie/' . $uploadMovie->id);
        }

        $uploadMovie->is_deleted = 1;
        if ($this->UploadMovies->save($uploadMovie)) {
            $this->Flash->success(__('削除しました。'));
        } else {
            $this->Flash->error(__('エラーが発生しました。'));
        }

        return $this->redirect(['action' => 'index']);
    }
}