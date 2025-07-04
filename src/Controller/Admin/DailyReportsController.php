<?php
declare(strict_types=1);

namespace App\Controller\Admin;

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
    public function beforeFilter(\Cake\Event\EventInterface $event)
    {
        parent::beforeFilter($event);

        $user = $this->Authentication->getIdentity();
        if($user && $user->role_id == 1) {
            $this->Flash->error('このページへのアクセス権がありません。');
            return $this->redirect(['controller' => 'UploadMovies', 'action' => 'index']);
        }
    }
    
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
            ->order([
                'DailyReports.report_date' => 'desc'
            ]);

        // ユーザーを配列で取得
        $users = $this->DailyReports->Users->find('list', [
            'keyField' => 'id',
            'valueField' => function ($q) {
                return $q->get('fullname');
            },
            'conditions' => [
                'is_deleted' => 0
            ],
            'order' => [
                'id' => 'desc'
            ]
        ])->toArray();

        // 検索
        $param = $this->request->getQuery();

        // 報告日
        if (!empty($param['report_date'])) {
            $query->where(['DailyReports.report_date' => $param['report_date']]);
        } else {
            $param['report_date'] = '';
            $dailyReports = null;
        }

        // 会員
        if (!empty($param['user_id'])) {
            $query->where(['DailyReports.user_id' => $param['user_id']]);
        } else {
            $param['user_id'] = '';
            $dailyReports = null;
        }

        $user = null;
        if (!empty($param['user_name'])) {
            $users = $this->DailyReports->Users->find('all')
                ->where([
                    'OR' => [
                        'Users.last_name LIKE' => '%' . $param['user_name'] . '%',
                        'Users.first_name LIKE' => '%' . $param['user_name'] . '%',
                        'Users.last_name_kana LIKE' => '%' . $param['user_name'] . '%',
                        'Users.first_name_kana LIKE' => '%' . $param['user_name'] . '%',
                    ]
                ])
                ->extract('id')
                ->toArray();
        } else {
            $param['user_name'] = '';
        }

        if (!empty($param['user_name']) && !empty($users)) {
            $query->where(['DailyReports.user_id IN' => $users]);
            $dailyReports = $query->all();
        } else if (!empty($param['user_name']) && !$user) {
            $param['user_name'] = '';
            $dailyReports = $this->paginate($this->DailyReports->find()->where(['id' => -1])); // return empty result
            $this->set(compact('dailyReports', 'users', 'param'));
            return;
        }


        $dailyReports = $this->paginate($query);



        $this->set(compact('dailyReports', 'users', 'param'));
    }
}
