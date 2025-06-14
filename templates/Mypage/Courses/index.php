<?php $this->assign('title', '初めての方へ'); ?>

<div class="col-lg-12">
    <div class="card card-timeline card-plain">
        <div class="card-content">
            <ul class="timeline timeline-simple">
                <li class="timeline-inverted">
                    <?php if (!$eventEntry1): ?>
                        <div class="timeline-badge primary">
                            <i class="ti-light-bulb"></i>
                        </div>
                    <?php else: ?>
                        <div class="timeline-badge success">
                            <i class="ti-check-box"></i>
                        </div>
                    <?php endif ?>
                    <div class="timeline-panel">
                        <div class="timeline-heading">
                            <h5 class="card-title" style="margin-bottom:5px"><span class="label label-primary">１</span>
                                受講生手引き</h5>
                        </div>
                        <div class="timeline-body">
                            <p class="description" style="margin-bottom:10px">
                                <small>当サイトの使い方や進める順番について、確認して進めてください</small>
                            </p>
                            <p class="description" style="margin-bottom:5px">
                                <small>「エバーコース」の方は<a href="https://gxu1lwgp.autosns.app/cp/mGD4OsT1vo"
                                        target="blank">こちら</a></small>
                            </p>
                            <p class="description" style="margin-bottom:10px">
                                <small>「マスターコース」の方は<a href="https://gxu1lwgp.autosns.app/cp/XTN4k9hZnQ"
                                        target="blank">こちら</a></small>
                            </p>
                            <p class="description" style="margin-bottom:10px">
                                <small>「自習コース」の方は<a href="https://gxu1lwgp.autosns.app/cp/xKlFqCIP8s"
                                        target="blank">こちら</a></small>
                            </p>
                            <?php if (!$eventEntry1): ?>
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
                    <!-- <?php if (!$firstWeeklyReport): ?>
                        <div class="timeline-badge primary">
                            <i class="ti-light-bulb"></i>
                        </div>
                    <?php else: ?>
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
                            <p class="description" style="margin-bottom:10px">
                                <small>初回コンサルに必要なため、部位写真を登録してください</small>
                            </p>
                            <?php if (!$firstWeeklyReport): ?>
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
                <li class="timeline-inverted">
                    <!-- <?php if (!$firstWeeklyReport): ?>
                        <div class="timeline-badge primary">
                            <i class="ti-light-bulb"></i>
                        </div>
                    <?php else: ?>
                        <div class="timeline-badge success">
                            <i class="ti-check-box"></i>
                        </div>
                    <?php endif ?> -->
                    <div class="timeline-badge primary">
                        <i class="ti-light-bulb"></i>
                    </div>
                    <div class="timeline-panel">
                        <div class="timeline-heading">
                            <h5 class="card-title" style="margin-bottom:5px"><span class="label label-primary">３</span>
                                オリエンテーションの予約</h5>
                        </div>
                        <div class="timeline-body">
                            <p class="description" style="margin-bottom:10px">
                                <small>受講開始前までにオリエンテーションの日程予約を行い、受講をお願いします</small>
                            </p>
                        </div>
                    </div>
                </li>
                <li class="timeline-inverted">
                    <!-- <?php if (!$firstWeeklyReport): ?>
                        <div class="timeline-badge primary">
                            <i class="ti-light-bulb"></i>
                        </div>
                    <?php else: ?>
                        <div class="timeline-badge success">
                            <i class="ti-check-box"></i>
                        </div>
                    <?php endif ?> -->
                    <div class="timeline-badge primary">
                        <i class="ti-light-bulb"></i>
                    </div>
                    <div class="timeline-panel">
                        <div class="timeline-heading">
                            <h5 class="card-title" style="margin-bottom:5px"><span class="label label-primary">４</span>
                            目標設定シートの作成</h5>
                        </div>
                        <div class="timeline-body">
                            <p class="description" style="margin-bottom:10px">
                                <small>受講開始前までに目標作成シートを作成し、事務局へ提出をお願いします</small>
                            </p>
                        </div>
                    </div>
                </li>
                <li class="timeline-inverted">
                    <!-- <?php if (!$firstWeeklyReport): ?>
                        <div class="timeline-badge primary">
                            <i class="ti-light-bulb"></i>
                        </div>
                    <?php else: ?>
                        <div class="timeline-badge success">
                            <i class="ti-check-box"></i>
                        </div>
                    <?php endif ?> -->
                    <div class="timeline-badge primary">
                        <i class="ti-light-bulb"></i>
                    </div>
                    <div class="timeline-panel">
                        <div class="timeline-heading">
                            <h5 class="card-title" style="margin-bottom:5px"><span class="label label-primary">５</span>
                            初回の個別コンサル予約</h5>
                        </div>
                        <div class="timeline-body">
                            <p class="description" style="margin-bottom:10px">
                                <small>初期診断結果と提出済みの目標設定シートをもとに行う個別コンサルの予約をお願いします</small>
                            </p>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</div>