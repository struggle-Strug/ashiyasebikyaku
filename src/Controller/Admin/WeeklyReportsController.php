<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\AppController;
use Cake\Database\Expression\QueryExpression;
use Cake\I18n\Time;
use Cake\ORM\Query;
use Cake\Core\Configure;
use Cake\Filesystem\Folder;
use Cake\Filesystem\File;
use ZipArchive;


/**
 * WeeklyReports Controller
 *
 * @property \App\Model\Table\WeeklyReportsTable $weeklyReports
 * @method \App\Model\Entity\information[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class WeeklyReportsController extends AppController
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
                $report = $this->WeeklyReports->get($data['id']);
                $report->is_checked = 1;
                $this->WeeklyReports->save($report);
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
        $photoName = Configure::read('PHOTO_NAME');

        $query = $this->WeeklyReports->find('all')
        ->contain([
            'Users',
            'Photos' => function (Query $q) {
                return $q->order([
                    'Photos.photo_number' => 'asc'
                ]);
            }
        ])
        ->where([
            'WeeklyReports.is_draft' => 0
        ])
        ->order([
            'WeeklyReports.report_date' => 'desc'
        ]);

        // 検索
        $param = $this->request->getQuery();

        // 報告日
        if (!empty($param['start_report_date'])) {
            $query->where(['WeeklyReports.report_date >=' => $param['start_report_date']]);
        } else {
            $param['start_report_date'] = '';
        }
        if (!empty($param['end_report_date'])) {
            $query->where(['WeeklyReports.report_date <=' => $param['end_report_date']]);
        } else {
            $param['end_report_date'] = '';
        }

        // 会員
        $user = null;
        if (!empty($param['user_name'])) {
            // Remove all whitespace from the input name
            $cleanName = preg_replace('/\s+/u', '', $param['user_name']);
            
            // Fetch all users and compare combined name in PHP
            $user = $this->WeeklyReports->Users->find()
            ->all()
            ->filter(function ($u) use ($cleanName) {
                $full = preg_replace('/\s+/u', '', $u->fullname);
                    return $full === $cleanName;
                })
                ->first();
        }


        if (!empty($param['user_name']) && $user) {
            $query->where(['WeeklyReports.user_id' => $user->id]);
            $weeklyReports = $query->all();
        } else if (!empty($param['user_name']) && !$user) {
            $weeklyReports = null;
        } else if (empty($param['user_name']) && empty($param['start_report_date']) && empty($param['end_report_date'])) {
            $param['user_name'] = '';
            $weeklyReports = null;
        } else {
            $param['user_name'] = '';
            $weeklyReports = $query->all();
        }

        $this->set(compact('weeklyReports', 'photoName', 'param'));
    }


public function downloadImages($weeklyReportId)
{
    $weeklyReport = $this->WeeklyReports->get($weeklyReportId, [
        'contain' => ['Photos']
    ]);



    $zip = new ZipArchive();
    $zipFilename = tempnam(sys_get_temp_dir(), 'zip') . '.zip';

    if ($zip->open($zipFilename, ZipArchive::CREATE) === TRUE) {
        foreach ($weeklyReport->photos as $photo) {
            if (empty($photo->image_url) || $photo->image_url == '' || $photo->image_url == null) {
                continue;
            }        
            $filePath = WWW_ROOT . $photo->image_url; // 実際のファイルパスに置き換えてください
            if (file_exists($filePath)) {
                $zip->addFile($filePath, basename($filePath));
            }
        }
        $zip->close();

        $this->response = $this->response->withFile(
            $zipFilename,
            ['download' => true, 'name' => 'weekly_report_images.zip']
        );

        return $this->response;
    } else {
        $this->Flash->error(__('Unable to create ZIP file.'));
        return $this->response;
    }
}
}
