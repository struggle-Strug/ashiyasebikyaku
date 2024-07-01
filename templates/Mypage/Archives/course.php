<?php $this->assign('title', h($course->name)); ?>

<div class="col-lg-12">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title"><?= h($courseMovie->name) ?></h4>
        </div>
        <div class="card-content">
            <p class="category"><?= $this->Link->linkify(h($course->note)) ?></p>
            <p class="category"><?= $this->Link->linkify(h($courseMovie->note)) ?></p>
            <div class="row">
                <?php if ($courseMovie->info_movie_url) : ?>
                    <div class="col-lg-6">
                        <h3><span class="label label-info" style="margin-bottom:10px">解説動画</span></h3>
                        <div style="padding:56.25% 0 0 0;position:relative;margin-bottom:20px">
                            <iframe src="<?= h($courseMovie->info_movie_url) ?>"
                                style="position:absolute;top:0;left:0;width:100%;height:100%;" frameborder="0"
                                allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>
                        </div>
                    </div>
                <?php endif ?>
                <div class="col-lg-6">
                    <h3><span class="label label-primary" style="margin-bottom:10px">ワーク動画</span></h3>
                    <div style="padding:56.25% 0 0 0;position:relative;">
                        <iframe src="<?= h($courseMovie->movie_url) ?>"
                            style="position:absolute;top:0;left:0;width:100%;height:100%;" frameborder="0"
                            allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>
                    </div>
                </div>
            </div>
            <script src="https://player.vimeo.com/api/player.js"></script>
        </div>
    </div>
</div>
