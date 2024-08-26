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
                'Courses.is_deleted' => 0
            ])
            ->order([
                'Courses.sort' => 'asc'
            ])
            ->all();

        $query = $this->LessonMovies->find('all')
            ->where([
                'is_deleted' => 0,
            ])
            ->order([
                'id' => 'desc'
            ]);
        $lessonMovies = $this->paginate($query);

        $this->set(compact('courses', 'lessonMovies'));
    }

    public function lessons()
    {
        $query = $this->LessonMovies->find('all')
            ->where([
                'is_deleted' => 0,
            ])
            ->order([
                'id' => 'desc'
            ]);
        $lessonMovies = $this->paginate($query);

        $this->set(compact('lessonMovies'));
    }

    public function courses()
    {
        $courses = $this->Courses->find('all')
            ->contain([
                'CourseMovies' => function (Query $q) {
                    return $q->where(['CourseMovies.is_deleted' => 0, 'CourseMovies.type' => 0])->order(['CourseMovies.sort' => 'asc']);
                }
            ])
            ->where([
                'Courses.is_deleted' => 0,
                'OR' => [
                    ['Courses.type' => 1],
                    ['Courses.type' => 2],
                    ['Courses.type' => 3],
                    ['Courses.type' => 4],
                ]
            ])
            ->order([
                'Courses.sort' => 'asc'
            ])
            ->all();
        $this->set(compact('courses'));
    }

    public function lesson($id = null)
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

    public function course($courseId = null, $courseMovieId = null)
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
