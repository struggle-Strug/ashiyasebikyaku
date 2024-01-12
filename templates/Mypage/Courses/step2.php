<?php $this->assign('title', '【STEP2】Go To 美脚マスター講座'); ?>

<div class="col-md-12">
    <?php foreach ($courses as $course): ?>
        <div class="card">
            <div class="card-header">
                <h4 class="card-title"><?= h($course->name) ?></h4>
            </div>
            <div class="card-content">
                <!-- <div style="margin-bottom:20px"><?= nl2br(h($course->note)) ?></div> -->
                <div class="row">
                    <div class="col-lg-12 col-md-12">

                        <?php foreach ($course->course_movies as $courseMovie): ?>
                            <?php
                                $color = 'pink';
                                if ($course->type == 2) {
                                    $color = 'purple';
                                } elseif ($course->type == 3) {
                                    $color = 'green';
                                } elseif ($course->type == 4) {
                                    $color = 'straw';
                                }
                            ?>
                            <a href="<?= $this->Url->build(['action' => 'view', $course->id, $courseMovie->id]); ?>">
                                <div class="card" data-background-color="<?= $color ?>">
                                    <div class="card-content">
                                        <div class="row">
                                            <div class="col-md-1 col-xs-1">
                                                <?php if ($courseMovie->image_url) : ?>
                                                    <img src="<?= h($courseMovie->image_url) ?>" style="width:100%">
                                                <?php else : ?>
                                                    <img src="/img/noimage.png" style="width:100%">
                                                <?php endif ?>
                                            </div>
                                            <div class="col-md-11 col-xs-11">
                                                <div class="h5 pull-left">
                                                    <p class="description">
                                                        <?= h($courseMovie->name) ?>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        <?php endforeach ?>

                    </div>
                </div>
            </div>
        </div>
    <?php endforeach ?>
</div>
