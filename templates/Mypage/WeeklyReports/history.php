<?php $this->assign('title', '毎週の報告（過去の記録）'); ?>
<?php
    $week = [
        '日', //0
        '月', //1
        '火', //2
        '水', //3
        '木', //4
        '金', //5
        '土', //6
    ];
?>

<div class="col-lg-12">
    <div class="card">
        <div class="card-content">
            <div class="nav-tabs-navigation">
                <div class="nav-tabs-wrapper">
                    <ul id="tabs" class="nav nav-tabs">
                        <li><a href="<?= $this->Url->build(['action' => 'index']); ?>">登録</a></li>
                        <li class="active"><a href="<?= $this->Url->build(['action' => 'history']); ?>">過去の記録</a></li>
                    </ul>
                </div>
            </div>

            <?php foreach ($weeklyReports as $weeklyReport): ?>
                <div class="card">
                    <div class="card-header">
                        <h5><?= date('Y年n月j日', strtotime($weeklyReport->report_date)) ?>(<?= $week[$weeklyReport->report_date->format('w')] ?>)</h5>
                    </div>
                    <div class="card-content">
                        <table class="table table-bordered">
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
                        <div class="swiper">
                            <!-- Additional required wrapper -->
                            <div class="swiper-wrapper">
                                <!-- Slides -->
                                <?php foreach ($weeklyReport->photos as $photo) : ?>
                                <div class="swiper-slide">
                                    <div><b><?= h($photoName[$photo->photo_number]) ?></b></div>
                                    <?php if ($photo->image_url) : ?>
                                        <?php if (strstr($photo->image_url, '.mp4') !== false || strstr($photo->image_url, '.m4v') !== false || strstr($photo->image_url, '.3gp') !== false || strstr($photo->image_url, '.mov') !== false) : ?>
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
    </div>
</div>

<div class="col-md-12 text-center">
    <?= $this->element('pagination'); ?>
</div>

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
