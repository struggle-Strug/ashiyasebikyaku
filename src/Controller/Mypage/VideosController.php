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
class VideosController extends AppController
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
         $this->loadModel('CourseMovies');
         $this->loadModel('Courses');
     }
    public function index()
    {
        $courses = $this->Courses->find('all')
            ->contain([
                'CourseMovies' => function (Query $q) {
                    return $q->where(['CourseMovies.is_deleted' => 0])->order(['CourseMovies.sort' => 'asc']);
                }
            ])
            ->where([
                'Courses.is_deleted' => 0,
                'Courses.type' => 5,
            ])
            ->order([
                'Courses.sort' => 'asc'
            ])
            ->all();
        $this->set(compact('courses'));
    }

    /**
     * View method
     *
     * @param string|null $id lessonMovie id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */

    public function view($courseId = null, $courseMovieId = null)
    {
        $course = $this->Courses->find('all')
            ->where([
                'id' => $courseId,
                'is_deleted' => 0
            ])
            ->first();

        if (!$course) {
            return $this->redirect(['action' => 'index']);
        }

        $courseMovie = $this->Courses->CourseMovies->find('all')
            ->where([
                'course_id' => $courseId,
                'id' => $courseMovieId,
                'is_deleted' => 0
            ])
            ->first();

        if (!$courseMovie) {
            return $this->redirect(['action' => 'index']);
        }

        $this->set(compact('course', 'courseMovie'));
    }
}
