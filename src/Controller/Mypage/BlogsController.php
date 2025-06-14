<?php
declare(strict_types=1);

namespace App\Controller\Mypage;

use App\Controller\AppController;
use Cake\I18n\Time;
use Cake\ORM\Query;
use Cake\Core\Configure;
use Cake\Log\Log;

/**
 * Blogs Controller
 *
 * @property \App\Model\Table\InformationsTable $Informations
 * @method \App\Model\Entity\Information[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class BlogsController extends AppController
{
    /**
     * Initialize method
     *
     * @return void
     */
    public function initialize(): void
    {
        parent::initialize();
        $this->loadModel('Informations');        
        $this->viewBuilder()->addHelper('Link');
        $this->paginate = [
            'limit' => 10,
            'order' => ['start_datetime' => 'DESC']
        ];
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        // 検索条件を取得
        $keyword = $this->request->getQuery('keyword');
        $category = $this->request->getQuery('category');
        $query = $this->Informations->find()
            ->where([
                'OR' => [
                    ['target' => '1'], // 全員
                    ['target' => '3']  // 利用者
                ],
                'start_datetime <=' => date('Y-m-d H:i:s'), //未来日付の場合は表示しない
                'OR' => [  // 終了日時が未設定または現在時刻より後のものを表示
                    ['end_datetime IS NULL'],
                    ['end_datetime >=' => date('Y-m-d H:i:s')]
                ],
                'is_deleted' => 0,
                'category !=' => 10
            ])
            ->order(['start_datetime' => 'desc']);

        // キーワードが入力されている場合、内容での検索を追加
        if (!empty($keyword)) {
            $query->where(['content LIKE' => '%' . $keyword . '%']);
        }

        $query->where(['OR' => [
            ['category !=' => 10],
            ['category IS' => null]
        ]]);

        // カテゴリーが選択されている場合、カテゴリーでの絞り込みを追加
        if (!empty($category)) {
            $query->where(['category' => $category]);
        }

        // カテゴリー一覧を取得し、テンプレートに渡す
        $categories = Configure::read('INFORMATION_CATEGORY');
        unset($categories['10']); // 事務局からのお知らせを除外
        $categories = ['' => 'すべて'] + $categories;

        try {
            $informations = $this->paginate($query);
            $this->set(compact('informations', 'keyword', 'category', 'categories'));
        } catch (\Exception $e) {
            $this->Flash->error('データの取得中にエラーが発生しました。');
        }
    }

    public function information($id = null)
    {
        $information = $this->Informations->find()
            ->where([
                'Informations.id' => $id,
                'OR' => [
                    ['target' => '1'], // 全員
                    ['target' => '3']  // 利用者
                ],
                'start_datetime <=' => date('Y-m-d H:i:s'),
                'is_deleted' => 0
            ])
            ->first();

        if (!$information) {
            $this->Flash->error('お知らせが見つかりません。');
            return $this->redirect(['action' => 'index']);
        }

        $this->set(compact('information'));
    }
} 