<?php $this->assign('title', '週次報告'); ?>

<?= $this->Form->create(null, ['type' => 'get']) ?>
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-4 mt-2">
                <label>開催日時</label>
                <div class="input-group">
                    <?= $this->Form->date('start_date', ['class' => 'form-control', 'placeholder' => '', 'default' => $param['start_date']]) ?>
                    <div class="input-group-prepend">
                        <span class="input-group-text">
                            〜
                        </span>
                    </div>
                    <?= $this->Form->date('end_date', ['class' => 'form-control', 'placeholder' => '', 'default' => $param['end_date']]) ?>
                </div>
            </div>
            <div class="col-md-3 mt-2">
                <label>区分</label>
                <div class="input-group">
                    <?= $this->Form->select('list_id', $listOptions, ['class' => 'form-control', 'empty' => '-', 'default' => $param['list_id']]) ?>
                </div>
            </div>
            <div class="col-md-3 mt-2">
                <label>講師</label>
                <div class="input-group">
                    <?= $this->Form->select('staff_id', $staffs, ['class' => 'form-control', 'empty' => '-', 'default' => $param['staff_id']]) ?>
                </div>
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

.uniform-image {
  width: 100px;
  display: block;
  margin: 0;
  padding: 0;
  border: none;
}
</style>

<div class="card">
    <div class="card-body">
        <!-- <?php if (!$weeklyReports || count($weeklyReports) == 0) : ?>

                該当のデータはありません。

        <?php else : ?>

            <div class="table-responsive-md   scrollbar scrollbar-thumb-rounded scrollbar-thumb-black" style="height: 50vh; ">
                <table class="table table-bordered table-striped overflow-y: scroll">
                    <thead>
                        <tr>
                        <th nowrap>ダウンロード</th>
                            <th nowrap>測定日</th>
                            <th nowrap>会員名</th>
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
                            <?php if ($role_id != 1): ?>
                                        <?php echo $this->Html->link('一括ダウンロード', ['controller' => 'WeeklyReports', 'action' => 'downloadImages', $weeklyReport->id], ['class' => 'btn btn-primary']); ?>
                                    <?php else: ?>
                                        <button class="btn btn-secondary" disabled>一括ダウンロード</button>
                                    <?php endif; ?>
                                </td>
                                <td nowrap><?= date('Y年n月j日', strtotime($weeklyReport->report_date)) ?></td>
                                <td nowrap><?= h($weeklyReport->user->fullname) ?></td>
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
                                                <a href="<?= $photo->image_url ?>" title="<?= h($photoName[$photo->photo_number]) ?> - <?= date('Y年n月j日', strtotime($weeklyReport->report_date)) ?>" data-lightbox="<?= $photo->photo_number ?>"><img src="<?= $photo->image_url ?>" class="uniform-image"></a>
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
                            <th nowrap>会員名</th>
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

        <?php endif ?> -->

    </div>
</div>
<!-- /.card -->
<?= $this->Form->end() ?>
<script>
</script>
