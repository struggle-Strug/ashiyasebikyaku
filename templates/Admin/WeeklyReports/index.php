<?php $this->assign('title', '週次報告'); ?>

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
                <?= $this->Form->select('user_id', $users, ['class' => 'form-control', 'empty' => '-', 'default' => $param['user_id']]) ?>
            </div>
            <div class="col-md-12 text-right mt-2">
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-magnifying-glass"></i> 検索</button>
            </div>
        </div>
    </div>
</div>
<?= $this->Form->end() ?>

<style>
.scrollbar::-webkit-scrollbar {
  width: 10px;
  height: 10px;
}
/* スクロールバーの形を設定する */
.scrollbar-thumb-rounded::-webkit-scrollbar-thumb {
  border-radius: 0.25rem;
}
/* スクロールバーの色を設定する */
.scrollbar-thumb-black::-webkit-scrollbar-thumb {
  --bg-opacity: 1;
  background-color: rgba(0, 0, 0, 0.5);
}
</style>

<div class="card">
    <div class="card-body">
        <?php if (!$weeklyReports || count($weeklyReports) == 0) : ?>

           <?php if (empty($param['user_id'])) : ?>
                会員を選択してください。
            <?php else : ?>
                該当のデータはありません。
            <?php endif ?>

        <?php else : ?>

            <div class="table-responsive-md   scrollbar scrollbar-thumb-rounded scrollbar-thumb-black" style="height: 50vh; ">
                <table class="table table-bordered table-striped overflow-y: scroll">
                    <thead>
                        <tr>
                        <th nowrap>ダウンロード</th>
                            <th nowrap>測定日</th>
                            <th nowrap>体重</th>
                            <th nowrap>体脂肪率</th>
                            <th nowrap>アンダー<br>バスト</th>
                            <th nowrap>ウエスト</th>
                            <th nowrap>おへそ<br>回り</th>
                            <th nowrap>お尻</th>
                            <th nowrap>太もも<br>一番太い<br>ところ</th>
                            <th nowrap>太もも<br>中間</th>
                            <th nowrap>膝上</th>
                            <th nowrap>ふくら<br>はぎ</th>
                            <th nowrap>足首</th>
                            <?php foreach ($photoName as $key => $name): ?>
                                <th nowrap style="min-width:120px"><?= h($name) ?></th>
                            <?php endforeach ?>
                            
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($weeklyReports as $key => $weeklyReport): ?>
                            <tr>
                            <td nowrap style="text-align:right">
                                    <?php echo $this->Html->link('一括ダウンロード', ['controller' => 'WeeklyReports', 'action' => 'downloadImages', $weeklyReport->id], ['class' => 'btn btn-primary']); ?>
                                </td>
                                <td nowrap><?= date('Y年n月j日', strtotime($weeklyReport->report_date)) ?></td>
                                <td nowrap style="text-align:right"><?= h($weeklyReport->weight) ?> kg</td>
                                <td nowrap style="text-align:right"><?= h($weeklyReport->fat_percent) ?> %</td>
                                <td nowrap style="text-align:right"><?= h($weeklyReport->size1) ?> cm</td>
                                <td nowrap style="text-align:right"><?= h($weeklyReport->size2) ?> cm</td>
                                <td nowrap style="text-align:right"><?= h($weeklyReport->size3) ?> cm</td>
                                <td nowrap style="text-align:right"><?= h($weeklyReport->size4) ?> cm</td>
                                <td nowrap style="text-align:right"><?= h($weeklyReport->size5) ?> cm</td>
                                <td nowrap style="text-align:right"><?= h($weeklyReport->size6) ?> cm</td>
                                <td nowrap style="text-align:right"><?= h($weeklyReport->size7) ?> cm</td>
                                <td nowrap style="text-align:right"><?= h($weeklyReport->size8) ?> cm</td>
                                <td nowrap style="text-align:right"><?= h($weeklyReport->size9) ?> cm</td>
                                <?php foreach ($weeklyReport->photos as $photo) : ?>
                                    <td>
                                        <?php if ($photo->image_url) : ?>
                                            <?php if (strstr($photo->image_url, '.mp4') !== false || strstr($photo->image_url, '.m4v') !== false || strstr($photo->image_url, '.3gp') !== false || strstr($photo->image_url, '.mov') !== false || strstr($photo->image_url, '.webm') !== false) : ?>
                                                <video src="<?= $photo->image_url ?>" style="max-width:100%;" controls></video>
                                            <?php else : ?>
                                                <a href="<?= $photo->image_url ?>" title="<?= h($photoName[$photo->photo_number]) ?> - <?= date('Y年n月j日', strtotime($weeklyReport->report_date)) ?>" data-lightbox="<?= $photo->photo_number ?>"><img src="<?= $photo->image_url ?>" style="max-width:100%;"></a>
                                            <?php endif ?>
                                        <?php endif ?>
                                    </td>
                                <?php endforeach ?>
                                <td nowrap style="text-align:right">
                                </td>
                                <td nowrap style="text-align:right">
                                </td>
                                <td nowrap style="text-align:right">
                                </td>
                                
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                    <tfoot>
                        <tr>
                        <th nowrap>ダウンロード</th>
                            <th nowrap>測定日</th>
                            <th nowrap>体重</th>
                            <th nowrap>体脂肪率</th>
                            <th nowrap>アンダー<br>バスト</th>
                            <th nowrap>ウエスト</th>
                            <th nowrap>おへそ<br>回り</th>
                            <th nowrap>お尻</th>
                            <th nowrap>太もも<br>一番太い<br>ところ</th>
                            <th nowrap>太もも<br>中間</th>
                            <th nowrap>膝上</th>
                            <th nowrap>ふくら<br>はぎ</th>
                            <th nowrap>足首</th>
                            <?php foreach ($photoName as $key => $name): ?>
                                <th nowrap style="min-width:120px"><?= h($name) ?></th>
                            <?php endforeach ?>
                        </tr>
                    </tfoot>
                </table>
            </div>

        <?php endif ?>

    </div>
</div>
<!-- /.card -->
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
            data: {
                "id": id
            },
            beforeSend: function(xhr) {
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
