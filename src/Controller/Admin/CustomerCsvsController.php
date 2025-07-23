<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\AppController;

use Cake\Utility\Text;
use Cake\I18n\FrozenDate;
use Cake\Core\Configure;

class CustomerCsvsController extends AppController
{
    public function beforeFilter(\Cake\Event\EventInterface $event)
    {
        parent::beforeFilter($event);

        $user = $this->Authentication->getIdentity();
        if ($user && $user->role_id == 1) {
            $this->Flash->error('このページへのアクセス権がありません。');
            return $this->redirect(['controller' => 'UploadMovies', 'action' => 'index']);
        }
    }

    public function initialize(): void
    {
        parent::initialize();

        $this->loadModel('Users');
    }

    public function index()
    {}

    public function exportCustomerCsv()
    {
        $year = $this->request->getQuery('year');
        $month = $this->request->getQuery('month');

        if (!$year || !$month) {
            $this->Flash->error('年と月を指定してください。');
            return $this->redirect($this->referer());
        }

        $startDate = new \Cake\I18n\FrozenDate("{$year}-{$month}-01");
        $endDate = $startDate->endOfMonth();

        $users = $this->Users->find()
            ->where([
                'Users.is_deleted' => 0,
                'Users.start_datetime >=' => $startDate,
                'Users.start_datetime <=' => $endDate
            ])
            ->contain([
                'Staffs',
                'Habits',
                'Purposes',
                'Mentions',
                'Consults',
                'SelfDatas',
                'BodyDatas',
                'Differences'
            ])
            ->all();

        $this->response = $this->response
            ->withDownload("顧客カルテ_{$year}_{$month}.csv")
            ->withType('csv');

        $csv = fopen('php://output', 'w');

        // CSVヘッダー（based on your uploaded CSV）
        fputcsv($csv, [
            '受講生ID', '氏名', 'カナ', 'コース名', '開始日', '担当講師',
            '職業', '食事習慣', '睡眠', 'ストレス要因', '運動歴', '既往歴', '注意点',
            '容姿の悩み', '機能の悩み', '容姿の目標', '機能の目標', '未来像',
            'モチベーション', '離脱リスク',
            'テーマ', '指導内容', '注意点（分かったこと）', '申し送り事項（次やること）'
        ]);

        foreach ($users as $user) {
            $habit = $user->habits ?? [];
            $purpose = $user->purposes ?? [];
            $mention = $user->mentions ?? [];
            $consult = !empty($user->consults) ? end($user->consults) : null;

            fputcsv($csv, [
                $user->id,
                $user->last_name . ' ' . $user->first_name,
                $user->last_name_kana . ' ' . $user->first_name_kana,
                Configure::read("COURSE")[$user->course_id] ?? '',
                $user->start_datetime?->format('Y/m/d') ?? '',
                $user->staff->name ?? '',

                $habit[0]->occupation ?? '',
                $habit[0]->eating_habits ?? '',
                $habit[0]->sleep ?? '',
                $habit[0]->stress_factors ?? '',
                $habit[0]->exercise_history ?? '',
                $habit[0]->medical_history ?? '',
                $habit[0]->other_notes ?? '',

                $purpose[0]->current_concerns_appearance ?? '',
                $purpose[0]->current_concerns_function ?? '',
                $purpose[0]->goal_appearance ?? '',
                $purpose[0]->goal_function ?? '',
                $purpose[0]->future_self_image ?? '',

                $mention[0]->motivation_source ?? '',
                $mention[0]->dropout_risk ?? '',

                $consult->theme ?? '',
                $consult->instruction ?? '',
                $consult->note ?? '',
                $consult->next_step ?? '',
            ]);
        }

        fclose($csv);
        return $this->response;
    }
}
