<?php $this->assign('title', 'パーソナルプログラム'); ?>

<div class="col-lg-12">
    <div class="card card-timeline card-plain">
        <div class="card-content">
            <ul class="timeline timeline-simple">
                <li class="timeline-inverted">
                    <?php if (!$eventEntry1) : ?>
                        <div class="timeline-badge primary">
                            <i class="ti-light-bulb"></i>
                        </div>
                    <?php else : ?>
                        <div class="timeline-badge success">
                            <i class="ti-check-box"></i>
                        </div>
                    <?php endif ?>
                    <div class="timeline-panel">
                        <div class="timeline-heading">
                            <h5 class="card-title" style="margin-bottom:5px"><span class="label label-primary">１</span>
                                オリエンテーション申込</h5>
                        </div>
                        <div class="timeline-body">
                            <p class="description" style="margin-bottom:10px">
                                <small>当サイトの使い方や進める順番について、<a href="https://leeward-utensil-225.notion.site/1b170ba5029a4358b7518450c62184c3?pvs=4" target="blank">こちら</a>から確認して進めてください</small>
                            </p>
                            <?php if (!$eventEntry1) : ?>
                                <!-- <a href="<?= $this->Url->build(['controller' => 'Events', 'action' => 'view', 1]); ?>"
                                    class="btn btn-wd btn-primary">
                                    <span class="btn-label">
                                        <i class="fa-solid fa-chevron-right"></i>
                                    </span>
                                    申し込む
                                </a> -->
                            <?php endif ?>
                        </div>
                    </div>
                </li>
                <li class="timeline-inverted">
                    <!-- <?php if (!$firstWeeklyReport) : ?>
                        <div class="timeline-badge primary">
                            <i class="ti-light-bulb"></i>
                        </div>
                    <?php else : ?>
                        <div class="timeline-badge success">
                            <i class="ti-check-box"></i>
                        </div>
                    <?php endif ?> -->
                    <div class="timeline-badge primary">
                            <i class="ti-light-bulb"></i>
                    </div>
                    <div class="timeline-panel">
                        <div class="timeline-heading">
                            <h5 class="card-title" style="margin-bottom:5px"><span class="label label-primary">２</span>
                                初期診断のための情報登録</h5>
                        </div>
                        <div class="timeline-body">
                            <?php if (!$firstWeeklyReport) : ?>
                                <a href="<?= $this->Url->build(['controller' => 'WeeklyReports', 'action' => 'index']); ?>"
                                    class="btn btn-wd btn-primary">
                                    <span class="btn-label">
                                        <i class="fa-solid fa-chevron-right"></i>
                                    </span>
                                    情報登録をする
                                </a>
                            <?php endif ?>
                        </div>
                    </div>
                </li>
        </div>
    </div>
</div>
