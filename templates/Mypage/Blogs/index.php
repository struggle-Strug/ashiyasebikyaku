<?php $this->assign('title', '美脚虎の巻ブログ');?>

<style>
.table-container {
    padding: 20px 20px 0;
    margin-right: 1%;  /* 右側に1%の余白を追加 */
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
    padding: 10px 0px 20px;  /* 上部に10pxの余白を追加 */
}
.search-input-group {
    display: flex;
    gap: 10px;
    flex: 1 1 100%;
    min-width: 0;
}
.search-input {
    flex: 1;
    min-width: 200px;
    max-width: 400px;
}
.filter-group {
    display: flex;
    flex-direction: column;
    gap: 10px;
    width: 100%;
}
.category-label {
    margin-bottom: 0;
    margin-right: 10px;
    white-space: nowrap;
    flex-shrink: 0;
    font-size: 1.2rem;
}
.category-buttons {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;  /* 10px から 6px に縮小 */
    width: 100%;
}
.category-btn {
    padding: 8px 16px;
    border: 1px solid #dee2e6;
    border-radius: 4px;
    background-color: #fff;
    color: #6c757d;
    cursor: pointer;
    transition: all 0.2s;
    font-size: 1.1rem;
}
.category-btn:hover {
    background-color: #f8f9fa;
}
.category-btn.active {
    background-color: #998A8A;
    border-color: #998A8A;
    color: #fff;
}
.category-btn.active:hover {
    background-color: #8a7b7b;
    border-color: #8a7b7b;
}

@media (max-width: 768px) {
    .category-btn {
        font-size: 0.95rem;
    }
}

.content-text {
    color: #66615b;  /* テキストカラー */
    font-size: 14px;  /* フォントサイズ */
    line-height: 1.4;  /* 行の高さ */
    margin-bottom: 0;  /* 下マージンを削除 */
}

@media screen and (max-width: 390px) {
    /* 検索入力部分の調整を追加 */
    .search-input {
        min-width: 175px;
        max-width: 350px;
    }
    
    .search-input .form-control {
        padding: 0.4rem 0.75rem;
        height: calc(1.6em + 0.8rem + 2px);
    }

    .search-input-group .btn {
        padding: 0.4rem 0.75rem;
        margin-right: 1%;
    }

    .table td:first-child a {
        font-size: 0.9rem;
    }

    .content-text {
        font-size: 0.95rem;
        line-height: 1.3;
        overflow: hidden;
        display: -webkit-box;
        -webkit-box-orient: vertical;
        -webkit-line-clamp: 2;
    }

    .category-btn {
        padding: 7px 14px;
        font-size: 1rem;
    }

    .category-buttons {
        gap: 5px;  /* モバイルではさらに小さく */
    }

    .mobile-header {  /* 新しいクラスを追加 */
        font-size: 1.3rem !important;  /* !importantを使用して確実に適用 */
        padding: 4px !important;
        font-weight: normal;
    }
}
</style>

<div class="col-lg-12">  
    <div class="card">
        <div class="card-body">
            <div class="container-fluid">
                <div class="row justify-content-center">
                    <div class="col-12 col-md-12">  <!-- 幅を拡大（10→11） -->
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
                                <?php foreach ($categories as $key => $value): ?>
                                    <?php if ($key !== ''): ?>
                                        <button type="button" 
                                            class="category-btn <?= $category == $key ? 'active' : '' ?>"
                                            onclick="updateCategory('<?= $key ?>')">
                                            <?= h($value) ?>
                                        </button>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </div>
                            <?= $this->Form->hidden('category', ['value' => $category]) ?>
                        </div>
                        <?= $this->Form->end() ?>

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th width="150" class="text-center mobile-header" nowrap>投稿日</th>
                                        <th class="text-center mobile-header" nowrap>内容</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if ($informations->count() > 0): ?>
                                        <?php foreach ($informations as $information): ?>
                                        <tr>
                                            <td class="text-center align-middle">
                                                <?= $information->start_datetime->format('Y年n月j日') ?>
                                            </td>
                                            <td class="text-start align-middle">
                                                <?php
                                                $url = $this->Url->build(['action' => 'information', $information->id]);
                                                $content = str_replace(["\r\n", "\r", "\n"], ' ', $information->content);
                                                $content = preg_replace('/\s+/', ' ', $content);
                                                $maxLength = (isset($_SERVER['HTTP_USER_AGENT']) && 
                                                             preg_match('/(iPhone|Android.*Mobile)/i', $_SERVER['HTTP_USER_AGENT'])) 
                                                             ? 23  // モバイルの場合は23文字
                                                             : 70; // PCの場合は70文字
                                                
                                                if (mb_strlen($content) > $maxLength) {
                                                    $content = mb_substr($content, 0, $maxLength) . ' ・・・';
                                                }
                                                ?>
                                                <a href="<?= $url ?>" class="text-dark">
                                                    <?= $this->Link->linkify(h($content)) ?>
                                                </a>
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
            </div>
        </div>
        <div class="card-footer">
            <div class="pagination-wrapper">
                <?= $this->element('pagination') ?>
            </div>
        </div>
    </div>
</div>

<script>
function updateCategory(value) {
    document.querySelector('input[name="category"]').value = value;
    document.querySelectorAll('.category-btn').forEach(btn => {
        btn.classList.remove('active');
    });
    event.target.classList.add('active');
    document.querySelector('form').submit();
}
</script>
