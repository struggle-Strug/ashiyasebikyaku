<?php $this->assign('title', '動画'); ?>

<div class="col-lg-12">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title"><?= h($lessonMovie->name) ?></h4>
            <p class="category"><?= $this->Link->linkify(h($lessonMovie->note)) ?></p>
        </div>
        <div class="card-content">
            <div style="padding:56.25% 0 0 0;position:relative;">
                <iframe src="<?= h($lessonMovie->movie_url) ?>"
                    style="position:absolute;top:0;left:0;width:100%;height:100%;" frameborder="0"
                    allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>
            </div>
            <script src="https://player.vimeo.com/api/player.js"></script>
        </div>
    </div>
</div>
