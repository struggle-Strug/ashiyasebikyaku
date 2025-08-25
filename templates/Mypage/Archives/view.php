<?php $this->assign('title', 'アーカイブ'); ?>

<div class="col-lg-12">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title"><?= h($lessonMovie->name) ?></h4>
            <p class="category"><?= $this->Link->linkify(h($lessonMovie->note)) ?></p>
        </div>
        <div class="card-content">
            <div class="video-container" data-video-id="lesson-video">
                <div class="video-controls">
                    <button class="control-btn play-pause" title="再生/停止">▶</button>
                    <button class="control-btn rewind" title="10秒戻る">⏪</button>
                    <button class="control-btn fast-forward" title="10秒進む">⏩</button>
                    <!-- Added playback speed control dropdown -->
                    <div class="speed-control">
                        <button class="control-btn speed-btn" title="再生速度">1x</button>
                        <div class="speed-dropdown">
                            <div class="speed-option" data-speed="0.5">0.5x</div>
                            <div class="speed-option" data-speed="0.75">0.75x</div>
                            <div class="speed-option active" data-speed="1">1x</div>
                            <div class="speed-option" data-speed="1.25">1.25x</div>
                            <div class="speed-option" data-speed="1.5">1.5x</div>
                            <div class="speed-option" data-speed="2">2x</div>
                        </div>
                    </div>
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
                    $videoUrl = h($lessonMovie->movie_url);
                    if (strpos($videoUrl, 'vimeo') !== false): 
                        // Add parameters to hide Vimeo controls and autoplay
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
                <div class="progress-container">
                    <div class="progress-bar">
                        <div class="progress-track">
                            <div class="progress-fill"></div>
                            <div class="progress-handle"></div>
                        </div>
                    </div>
                    <div class="time-display">
                        <span class="current-time">0:00</span>
                        <span class="duration">0:00</span>
                    </div>
                </div>
                <div class="zoom-indicator">100%</div>
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

/* Drag overlay - only visible when zoomed and dragging is enabled */
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

/* Play overlay with large play button */
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
    margin-left: 4px; /* Adjust for visual centering */
}

/* Ensure video/iframe can still receive clicks when not dragging */
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

/* Play/Pause button specific styling */
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

/* Rewind and Fast Forward button specific styling */
.control-btn.rewind,
.control-btn.fast-forward {
    background: rgba(33, 150, 243, 0.8);
}

.control-btn.rewind:hover,
.control-btn.fast-forward:hover {
    background: rgba(33, 150, 243, 1);
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

/* Progress bar styles */
.progress-container {
    position:absolute; 
    bottom:0; 
    left:0; 
    right:0; 
    background:rgba(0,0,0,0.9); 
    padding:12px 20px; 
    z-index:1000; 
    backdrop-filter: blur(10px); 
    border-top:1px solid rgba(255,255,255,0.1);
}

.time-display {
    display:flex; 
    justify-content:space-between; 
    color:#fff; 
    font-size:13px; 
    font-weight:600; 
    text-shadow:0 1px 2px rgba(0,0,0,.5); 
    margin-top:8px;
    width:100%;
}

.progress-bar {
    position:relative; 
    height:6px; 
    width:100%; 
    background:rgba(255,255,255,0.6); 
    border:1px solid rgba(255,255,255,0.8); 
    border-radius:4px; 
    cursor:pointer; 
    margin-bottom:10px; 
    transition:height .2s ease; 
    box-shadow:0 1px 3px rgba(0,0,0,.5); 
}

.progress-bar:hover { 
    height:8px; 
    background:rgba(255,255,255,0.8); 
    box-shadow:0 1px 3px rgba(0,0,0,.5); 
}


.progress-track {
    position: relative;
    width: 100%;
    height: 6px;
    background: rgba(255, 255, 255, 0.3);
    border-radius: 3px;
    overflow: visible;
}

.progress-fill {
    position: absolute;
    top: 0;
    left: 0;
    height: 100%;
    background: #dc2626;
    border-radius: 3px;
    width: 0%;
    transition: width 0.1s ease;
}

.progress-handle {
    position: absolute;
    top: 50%;
    transform: translate(-50%, -50%);
    width: 16px;
    height: 16px;
    background: #dc2626;
    border: 2px solid white;
    border-radius: 50%;
    cursor: pointer;
    left: 0%;
    transition: all 0.1s ease;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
}

.progress-handle:hover,
.progress-handle.dragging {
    transform: translate(-50%, -50%) scale(1.2);
    box-shadow: 0 3px 6px rgba(0, 0, 0, 0.4);
}

/* Mobile responsive */
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
    
    .progress-container {
        padding: 8px 10px;
        gap: 6px;
    }
    
    .time-display {
        font-size: 12px;
    }
    
    .progress-handle {
        width: 20px;
        height: 20px;
    }
}

