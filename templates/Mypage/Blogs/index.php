<?php
use Cake\Core\Configure;
$this->assign('title', 'ブログ');
?>

<style>
.table-container {
    padding: 20px 20px 0;
}
.pagination-wrapper {
    display: flex;
    justify-content: center;
    align-items: center;
}
.pagination {
    margin: 0;
}
.search-form {
    display: flex;
    flex-wrap: wrap;  /* 折り返しを許可 */
    gap: 10px;
    padding: 0px 0px 20px;
}
.search-input-group {
    display: flex;
    gap: 10px;
    flex: 1 1 100%;  /* 小さい画面では100%幅を取る */
    min-width: 0;  /* flexboxの子要素がはみ出すのを防ぐ */
}
.search-input {
    flex: 1;  /* 利用可能な幅いっぱいに広がる */
    min-width: 300px;  /* 最小幅を設定 */
}
.filter-group {
    display: flex;
    align-items: center;
    width: 100%;  /* 小さい画面では100%幅を取る */
    margin-top: 10px;  /* 上部との間隔 */
}
/* 大きい画面用のスタイル */
@media (min-width: 768px) {
    .search-input-group {
        flex: 0 1 auto;  /* 自然な幅に戻す */
        width: 100%;  /* 幅を確保 */
        max-width: 600px;  /* 最大幅を制限 */
    }
    .filter-group {
        width: auto;
        margin-top: 0;
        margin-left: 30px;
    }
}
.category-label {
    margin-bottom: 0;
    margin-right: 10px;
    white-space: nowrap;
    flex-shrink: 0;
}
.category-buttons {
    display: flex;
    gap: 8px;
    overflow-x: auto;  /* 横スクロール可能に */
    flex-wrap: nowrap;  /* 折り返しを防止 */
    -webkit-overflow-scrolling: touch;  /* スマートフォンでのスムーズスクロール */
    scrollbar-width: none;  /* Firefoxのスクロールバーを非表示 */
    -ms-overflow-style: none;  /* IEのスクロールバーを非表示 */
    padding-bottom: 5px;  /* スクロールバーのための余白 */
}
/* Chromeのスクロールバーを非表示 */
.category-buttons::-webkit-scrollbar {
    display: none;
}
.category-btn {
    padding: 6px 12px;
    border: 1px solid #dee2e6;
    border-radius: 4px;
    background-color: #fff;
    color: #6c757d;
    cursor: pointer;
    transition: all 0.2s;
    flex-shrink: 0;  /* ボタンは縮まないように */
}
.category-btn:hover {
    background-color: #f8f9fa;  /* ホバー時は少し灰色がかった背景に */
}
.category-btn.active {
    background-color: #998A8A;
    border-color: #998A8A;
    color: #fff;  /* アクティブ時は白抜き文字 */
}
.category-btn.active:hover {
    background-color: #8a7b7b;  /* アクティブ状態でホバー時は少し暗く */
    border-color: #8a7b7b;
}
/* 内容のテキストスタイル */
.content-text {
    color: #66615b;  /* テキストカラー */
    font-size: 14px;  /* フォントサイズ */
    line-height: 1.4;  /* 行の高さ */
    margin-bottom: 0;  /* 下マージンを削除 */
}
</style>

<div class="card">
    <div class="card-body">
        <div class="table-container">
            <?= $this->Form->create(null, [
                'type' => 'get',
                'class' => 'mb-4 search-form'
            ]) ?>
            <div class="search-input-group">
                <div class="search-input">
                    <?= $this->Form->control('keyword', [
                        'label' => false,
                        'class' => 'form-control',
                        'placeholder' => '内容を検索...',
                        'value' => $keyword ?? ''
                    ]) ?>
                </div>
                <?= $this->Form->button('検索', [
                    'class' => 'btn btn-primary',
                    'type' => 'submit'
                ]) ?>
            </div>
            <div class="filter-group">
                <label class="category-label">カテゴリー選択</label>
                <div class="category-buttons">
                    <button type="button" 
                        class="category-btn <?= empty($category) ? 'active' : '' ?>"
                        onclick="updateCategory('')">
                        すべて
                    </button>
                    <?php foreach (Configure::read('INFORMATION_CATEGORY') as $key => $value): ?>
                        <button type="button" 
                            class="category-btn <?= $category == $key ? 'active' : '' ?>"
                            onclick="updateCategory('<?= $key ?>')">
                            <?= h($value) ?>
                        </button>
                    <?php endforeach; ?>
                </div>
                <?= $this->Form->hidden('category', ['value' => $category]) ?>
            </div>
            <?= $this->Form->end() ?>

            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th width="150" class="text-center" nowrap>投稿日</th>
                            <th class="text-center" nowrap>内容</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($informations->count() > 0): ?>
                            <?php foreach ($informations as $information): ?>
                            <tr>
                                <td class="text-center align-middle">
                                    <?php
                                    $url = $this->Url->build(['action' => 'information', $information->id]);
                                    ?>
                                    <a href="<?= $url ?>" class="text-dark">
                                        <?= $information->start_datetime->format('Y年n月j日') ?>
                                    </a>
                                </td>
                                <td class="text-start align-middle">
                                    <?php
                                    // 改行を空白に置換
                                    $content = str_replace(["\r\n", "\r", "\n"], ' ', $information->content);
                                    // 連続する空白を1つにまとめる
                                    $content = preg_replace('/\s+/', ' ', $content);
                                    // 70文字で切り取り
                                    if (mb_strlen($content) > 70) {
                                        $content = mb_substr($content, 0, 70) . ' ・・・';
                                    }
                                    ?>
                                    <p class="content-text"><?= $this->Link->linkify(h($content)) ?></p>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="2" class="text-center">データがありません</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="card-footer">
        <div class="pagination-wrapper">
            <?= $this->element('pagination') ?>
        </div>
    </div>
</div>

<script>
function updateCategory(value) {
    // hidden inputの値を更新
    document.querySelector('input[name="category"]').value = value;
    
    // ボタンのアクティブ状態を更新
    document.querySelectorAll('.category-btn').forEach(btn => {
        btn.classList.remove('active');
    });
    event.target.classList.add('active');
    
    // フォームを送信
    document.querySelector('form').submit();
}
</script>
