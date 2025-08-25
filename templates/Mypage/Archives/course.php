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
                        <div class="video-container" data-video-id="info-video">
                            <div class="video-controls">
                                <button class="control-btn play-pause" title="再生/停止">▶</button>
                                <button class="control-btn zoom-out" title="ズームアウト">-</button>
                                <button class="control-btn zoom-in" title="ズームイン">+</button>
                                <button class="control-btn reset" title="リセット">⟲</button>
                                <button class="control-btn fullscreen" title="フルスクリーン">⛶</button>
                            </div>
                            <div class="video-wrapper">
                                <div class="drag-overlay"></div>
                                <div class="play-overlay">
                                    <div class="play-button-large">
                                        <div class="play-icon">▶</div>
                                    </div>
                                </div>
                                <?php
                                $videoUrl = h($courseMovie->info_movie_url);
                                if (strpos($videoUrl, 'vimeo') !== false): 
                                    $separator = strpos($videoUrl, '?') !== false ? '&' : '?';
                                    // iOS Safari対応のため、muted=1を追加
                                    $videoUrl .= $separator . 'controls=0&fullscreen=0&byline=0&portrait=0&title=0&autoplay=0&muted=1';
                                ?>
                                    <div class="video-content" style="padding:56.25% 0 0 0;position:relative;">
                                        <iframe src="<?= $videoUrl ?>"
                                            style="position:absolute;top:0;left:0;width:100%;height:100%;" frameborder="0"
                                            allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>
                                    </div>
                                <?php else: ?>
                                    <div class="video-content">
                                        <video controlsList="nodownload" style="width:100%; height:auto;">
                                            <source src="<?= $videoUrl ?>" type="video/mp4">
                                        </video>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="zoom-indicator">100%</div>
                        </div>
                    </div>
                <?php endif ?>
                <div class="col-lg-6">
                    <h3><span class="label label-primary" style="margin-bottom:10px">ワーク動画</span></h3>
                    <div class="video-container" data-video-id="work-video">
                        <div class="video-controls">
                            <button class="control-btn play-pause" title="再生/停止">▶</button>
                            <button class="control-btn zoom-out" title="ズームアウト">-</button>
                            <button class="control-btn zoom-in" title="ズームイン">+</button>
                            <button class="control-btn reset" title="リセット">⟲</button>
                            <button class="control-btn fullscreen" title="フルスクリーン">⛶</button>
                        </div>
                        <div class="video-wrapper">
                            <div class="drag-overlay"></div>
                            <div class="play-overlay">
                                <div class="play-button-large">
                                    <div class="play-icon">▶</div>
                                </div>
                            </div>
                            <div class="video-content" style="padding:56.25% 0 0 0;position:relative;">
                                <?php
                                $videoUrl = h($courseMovie->movie_url);
                                if (strpos($videoUrl, 'vimeo') !== false): 
                                    $separator = strpos($videoUrl, '?') !== false ? '&' : '?';
                                    // iOS Safari対応のため、muted=1を追加
                                    $videoUrl .= $separator . 'controls=0&fullscreen=0&byline=0&portrait=0&title=0&autoplay=0&muted=1';
                                ?>
                                    <iframe src="<?= $videoUrl ?>"
                                        style="position:absolute;top:0;left:0;width:100%;height:100%;" frameborder="0"
                                        allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>
                                <?php else: ?>
                                    <video controlsList="nodownload" style="width:100%; height:auto;">
                                        <source src="<?= $videoUrl ?>" type="video/mp4">
                                    </video>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="zoom-indicator">100%</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.video-container {
    position: relative;
    background: #000;
    border-radius: 8px;
    overflow: hidden;
}

.video-wrapper {
    position: relative;
    overflow: hidden;
    cursor: default;
    user-select: none;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
}

.video-wrapper.draggable {
    cursor: grab;
}

.video-wrapper.dragging {
    cursor: grabbing;
}

.video-content {
    transform-origin: center center;
    transition: transform 0.2s ease-out;
    position: relative;
}

.video-content.dragging {
    transition: none;
}

.drag-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 5;
    pointer-events: none;
    background: transparent;
}

.video-wrapper.draggable .drag-overlay {
    pointer-events: auto;
}

