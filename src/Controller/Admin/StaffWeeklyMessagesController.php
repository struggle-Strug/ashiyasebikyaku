<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\AppController;
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
class StaffWeeklyMessagesController extends AppController
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

    public function index()
    {
        $param = $this->request->getQuery();
        $query = $this->StaffWeeklyMessages->find('all');
        $param = $this->request->getQuery();

        $query->order(['StaffWeeklyMessages.created' => 'DESC']);

        $query->where([
            'StaffWeeklyMessages.is_deleted' => "0",
        ]);

        if (!empty($param['name'])) {
            $query->where([
                'StaffWeeklyMessages.name LIKE' => '%' . $param['name'] . '%',
            ]);
        } else {
            $param['name'] = '';
        }

        if (!empty($param['staff_name'])) {
            $query->where([
                'StaffWeeklyMessages.staff_name LIKE' => '%' . $param['staff_name'] . '%',
            ]);
        } else {
            $param['staff_name'] = '';
        }

        $staffWeeklyMessages = $this->paginate($query);

        $this->set(compact('param', 'staffWeeklyMessages'));
    }

    public function delete($id = null)
    {
        $staff_weekly_message = $this->StaffWeeklyMessages->find('all')
        ->where([
            'id' => $id,
            'is_deleted' => 0
        ])
        ->first();

        if (!$staff_weekly_message) {
            return $this->redirect(['action' => 'index']);
        }

        $staff_weekly_message->is_deleted = 1;
        if ($this->StaffWeeklyMessages->save($staff_weekly_message)) {
            $this->Flash->success(__('削除しました。'));
        } else {
            $this->Flash->error(__('エラーが発生しました。'));
        }

        return $this->redirect(['action' => 'index']);
    }
}