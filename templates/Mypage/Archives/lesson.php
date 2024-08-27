<?php $this->assign('title', 'グループレッスン（アーカイブ）'); ?>

<div class="col-lg-12">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title"><?= h($lessonMovie->name) ?></h4>
            <p class="category"><?= $this->Link->linkify(h($lessonMovie->note)) ?></p>
        </div>
        <div class="card-content">
            <?php
            $videoUrl = h($lessonMovie->movie_url);

            if(strpos($videoUrl, 'vimeo')!== false){
                echo '<div style="padding:56.25% 0 0 0;position:relative;">
                    <iframe src="' . $videoUrl . '?dnt=1" 
                    style="position:absolute;top:0;left:0;width:100%;height:100%;" frameborder="0"
                    allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>
                </div>
                <script src="https://player.vimeo.com/api/player.js"></script>';
            } else {
                echo '<video controls controlsList="nodownload" style="width:100%; height:auto;">
                    <source src="' . $videoUrl . '" type="video/mp4">
                    </video>';
            }
            ?>
        </div>
    </div>
</div>