.video-wrapper.dragging .drag-overlay {
    cursor: grabbing;
}

.play-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 10;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(0, 0, 0, 0.3);
    opacity: 1;
    transition: opacity 0.3s ease;
    pointer-events: auto;
}

.play-overlay.hidden {
    opacity: 0;
    pointer-events: none;
}

.play-button-large {
    width: 80px;
    height: 80px;
    background: rgba(255, 255, 255, 0.9);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
}

.play-button-large:hover {
    background: rgba(255, 255, 255, 1);
    transform: scale(1.1);
    box-shadow: 0 6px 25px rgba(0, 0, 0, 0.4);
}

.play-button-large:active {
    transform: scale(0.95);
}

.play-icon {
    font-size: 28px;
    color: #333;
    margin-left: 4px;
}

.video-content iframe,
.video-content video {
    position: relative;
    z-index: 1;
}

.video-controls {
    position: absolute;
    top: 10px;
    right: 10px;
    z-index: 1000;
    display: flex;
    gap: 5px;
    background: rgba(0, 0, 0, 0.8);
    border-radius: 5px;
    padding: 5px;
    opacity: 0.7;
    transition: opacity 0.3s;
}

.video-controls:hover {
    opacity: 1;
}

.control-btn {
    background: rgba(255, 255, 255, 0.2);
    border: none;
    color: white;
    width: 35px;
    height: 35px;
    border-radius: 3px;
    cursor: pointer;
    font-size: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s;
    font-weight: bold;
}

.control-btn:hover {
    background: rgba(255, 255, 255, 0.4);
    transform: scale(1.1);
}

.control-btn:active {
    transform: scale(0.95);
}

.control-btn:disabled {
    opacity: 0.3;
    cursor: not-allowed;
    transform: none;
}

.control-btn:disabled:hover {
    background: rgba(255, 255, 255, 0.2);
    transform: none;
}

.control-btn.play-pause {
    background: rgba(76, 175, 80, 0.8);
}

.control-btn.play-pause:hover {
    background: rgba(76, 175, 80, 1);
}

.control-btn.play-pause.playing {
    background: rgba(244, 67, 54, 0.8);
}

.control-btn.play-pause.playing:hover {
    background: rgba(244, 67, 54, 1);
}

.zoom-indicator {
    position: absolute;
    bottom: 10px;
    left: 10px;
    background: rgba(0, 0, 0, 0.8);
    color: white;
    padding: 8px 12px;
    border-radius: 5px;
    font-size: 14px;
    font-weight: bold;
    z-index: 1000;
    display: none;
    min-width: 50px;
    text-align: center;
}

.video-container.zoomed .zoom-indicator {
    display: block;
}

.video-container.fullscreen {
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    z-index: 9999;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 0;
}