/* Added speed control styling */
.speed-control {
    position: relative;
    display: inline-block;
}

.speed-btn {
    background: rgba(156, 39, 176, 0.8);
    min-width: 40px;
}

.speed-btn:hover {
    background: rgba(156, 39, 176, 1);
}

.speed-dropdown {
    position: absolute;
    top: 100%;
    left: 0;
    background: rgba(0, 0, 0, 0.9);
    border-radius: 3px;
    padding: 5px 0;
    min-width: 60px;
    display: none;
    z-index: 1001;
    margin-top: 2px;
}

.speed-control:hover .speed-dropdown {
    display: block;
}

.speed-option {
    padding: 8px 12px;
    color: white;
    cursor: pointer;
    font-size: 14px;
    text-align: center;
    transition: background 0.2s;
}

.speed-option:hover {
    background: rgba(255, 255, 255, 0.2);
}

.speed-option.active {
    background: rgba(156, 39, 176, 0.8);
    color: white;
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
        
        this.progressContainer = container.querySelector('.progress-container');
        this.progressBar = container.querySelector('.progress-bar');
        this.progressTrack = container.querySelector('.progress-track');
        this.progressFill = container.querySelector('.progress-fill');
        this.progressHandle = container.querySelector('.progress-handle');
        this.currentTimeDisplay = container.querySelector('.current-time');
        this.durationDisplay = container.querySelector('.duration');
        
        this.scale = 1;
        this.translateX = 0;
        this.translateY = 0;
        this.isDragging = false;
        this.dragStart = { x: 0, y: 0 };
        this.lastTranslate = { x: 0, y: 0 };
        this.isFullscreen = false;
        this.isPlaying = false;
        this.vimeoPlayer = null;
        
        this.currentTime = 0;
        this.duration = 0;
        this.isProgressDragging = false;
        this.progressUpdateInterval = null;
        
        this.minScale = 0.5;
        this.maxScale = 3;
        this.scaleStep = 0.2;
        
        this.isVimeo = this.iframe && this.iframe.src.includes('vimeo');
        
        this.playbackSpeed = 1;
        
        this.init();
    }
    
    init() {
        this.bindEvents();
        this.updateTransform();
        this.updateUI();
        this.initializePlayer();
        this.startProgressTracking();
    }
    
    initializePlayer() {
        if (this.isVimeo && typeof Vimeo !== 'undefined') {
            this.vimeoPlayer = new Vimeo.Player(this.iframe);
            
            this.vimeoPlayer.on('play', () => {
                this.isPlaying = true;
                this.updatePlayButton();
                this.hidePlayOverlay();
                this.startProgressTracking();
            });
            
            this.vimeoPlayer.on('pause', () => {
                this.isPlaying = false;
                this.updatePlayButton();
                this.stopProgressTracking();
            });
            
            this.vimeoPlayer.on('ended', () => {
                this.isPlaying = false;
                this.updatePlayButton();
                this.showPlayOverlay();
                this.stopProgressTracking();
            });
            
            this.vimeoPlayer.getDuration().then((duration) => {
                this.duration = duration;
                this.updateTimeDisplay();
            });
            
            this.vimeoPlayer.on('timeupdate', (data) => {
                if (!this.isProgressDragging) {
                    this.currentTime = data.seconds;
                    this.updateProgressBar();
                    this.updateTimeDisplay();
                }
            });
        } else if (this.video) {
            this.video.addEventListener('play', () => {
                this.isPlaying = true;
                this.updatePlayButton();
                this.hidePlayOverlay();
                this.startProgressTracking();
            });
            
            this.video.addEventListener('pause', () => {
                this.isPlaying = false;
                this.updatePlayButton();
                this.stopProgressTracking();
            });
            
            this.video.addEventListener('ended', () => {
                this.isPlaying = false;
                this.updatePlayButton();
                this.showPlayOverlay();
                this.stopProgressTracking();
            });
            
            this.video.addEventListener('loadedmetadata', () => {
                this.duration = this.video.duration;
                this.updateTimeDisplay();
            });
            
            this.video.addEventListener('timeupdate', () => {
                if (!this.isProgressDragging) {
                    this.currentTime = this.video.currentTime;
                    this.updateProgressBar();
                    this.updateTimeDisplay();
                }
            });
        }
    }
    
    bindEvents() {
        this.container.querySelector('.play-pause').addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            this.togglePlayPause();
        });
        
        this.container.querySelector('.rewind').addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            this.rewind();
        });
        
        this.container.querySelector('.fast-forward').addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            this.fastForward();
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
        
        this.progressBar.addEventListener('click', (e) => this.handleProgressClick(e));
        this.progressHandle.addEventListener('mousedown', (e) => this.handleProgressDragStart(e));
        document.addEventListener('mousemove', (e) => this.handleProgressDrag(e));
        document.addEventListener('mouseup', () => this.handleProgressDragEnd());
        
        this.progressHandle.addEventListener('touchstart', (e) => this.handleProgressTouchStart(e));
        document.addEventListener('touchmove', (e) => this.handleProgressTouchMove(e));
        document.addEventListener('touchend', () => this.handleProgressTouchEnd());
        
        this.dragOverlay.addEventListener('mousedown', (e) => this.handleMouseDown(e));
        document.addEventListener('mousemove', (e) => this.handleMouseMove(e));
        document.addEventListener('mouseup', () => this.handleMouseUp());
        
        this.videoWrapper.addEventListener('wheel', (e) => this.handleWheel(e), { passive: false });
        
        this.dragOverlay.addEventListener('touchstart', (e) => this.handleTouchStart(e), { passive: false });
        document.addEventListener('touchmove', (e) => this.handleTouchMove(e), { passive: false });
        document.addEventListener('touchend', () => this.handleTouchEnd());
        
        document.addEventListener('fullscreenchange', () => this.handleFullscreenChange());
        document.addEventListener('webkitfullscreenchange', () => this.handleFullscreenChange());
        document.addEventListener('mozfullscreenchange', () => this.handleFullscreenChange());
        document.addEventListener('MSFullscreenChange', () => this.handleFullscreenChange());
        
        document.addEventListener('keydown', (e) => this.handleKeyDown(e));
        
        this.dragOverlay.addEventListener('contextmenu', (e) => e.preventDefault());
        
        const speedOptions = this.container.querySelectorAll('.speed-option');
        speedOptions.forEach(option => {
            option.addEventListener('click', (e) => {
                e.stopPropagation();
                const speed = parseFloat(option.dataset.speed);
                this.setPlaybackSpeed(speed);
            });
        });
    }
    
    rewind() {
        const newTime = Math.max(this.currentTime - 10, 0);
        this.seekTo(newTime);
    }
    
    fastForward() {
        const newTime = Math.min(this.currentTime + 10, this.duration);
        this.seekTo(newTime);
    }
    
    seekTo(time) {
        if (this.vimeoPlayer) {
            this.vimeoPlayer.setCurrentTime(time);
        } else if (this.video) {
            this.video.currentTime = time;
        }
        this.currentTime = time;
        this.updateProgressBar();
        this.updateTimeDisplay();
    }
    
    handleProgressClick(e) {
        if (this.duration === 0) return;
        
        const rect = this.progressTrack.getBoundingClientRect();
        const clickX = e.clientX - rect.left;
        const percentage = Math.max(0, Math.min(1, clickX / rect.width));
        const newTime = percentage * this.duration;
        
        this.seekTo(newTime);
    }
    
    handleProgressDragStart(e) {
        e.preventDefault();
        e.stopPropagation();
        this.isProgressDragging = true;
        this.progressHandle.classList.add('dragging');
    }
    
    handleProgressDrag(e) {
        if (!this.isProgressDragging || this.duration === 0) return;
        
        const rect = this.progressTrack.getBoundingClientRect();
        const dragX = e.clientX - rect.left;
        const percentage = Math.max(0, Math.min(1, dragX / rect.width));
        const newTime = percentage * this.duration;
        
        this.currentTime = newTime;
        this.updateProgressBar();
        this.updateTimeDisplay();
    }
    
    handleProgressDragEnd() {
        if (!this.isProgressDragging) return;
        
        this.isProgressDragging = false;
        this.progressHandle.classList.remove('dragging');
        this.seekTo(this.currentTime);
    }
    
    handleProgressTouchStart(e) {
        e.preventDefault();
        this.isProgressDragging = true;
        this.progressHandle.classList.add('dragging');
    }
    
    handleProgressTouchMove(e) {
        if (!this.isProgressDragging || this.duration === 0) return;
        
        e.preventDefault();
        const touch = e.touches[0];
        const rect = this.progressTrack.getBoundingClientRect();
        const dragX = touch.clientX - rect.left;
        const percentage = Math.max(0, Math.min(1, dragX / rect.width));
        const newTime = percentage * this.duration;
        
        this.currentTime = newTime;
        this.updateProgressBar();
        this.updateTimeDisplay();
    }
    
    handleProgressTouchEnd() {
        if (!this.isProgressDragging) return;
        
        this.isProgressDragging = false;
        this.progressHandle.classList.remove('dragging');
        this.seekTo(this.currentTime);
    }
    
    updateProgressBar() {
        if (this.duration === 0) return;
        
        const percentage = (this.currentTime / this.duration) * 100;
        this.progressFill.style.width = `${percentage}%`;
        this.progressHandle.style.left = `${percentage}%`;
    }
    
    updateTimeDisplay() {
        this.currentTimeDisplay.textContent = this.formatTime(this.currentTime);
        this.durationDisplay.textContent = this.formatTime(this.duration);
    }
    
    formatTime(seconds) {
        if (isNaN(seconds) || seconds < 0) return '0:00';
        
        const minutes = Math.floor(seconds / 60);
        const remainingSeconds = Math.floor(seconds % 60);
        return `${minutes}:${remainingSeconds.toString().padStart(2, '0')}`;
    }
    
    startProgressTracking() {
        this.stopProgressTracking();
        this.progressUpdateInterval = setInterval(() => {
            if (this.isPlaying && !this.isProgressDragging) {
                if (this.vimeoPlayer) {
                    this.vimeoPlayer.getCurrentTime().then((time) => {
                        this.currentTime = time;
                        this.updateProgressBar();
                        this.updateTimeDisplay();
                    });
                } else if (this.video && !this.video.paused) {
                    this.currentTime = this.video.currentTime;
                    this.updateProgressBar();
                    this.updateTimeDisplay();
                }
            }
        }, 100);
    }
    
    stopProgressTracking() {
        if (this.progressUpdateInterval) {
            clearInterval(this.progressUpdateInterval);
            this.progressUpdateInterval = null;
        }
    }
    
    togglePlayPause() {
        if (this.vimeoPlayer) {
            if (this.isPlaying) {
                this.vimeoPlayer.pause();
            } else {
                this.vimeoPlayer.setVolume(1);
                this.vimeoPlayer.play();
            }
        } else if (this.video) {
            if (this.isPlaying) {
                this.video.pause();
            } else {
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
            case 'ArrowLeft':
                e.preventDefault();
                this.rewind();
                break;
            case 'ArrowRight':
                e.preventDefault();
                this.fastForward();
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
    
    setPlaybackSpeed(speed) {
        this.playbackSpeed = speed;
        
        if (this.isVimeo && this.vimeoPlayer) {
            this.vimeoPlayer.setPlaybackRate(speed);
        } else if (this.video) {
            this.video.playbackRate = speed;
        }
        
        // Update UI
        const speedBtn = this.container.querySelector('.speed-btn');
        speedBtn.textContent = speed + 'x';
        
        // Update active option
        const speedOptions = this.container.querySelectorAll('.speed-option');
        speedOptions.forEach(option => {
            option.classList.remove('active');
            if (parseFloat(option.dataset.speed) === speed) {
                option.classList.add('active');
            }
        });
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
