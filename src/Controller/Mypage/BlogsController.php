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
        
        // ビューヘルパーの追加
        $this->viewBuilder()->addHelper('Link');
        
        // ページネーション設定を追加
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
        
        // まず全件取得してみる
        $query = $this->Informations->find();
        
        // 基本条件を設定
        $query = $this->Informations->find()
            ->where([
                'OR' => [
                    ['target' => '1'], // 全員
                    ['target' => '3']  // 利用者
                ],
                'start_datetime <=' => date('Y-m-d H:i:s'),
                'is_deleted' => 0
            ])
            ->order(['start_datetime' => 'desc']);

        // キーワードが入力されている場合、内容での検索を追加
        if (!empty($keyword)) {
            $query->where(['content LIKE' => '%' . $keyword . '%']);
        }

        // カテゴリが選択されている場合、カテゴリでの絞り込みを追加
        if (!empty($category)) {
            $query->where(['category' => $category]);
        }

        try {
            $informations = $this->paginate($query);
            
            // カテゴリ一覧を取得（const.phpから）
            $categories = ['' => 'すべて'] + Configure::read('INFORMATION_CATEGORY');
            
            $this->set(compact('informations', 'keyword', 'category', 'categories'));
        } catch (\Exception $e) {
            Log::write('error', $e->getMessage());
            $this->Flash->error('データの取得中にエラーが発生しました。');
        }
    }

    public function information($id = null)
    {
        Log::write('debug', 'Information action called with ID: ' . $id);

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
            Log::write('debug', 'Information not found for ID: ' . $id);
            $this->Flash->error('お知らせが見つかりません。');
            return $this->redirect(['action' => 'index']);
        }

        $this->set(compact('information'));
    }
} 