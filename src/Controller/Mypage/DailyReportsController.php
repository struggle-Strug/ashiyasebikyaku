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
            $dailyReport = $this->DailyReports->patchEntity($dailyReport, $data);
            if ($this->DailyReports->save($dailyReport)) {
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
