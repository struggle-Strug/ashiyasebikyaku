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
                                初めての方へ</h5>
                        </div>
                        <div class="timeline-body">
                            <p class="description" style="margin-bottom:10px">
                                <small>当サイトの使い方や進める順番について、確認して進めてください</small>
                            </p>
                            <p class="description" style="margin-bottom:5px">
                                <small>『セルフデザインコース』は<a href="https://angry-manatee-55c.notion.site/19ed059323c280c9b472e35215ea820b?pvs=4" target="blank">こちら</a></small>
                            </p>
                            <p class="description" style="margin-bottom:10px">
                                <small>『自習コース』は<a href="https://angry-manatee-55c.notion.site/19ed059323c280cdbc20c91d6e3ddcfb?pvs=4" target="blank">こちら</a></small>
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
