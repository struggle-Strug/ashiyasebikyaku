<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\AppController;

use App\Mailer\AppMailer;
use Cake\Mailer\MailerAwareTrait;
use Cake\Core\Configure;
/**
 * Lessons Controller
 *
 * @property \App\Model\Table\AdminUsersTable $AdminUsers
 * @method \App\Model\Entity\AdminUser[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class LessonsController extends AppController
{

    public function index()
    {
        $listOptions = Configure::read('ADMINLESSONOPTIONS');

        // スタッフを配列で取得
        $staffs = $this->Lessons->Staffs->find('list', [
            'keyField' => 'id',
            'valueField' => 'name',
            'conditions' => [
                'is_deleted' => 0
            ]
        ])->toArray();

        $param = $this->request->getQuery();

        $this->set(compact('listOptions', 'staffs', 'param'));
    }

}