.video-container.fullscreen .video-wrapper {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.video-container.fullscreen .video-content {
    width: 100%;
    height: 100%;
    max-width: none;
    max-height: none;
}

.video-container.fullscreen .video-controls {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 10000;
}

.video-container.fullscreen .zoom-indicator {
    position: fixed;
    bottom: 20px;
    left: 20px;
    z-index: 10000;
}

.video-container.fullscreen .play-button-large {
    width: 100px;
    height: 100px;
}

.video-container.fullscreen .play-icon {
    font-size: 36px;
}

.fullscreen-instructions {
    position: fixed;
    bottom: 20px;
    right: 20px;
    background: rgba(0, 0, 0, 0.8);
    color: white;
    padding: 15px;
    border-radius: 8px;
    font-size: 14px;
    max-width: 250px;
    z-index: 10000;
    line-height: 1.4;
}

.fullscreen-instructions div {
    margin-bottom: 5px;
}

.fullscreen-instructions div:last-child {
    margin-bottom: 0;
}

@media (max-width: 768px) {
    .video-controls {
        top: 5px;
        right: 5px;
        padding: 3px;
        gap: 3px;
    }
    
    .control-btn {
        width: 30px;
        height: 30px;
        font-size: 14px;
    }
    
    .play-button-large {
        width: 60px;
        height: 60px;
    }
    
    .play-icon {
        font-size: 20px;
    }
    
    .video-container.fullscreen .video-controls {
        top: 10px;
        right: 10px;
    }
    
    .fullscreen-instructions {
        bottom: 10px;
        right: 10px;
        padding: 10px;
        font-size: 12px;
        max-width: 200px;
    }
}

.video-wrapper.dragging * {
    user-select: none;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
}
</style>

<script>
class EnhancedVideoPlayer {
    constructor(container) {
        this.container = container;
        this.videoWrapper = container.querySelector('.video-wrapper');
        this.videoContent = container.querySelector('.video-content');
        this.dragOverlay = container.querySelector('.drag-overlay');
        this.playOverlay = container.querySelector('.play-overlay');
        this.playButtonLarge = container.querySelector('.play-button-large');
        this.zoomIndicator = container.querySelector('.zoom-indicator');
        this.iframe = container.querySelector('iframe');
        this.video = container.querySelector('video');
        
        this.scale = 1;
        this.translateX = 0;
        this.translateY = 0;
        this.isDragging = false;
        this.dragStart = { x: 0, y: 0 };
        this.lastTranslate = { x: 0, y: 0 };
        this.isFullscreen = false;
        this.isPlaying = false;
        this.vimeoPlayer = null;
        
        this.minScale = 0.5;
        this.maxScale = 3;
        this.scaleStep = 0.2;
        
        this.init();
    }
    
    init() {
        this.bindEvents();
        this.updateTransform();
        this.updateUI();
        this.initializePlayer();
    }
    
    initializePlayer() {
        if (this.iframe && this.iframe.src.includes('vimeo')) {
            if (typeof Vimeo !== 'undefined') {
                this.vimeoPlayer = new Vimeo.Player(this.iframe);
                
                this.vimeoPlayer.on('play', () => {
                    this.isPlaying = true;
                    this.updatePlayButton();
                    this.hidePlayOverlay();
                });
                
                this.vimeoPlayer.on('pause', () => {
                    this.isPlaying = false;
                    this.updatePlayButton();
                });
                
                this.vimeoPlayer.on('ended', () => {
                    this.isPlaying = false;
                    this.updatePlayButton();
                    this.showPlayOverlay();
                });
            }
        } else if (this.video) {
            // Handle regular video element
            this.video.addEventListener('play', () => {
                this.isPlaying = true;
                this.updatePlayButton();
                this.hidePlayOverlay();
            });
            
            this.video.addEventListener('pause', () => {
                this.isPlaying = false;
                this.updatePlayButton();
            });
            
            this.video.addEventListener('ended', () => {
                this.isPlaying = false;
                this.updatePlayButton();
                this.showPlayOverlay();
            });
        }
    }
    
    bindEvents() {
        this.container.querySelector('.play-pause').addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            this.togglePlayPause();
        });
        
        this.container.querySelector('.zoom-in').addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            this.zoomIn();
        });
        
        this.container.querySelector('.zoom-out').addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            this.zoomOut();
        });
        
        this.container.querySelector('.reset').addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            this.reset();
        });
        
        this.container.querySelector('.fullscreen').addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            this.toggleFullscreen();
        });
        
        this.playButtonLarge.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            this.togglePlayPause();
        });
        
        this.dragOverlay.addEventListener('mousedown', (e) => this.handleMouseDown(e));
        document.addEventListener('mousemove', (e) => this.handleMouseMove(e));
        document.addEventListener('mouseup', () => this.handleMouseUp());
        
        this.videoWrapper.addEventListener('wheel', (e) => this.handleWheel(e), { passive: false });
        
        this.dragOverlay.addEventListener('touchstart', (e) => this.handleTouchStart(e), { passive: false });
        document.addEventListener('touchmove', (e) => this.handleTouchMove(e), { passive: false });
        document.addEventListener('touchend', () => this.handleTouchEnd());
        
        // Fullscreen change events
        document.addEventListener('fullscreenchange', () => this.handleFullscreenChange());
        document.addEventListener('webkitfullscreenchange', () => this.handleFullscreenChange());
        document.addEventListener('mozfullscreenchange', () => this.handleFullscreenChange());
        document.addEventListener('MSFullscreenChange', () => this.handleFullscreenChange());
        
        document.addEventListener('keydown', (e) => this.handleKeyDown(e));
        
        this.dragOverlay.addEventListener('contextmenu', (e) => e.preventDefault());
    }
    
    togglePlayPause() {
        if (this.vimeoPlayer) {
            if (this.isPlaying) {
                this.vimeoPlayer.pause();
            } else {
                // iOS Safari対応：再生時に音声を有効化
                this.vimeoPlayer.setVolume(1);
                this.vimeoPlayer.play();
            }
        } else if (this.video) {
            if (this.isPlaying) {
                this.video.pause();
            } else {
                // 通常のvideo要素でも音声を有効化
                this.video.muted = false;
                this.video.play();
            }
        }
    }
    
    updatePlayButton() {
        const playPauseBtn = this.container.querySelector('.play-pause');
        if (this.isPlaying) {
            playPauseBtn.innerHTML = '⏸';
            playPauseBtn.title = '停止';
            playPauseBtn.classList.add('playing');
        } else {
            playPauseBtn.innerHTML = '▶';
            playPauseBtn.title = '再生';
            playPauseBtn.classList.remove('playing');
        }
    }
    
    showPlayOverlay() {
        this.playOverlay.classList.remove('hidden');
    }
    
    hidePlayOverlay() {
        this.playOverlay.classList.add('hidden');
    }
    
    zoomIn() {
        this.scale = Math.min(this.scale + this.scaleStep, this.maxScale);
        this.updateTransform();
        this.updateUI();
    }
    
    zoomOut() {
        this.scale = Math.max(this.scale - this.scaleStep, this.minScale);
        if (this.scale === 1) {
            this.translateX = 0;
            this.translateY = 0;
        }
        this.updateTransform();
        this.updateUI();
    }
    
    reset() {
        this.scale = 1;
        this.translateX = 0;
        this.translateY = 0;
        this.updateTransform();
        this.updateUI();
    }
    
    toggleFullscreen() {
        if (!this.isFullscreen) {
            this.enterFullscreen();
        } else {
            this.exitFullscreen();
        }
    }
    
    enterFullscreen() {
        this.container.classList.add('fullscreen');
        
        const element = this.container;
        if (element.requestFullscreen) {
            element.requestFullscreen();
        } else if (element.webkitRequestFullscreen) {
            element.webkitRequestFullscreen();
        } else if (element.mozRequestFullScreen) {
            element.mozRequestFullScreen();
        } else if (element.msRequestFullscreen) {
            element.msRequestFullscreen();
        }
        
        this.showInstructions();
    }
    
    exitFullscreen() {
        this.container.classList.remove('fullscreen');
        
        if (document.exitFullscreen) {
            document.exitFullscreen();
        } else if (document.webkitExitFullscreen) {
            document.webkitExitFullscreen();
        } else if (document.mozCancelFullScreen) {
            document.mozCancelFullScreen();
        } else if (document.msExitFullscreen) {
            document.msExitFullscreen();
        }
        
        this.hideInstructions();
    }
    
    showInstructions() {
        this.hideInstructions();
        
        const instructions = document.createElement('div');
        instructions.className = 'fullscreen-instructions';
        instructions.innerHTML = `
            <div>▶️ 再生/停止ボタンで動画制御</div>
            <div>🖱️ マウスホイール/ピンチでズーム</div>
            <div>✋ ドラッグで移動 (ズーム時)</div>
            <div>⌨️ ESCで終了</div>
        `;
        document.body.appendChild(instructions);
    }
    
    hideInstructions() {
        const instructions = document.querySelector('.fullscreen-instructions');
        if (instructions) {
            instructions.remove();
        }
    }
    
    handleMouseDown(e) {
        if (this.scale <= 1) return;
        
        e.preventDefault();
        e.stopPropagation();
        
        this.isDragging = true;
        this.dragStart.x = e.clientX;
        this.dragStart.y = e.clientY;
        this.lastTranslate.x = this.translateX;
        this.lastTranslate.y = this.translateY;
        
        this.videoWrapper.classList.add('dragging');
        this.videoContent.classList.add('dragging');
    }
    
    handleMouseMove(e) {
        if (!this.isDragging || this.scale <= 1) return;
        
        e.preventDefault();
        
        const deltaX = e.clientX - this.dragStart.x;
        const deltaY = e.clientY - this.dragStart.y;
        
        this.translateX = this.lastTranslate.x + deltaX;
        this.translateY = this.lastTranslate.y + deltaY;
        
        this.updateTransform();
    }
    
    handleMouseUp() {
        if (!this.isDragging) return;
        
        this.isDragging = false;
        this.videoWrapper.classList.remove('dragging');
        this.videoContent.classList.remove('dragging');
    }
    
    handleWheel(e) {
        e.preventDefault();
        e.stopPropagation();
        
        const delta = e.deltaY > 0 ? -this.scaleStep : this.scaleStep;
        const newScale = Math.min(Math.max(this.scale + delta, this.minScale), this.maxScale);
        
        if (newScale !== this.scale) {
            this.scale = newScale;
            if (this.scale === 1) {
                this.translateX = 0;
                this.translateY = 0;
            }
            this.updateTransform();
            this.updateUI();
        }
    }
    
    handleTouchStart(e) {
        if (this.scale <= 1 || e.touches.length !== 1) return;
        
        e.preventDefault();
        
        const touch = e.touches[0];
        this.isDragging = true;
        this.dragStart.x = touch.clientX;
        this.dragStart.y = touch.clientY;
        this.lastTranslate.x = this.translateX;
        this.lastTranslate.y = this.translateY;
        
        this.videoWrapper.classList.add('dragging');
        this.videoContent.classList.add('dragging');
    }
    
    handleTouchMove(e) {
        if (!this.isDragging || this.scale <= 1 || e.touches.length !== 1) return;
        
        e.preventDefault();
        
        const touch = e.touches[0];
        const deltaX = touch.clientX - this.dragStart.x;
        const deltaY = touch.clientY - this.dragStart.y;
        
        this.translateX = this.lastTranslate.x + deltaX;
        this.translateY = this.lastTranslate.y + deltaY;
        
        this.updateTransform();
    }
    
    handleTouchEnd() {
        if (!this.isDragging) return;
        
        this.isDragging = false;
        this.videoWrapper.classList.remove('dragging');
        this.videoContent.classList.remove('dragging');
    }
    
    handleFullscreenChange() {
        this.isFullscreen = !!(document.fullscreenElement || 
                              document.webkitFullscreenElement || 
                              document.mozFullScreenElement || 
                              document.msFullscreenElement);
        
        if (!this.isFullscreen) {
            this.container.classList.remove('fullscreen');
            this.hideInstructions();
        }
        
        const fullscreenBtn = this.container.querySelector('.fullscreen');
        fullscreenBtn.innerHTML = this.isFullscreen ? '⛶' : '⛶';
        fullscreenBtn.title = this.isFullscreen ? 'フルスクリーン終了' : 'フルスクリーン';
    }
    
    handleKeyDown(e) {
        if (!this.isFullscreen) return;
        
        switch(e.key) {
            case 'Escape':
                this.exitFullscreen();
                break;
            case ' ':
                e.preventDefault();
                this.togglePlayPause();
                break;
            case '+':
            case '=':
                e.preventDefault();
                this.zoomIn();
                break;
            case '-':
                e.preventDefault();
                this.zoomOut();
                break;
            case '0':
                e.preventDefault();
                this.reset();
                break;
        }
    }
    
    updateTransform() {
        const transform = `scale(${this.scale}) translate(${this.translateX / this.scale}px, ${this.translateY / this.scale}px)`;
        this.videoContent.style.transform = transform;
    }
    
    updateUI() {
        this.zoomIndicator.textContent = Math.round(this.scale * 100) + '%';
        
        if (this.scale !== 1) {
            this.container.classList.add('zoomed');
            this.videoWrapper.classList.add('draggable');
        } else {
            this.container.classList.remove('zoomed');
            this.videoWrapper.classList.remove('draggable');
        }
        
        this.container.querySelector('.zoom-in').disabled = this.scale >= this.maxScale;
        this.container.querySelector('.zoom-out').disabled = this.scale <= this.minScale;
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const videoContainers = document.querySelectorAll('.video-container');
    videoContainers.forEach(container => {
        new EnhancedVideoPlayer(container);
    });
});
</script>

<script src="https://player.vimeo.com/api/player.js"></script>