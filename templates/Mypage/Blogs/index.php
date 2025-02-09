<?php
use Cake\Core\Configure;
$this->assign('title', 'ブログ');
?>

<style>
.table-container {
    padding: 20px 20px 0;  /* 上右左のみ余白を追加（上、右左、下） */
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
    align-items: center;
    gap: 10px;  /* 入力欄とボタンの間隔 */
    padding: 0px 0px 20px;  /* 上右左のみ余白を追加（上、右左、下） */
}
.filter-group {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-left: 30px;  /* 検索ボタンとの余白 */
}
.category-label {
    margin-bottom: 0;  /* デフォルトの下マージンを削除 */
    margin-right: 10px;  /* 検索ボタンとの余白 */
    white-space: nowrap;  /* ラベルが折り返されないようにする */
}
</style>

<div class="card">
    <div class="card-body">
        <div class="table-container">
            <?= $this->Form->create(null, [
                'type' => 'get',
                'class' => 'mb-4 search-form'
            ]) ?>
            <div class="col-md-4 p-0">
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
            <div class="filter-group">
                <label class="category-label">カテゴリー選択</label>
                <?= $this->Form->select('category', 
                    $categories,
                    [
                        'class' => 'form-control',
                        'value' => $category ?? '',
                        'empty' => false,
                        'style' => 'width: auto;',
                        'onchange' => 'this.form.submit()'  // 選択時に自動送信
                    ]
                ) ?>
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
                                    // 80文字で切り取り
                                    if (mb_strlen($content) > 80) {
                                        $content = mb_substr($content, 0, 80) . ' ・・・';
                                    }
                                    ?>
                                    <small><?= $this->Link->linkify(h($content)) ?></small>
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
