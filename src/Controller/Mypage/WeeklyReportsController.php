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
 * WeeklyReports Controller
 *
 * @property \App\Model\Table\WeeklyReportsTable $weeklyReports
 * @method \App\Model\Entity\information[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class WeeklyReportsController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        // 初めての週次報告かチェック
        $firstWeeklyReport = $this->WeeklyReports->find('all')
        ->where([
            'user_id' => $this->Authentication->getIdentity()->id,
            'is_draft' => 0
        ])
        ->first();

        // 下書きの週次報告を取得
        $weeklyReport = $this->WeeklyReports->find('all')
        ->where([
            'user_id' => $this->Authentication->getIdentity()->id,
            'is_draft' => 1
        ])
        ->first();

        if (!$weeklyReport) {
            $weeklyReport = $this->WeeklyReports->newEmptyEntity();
        }

        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();

            $data['user_id'] = $this->Authentication->getIdentity()->id;
            $data['is_draft'] = 1; // 下書き

            $weeklyReport = $this->WeeklyReports->patchEntity($weeklyReport, $data);
            if ($this->WeeklyReports->save($weeklyReport)) {
                $this->Flash->success(__('データを保存しました。続けて写真を撮影してください。'));

                return $this->redirect(['action' => 'edit', $weeklyReport->id]);
            }
            $this->Flash->error(__('データを送信できませんでした。入力内容を見直してください。'));
        }

        $this->set(compact('firstWeeklyReport', 'weeklyReport'));
    }

    /**
     * Edit method
     *
     * @param string|null $id WeeklyReport id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null, $requestPhotoNumber = null)
    {
        $photoName = Configure::read('PHOTO_NAME');

        // 初めての週次報告かチェック
        $firstWeeklyReport = $this->WeeklyReports->find('all')
        ->where([
            'user_id' => $this->Authentication->getIdentity()->id,
            'is_draft' => 0
        ])
        ->first();

        $weeklyReport = $this->WeeklyReports->find('all')
        ->contain([
            'Photos' => function (Query $q) {
                return $q->order([
                    'Photos.photo_number' => 'desc'
                ]);
            }
        ])
        ->where([
            'WeeklyReports.user_id' => $this->Authentication->getIdentity()->id,
            'WeeklyReports.id' => $id,
            'is_draft' => 1
        ])
        ->first();

        if (!$weeklyReport) {
            return $this->redirect(['action' => 'index']);
        }

        // 現在の最新の写真
        $nowPhotoNumber = 0;
        if ($weeklyReport->photos) {
            foreach ($weeklyReport->photos as $key => $value) {
                $nowPhotoNumber = $value->photo_number;
                break;
            }
        }

        // 指定された写真がない場合
        if (!$requestPhotoNumber) {
            $requestPhotoNumber = $nowPhotoNumber + 1;
        }
        // 最新の写真よりも1つ先は指定できない
        if ($requestPhotoNumber > $nowPhotoNumber + 1) {
            $requestPhotoNumber = $nowPhotoNumber + 1;
        }
        // 写真の最大枚数超過
        if ($requestPhotoNumber > count($photoName)) {
            $requestPhotoNumber = count($photoName);
        }

        // 写真の登録があれば取得
        $photo = $this->WeeklyReports->Photos->find('all')
        ->where([
            'weekly_report_id' => $id,
            'photo_number' => $requestPhotoNumber
        ])
        ->first();

        if (!$photo) {
            $photo = $this->WeeklyReports->Photos->newEmptyEntity();
        }

        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();
            $imageFile = $this->request->getData('image_file');

            // ファイルチェック
            if (!$firstWeeklyReport) {
                // 初回
                if (empty($photo->id) && $imageFile->getSize() === 0 && $requestPhotoNumber < 18) {
                    $this->Flash->error('ファイルが選択されていません。');
                    $this->set(compact('weeklyReport', 'photo', 'photoName', 'requestPhotoNumber', 'nowPhotoNumber', 'firstWeeklyReport'));
                    return;
                }
            } else {
                // 2回目以降
                if (empty($photo->id) && $imageFile->getSize() === 0 && ($requestPhotoNumber < 11 && $requestPhotoNumber != 6)) {
                    $this->Flash->error('ファイルが選択されていません。');
                    $this->set(compact('weeklyReport', 'photo', 'photoName', 'requestPhotoNumber', 'nowPhotoNumber', 'firstWeeklyReport'));
                    return;
                }
            }

            if ($imageFile->getError() === 0) {
                if ($imageFile->getClientMediaType() == 'image/jpeg') {
                    $typeProfile = '.jpg';
                } elseif ($imageFile->getClientMediaType() == 'image/png') {
                    $typeProfile = '.png';
                } elseif ($imageFile->getClientMediaType() == 'video/mp4') {
                    $typeProfile = '.mp4';
                } elseif ($imageFile->getClientMediaType() == 'video/m4v') {
                    $typeProfile = '.m4v';
                } elseif ($imageFile->getClientMediaType() == 'video/3gpp') {
                    $typeProfile = '.3gp';
                } elseif ($imageFile->getClientMediaType() == 'video/quicktime') {
                    $typeProfile = '.mov';
                }

                // check MIME-TYPE
                $finfo  = finfo_open(FILEINFO_MIME_TYPE);
                $mimetype = finfo_file($finfo, $_FILES['image_file']['tmp_name']);
                finfo_close($finfo);
                if ($mimetype != 'image/jpeg' && $mimetype != 'image/png' && $mimetype != 'video/mp4' && $mimetype != 'video/m4v' && $mimetype != 'video/3gpp' && $mimetype != 'video/quicktime') {
                    $this->Flash->error('写真はJPEG/PNGファイル、動画はMP4/M4V/MOV/3GPファイルのみ登録できます。');
                    $this->set(compact('weeklyReport', 'photo', 'photoName', 'requestPhotoNumber', 'nowPhotoNumber', 'firstWeeklyReport'));
                    return;
                }
                // check file size
                if ($imageFile->getSize() > 15000000) {
                    $this->Flash->error('ファイルの容量は15MB以下のものを指定してください。');
                    $this->set(compact('weeklyReport', 'photo', 'photoName', 'requestPhotoNumber', 'nowPhotoNumber', 'firstWeeklyReport'));
                    return;
                }
                // temp filename
                $tmpImage = sha1_file($imageFile->getStream()->getMetadata('uri')) . strtotime(date('Y-m-d H:i:s')) . $typeProfile;
            }

            $photo = $this->WeeklyReports->Photos->patchEntity($photo, $data);
            if ($this->WeeklyReports->Photos->save($photo)) {
                // ファイルの保存
                $toMoveFile = null;
                $fromMoveFile = null;
                if ($imageFile->getError() === 0) {
                    $fromMoveFile = $imageFile->getStream()->getMetadata('uri');
                    $toMoveFile = 'uploads/weekly_report/' . $weeklyReport->id . '/' . $tmpImage;
                }
                // ファイルアップロード処理
                if ($toMoveFile && $fromMoveFile) {
                    // 古いファイルを削除
                    if ($photo->image_url) {
                        unlink(WWW_ROOT . $photo->image_url);
                    }

                    // アップロード先ディレクトリの確認と生成
                    $dir = new Folder(WWW_ROOT . 'uploads/weekly_report/' . $weeklyReport->id, true, 0777);

                    // ファイルの移動
                    if (!@move_uploaded_file($fromMoveFile, WWW_ROOT . $toMoveFile)){
                        $this->Flash->set('写真の保存に失敗しました。');
                    } else {
                        // データベースを更新
                        $photo->image_url = '/' . $toMoveFile;
                        $this->WeeklyReports->Photos->save($photo);
                    }
                }

                if ($data['is_confirm'] == '1') {
                    return $this->redirect(['action' => 'confirm', $weeklyReport->id]);
                } else {
                    $this->Flash->success(__('ファイルをアップロードしました。'));
                    return $this->redirect(['action' => 'edit', $weeklyReport->id, $photo->photo_number + 1]);
                }
            }
            $this->Flash->error(__('エラーが発生しました。'));
        }

        $this->set(compact('weeklyReport', 'photo', 'photoName', 'requestPhotoNumber', 'nowPhotoNumber', 'firstWeeklyReport'));
    }

    /**
     * Confirm method
     *
     * @param string|null $id WeeklyReport id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function confirm($id = null)
    {
        $photoName = Configure::read('PHOTO_NAME');

        // 初めての週次報告かチェック
        $firstWeeklyReport = $this->WeeklyReports->find('all')
        ->where([
            'user_id' => $this->Authentication->getIdentity()->id,
            'is_draft' => 0
        ])
        ->first();

        $weeklyReport = $this->WeeklyReports->find('all')
        ->contain([
            'Photos' => function (Query $q) {
                return $q->order([
                    'Photos.photo_number' => 'asc'
                ]);
            }
        ])
        ->where([
            'WeeklyReports.user_id' => $this->Authentication->getIdentity()->id,
            'WeeklyReports.id' => $id,
            'is_draft' => 1
        ])
        ->first();

        if (!$weeklyReport) {
            return $this->redirect(['action' => 'index']);
        }

        if ($this->request->is(['patch', 'post', 'put'])) {
            $weeklyReport->is_draft = 0;
            $this->WeeklyReports->save($weeklyReport);

            $this->Flash->success(__('情報を送信しました。'));
            return $this->redirect(['action' => 'history']);
        }

        $this->set(compact('weeklyReport', 'photoName', 'firstWeeklyReport'));
    }


    /**
     * History method
     *
     * @param string|null $id WeeklyReport id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function history($id = null)
    {
        $photoName = Configure::read('PHOTO_NAME');

        $query = $this->WeeklyReports->find('all')
        ->contain([
            'Photos' => function (Query $q) {
                return $q->order([
                    'Photos.photo_number' => 'asc'
                ]);
            }
        ])
        ->where([
            'WeeklyReports.user_id' => $this->Authentication->getIdentity()->id,
            'WeeklyReports.is_draft' => 0
        ])
        ->order([
            'WeeklyReports.report_date' => 'desc'
        ]);
        $weeklyReports = $this->paginate($query);

        $this->set(compact('weeklyReports', 'photoName'));
    }
}
