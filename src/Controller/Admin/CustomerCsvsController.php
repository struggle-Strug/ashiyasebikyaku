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

        $listOptions = Configure::read('ADMINLESSONOPTIONS');
    
        if (!$start || !$end) {
            $this->Flash->error('開始日と終了日を指定してください。');
            return $this->redirect($this->referer());
        }
    
        try {
            $startDate = new FrozenDate($start);
            $endDate = new FrozenDate($end);
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
    
        $exportDir = WWW_ROOT . 'files' . DS . 'exports' . DS;
        if (!is_dir($exportDir)) {
            mkdir($exportDir, 0775, true);
        }
    
        $timestamp = date('Ymd_His');
    
        /** ───────────────
         * 1️⃣ Main CSV for all users
         * ─────────────── */
        $mainFile = fopen($exportDir . "顧客カルテ一覧_{$timestamp}.csv", 'w+');
        fputcsv($mainFile, [
            '受講生ID', '氏名', 'カナ', 'コース', '生年月日',
            '住所', '開始日', '担当講師',
            '職業', '食事習慣', '睡眠', 'ストレス要因', 
            '運動歴', '既往歴', '注意点',
            '目標メール', '目標名前', '現在の悩み(容姿面)', '現在の悩み(機能面)',
            '期間中の目標(容姿面)', '期間中の目標(機能面)', '未来のセルフイメージ',
            '次の2週間でやるワーク', '個別コンサルで相談したいこと'
        ]);
    
        foreach ($users as $user) {
            $habit = $user->habits[0] ?? null;
            $purpose = $user->purposes[0] ?? null;
    
            fputcsv($mainFile, [
                $user->id,
                $user->last_name . $user->first_name,
                $user->last_name_kana . $user->first_name_kana,
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
                $purpose?->consulting_topics ?? '',
            ]);
        }
        fclose($mainFile);
    
        /** ───────────────
         * 2️⃣ Individual CSVs for each user
         * ─────────────── */
        foreach ($users as $user) {
            $userName = $user->last_name . $user->first_name;
    
            // 身体データ
            $bodyFile = fopen($exportDir . "{$userName}_身体データ_{$timestamp}.csv", 'w+');
            fputcsv($bodyFile, ['日付', '足首', '足の甲', '縦アーチ', '横アーチ', '足指', '備考']);
            foreach ($user->body_datas as $body) {
                fputcsv($bodyFile, [
                    $body->date?->format('Y/m/d') ?? '',
                    $body->ankle ?? '',
                    $body->instep ?? '',
                    $body->vertical_arch ?? '',
                    $body->horizontal_arch ?? '',
                    $body->toes ?? '',
                    $body->note ?? '',
                ]);
            }
            fclose($bodyFile);
    
            // レッスン
            $lessonFile = fopen($exportDir . "{$userName}_レッスン指導履歴_{$timestamp}.csv", 'w+');
            fputcsv($lessonFile, ['開催日時', '区分', 'テーマ', '指導内容', '注意点', '申し送り事項', '担当講師']);
            foreach ($user->consults as $consult) {
                fputcsv($lessonFile, [
                    $consult->date?->format('Y/m/d') ?? '',
                    $consult->category ? $listOptions[$consult->category] : '',
                    $consult->theme ?? '',
                    $consult->instruction ?? '',
                    $consult->note ?? '',
                    $consult->next_step ?? '',
                    $consult->staff->name ?? '',
                ]);
            }
            fclose($lessonFile);
    
            // 自己認識
            $selfFile = fopen($exportDir . "{$userName}_自己認識結果_{$timestamp}.csv", 'w+');
            fputcsv($selfFile, [
                '登録日', 'A.良い変化・成長', 'B.前回ワーク振り返り', 
                'C.まだ変化なし', 'D.今の状態', 'E.次の2週間ワーク', 
                'F.個別コンサル相談', '指導内容'
            ]);
            foreach ($user->self_datas as $self) {
                fputcsv($selfFile, [
                    $self->date?->format('Y/m/d') ?? '',
                    $self->good_changes ?? '',
                    $self->work_review ?? '',
                    $self->no_change_parts ?? '',
                    $self->current_state ?? '',
                    $self->next_two_weeks_work ?? '',
                    $self->consult_topics ?? '',
                    $self->consult_content ?? '',
                ]);
            }
            fclose($selfFile);
    
            // 差異結果
            $diffFile = fopen($exportDir . "{$userName}_差異結果_{$timestamp}.csv", 'w+');
            fputcsv($diffFile, [
                'マイルストーン', '成果カテゴリ', '標準達成目安1', '標準達成目安2',
                '評価1', '評価2', 'コメント1', 'コメント2'
            ]);
            foreach ($user->differences as $diff) {
                fputcsv($diffFile, [
                    $diff->milestone ?? '',
                    $diff->category ?? '',
                    $diff->target1 ?? '',
                    $diff->target2 ?? '',
                    $diff->evaluation1 ?? '',
                    $diff->evaluation2 ?? '',
                    $diff->comment1 ?? '',
                    $diff->comment2 ?? '',
                ]);
            }
            fclose($diffFile);
        }
    
        $this->Flash->success('タイムスタンプ付きのカルテCSVを出力しました。files/exports フォルダを確認してください。');
        return $this->redirect($this->referer());
    }
    


}
