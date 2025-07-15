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

            if(strpos($videoUrl, 'vimeo') !== false): ?>
                <div style="padding:56.25% 0 0 0;position:relative;">
                    <iframe src="<?= $videoUrl ?>"
                    style="position:absolute;top:0;left:0;width:100%;height:100%;" frameborder="0"
                    allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>
                </div>
                <script src="https://player.vimeo.com/api/player.js"></script>
            <?php else: ?>
                <div id="video-container" class="mobile-zoom-wrapper">
                    <video
                        id="lesson-video"
                        src="<?= $videoUrl ?>"
                        playsinline
                        controls
                        controlsList="nodownload"
                        style="width: 100%; height: auto;">
                    </video>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>

@media (max-width: 768px) {
  .mobile-zoom-wrapper {
    overflow: hidden;
    touch-action: none;
  }

  .mobile-zoom-wrapper video {
    transform-origin: center center;
    transition: transform 0.1s ease-out;
  }
}

</style>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const isMobile = /iPhone|Android.*Mobile|Windows Phone/.test(navigator.userAgent);
    if (!isMobile) return; // モバイルでのみ有効

    let container = document.getElementById('video-container');
    let video = document.getElementById('lesson-video');
    let scale = 1;
    let startDistance = 0;
    let lastScale = 1;
    let offsetX = 0;
    let offsetY = 0;
    let lastOffsetX = 0;
    let lastOffsetY = 0;

    container.addEventListener('touchstart', (e) => {
        if (e.touches.length === 2) {
            startDistance = getDistance(e.touches[0], e.touches[1]);
        }
    }, { passive: false });

    container.addEventListener('touchmove', (e) => {
        if (e.touches.length === 2) {
            e.preventDefault();
            const currentDistance = getDistance(e.touches[0], e.touches[1]);
            scale = lastScale * (currentDistance / startDistance);
            scale = Math.max(1, Math.min(scale, 3)); // limit zoom
            updateTransform();
        } else if (e.touches.length === 1 && scale > 1) {
            e.preventDefault();
            const deltaX = e.touches[0].clientX - e.touches[0].screenX;
            const deltaY = e.touches[0].clientY - e.touches[0].screenY;
            offsetX = lastOffsetX + deltaX;
            offsetY = lastOffsetY + deltaY;
            updateTransform();
        }
    }, { passive: false });

    container.addEventListener('touchend', (e) => {
        lastScale = scale;
        lastOffsetX = offsetX;
        lastOffsetY = offsetY;
    });

    function getDistance(touch1, touch2) {
        return Math.hypot(
            touch2.pageX - touch1.pageX,
            touch2.pageY - touch1.pageY
        );
    }

    function updateTransform() {
        video.style.transform = `scale(${scale}) translate(${offsetX / scale}px, ${offsetY / scale}px)`;
    }
});
</script>

