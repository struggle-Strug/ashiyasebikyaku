<?php $this->assign('title', '動画'); ?>
<?php if ($this->Identity->get('staff_plan') == "1"): ?>
    <div class="col-md-12">
        <?php foreach ($courses as $course): ?>
            <?php if ($course->type == "5" || $course->type == "6"): ?>
                <div class="card relative">
                    <div class="card-header">
                        <h4 class="card-title"><?= h($course->name) ?></h4>
                    </div>
                    <div class="card-content">
                        <!-- <div style="margin-bottom:20px"><?= $this->Link->linkify(h($course->note)) ?></div> -->
                        <div class="row">
                            <div class="col-lg-12 col-md-12">
                                <?php foreach ($course->course_movies as $courseMovie): ?>
                                    <a href="<?= $this->Url->build(['action' => 'view', $course->id, $courseMovie->id]); ?>">
                                        <div class="card" data-background-color="pink">
                                            <div class="card-content">
                                                <div class="row">
                                                    <div class="col-md-1 col-xs-1">
                                                        <?php if ($courseMovie->image_url): ?>
                                                            <img src="<?= h($courseMovie->image_url) ?>" style="width:100%">
                                                        <?php else: ?>
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
            <?php endif; ?>
        <?php endforeach ?>
    </div>
<?php else: ?>
    <div class="col-md-12">
        <?php foreach ($courses as $course): ?>
            <div class="card relative">
                <div class="card-header">
                    <h4 class="card-title"><?= h($course->name) ?></h4>
                </div>
                <div class="card-content">
                    <!-- <div style="margin-bottom:20px"><?= $this->Link->linkify(h($course->note)) ?></div> -->
                    <div class="row">
                        <div class="col-lg-12 col-md-12">
                            <?php foreach ($course->course_movies as $courseMovie): ?>
                                <a href="<?= $this->Url->build(['action' => 'view', $course->id, $courseMovie->id]); ?>">
                                    <div class="card" data-background-color="pink">
                                        <div class="card-content">
                                            <div class="row">
                                                <div class="col-md-1 col-xs-1">
                                                    <?php if ($courseMovie->image_url): ?>
                                                        <img src="<?= h($courseMovie->image_url) ?>" style="width:100%">
                                                    <?php else: ?>
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
<?php endif; ?>