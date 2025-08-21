<?php $this->assign('title', '比較画像一覧'); ?>
<?= $this->Form->create(null, ['type' => 'get']) ?>
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-3 mt-2">
                <label>伝送有無</label>
                <?= $this->Form->control('active', [
                    'type' => 'select',
                    'label' => false,
                    'empty' => '全て表示',
                    'options' => ['0' => '未送信のみ表示'],
                    'default' => $this->request->getQuery('active'),
                    'class' => 'form-control mr-2'
                ]) ?>
            </div>
            <div class="col-md-12 text-right mt-2">
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-magnifying-glass"></i> 検索</button>
            </div>
        </div>
    </div>
</div>
<?= $this->Form->end() ?>


<div class="col-lg-12">
    <div class="card">
        <div class="card-content">
            <?php foreach ($comparedImages as $comparedImage): ?>
                    <div class="card">
                        <div class="card-header">
                            <h5 style="display: flex; justify-content: end; align-items:center">
                                <div style="flex-grow: 0; display: flex;">
                                    <div style="margin: 4px">
                                        <a href="<?= $this->Url->build(['action' => 'editComment', $comparedImage->id, '?' => $param]) ?>" class="btn btn-outline-primary btn-sm">コメント編集</a>
                                        <?php if ($comparedImage->active): ?>
                                            <?= $this->Form->postLink(__('送信取消'), ['action' => 'setNotActive', $comparedImage->id], ['class' => 'btn btn-outline-success btn-sm', 'confirm' => __('送信取り消ししてもよろしいでしょうか？', $comparedImage->id)]) ?>
                                        <?php else: ?>
                                            <?= $this->Form->postLink(__('送信'), ['action' => 'setActive', $comparedImage->id], ['class' => 'btn btn-outline-success btn-sm', 'confirm' => __('送信してもよろしいでしょうか？', $comparedImage->id)]) ?>
                                        <?php endif ?>
                                    </div>
                                    <div style="margin: 4px">
                                        <?= $this->Form->postLink(__('削除'), ['action' => 'deleteImage', $comparedImage->id], ['class' => 'btn btn-outline-danger btn-sm', 'confirm' => __('削除してもよろしいでしょうか？', $comparedImage->id)]) ?>
                                    </div>
                                </div>
                            </h5>
                        </div>
                        <div class="card-content">
                            <div style="padding: 12px 20px 0px;font-size: 20px; margin-left: -10px">【変化ポイント解説】</div>
                            <div style="padding: 0px 20px;font-size: 20px;">【管理者】<?= $comparedImage->admin_comment ?></div>
                            <div style="padding: 0px 20px;font-size: 20px;">【スタッフ】<?= $comparedImage->staff_comment ?></div>
                            <a href="<?= $comparedImage->image ?>"
                                title="<?= h($comparedImage->comment) ?> - <?= date('Y年n月j日', strtotime($comparedImage->created)) ?>"
                                data-lightbox="<?= $comparedImage->id ?>"><img src="<?= h($comparedImage->image) ?>"
                                    width="100%" /></a>
                        </div>
                    </div>
            <?php endforeach ?>
        </div>
    </div>
    <div class="card-footer">
        <a class="btn btn-outline-secondary" href="<?= $this->Url->build(['action' => 'index', '?' => $param]); ?>"><i class="fas fa-arrow-circle-left"></i> 戻る</a>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    // Restore scroll
    const savedScrollPos = sessionStorage.getItem("scrollPos");
    if (savedScrollPos) {
        window.scrollTo(0, parseInt(savedScrollPos));
        sessionStorage.removeItem("scrollPos");
    }

    // Save scroll before leaving
    document.querySelectorAll("a, button").forEach(el => {
        el.addEventListener("click", () => {
            sessionStorage.setItem("scrollPos", window.scrollY);
        });
    });
});
</script>