<?php $this->assign('title', 'グループレッスン過去動画'); ?>
<div class="col-md-12">
    <?php foreach ($lessonMovies as $lessonMovie): ?>
        <div class="card relative">
            <div style="position: absolute; right:15px; top:15px;">
            </div>
            <div class="card-header">
                <h4 class="card-title"><?= h($lessonMovie->name) ?></h4>
            </div>
            <div class="card-content">
                <div class="row">
                    <div class="col-lg-12 col-md-12">
                        <a
                            href="<?= $this->Url->build(['action' => 'lesson', $lessonMovie->id]); ?>">
                            <div class="card" data-background-color="pink">
                                <div class="card-content">
                                    <div class="row">
                                        <div class="col-md-1 col-xs-1">
                                            <?php if ($lessonMovie->image_url): ?>
                                                <img src="<?= h($lessonMovie->image_url) ?>" style="width:100%">
                                            <?php else: ?>
                                                <img src="/img/noimage.png" style="width:100%">
                                            <?php endif ?>
                                        </div>
                                        <div class="col-md-11 col-xs-11">
                                            <div class="h5 pull-left">
                                                <p class="description">
                                                    <?= h($lessonMovie->name) ?>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach ?>
</div>