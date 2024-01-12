<?php $this->assign('title', '送信前の確認'); ?>

<div class="col-lg-12">
    <div class="card">
        <div class="card-content">
            <div class="nav-tabs-navigation">
                <div class="nav-tabs-wrapper">
                    <ul id="tabs" class="nav nav-tabs">
                        <li class="active"><a href="<?= $this->Url->build(['action' => 'index']); ?>">登録</a></li>
                        <li><a href="<?= $this->Url->build(['action' => 'history']); ?>">過去の記録</a></li>
                    </ul>
                </div>
            </div>
            <?php if ((!$firstWeeklyReport && count($weeklyReport->photos) < 17) || ($firstWeeklyReport && count($weeklyReport->photos) < 10)) : // 指定枚数撮影していない ?>
                <div class="col-lg-12">
                    <div class="alert alert-primary alert-with-icon" data-notify="container">
                        <span data-notify="icon" class="ti-alert"></span>
                        <span data-notify="message">必要な撮影が完了していません。</span>
                    </div>
                </div>
                <div class="text-center">
                    <a href="<?= $this->Url->build(['action' => 'edit', $weeklyReport->id, count($weeklyReport->photos) + 1]); ?>"
                        class="btn btn-primary">次の撮影に進む</a>
                </div>
            <?php else : ?>
                <h5 class="text-center">以下の内容でよろしければ、<br>「送信する」ボタンを押してください。</h5>
            <?php endif ?>

            <div class="text-right">
                <a href="<?= $this->Url->build(['action' => 'index']); ?>" class="btn btn-primary">編集</a>
            </div>
            <br>
            <table class="table table-bordered">
                <tr>
                    <th>測定日</th>
                    <td><?= date('Y年n月j日', strtotime($weeklyReport->report_date)) ?></td>
                </tr>
                <tr>
                    <th>体重</th>
                    <td><?= h($weeklyReport->weight) ?> kg</td>
                </tr>
                <tr>
                    <th>体脂肪率</th>
                    <td><?= h($weeklyReport->fat_percent) ?> %</td>
                </tr>
                <tr>
                    <th>アンダーバスト</th>
                    <td><?= h($weeklyReport->size1) ?> cm</td>
                </tr>
                <tr>
                    <th>ウエスト</th>
                    <td><?= h($weeklyReport->size2) ?> cm</td>
                </tr>
                <tr>
                    <th>おへそ回り</th>
                    <td><?= h($weeklyReport->size3) ?> cm</td>
                </tr>
                <tr>
                    <th>お尻</th>
                    <td><?= h($weeklyReport->size4) ?> cm</td>
                </tr>
                <tr>
                    <th>太もも一番太いところ</th>
                    <td><?= h($weeklyReport->size5) ?> cm</td>
                </tr>
                <tr>
                    <th>太もも中間</th>
                    <td><?= h($weeklyReport->size6) ?> cm</td>
                </tr>
                <tr>
                    <th>膝上</th>
                    <td><?= h($weeklyReport->size7) ?> cm</td>
                </tr>
                <tr>
                    <th>ふくらはぎ</th>
                    <td><?= h($weeklyReport->size8) ?> cm</td>
                </tr>
                <tr>
                    <th>足首</th>
                    <td><?= h($weeklyReport->size9) ?> cm</td>
                </tr>
            </table>
            <br>
            <?php if (!empty($weeklyReport->photos)) : ?>
            <?php foreach ($weeklyReport->photos as $photo) : ?>
            <div class="card">
                <div class="card-header">
                    <b><?= h($photoName[$photo->photo_number]) ?></b>
                    <div class="text-right">
                        <a href="<?= $this->Url->build(['action' => 'edit', $weeklyReport->id, $photo->photo_number]); ?>" class="btn btn-primary">再撮影</a>
                    </div>
                </div>
                <div class="card-content">
                    <?php if ($photo->image_url) : ?>
                        <?php if (strstr($photo->image_url, '.mp4') !== false || strstr($photo->image_url, '.m4v') !== false || strstr($photo->image_url, '.3gp') !== false || strstr($photo->image_url, '.mov') !== false) : ?>
                            <video src="<?= $photo->image_url ?>" style="max-width:100%;" controls></video>
                        <?php else : ?>
                            <img src="<?= $photo->image_url ?>" style="max-width:100%;">
                        <?php endif ?>
                    <?php endif ?>
                </div>
            </div>
            <?php endforeach ?>
            <?php endif ?>
        </div>
        <div class="card-footer">
            <?php if ((!$firstWeeklyReport && count($weeklyReport->photos) < 17) || ($firstWeeklyReport && count($weeklyReport->photos) < 10)) : // 指定枚数撮影していない ?>
                <div class="col-lg-12">
                    <div class="alert alert-primary alert-with-icon" data-notify="container">
                        <span data-notify="icon" class="ti-alert"></span>
                        <span data-notify="message">必要な撮影が完了していません。</span>
                    </div>
                </div>
                <div class="text-center" style="margin:30px">
                    <a href="<?= $this->Url->build(['action' => 'edit', $weeklyReport->id, count($weeklyReport->photos) + 1]); ?>"
                        class="btn btn-primary">次の撮影に進む</a>
                </div>
            <?php else : ?>
                <?= $this->Form->create() ?>
                <div class="text-center" style="margin:30px">
                    <button type="submit" class="btn btn-info btn-fill btn-wd">この内容で送信する</button>
                </div>
                <?= $this->Form->end() ?>
            <?php endif ?>
        </div>
    </div>
    <!-- /.card -->
</div>
