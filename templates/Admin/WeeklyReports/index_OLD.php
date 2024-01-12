<?php $this->assign('title', '週次報告'); ?>

<?= $this->Form->create(null, ['type' => 'get']) ?>
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-3 mt-2">
                <label>測定日</label>
                <?= $this->Form->date('report_date', ['class' => 'form-control', 'placeholder' => '', 'default' => $param['report_date']]) ?>
            </div>
            <div class="col-md-3 mt-2">
                <label>会員名</label>
                <?= $this->Form->select('user_id', $users, ['class' => 'form-control', 'empty' => '-', 'default' => $param['user_id']]) ?>
            </div>
            <div class="col-md-12 text-right mt-2">
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-magnifying-glass"></i> 検索</button>
            </div>
        </div>
    </div>
</div>
<?= $this->Form->end() ?>


<div class="card">
    <div class="card-body">

        <?php foreach ($weeklyReports as $key => $weeklyReport): ?>
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <small>会員番号：</small><?= h($weeklyReport->user->member_number) ?>&nbsp;
                        <small>会員名：</small><?= h($weeklyReport->user->last_name) ?> <?= h($weeklyReport->user->first_name) ?>&nbsp;
                        <small>測定日：</small><?= date('Y年n月j日', strtotime($weeklyReport->report_date)) ?>
                    </h3>
                    <!-- <div class="card-tools">
                        <?php if (!$weeklyReport->is_checked) : ?>
                            <button type="button" class="btn btn-sm btn-primary btn-check" id="btn-check-<?= h($weeklyReport->id) ?>" data-id="<?= h($weeklyReport->id) ?>">確認済みにする</button>
                            <button disabled="disabled" class="btn btn-sm btn-secondary" id="label-checked-<?= h($weeklyReport->id) ?>" style="display:none">確認済み</button>
                        <?php else : ?>
                            <button disabled="disabled" class="btn btn-sm btn-secondary">確認済み</button>
                        <?php endif ?>
                    </div> -->
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <tr>
                            <th style="width:20%">体重</th>
                            <td><?= h($weeklyReport->weight) ?> kg</td>
                            <th style="width:20%">体脂肪率</th>
                            <td><?= h($weeklyReport->fat_percent) ?> %</td>
                        </tr>
                        <tr>
                            <th>アンダーバスト</th>
                            <td><?= h($weeklyReport->size1) ?> cm</td>
                            <th>ウエスト</th>
                            <td><?= h($weeklyReport->size2) ?> cm</td>
                        </tr>
                        <tr>
                            <th>おへそ回り</th>
                            <td><?= h($weeklyReport->size3) ?> cm</td>
                            <th>お尻</th>
                            <td><?= h($weeklyReport->size4) ?> cm</td>
                        </tr>
                        <tr>
                            <th>太もも一番太いところ</th>
                            <td><?= h($weeklyReport->size5) ?> cm</td>
                            <th>太もも中間</th>
                            <td><?= h($weeklyReport->size6) ?> cm</td>
                        </tr>
                        <tr>
                            <th>膝上</th>
                            <td><?= h($weeklyReport->size7) ?> cm</td>
                            <th>ふくらはぎ</th>
                            <td><?= h($weeklyReport->size8) ?> cm</td>
                        </tr>
                        <tr>
                            <th>足首</th>
                            <td><?= h($weeklyReport->size9) ?> cm</td>
                            <th></th>
                            <td></td>
                        </tr>
                    </table>
                    <br>
                    <div class="swiper">
                        <!-- Additional required wrapper -->
                        <div class="swiper-wrapper">
                            <!-- Slides -->
                            <?php foreach ($weeklyReport->photos as $photo) : ?>
                            <div class="swiper-slide">
                                <div><b><?= h($photoName[$photo->photo_number]) ?></b></div>
                                <?php if ($photo->image_url) : ?>
                                    <?php if (strstr($photo->image_url, '.mp4') !== false || strstr($photo->image_url, '.m4v') !== false || strstr($photo->image_url, '.mov') !== false) : ?>
                                        <video src="<?= $photo->image_url ?>" style="max-width:100%;" controls></video>
                                    <?php else : ?>
                                        <img src="<?= $photo->image_url ?>" style="max-width:100%;">
                                    <?php endif ?>
                                <?php endif ?>
                            </div>
                            <?php endforeach ?>
                        </div>
                        <!-- If we need pagination -->
                        <div class="swiper-pagination"></div>

                        <!-- If we need navigation buttons -->
                        <div class="swiper-button-prev"></div>
                        <div class="swiper-button-next"></div>

                        <!-- If we need scrollbar -->
                        <div class="swiper-scrollbar"></div>
                    </div>
                </div>
            </div>
        <?php endforeach ?>

    </div>
    <!-- /.card-body -->
    <div class="card-footer clearfix">
        <?= $this->element('pagination'); ?>
    </div>
</div>
<!-- /.card -->
<script>
    var swiper = new Swiper ('.swiper', {
        loop: true,
        spaceBetween: 10,
        centeredSlides : true,
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
</script>

<?= $this->Form->create(null, ['id' => 'ajax-form']) ?>
<?= $this->Form->end() ?>
<script>
$(function() {
    let csrf = $('#ajax-form').find('input[name=_csrfToken]').val();

    // 確認済みにする
    $('.btn-check').click(function() {
        var id = $(this).data('id');
        $.ajax({
            type: 'POST',
            datatype: 'json',
            url: "<?= $this->Url->build(['action' => 'check']); ?>",
            data: {"id":id},
            beforeSend: function(xhr){
                xhr.setRequestHeader('X-CSRF-Token', csrf);
            },
            success: function(data, dataType) {
                console.log(data);
                $('#btn-check-' + id).hide();
                $('#label-checked-' + id).show();
            }
        });
    });
});
</script>
