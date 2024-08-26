<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\AppController;
use Cake\I18n\Time;
use Cake\ORM\Query;
use Cake\Core\Configure;
use Cake\Filesystem\Folder;
use Cake\Filesystem\File;
use Cake\Routing\Router;

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

    public function add()
    {
        $uploadMovie = $this->UploadMovies->newEmptyEntity();

        if ($this->request->is('post')) {
            $file = $this->request->getData('video_file');
            if ($file) {
                $fileSize = $file->getSize();
                $filename = time() . '_' . $file->getClientFilename();
                $uploadPath = WWW_ROOT . 'uploads/upload_movie/';
                $uploadFile = $uploadPath . $filename;
                $file->moveTo($uploadFile);
                $fullUrl = Router::url('/', true) . 'uploads/upload_movie/' . $filename;
                $uploadMovie->movie_url = $fullUrl;
            }
            $uploadMovie->name = $this->request->getData('name');
            $uploadMovie->created = date('Y-m-d H:i:s');
            $uploadMovie->is_deleted = false;

            if ($this->UploadMovies->save($uploadMovie)) {
                $this->Flash->success(__('動画が保存されました。'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('動画の保存に失敗しました。もう一度お試しください。'));
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