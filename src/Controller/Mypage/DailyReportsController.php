<?php
declare(strict_types=1);

namespace App\Controller\Mypage;

use App\Controller\AppController;
use Cake\I18n\Time;
use Cake\ORM\Query;
use Cake\Core\Configure;
use Cake\Filesystem\Folder;
use Cake\Filesystem\File;

use App\Mailer\AppMailer;
use Cake\Mailer\MailerAwareTrait;

/**
 * DailyReports Controller
 *
 * @property \App\Model\Table\DailyReportsTable $DailyReports
 * @method \App\Model\Entity\information[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class DailyReportsController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    use MailerAwareTrait;
    public function index()
    {
        $dailyReport = $this->DailyReports->newEmptyEntity();

        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();

            $data['user_id'] = $this->Authentication->getIdentity()->id;

            // 同じ日の場合は上書き
            $dailyReport = $this->DailyReports->find('all')
            ->where([
                'user_id' => $this->Authentication->getIdentity()->id,
                'report_date' => date('Y-m-d', strtotime($data['report_date']))
            ])
            ->first();

            if (!$dailyReport) {
                $dailyReport = $this->DailyReports->newEmptyEntity();
            }

            $host = $this->request->getUri()->getScheme() . '://' . $this->request->getUri()->getHost();
            $staffPath = '/staff/daily-reports?user_id=';
            $path = '/admin/daily-reports';
            $port = $this->request->getUri()->getPort();
            $fullUrl = $host . ($port ? ":$port" : '') . $path;
            $fullUrlStaff = $host . ($port ? ":$port" : '') . $staffPath  . $data['user_id'];

            $dailyReport = $this->DailyReports->patchEntity($dailyReport, $data);
            $now = Time::now();
            $formattedDateTime = $now->i18nFormat('yyyy年MM月dd日 HH時mm分ss秒');  

            if ($this->DailyReports->save($dailyReport)) {
                $this->getMailer('App')->send('sendGridEmail',["【日報】脚やせ美脚リバースエイジングプログラム", $this->Authentication->getIdentity()->email, "daily_report", ['today' => $formattedDateTime]]);
                $this->getMailer('App')->send('sendGridEmail',["【日報】脚やせ美脚リバースエイジングプログラム", "ysonoda@someonelikeyou.co.jp", "daily_report_operation", ['reportUrl' => $fullUrl, 'name' => $this->Authentication->getIdentity()->last_name . $this->Authentication->getIdentity()->first_name, 'today' => $formattedDateTime]]);
                $this->getMailer('App')->send('sendGridEmail',["【日報】脚やせ美脚リバースエイジングプログラム", "shocola.stayfit@gmail.com", "daily_report_operation", ['reportUrl' => $fullUrlStaff, 'name' => $this->Authentication->getIdentity()->last_name . $this->Authentication->getIdentity()->first_name, 'today' => $formattedDateTime]]);
                $this->getMailer('App')->send('sendGridEmail',["【日報】脚やせ美脚リバースエイジングプログラム", "ank2019.studio@gmail.com", "daily_report_operation", ['reportUrl' => $fullUrlStaff, 'name' => $this->Authentication->getIdentity()->last_name . $this->Authentication->getIdentity()->first_name, 'today' => $formattedDateTime]]);
                $this->Flash->success(__('報告を送信しました。'));

                return $this->redirect(['action' => 'history']);
            }
            $this->Flash->error(__('データを送信できませんでした。入力内容を見直してください。'));
        }

        $this->set(compact('dailyReport'));
    }

    /**
     * History method
     *
     * @param string|null $id DailyReport id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function history($id = null)
    {
        $query = $this->DailyReports->find('all')
        ->where([
            'user_id' => $this->Authentication->getIdentity()->id,
        ])
        ->order([
            'report_date' => 'desc'
        ]);
        $dailyReports = $this->paginate($query);

        $this->set(compact('dailyReports'));
    }
}
