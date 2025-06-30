<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\AppController;
use Cake\ORM\Query;
use Cake\Core\Configure;
use Cake\Routing\Router;
use Cake\Filesystem\Filesystem;
use Cake\Filesystem\File;


/**
 * ComparedImages Controller
 *
 * @property \App\Model\Table\ComparedImagesTable $ComparedImages
 * @method \App\Model\Entity\ComparedImage[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ComparedImagesController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */

    public function initialize(): void
    {
        parent::initialize();
        $this->loadModel('WeeklyReports'); // ユーザーテーブルを読み込む
        $this->loadModel('Photos');
    }

    public function beforeFilter(\Cake\Event\EventInterface $event)
    {
        parent::beforeFilter($event);

        $user = $this->Authentication->getIdentity();
        if ($user->role_id == 1) {
            $this->Flash->error('このページへのアクセス権がありません。');
            return $this->redirect(['controller' => 'UploadMovies', 'action' => 'index']);
        }
    }

    public function index()
    {
        $this->paginate = [
            'contain' => ['Users'],
        ];

        $query = $this->ComparedImages->find('all')
            ->contain([
                'Users',
            ])
            ->order([
                'ComparedImages.created' => 'desc'
            ]);

        $param = $this->request->getQuery();

        if (!empty($param['start_report_date'])) {
            $query->where(['ComparedImages.created >=' => $param['start_report_date']]);
        } else {
            $param['start_report_date'] = '';
        }
        if (!empty($param['end_report_date'])) {
            $query->where(['ComparedImages.created <=' => $param['end_report_date']]);
        } else {
            $param['end_report_date'] = '';
        }

        // 会員
        if (!empty($param['user_id'])) {
            $query->where(['ComparedImages.user_id' => $param['user_id']]);
        } else {
            $param['user_id'] = '';
        }

        if (!empty($param['user_name'])) {
            $users = $this->ComparedImages->Users->find('all')
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
            $query->where(['ComparedImages.user_id IN' => $users]);
        } else if (!empty($param['user_name']) && empty($users)) {
            $comparedImages = null;
        } else if (empty($param['user_id']) && empty($param['user_name']) && empty($param['start_report_date']) && empty($param['end_report_date'])) {
            $param['user_name'] = '';
            $comparedImages = null;
        } else {
            $param['user_name'] = '';
        }

        $comparedImages = $query->all();

        $users = $this->ComparedImages->Users->find('list', [
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
        ]);
        $this->set(compact('comparedImages', 'users', 'param'));
    }

    /**
     * View method
     *
     * @param string|null $id Compared Image id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $comparedImage = $this->ComparedImages->get($id, [
            'contain' => ['Users'],
        ]);

        $this->set(compact('comparedImage'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $comparedImage = $this->ComparedImages->newEmptyEntity();
        if ($this->request->is('post')) {
            $comparedImage = $this->ComparedImages->patchEntity($comparedImage, $this->request->getData());
            if ($this->ComparedImages->save($comparedImage)) {
                $this->Flash->success(__('The compared image has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The compared image could not be saved. Please, try again.'));
        }

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

        $param = $this->request->getQuery();

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

        if (empty($param["target_image_id"])) {
            $param['target_image_id'] = '';
        }

        if (empty($param["subject_image_id"])) {
            $param['subject_image_id'] = '';
        }

        if (empty($param["target_image"])) {
            $param['target_image'] = '';
        }

        if (empty($param["subject_image"])) {
            $param['subject_image'] = '';
        }

        // 会員
        if (!empty($param['user_id'])) {
            $query->where(['WeeklyReports.user_id' => $param['user_id']]);
        } else {
            $param['user_id'] = '';
        }

        if (!empty($param['user_name'])) {
            $users = $this->WeeklyReports->Users->find('all')
                ->where([
                    'OR' => [
                        'Users.last_name LIKE' => '%' . $param['user_name'] . '%',
                        'Users.first_name LIKE' => '%' . $param['user_name'] . '%',
                        'Users.last_name_kana LIKE' => '%' . $param['user_name'] . '%',
                        'Users.first_name_kana LIKE' => '%' . $param['user_name'] . '%',
                    ]
                ])->toArray();
        } else {
            $param['user_name'] = '';
        }


        if (!empty($param['user_name']) && !empty($users)) {
            $userIds = array_column($users, 'id');
            $query->where(['WeeklyReports.user_id IN' => $userIds]);
            $weeklyReports = $query->all();
        } else if (!empty($param['user_name']) && empty($users)) {
            $weeklyReports = null;
        } else if (empty($param['user_id']) && empty($param['user_name']) && empty($param['start_report_date']) && empty($param['end_report_date']) && empty($param['target_image_id']) && empty($param['subject_image_id']) && empty($param['target_image']) && empty($param['subject_image'])) {
            $param['user_name'] = '';
            $weeklyReports = null;
        } else {
            $param['user_name'] = '';
            $weeklyReports = $query->all();
        }

        $users = $this->WeeklyReports->Users->find('list', [
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
        $this->set(compact('weeklyReports', 'comparedImage', 'users', 'param', 'photoName'));
    }

    private function base64Encode($imagePath)
    {
        debug($imagePath);
        $imageUrl = Router::url('', true) . $imagePath;
        $imageData = file_get_contents($imageUrl);

        $base64 = base64_encode($imageData);

        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($imagePath);

        $base64src = 'data:' . $mime . ';base64,' . $base64;

        return $base64src;
    }

    public function createComparedImage()
    {
        $comparedImage = $this->ComparedImages->newEmptyEntity();
        $param = $this->request->getQuery();
        $targetImage = $this->Photos->find("all");
        $subjectImage = $this->Photos->find("all");
        $targetImageDate = "";
        $subjectImageDate = "";
        if (!empty($param["target_id"])) {
            $targetImage = $targetImage->where([
                "id" => $param["target_id"]
            ])
                ->first();
            if ($targetImage) {
                $weeklyReport = $this->WeeklyReports->find()
                    ->where(['id' => $targetImage->weekly_report_id])
                    ->first();

                if ($weeklyReport) {
                    $comparedImage->user_id = $weeklyReport->user_id;
                    $param['user'] = $weeklyReport->user_id;
                    debug($weeklyReport);
                    $targetImageDate = $weeklyReport->report_date;
                }
            }
        } else {
            $targetImage = null;
        }

        if (!empty($param["subject_id"])) {
            $subjectImage = $subjectImage->where([
                "id" => $param["subject_id"]
            ])
                ->first();
            if ($subjectImage) {
                $weeklyReport = $this->WeeklyReports->find()
                    ->where(['id' => $subjectImage->weekly_report_id])
                    ->first();

                debug($weeklyReport);
                $subjectImageDate = $weeklyReport->report_date;
            }
        } else {
            $subjectImage = null;
        }
        $this->set(compact('param', 'targetImage', 'subjectImage', 'comparedImage', 'targetImageDate', 'subjectImageDate'));
    }

    public function uploadComparedImage()
    {
        $comparedImage = $this->ComparedImages->newEmptyEntity();

        if ($this->request->is('post')) {
            $imageData = $this->request->getData('image');
            $data = $this->request->getData();
            list($type, $imageData) = explode(';', $imageData);
            list(, $imageData) = explode(',', $imageData);
            $imageData = base64_decode($imageData);

            $imageName = uniqid() . '.png';
            $imagePath = WWW_ROOT . 'uploads' . DS . $imageName;


            file_put_contents($imagePath, $imageData);
            if (!file_put_contents($imagePath, $imageData)) {
                $error = error_get_last();
                echo "エラーメッセージ: " . $error['message'];
                debug("エラーメッセージ: " . $error);
            }

            $imageUrl = Router::url('/', true) . 'uploads/' . $imageName;

            $data["image"] = $imageUrl;


            $comparedImage = $this->ComparedImages->patchEntity($comparedImage, $data);
            if ($this->ComparedImages->save($comparedImage)) {
                $this->Flash->success(__('The compared image has been saved.'));
            }
            return $this->redirect(['action' => 'index']);
        }
    }





    /**
     * Edit method
     *
     * @param string|null $id Compared Image id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $comparedImage = $this->ComparedImages->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $comparedImage = $this->ComparedImages->patchEntity($comparedImage, $this->request->getData());
            if ($this->ComparedImages->save($comparedImage)) {
                $this->Flash->success(__('The compared image has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The compared image could not be saved. Please, try again.'));
        }
        $users = $this->ComparedImages->Users->find('list', ['limit' => 200])->all();
        $this->set(compact('comparedImage', 'users'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Compared Image id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $comparedImage = $this->ComparedImages->get($id);
        $filePath = $comparedImage->image;
        $path = parse_url($filePath, PHP_URL_PATH);
        $filename = basename($path);
        if ($this->ComparedImages->delete($comparedImage)) {
            if ($filePath) {
                unlink(WWW_ROOT . "/uploads/" . $filename);
                $this->Flash->success(__('画像が削除されました。'));
            }
            $this->Flash->success(__('比較画像が画像が削除されました。'));
        } else {
            $this->Flash->error(__('比較画像が削除されませんでした。'));
        }
        return $this->redirect(['action' => 'index']);
    }

    public function setActive($id = null)
    {
        $comparedImage = $this->ComparedImages->get($id, [
            'contain' => [],
        ]);
        $this->request->allowMethod(['post', 'delete']);
        $data = [];
        $data["active"] = 1;
        $comparedImage = $this->ComparedImages->patchEntity($comparedImage, $data);
        if ($this->ComparedImages->save($comparedImage)) {
            $this->Flash->success(__('送信完了いたしました。'));
            return $this->redirect(['action' => 'index']);
        }
    }

    public function setNotActive($id = null)
    {
        $comparedImage = $this->ComparedImages->get($id, [
            'contain' => [],
        ]);
        $this->request->allowMethod(['post', 'delete']);
        $data = [];
        $data["active"] = 0;
        $comparedImage = $this->ComparedImages->patchEntity($comparedImage, $data);
        if ($this->ComparedImages->save($comparedImage)) {
            $this->Flash->success(__('送信取り消し完了いたしました。'));
            return $this->redirect(['action' => 'index']);
        }

    }
}
