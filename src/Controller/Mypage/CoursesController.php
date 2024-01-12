<?php
declare(strict_types=1);

namespace App\Controller\Mypage;

use App\Controller\AppController;
use Cake\ORM\Query;
use Cake\ORM\TableRegistry;

/**
 * Courses Controller
 *
 * @property \App\Model\Table\CoursesTable $Courses
 * @method \App\Model\Entity\Course[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class CoursesController extends AppController
{
    public function initialize(): void
    {
        parent::initialize();
        $this->WeeklyReports = TableRegistry::get('WeeklyReports');
        $this->EventEntries = TableRegistry::get('EventEntries');
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        // 初めての週次報告かチェック
        $firstWeeklyReport = $this->WeeklyReports->find('all')
        ->where([
            'user_id' => $this->Authentication->getIdentity()->id,
            'is_draft' => 0
        ])
        ->first();

        // イベントID=1に申込済みかチェック
        $eventEntry1 = $this->EventEntries->find('all')
        ->where([
            'user_id' => $this->Authentication->getIdentity()->id,
            'event_id' => 1
        ])
        ->first();
        // イベントID=2に申込済みかチェック
        $eventEntry2 = $this->EventEntries->find('all')
        ->where([
            'user_id' => $this->Authentication->getIdentity()->id,
            'event_id' => 2
        ])
        ->first();
        // イベントID=3に申込済みかチェック
        $eventEntry3 = $this->EventEntries->find('all')
        ->where([
            'user_id' => $this->Authentication->getIdentity()->id,
            'event_id' => 3
        ])
        ->first();
        // イベントID=4に申込済みかチェック
        $eventEntry4 = $this->EventEntries->find('all')
        ->where([
            'user_id' => $this->Authentication->getIdentity()->id,
            'event_id' => 4
        ])
        ->first();

        $this->set(compact('firstWeeklyReport', 'eventEntry1', 'eventEntry2', 'eventEntry3', 'eventEntry4'));
    }

    /**
     * Step1 method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function step1()
    {
        $courses = $this->Courses->find('all')
        ->contain([
            'CourseMovies' => function (Query $q) {
                return $q->where(['CourseMovies.is_deleted' => 0])->order(['CourseMovies.sort' => 'asc']);
            }
        ])
        ->where([
            'Courses.type' => 1,
            'Courses.is_deleted' => 0
        ])
        ->order([
            'Courses.sort' => 'asc'
        ])
        ->all();

        $this->set(compact('courses'));
    }

    /**
     * Step2 method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function step2()
    {
        // STEP2の場合、初回週報チェック
        $weeklyReport = $this->WeeklyReports->find('all')
        ->where([
            'user_id' => $this->Authentication->getIdentity()->id,
            'is_checked' => 1 // スタッフ確認済み
        ])
        ->first();

        if (!$weeklyReport) {
            $this->Flash->error(__('まだSTEP2はご利用になれません。'));
            return $this->redirect(['action' => 'index']);
        }

        $courses = $this->Courses->find('all')
        ->contain([
            'CourseMovies' => function (Query $q) {
                return $q->where(['CourseMovies.is_deleted' => 0])->order(['CourseMovies.sort' => 'asc']);
            }
        ])
        ->where([
            'Courses.type !=' => 1,
            'Courses.is_deleted' => 0
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
     * @return \Cake\Http\Response|null|void Renders view
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

        // STEP2の場合、初回週報チェック
        if ($course->type != '1') {
            $weeklyReport = $this->WeeklyReports->find('all')
            ->where([
                'user_id' => $this->Authentication->getIdentity()->id,
                'is_checked' => 1 // スタッフ確認済み
            ])
            ->first();

            if (!$weeklyReport) {
                $this->Flash->error(__('まだSTEP2はご利用になれません。'));
                return $this->redirect(['action' => 'index']);
            }
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
