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
    {
        $param = $this->request->getQuery();
        $this->set(compact('param'));
    }

    public function exportCustomerCsv()
    {
        $start = $this->request->getQuery('start_date');
        $end = $this->request->getQuery('end_date');

        if (!$start || !$end) {
            $this->Flash->error('開始日と終了日を指定してください。');
            return $this->redirect($this->referer());
        }

        try {
            $startDate = new \Cake\I18n\FrozenDate($start);
            $endDate = new \Cake\I18n\FrozenDate($end);
        } catch (\Exception $e) {
            $this->Flash->error('日付の形式が正しくありません。');
            return $this->redirect($this->referer());
        }

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

        $output = fopen('php://temp', 'r+');

        fputcsv($output, [
            '受講生ID', '基本情報_氏名', '基本情報_カナ', '基本情報_コース名', '基本情報_生年月日', '基本情報_住所', '基本情報_開始日', '基本情報_担当講師',
            '生活習慣_職業', '生活習慣_食事習慣', '生活習慣_睡眠', '生活習慣_ストレス要因', '生活習慣_運動歴', '生活習慣_既往歴', '生活習慣_注意点',
            '目標設定_メール', '目標設定_名前', '目標設定_現在の悩み(容姿面)', '目標設定_現在の悩み(機能面)', '目標設定_期間中の目標(容姿面)', '目標設定_期間中の目標(機能面)', '目標設定_未来のセルフイメージ', '目標設定_次の2週間でやるワーク', '目標設定_個別コンサルで相談したいこと', 
            '身体データ_日付', '身体データ_足首', '身体データ_足の甲', '身体データ_縦アーチ', '身体データ_横アーチ', '身体データ_足指', '身体データ_備考', 
            '心理_性格傾向', '心理_モチベーション源泉', '心理_離脱リスク', 
            'レッスン_開催日時', 'レッスン_区分', 'レッスン_テーマ', 'レッスン_指導内容（やったこと）', 'レッスン_注意点（分かったこと）', 'レッスン_申し送り事項（次やること）', 'レッスン_担当講師', 
            '自己認識_登録日', '自己認識_A.前回から今回までの良い変化・成長', '自己認識_B.前回設定したワークの振り返り', '自己認識_C.まだ変化を感じられていない部分', '自己認識_D.今の自分の状態', '自己認識_E.次の2週間でやりたいワーク', '自己認識_F.個別コンサルで相談したいこと', '自己認識_指導内容', 
            '差異_マイルストーン', '差異_成果カテゴリ', '差異_標準達成目安', '差異_評価', '差異_評価コメント', 
        ]);

        foreach ($users as $user) {
            $habit = $user->habits[0] ?? null;
            $purpose = $user->purposes[0] ?? null;
            $mention = $user->mentions[0] ?? null;

            $base = [
                $user->id,
                $user->last_name . ' ' . $user->first_name,
                $user->last_name_kana . ' ' . $user->first_name_kana,
                Configure::read("COURSE")[$user->course_id] ?? '',
                $user->birthday?->format('Y/m/d') ?? '',
                $user->zipcode . ' ' . $user->prefecture . ' ' . $user->city . ' ' . $user->address,
                $user->start_datetime?->format('Y/m/d') ?? '',
                $user->staff->name ?? '',
                $habit?->occupation ?? '',
                $habit?->eating_habits ?? '',
                $habit?->sleep ?? '',
                $habit?->stress_factors ?? '',
                $habit?->exercise_history ?? '',
                $habit?->medical_history ?? '',
                $habit?->other_notes ?? '',
                $purpose?->email ?? '',
                $purpose?->name ?? '',
                $purpose?->current_concerns_appearance ?? '',
                $purpose?->current_concerns_function ?? '',
                $purpose?->goal_appearance ?? '',
                $purpose?->goal_function ?? '',
                $purpose?->future_self_image ?? '',
                $purpose?->next_two_weeks_work ?? '',
                $purpose?->consulting_topics ?? ''
            ];

            $max = max(
                count($user->body_datas),
                count($user->consults),
                count($user->self_datas),
                count($user->differences),
                1
            );

            for ($i = 0; $i < $max; $i++) {
                $body = $user->body_datas[$i] ?? null;
                $consult = $user->consults[$i] ?? null;
                $self = $user->self_datas[$i] ?? null;
                $diff = $user->differences[$i] ?? null;

                $baseRow = ($i === 0) ? $base : array_fill(0, count($base), '');

                $row = array_merge($baseRow, [
                    $body?->date?->format('Y/m/d') ?? '',
                    $body?->ankle ?? '',
                    $body?->instep ?? '',
                    $body?->vertical_arch ?? '',
                    $body?->horizontal_arch ?? '',
                    $body?->toes ?? '',
                    $body?->note ?? '',
                    $mention?->personality_tendency ?? '',
                    $mention?->motivation_source ?? '',
                    $mention?->dropout_risk ?? '',
                    $consult?->date?->format('Y/m/d') ?? '',
                    $consult?->category ?? '',
                    $consult?->theme ?? '',
                    $consult?->instruction ?? '',
                    $consult?->note ?? '',
                    $consult?->next_step ?? '',
                    $consult?->staff->name ?? '',
                    $self?->date?->format('Y/m/d') ?? '',
                    $self?->good_changes ?? '',
                    $self?->work_review ?? '',
                    $self?->no_change_parts ?? '',
                    $self?->current_state ?? '',
                    $self?->next_two_weeks_work ?? '',
                    $self?->consult_topics ?? '',
                    $self?->consult_content ?? '',
                    $diff?->milestone ?? '',
                    $diff?->category ?? '',
                    trim($diff?->target1 . "\n" . $diff?->target2),
                    trim($diff?->evaluation1 . "\n" . $diff?->evaluation2),
                    trim($diff?->comment1 . "\n" . $diff?->comment2),
                ]);

                fputcsv($output, $row);
            }
        }

        rewind($output);
        $csvData = stream_get_contents($output);
        fclose($output);

        return $this->response
            ->withDownload("顧客カルテ_{$startDate->format('Ymd')}_{$endDate->format('Ymd')}.csv")
            ->withType('csv')
            ->withStringBody($csvData);
    }

}
