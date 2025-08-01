<?php
declare(strict_types=1);

namespace App\Controller\Mypage;

use App\Controller\AppController;
use Cake\I18n\FrozenDate;
use Cake\I18n\Time;
use Cake\ORM\Query;
use Cake\Core\Configure;
use Cake\Filesystem\Folder;
use Cake\Filesystem\File;

use App\Mailer\AppMailer;
use Cake\Mailer\MailerAwareTrait;

/**
 * Purposes Controller
 *
 * @property \App\Model\Table\PurposesTable $Purposes
 * @method \App\Model\Entity\information[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class PurposesController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    use MailerAwareTrait;

    public function initialize(): void
    {
        parent::initialize();
        $this->loadModel('Staffs');
        $this->loadModel('Purposes');
        $this->loadModel('SelfDatas');
    }

    public function index()
    {
        $user = $this->Authentication->getIdentity()->id;

        $purpose = $this->Purposes->find('all')
        ->where([
            'user_id' => $user,
        ])
        ->first();
        
        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();

            if (!$purpose) {
                $purpose = $this->Purposes->newEmptyEntity();
                $data['user_id'] = $user;  // user_id をセットし忘れない
            }

            $purpose = $this->Purposes->patchEntity($purpose, $data);

            if ($this->Purposes->save($purpose)) {
                $this->Flash->success(__('更新しました。'));

                $param = $this->request->getQuery();

                return $this->redirect(['action' => 'index', '?' => $param]);
            }
            $this->Flash->error(__('データを登録できませんでした。入力内容を見直してください。'));
        }

        $this->set(compact('purpose'));
    }

    public function updates()
    {
        $selfdata = $this->SelfDatas->newEmptyEntity();

        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();

            $data['user_id'] = $this->Authentication->getIdentity()->id;
            $data['date'] = FrozenDate::today();  // ✅ set current date

            $selfdata = $this->SelfDatas->patchEntity($selfdata, $data);

            $now = Time::now();
            $formattedDateTime = $now->i18nFormat('yyyy年MM月dd日 HH時mm分ss秒');  // you can use this if needed

            if ($this->SelfDatas->save($selfdata)) {
                $this->Flash->success(__('保存しました。'));

                $param = $this->request->getQuery();

                return $this->redirect(['action' => 'index', '?' => $param]);
            }
            $this->Flash->error(__('データを保存できませんでした。入力内容を見直してください。'));
        }

        $this->set(compact('selfdata'));
    }
}
