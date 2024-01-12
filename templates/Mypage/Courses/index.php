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
                            <!-- <p class="description" style="margin-bottom:10px">
                                <small>プログラムの概要が表示されます。プログラムの概要が表示されます。プログラムの概要が表示されます。</small>
                            </p> -->
                            <?php if (!$eventEntry1) : ?>
                                <a href="<?= $this->Url->build(['controller' => 'Events', 'action' => 'view', 1]); ?>"
                                    class="btn btn-wd btn-primary">
                                    <span class="btn-label">
                                        <i class="fa-solid fa-chevron-right"></i>
                                    </span>
                                    申し込む
                                </a>
                            <?php endif ?>
                        </div>
                    </div>
                </li>
                <li class="timeline-inverted">
                    <div class="timeline-badge primary">
                        <i class="ti-light-bulb"></i>
                    </div>
                    <div class="timeline-panel">
                        <div class="timeline-heading">
                            <h5 class="card-title" style="margin-bottom:5px"><span
                                    class="label label-primary">２</span>【STEP1】Ready to 美脚ファンデーション講座</h5>
                        </div>
                        <div class="timeline-body">
                            <a href="<?= $this->Url->build(['action' => 'step1']); ?>"
                                class="btn btn-wd btn-primary">
                                <span class="btn-label">
                                    <i class="fa-solid fa-chevron-right"></i>
                                </span>
                                ワーク動画を見る
                            </a>
                        </div>
                    </div>
                </li>
                <li class="timeline-inverted">
                    <?php if (!$firstWeeklyReport) : ?>
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
                            <h5 class="card-title" style="margin-bottom:5px"><span class="label label-primary">３</span>
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
                <li class="timeline-inverted">
                    <?php if (!$eventEntry2) : ?>
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
                            <h5 class="card-title" style="margin-bottom:5px"><span class="label label-primary">４</span>
                                目標設定コーチング申込</h5>
                        </div>
                        <div class="timeline-body">
                            <?php if (!$eventEntry2) : ?>
                                <a href="<?= $this->Url->build(['controller' => 'Events', 'action' => 'view', 2]); ?>"
                                    class="btn btn-wd btn-primary">
                                    <span class="btn-label">
                                        <i class="fa-solid fa-chevron-right"></i>
                                    </span>
                                    申し込む
                                </a>
                            <?php endif ?>
                        </div>
                    </div>
                </li>
                <li class="timeline-inverted">
                    <div class="timeline-badge primary">
                        <i class="ti-light-bulb"></i>
                    </div>
                    <div class="timeline-panel">
                        <div class="timeline-heading">
                            <h5 class="card-title" style="margin-bottom:5px"><span
                                    class="label label-primary">５</span>【STEP2】Go To 美脚マスター講座</h5>
                        </div>
                        <div class="timeline-body">
                            <a href="<?= $this->Url->build(['action' => 'step2']); ?>"
                                class="btn btn-wd btn-primary">
                                <span class="btn-label">
                                    <i class="fa-solid fa-chevron-right"></i>
                                </span>
                                ワーク動画を見る
                            </a>
                        </div>
                    </div>
                </li>
                <li class="timeline-inverted">
                    <?php if (!$eventEntry3) : ?>
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
                            <h5 class="card-title" style="margin-bottom:5px"><span class="label label-primary">６</span>
                                腸活コンサル申込</h5>
                        </div>
                        <div class="timeline-body">
                            <?php if (!$eventEntry3) : ?>
                                <a href="<?= $this->Url->build(['controller' => 'Events', 'action' => 'view', 3]); ?>"
                                    class="btn btn-wd btn-primary">
                                    <span class="btn-label">
                                        <i class="fa-solid fa-chevron-right"></i>
                                    </span>
                                    申し込む
                                </a>
                            <?php endif ?>
                        </div>
                    </div>
                </li>
                <li class="timeline-inverted">
                    <?php if (!$eventEntry4) : ?>
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
                            <h5 class="card-title" style="margin-bottom:5px"><span class="label label-primary">７</span>
                                最終カウンセリング申込（成果報告会）</h5>
                        </div>
                        <div class="timeline-body">
                            <?php if (!$eventEntry4) : ?>
                                <a href="<?= $this->Url->build(['controller' => 'Events', 'action' => 'view', 4]); ?>"
                                    class="btn btn-wd btn-primary">
                                    <span class="btn-label">
                                        <i class="fa-solid fa-chevron-right"></i>
                                    </span>
                                    申し込む
                                </a>
                            <?php endif ?>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</div>
