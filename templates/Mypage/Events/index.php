<?php $this->assign('title', 'イベント情報'); ?>

<div class="col-lg-12">
    <div class="card">
        <div class="card-content">
            <div class="nav-tabs-navigation">
                <div class="nav-tabs-wrapper">
                    <ul id="tabs" class="nav nav-tabs">
                        <li class="active"><a href="<?= $this->Url->build(['action' => 'index']); ?>">募集中のイベント</a></li>
                        <li><a href="<?= $this->Url->build(['action' => 'history']); ?>">応募したイベント</a></li>
                    </ul>
                </div>
            </div>

            <?php foreach ($events as $event): ?>
                <div class="col-md-12">
                    <div class="panel">
                        <div class="card-content">
                            <div class="row">
                                <div class="col-xs-12">
                                    <?php if ($event->type == '1') : ?>
                                        <span class="label label-info" style="margin-bottom:5px"><?= h($eventType[$event->type]) ?></span>
                                    <?php elseif ($event->type == '2') : ?>
                                        <span class="label label-warning" style="margin-bottom:5px"><?= h($eventType[$event->type]) ?></span>
                                    <?php elseif ($event->type == '3') : ?>
                                        <span class="label label-success" style="margin-bottom:5px"><?= h($eventType[$event->type]) ?></span>
                                    <?php endif ?>
                                    <h5 class="card-title" style="margin:0"><?= h($event->title) ?></h5>
                                    <p class="description"><small>募集期間：<br><?= date('Y年n月j日 H:i', strtotime($event->start_datetime)) ?> 〜 <?= ($event->end_datetime) ? date('Y年n月j日 H:i', strtotime($event->end_datetime)) : '' ?></small></p>
                                    <a href="<?= $this->Url->build(['action' => 'view', $event->id]); ?>" class="btn btn-wd btn-primary">
                                        <span class="btn-label">
                                            <i class="fa-solid fa-chevron-right"></i>
                                        </span>
                                        詳細を見る
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach ?>

            <div class="clearfix" style="margin:10px"></div>
        </div>
    </div>
</div>

