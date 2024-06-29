<?php
declare(strict_types=1);

namespace App\Controller\Mypage;

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
class ArchivesController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function initialize(): void
     {
         parent::initialize();
         $this->loadModel('LessonMovies');
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

        $this->set(compact('lessonMovies'));
    }

    /**
     * View method
     *
     * @param string|null $id lessonMovie id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
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

        $this->set(compact('lessonMovie'));
    }
}
