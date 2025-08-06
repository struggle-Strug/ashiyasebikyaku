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
                <?php if ($courseMovie->info_movie_url): ?>
                    <div class="col-lg-6">
                        <h3><span class="label label-info" style="margin-bottom:10px">解説動画</span></h3>
                        <?php
                        $videoUrl = h($courseMovie->info_movie_url);

                        if (strpos($videoUrl, 'vimeo') !== false): ?>
                            <div id="video-wrapper-info" style="overflow:hidden; width:100%; height:auto; position:relative; padding-top:56.25%;">
                                <iframe id="video-info" src="<?= $videoUrl ?>"
                                    style="position:absolute; top:0; left:0; width:100%; height:100%;" frameborder="0"
                                    allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>
                            </div>
                        <?php else: ?>
                            <div id="video-wrapper-info" style="overflow:hidden; width:100%; height:auto;">
                                <video id="video-info" controls controlsList="nodownload" style="width:100%; height:auto;">
                                    <source src="<?= $videoUrl ?>" type="video/mp4">
                                </video>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif ?>
                <div class="col-lg-6">
                    <h3><span class="label label-primary" style="margin-bottom:10px">ワーク動画</span></h3>
                    <?php
                    $videoUrl = h($courseMovie->movie_url);
                    if (strpos($videoUrl, 'vimeo') !== false): ?>
                        <div id="video-wrapper-work" style="overflow:hidden; width:100%; height:auto; position:relative; padding-top:56.25%;">
                            <iframe id="video-work" src="<?= $videoUrl ?>"
                                style="position:absolute; top:0; left:0; width:100%; height:100%;" frameborder="0"
                                allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>
                        </div>
                    <?php else: ?>
                        <div id="video-wrapper-work" style="overflow:hidden; width:100%; height:auto;">
                            <video id="video-work" controls controlsList="nodownload" style="width:100%; height:auto;">
                                <source src="<?= $videoUrl ?>" type="video/mp4">
                            </video>
                        </div>
                    <?php endif; ?>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- Panzoom Library -->
<script src="https://unpkg.com/@panzoom/panzoom/dist/panzoom.min.js"></script>

<script>
    // Initialize Panzoom for 解説動画
    const infoWrapper = document.getElementById('video-wrapper-info');
    if (infoWrapper) {
        const panzoomInfo = Panzoom(infoWrapper, {
            maxScale: 5,
            contain: 'outside'
        });
        infoWrapper.addEventListener('wheel', panzoomInfo.zoomWithWheel);
    }

    // Initialize Panzoom for ワーク動画
    const workWrapper = document.getElementById('video-wrapper-work');
    if (workWrapper) {
        const panzoomWork = Panzoom(workWrapper, {
            maxScale: 5,
            contain: 'outside'
        });
        workWrapper.addEventListener('wheel', panzoomWork.zoomWithWheel);
    }
</script>
