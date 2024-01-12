<?php
declare(strict_types=1);

namespace App\Controller\Staff;

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
     * [ajax] 確認済みにする
     */
    public function check()
    {
        $this->autoRender = false;
        if (!$this->request->is('ajax')) {
            return;
        }

        // データ更新
        if ($this->request->is(['post'])) {
            $data = $this->request->getData();

            if (!empty($data['id'])) {
                $report = $this->DailyReports->get($data['id']);
                $report->is_checked = 1;
                $this->DailyReports->save($report);
                echo 'SUCCESS';
                return;
            }
        }
        echo 'ERROR';
    }

    /**
     * [ajax] 未確認に戻す
     */
    public function uncheck()
    {
        $this->autoRender = false;
        if (!$this->request->is('ajax')) {
            return;
        }

        // データ更新
        if ($this->request->is(['post'])) {
            $data = $this->request->getData();

            if (!empty($data['id'])) {
                $report = $this->DailyReports->get($data['id']);
                $report->is_checked = 0;
                $this->DailyReports->save($report);
                echo 'SUCCESS';
                return;
            }
        }
        echo 'ERROR';
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $query = $this->DailyReports->find('all')
        ->contain([
            'Users'
        ])
        ->where([
            'Users.staff_id' => $this->Authentication->getIdentity()->id,
        ])
        ->order([
            'DailyReports.report_date' => 'desc'
        ]);

        // 検索
        $param = $this->request->getQuery();

        // 報告日
        if (!empty($param['report_date'])) {
            $query->where(['DailyReports.report_date' => $param['report_date']]);
        } else {
            $param['report_date'] = '';
        }

        // 会員
        if (!empty($param['user_id'])) {
            $query->where(['DailyReports.user_id' => $param['user_id']]);
        } else {
            $param['user_id'] = '';
        }

        $dailyReports = $this->paginate($query);

        // ユーザーを配列で取得
        $users = $this->DailyReports->Users->find('list', [
            'keyField' => 'id',
            'valueField' => function ($q) {
                return $q->get('fullname');
            },
            'conditions' => [
                'staff_id' => $this->Authentication->getIdentity()->id,
                'is_deleted' => 0
            ],
            'order' => [
                'id' => 'desc'
            ]
        ])->toArray();

        $this->set(compact('dailyReports', 'users', 'param'));
    }
}
