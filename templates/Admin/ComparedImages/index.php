<?php $this->assign('title', '比較画像一覧'); ?>

<?= $this->Form->create(null, ['type' => 'get']) ?>
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-4 mt-2">
                <label>測定日</label>
                <div class="input-group">
                    <?= $this->Form->date('start_report_date', ['class' => 'form-control', 'placeholder' => '', 'default' => $param['start_report_date']]) ?>
                    <div class="input-group-prepend">
                        <span class="input-group-text">
                            〜
                        </span>
                    </div>
                    <?= $this->Form->date('end_report_date', ['class' => 'form-control', 'placeholder' => '', 'default' => $param['end_report_date']]) ?>
                </div>
            </div>
            <div class="col-md-3 mt-2">
                <label>会員名</label>
                <div class="input-group">
                    <?= $this->Form->input('user_name', ['class' => 'form-control mr-4', 'empty' => '-', 'placeholder' => '会員名', 'default' => $param['user_name']]) ?>
                </div>
            </div>
            <div class="col-md-3 mt-2">
                <div class="input-group">
                    <?= $this->Form->select('user_id', $users, ['class' => 'form-control', 'empty' => '-', 'default' => $param['user_id'], 'style' => 'margin-top: 1.75rem;']) ?>
                </div>
            </div>
            <div class="col-md-12 text-right mt-2">
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-magnifying-glass"></i> 検索</button>
            </div>
        </div>
    </div>
</div>
<?= $this->Form->end() ?>

<div class="card">
    <div class="card-header">
        <a href="<?= $this->Url->build(['action' => 'add', '?' => $param]); ?>" class="btn btn-outline-primary">新規比較画像作成</a>
    </div>
    <div class="card-body">
        <?php if (!$comparedImages || count($comparedImages) == 0): ?>
                該当のデータはありません。
        <?php else: ?>
            <div class="table-responsive-md" style="height: 50vh; ">
                <?php foreach ($comparedImages as $comparedImage): ?>
                    <div class="card">
                        <div class="card-header">
                            <h5 style="display: flex; justify-content: space-between; align-items:center">
                                <div><?= $comparedImage->created->format('Y年m月d日'); ?>  <?= $comparedImage->user->last_name . ' ' . $comparedImage->user->first_name ?></div>
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
                                        <?= $this->Form->postLink(__('削除'), ['action' => 'delete', $comparedImage->id], ['class' => 'btn btn-outline-danger btn-sm', 'confirm' => __('削除してもよろしいでしょうか？', $comparedImage->id)]) ?>
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
        <?php endif ?>
    </div>
    <!-- /.card-body -->
    <div class="card-footer clearfix">
    </div>
</div>
<!-- /.card -->

<?= $this->Form->create(null, ['id' => 'ajax-form']) ?>
<?= $this->Form->end() ?>
<script>
    var swiper = new Swiper('.swiper', {
        loop: true,
        spaceBetween: 10,
        centeredSlides: true,
        pagination: '.swiper-pagination',
        navigation: {
            nextEl: ".swiper-button-next",
            prevEl: ".swiper-button-prev",
        },
        // スライドの表示枚数：600px未満の場合
        slidesPerView: 1,
        breakpoints: {
            // スライドの表示枚数：600px以上の場合
            600: {
                slidesPerView: 2,
            },
        },
    });

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