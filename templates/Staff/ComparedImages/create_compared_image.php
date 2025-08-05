<?php $this->assign('title', '比較画像作成ページ'); ?>
<?= $this->Form->create(null, ['type' => 'get', 'url' => ['action' => 'uploadComparedImage']]) ?>
<?= $this->Form->end() ?>
<style>
#touch-type {
    display: none;
}

@media only screen and (max-width: 767px) {
    #touch-type {
        display: block;
    }
}

/* ツールバー用のスタイル */
.toolbar {
    display: flex;
    gap: 10px;
    margin-left: 10px;
    align-items: center;
}

.tool-button {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 1px solid #ccc;
    border-radius: 4px;
    cursor: pointer;
    background-color: white;
}

.tool-button.active {
    background-color: #007bff;
    color: white;
    border-color: #0056b3;
}

/* コメントボックスのスタイル */
.comment-box {
    background-color: white;
    border: 1px solid #d1d5db; /* border-gray-300 */
    border-radius: 8px; /* rounded-lg */
    padding: 16px; /* p-4 */
    min-width: 100px; /* min-w-[100px] */
    min-height: 50px; /* min-h-[50px] */
    max-width: 100%; /* max-w-full */
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06); /* shadow-md */
    color: #374151; /* text-gray-800 */
    font-size: 16px; /* text-base */
    resize: none; /* resize-none */
    overflow: hidden; /* overflow-hidden */
    white-space: pre-wrap; /* whitespace-pre-wrap */
    word-break: break-words; /* break-words */
    display: flex; /* flex */
    flex-direction: column; /* flex-col */
    /* Removed justify-content: center; to allow JS to control vertical alignment */
    position: absolute; /* Keep this for positioning */
    z-index: 1000; /* Keep this for layering */
}

.comment-box:focus {
    outline: none;
    box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.5), 0 0 0 1px #3b82f6; /* focus:ring-2 focus:ring-blue-500 and focus:border-blue-500 */
    border-color: #3b82f6; /* focus:border-blue-500 */
}

.comment-box:empty::before {
    content: 'Start typing here...';
    color: #9ca3af; /* text-gray-400 */
    display: block;
    text-align: center;
}

/* リサイズハンドルのスタイル - 復活 */
.comment-resize-handle {
    position: absolute;
    width: 10px;
    height: 10px;
    background-color: transparent;
    /* 無色 */
    border: none;
    /* 境界線も削除 */
    border-radius: 50%;
    cursor: nwse-resize;
    z-index: 1001;
    bottom: -5px;
    right: -5px;
}

/* カラーピッカーコンテナのスタイル */
.color-picker-container {
    display: none;
    margin-left: 10px;
    align-items: center;
    gap: 10px;
}

/* フォントサイズ変更ツールのスタイル */
.font-size-tools {
    display: flex;
    margin-left: 10px;
    align-items: center;
    gap: 5px;
}

.font-size-button {
    width: 24px;
    height: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 1px solid #ccc;
    border-radius: 4px;
    cursor: pointer;
    background-color: white;
}

/* 統合されたSVGエリア用のスタイル */
.unified-drawing-container {
    position: relative;
    border: solid 1px rgba(0,0,0,0.1);
    background-color: white;
    width: 100%;
    height: 500px; /* デフォルト高さ、JSで動的に変更 */
    margin-bottom: 60px; /* 日付表示用のスペースを増やす */
}

.image-display-area {
    display: flex;
    width: 100%;
    height: 100%;
    position: relative;
}

.image-section {
    flex: 1;
    position: relative;
    height: 100%;
    overflow: hidden;
    border-right: 1px solid #ddd;
}

.image-section:last-child {
    border-right: none;
}

.image-svg {
    width: 100%;
    height: 100%;
    display: block;
}

/* 統合描画SVG - 全体をカバー */
.unified-svg {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 10;
    pointer-events: none;
}

.unified-svg.drawing-mode {
    pointer-events: all;
}

/* 日付テキスト用のスタイル - 統合キャンバスエリアの下に配置 */
.date-text {
    position: absolute;
    bottom: -40px;
    font-family: sans-serif;
    font-weight: bold;
    font-size: 16px;
    color: black;
    white-space: nowrap;
    background: white;
    padding: 5px 10px;
}

#left-date-text {
    left: 25%; /* 左側の中央 */
}

/* 右の日付は右側に配置 */
#right-date-text {
    left: 75%; /* 右側の中央 */
}

@media only screen and (max-width: 767px) {
    .unified-drawing-container {
        height: 400px; /* モバイルデフォルト高さ、JSで動的に変更 */
        margin-bottom: 50px;
    }
    
    .image-section {
        min-width: 50%;
    }
    
    .date-text {
        bottom: -40px;
        font-size: 14px;
        padding: 6px 12px;
    }
    #left-date-text {
        left: 5%; /* 左側の中央 */
    }

    /* 右の日付は右側に配置 */
    #right-date-text {
        left: 55%; /* 右側の中央 */
    }
}
</style>

<div class="card">
<div class="card-body">
    <div style="display: flex; flex-wrap: wrap; align-items: center;">
        <div class="p-sm-2 p-md-4 py-2">
            <button class="btn btn-outline-primary" id="board-type">書き込みモードOFF</button>
            <span class="mx-2">左の画像:</span>
            <button class="btn btn-outline-secondary" id="rotate-left-image-left" title="左回転">
                <i class="fas fa-undo-alt"></i>
            </button>
            <button class="btn btn-outline-secondary" id="rotate-left-image-right" title="右回転">
                <i class="fas fa-redo-alt"></i>
            </button>
            <span class="mx-2">右の画像:</span>
            <button class="btn btn-outline-secondary" id="rotate-right-image-left" title="左回転">
                <i class="fas fa-undo-alt"></i>
            </button>
            <button class="btn btn-outline-secondary" id="rotate-right-image-right" title="右回転">
                <i class="fas fa-redo-alt"></i>
            </button>
        </div>
        
        <!-- ツールバー -->
        <div class="toolbar p-sm-2 p-md-4 py-2" id="drawing-toolbar" style="display: none;">
            <button class="btn btn-outline-secondary tool-button active" id="free-draw" title="自由入力">
                <i class="fas fa-pencil-alt"></i>
            </button>
            <button class="btn btn-outline-secondary tool-button" id="line-draw" title="直線">
                <i class="fas fa-minus"></i>
            </button>
            <button class="btn btn-outline-secondary tool-button" id="comment-add" title="コメント">
                <i class="fas fa-comment"></i>
            </button>
            <button class="btn btn-outline-secondary tool-button" id="eraser" title="消しゴム">
                <i class="fas fa-eraser"></i>
            </button>
        </div>
        
        <!-- カラーピッカー -->
        <div class="color-picker-container p-sm-2 p-md-4 py-2" id="comment-color-picker">
            <div class="input-group" style="max-width: 200px;">
                <div class="input-group-prepend">
                    <span class="input-group-text">文字色</span>
                </div>
                <input type="color" id="text-color" class="form-control" value="#000000">
            </div>
            <div class="input-group" style="max-width: 200px;">
                <div class="input-group-prepend">
                    <span class="input-group-text">背景色</span>
                </div>
                <input type="color" id="background-color" class="form-control" value="#ffffff">
            </div>
                <!-- フォントサイズ変更ツール -->
            <div class="font-size-tools input-group" id="font-size-tools" style="max-width: 200px;">
                <div class="input-group-prepend">
                    <span class="input-group-text">文字サイズ</span>
                </div>
                    <button class="btn btn-outline-secondary" id="decrease-font-size" title="文字サイズを小さく">
                    <i class="fas fa-minus"></i>
                </button>
                <span class="form-control text-center" id="font-size-value">14</span>
                    <button class="btn btn-outline-secondary" id="increase-font-size" title="文字サイズを大きく">
                    <i class="fas fa-plus"></i>
                </button>
            </div>
        </div>
        
        <!-- 線の色ピッカー -->
        <div class="color-picker-container p-sm-2 p-md-4 py-2" id="line-color-picker">
            <div class="input-group" style="max-width: 200px;">
                <div class="input-group-prepend">
                    <span class="input-group-text">線の色</span>
                </div>
                <input type="color" id="line-color" class="form-control" value="#ff0000">
            </div>
        </div>
        
        <div class="p-sm-2 p-md-4 py-2 mx-2">
            <button class="btn btn-outline-primary" id="touch-type">ムーブモード</button>
        </div>
    </div>
    
    <!-- 統合された描画エリア -->
    <div class="unified-drawing-container" id="unified-container">
        <!-- 画像表示エリア -->
        <div class="image-display-area">
            <!-- 左の画像セクション -->
            <div class="image-section" id="left-section">
                <svg id="leftImageCanvas" class="image-svg">
                    <defs>
                        <clipPath id="leftImageClip">
                            <rect x="0" y="0" width="100%" height="100%" />
                        </clipPath>
                    </defs>
                </svg>
            </div>
            
            <!-- 右の画像セクション -->
            <div class="image-section" id="right-section">
                <svg id="rightImageCanvas" class="image-svg">
                    <defs>
                        <clipPath id="rightImageClip">
                            <rect x="0" y="0" width="100%" height="100%" />
                        </clipPath>
                    </defs>
                </svg>
            </div>
        </div>
        
        <!-- 統合されたSVG描画レイヤー - 全体をカバー -->
        <svg id="unifiedDrawingCanvas" class="unified-svg">
            <defs>
                <clipPath id="drawingClip">
                    <rect x="0" y="0" width="100%" height="100%" />
                </clipPath>
            </defs>
        </svg>
    </div>
    
    <div class="p-sm-2 p-md-4 py-2">
        <button class="btn btn-outline-primary" id="preview-btn">画像プレビューON</button>
    </div>
    <div class="p-sm-2 p-md-4 py-2">
        <img src="/placeholder.svg" id="preview" style="display: none; max-width: 100%;" />
    </div>
    <div class="form-group row">
        <label for="" class="col-form-label">コメント</label>
        <div class="col-sm-10">
                <input type="hidden" name="staff_id" id="staff_id" value="<?= h($staff) ?>">
                <textarea class="form-control" rows="5" name="staff_comment" id="staff_comment"></textarea>
            <span class="text-danger">
                    <?= $this->Form->error('staff_comment') ?>
            </span>
        </div>
    </div>
</div>
<div class="card-footer">
        <a class="btn btn-outline-secondary" href="<?= $this->Url->build(['action' => 'add', '?' => $param]); ?>"><i
                class="fas fa-arrow-circle-left"></i> 戻る</a>
    <span class="float-right">
        <button type="submit" class="btn btn-primary" id="submit">保存</button>
    </span>
</div>
</div>

<!-- 隠し画像要素 -->
<img src="<?= $targetImage["image_url"] ?>" style="display: none" id="target_image_url" crossorigin="anonymous" />
<img src="<?= $subjectImage["image_url"] ?>" style="display: none" id="subject_image_url" crossorigin="anonymous" />
<div id="error"></div>

<!-- 日付データ -->
<?php if ($targetImage["created"] === null): ?>
<input type="hidden" value="" id="target-image-date">
<?php else: ?>
    <input type="hidden" value="<?= $targetImage["created"]->format('Y年m月d日') ?>" id="target-image-date">
<?php endif ?>
<?php if ($subjectImage["created"] === null): ?>
<input type="hidden" value="" id="subject-image-date">
<?php else: ?>
    <input type="hidden" value="<?= $subjectImage["created"]->format('Y年m月d日') ?>" id="subject-image-date">
<?php endif ?>

<script defer>
$(function () {
// イベントタイプを定義
var start_event = 'ontouchstart' in window ? "touchstart" : "mousedown";
var move_event = 'ontouchstart' in window ? "touchmove" : "mousemove";
var end_event = 'ontouchstart' in window ? "touchend" : "mouseup";

// SVG要素を取得
var unifiedSvgElm = $("#unifiedDrawingCanvas");
var unifiedSvgElm_js = document.getElementById("unifiedDrawingCanvas");
var leftImageSvg_js = document.getElementById("leftImageCanvas");
var rightImageSvg_js = document.getElementById("rightImageCanvas");

var type = "imageMoveAndZoom"; // imageMoveAndZoom or pencil
var touchType = "move"; // move or zoom

// 描画状態管理のためのグローバル変数
var currentPathElement = null;
var tempLine = null;
var startX, startY;

// ツール関連の変数
var currentTool = "free-draw"; // 現在選択されているツール
var isDrawing = false; // 描画中かどうか
var textColor = "#000000"; // テキストの色
var backgroundColor = "#ffffff"; // 背景色
var lineColor = "#ff0000"; // 線の色
var lineWidth = 3; // 線の太さ
var selectedComment = null; // 選択中のコメントボックス
var fontSize = 14; // フォントサイズ

// 直線移動のための変数
var isMovingLine = false; // 直線移動中かどうか
var selectedLine = null; // 選択中の直線
var lineStartX, lineStartY; // 直線移動の開始点
var lineOffsetX, lineOffsetY; // 直線移動のオフセット

// 消しゴム機能用のグローバル変数
var eraseRect = null; // 消しゴムの範囲を示す矩形
var eraseStartX, eraseStartY; // 消しゴムの開始点

// 画像移動・ズームのためのグローバル変数
var isDraggingImage = false; // 画像ドラッグ中かどうか
var imageDragTarget = null; // ドラッグ中の画像要素
var imageDragStartX, imageDragStartY; // 画像ドラッグ開始時のポインタ位置
var imageDragMatrix; // ドラッグ開始時の画像変換行列

// ピンチズームのためのグローバル変数 (マウスホイールとタッチズームで共有)
var baseDistance = 0; // 前回のtouchmoveイベントでの2本の指間の距離
var pointX = 0; // ズームの中心X座標 (SVG座標)
var pointY = 0; // ズームの中心Y座標 (SVG座標)
var isSingleFingerZooming = false; // 1本指ズーム中かどうか
var singleFingerZoomStartY = 0; // 1本指ズーム開始時のY座標

// 画像回転用の変数
let leftRotation = 0;
let rightRotation = 0;

// 画像高さ計算用の変数
let calculatedLeftImageHeight = 0;
let calculatedRightImageHeight = 0;
let finalSvgHeight = 500;

        // 初期化時にカラーピッカーの値を設定
$(document).ready(function() {
    document.getElementById('line-color').value = lineColor;
    
    if (localStorage.getItem('lineColor')) {
        lineColor = localStorage.getItem('lineColor');
        document.getElementById('line-color').value = lineColor;
    }

    // 画像を読み込んで表示
    loadAndDisplayImages();
});

// ===== 1. 画像表示機能 =====
function loadImage(url) {
    return new Promise((resolve, reject) => {
        const image = new Image();
        image.crossOrigin = "anonymous";
        image.onload = () => resolve(image);
        image.onerror = () => reject(new Error(`Failed to load image: ${url}`));
        image.src = url;
    });
}

async function loadAndDisplayImages() {
    const targetImageUrl = document.getElementById("target_image_url").src;
    const subjectImageUrl = document.getElementById("subject_image_url").src;

    try {
        const [targetImage, subjectImage] = await Promise.all([
            loadImage(targetImageUrl),
            loadImage(subjectImageUrl)
        ]);

        const container = document.getElementById('unified-container');
        const containerWidth = container.clientWidth;
        const leftWidth = containerWidth / 2;
        const rightWidth = containerWidth / 2;

        // 各画像の高さを計算（幅をフルフィットさせた場合）
        calculatedLeftImageHeight = (leftWidth / targetImage.naturalWidth) * targetImage.naturalHeight;
        calculatedRightImageHeight = (rightWidth / subjectImage.naturalWidth) * subjectImage.naturalHeight;

        // 2つの画像のうち高い方に合わせてSVGエリアの高さを設定
        finalSvgHeight = Math.max(calculatedLeftImageHeight, calculatedRightImageHeight, 400);

        // コンテナの高さを更新
        container.style.height = finalSvgHeight + 'px';

        // 左の画像を表示
        displayImageInSvg(targetImage, leftImageSvg_js, "left-image", calculatedLeftImageHeight);
        // 右の画像を表示
        displayImageInSvg(subjectImage, rightImageSvg_js, "right-image", calculatedRightImageHeight);

        // 日付テキストを設定と表示 - 統合キャンバスエリアの下に配置
        const leftDateText = document.getElementById("target-image-date").value;
            const rightDateText = document.getElementById("subject-image-date").value;
            const unifiedContainer = document.getElementById('unified-container');

            // 左の日付を設定
            let leftDateDiv = document.getElementById('left-date-text');
            if (!leftDateDiv) {
                leftDateDiv = document.createElement('div');
                leftDateDiv.className = 'date-text';
                leftDateDiv.id = 'left-date-text';
                unifiedContainer.appendChild(leftDateDiv);
            }
            leftDateDiv.textContent = leftDateText;

            // 右の日付を設定
            let rightDateDiv = document.getElementById('right-date-text');
            if (!rightDateDiv) {
                rightDateDiv = document.createElement('div');
                rightDateDiv.className = 'date-text';
                rightDateDiv.id = 'right-date-text';
                unifiedContainer.appendChild(rightDateDiv);
            }
            rightDateDiv.textContent = rightDateText;

            // 日付テキストを表示
            leftDateDiv.style.display = 'block';
            rightDateDiv.style.display = 'block';
        
        

        // 統合SVGを初期化
        initializeUnifiedSvg();

    } catch (error) {
        console.error('Error loading or displaying images:', error);
    }
}

function displayImageInSvg(imgElement, svgElm_js, imageId, calculatedHeight) {
    const svgRect = svgElm_js.getBoundingClientRect();
    const svgWidth = svgRect.width;
    const svgHeight = finalSvgHeight;

    // ViewBoxを設定
    svgElm_js.setAttribute("viewBox", `0 0 ${svgWidth} ${svgHeight}`);

    // 画像を幅にフルフィットさせる
    const imageWidth = svgWidth;
    const imageHeight = calculatedHeight;
    const imageX = 0;
    const imageY = (svgHeight - imageHeight) / 2; // 縦方向は中央配置

    // Canvas経由でBase64データを作成
    const canvas = document.createElement('canvas');
    const ctx = canvas.getContext('2d');
    canvas.width = imgElement.naturalWidth;
    canvas.height = imgElement.naturalHeight;
    ctx.drawImage(imgElement, 0, 0);
    const base64ImageData = canvas.toDataURL('image/png');

    // 既存の画像があれば削除
    const existingImage = document.getElementById(imageId);
    if (existingImage) {
        existingImage.remove();
    }

    // SVG画像要素を作成
    createImage(imageX, imageY, imageWidth, imageHeight, base64ImageData, imageId, svgElm_js);
}

function createImage(x, y, w, h, href, id, targetSvg) {
    var image_elm = document.createElementNS("http://www.w3.org/2000/svg", "image");
    image_elm.setAttribute("href", href);
    image_elm.setAttribute("id", id);
    image_elm.setAttribute("width", w);
    image_elm.setAttribute("height", h);
    image_elm.setAttribute("x", x);
    image_elm.setAttribute("y", y);
    image_elm.setAttribute("crossorigin", "anonymous");
    
    // Transform行列を初期化
    var transform = image_elm.transform.baseVal.createSVGTransformFromMatrix(SVGMatrixCreate());
    image_elm.transform.baseVal.appendItem(transform);
    
    targetSvg.appendChild(image_elm);
}

function SVGMatrixCreate(a, b, c, d, e, f) {
    var svg = document.createElementNS("http://www.w3.org/2000/svg", "svg");
    var mtx = svg.createSVGMatrix();
    if (a !== undefined) mtx.a = a;
    if (b !== undefined) mtx.b = b;
    if (c !== undefined) mtx.c = c;
    if (d !== undefined) mtx.d = d;
    if (e !== undefined) mtx.e = e;
    if (f !== undefined) mtx.f = f;
    return mtx;
}

// ===== 2. 画像移動・ズーム機能 =====
function getImageSvgCoords(event, targetSvg) {
    const svgRect = targetSvg.getBoundingClientRect();
    let clientX, clientY;
    
    if (event.touches && event.touches.length > 0) {
        // タッチイベントの場合
        clientX = event.touches[0].clientX;
        clientY = event.touches[0].clientY;
    } else {
        // マウスイベントの場合
        clientX = event.clientX;
        clientY = event.clientY;
    }
    
    const viewBox = targetSvg.getAttribute('viewBox').split(' ').map(Number);
    const viewBoxWidth = viewBox[2];
    const viewBoxHeight = viewBox[3];

    const scaleX = viewBoxWidth / svgRect.width;
    const scaleY = viewBoxHeight / svgRect.height;

    const svgX = (clientX - svgRect.left) * scaleX;
    const svgY = (clientY - svgRect.top) * scaleY;

    return { x: svgX, y: svgY };
}

// マウスホイールズーム機能を追加
function addMouseWheelZoom(svgElement) {
    svgElement.addEventListener('wheel', function(event) {
        if (type === "imageMoveAndZoom") {
            event.preventDefault();
            
            const image = svgElement.querySelector('image');
            if (image) {
                const matrix = image.transform.baseVal[0].matrix;
                const delta = event.deltaY > 0 ? 0.9 : 1.1; // ズームイン/アウト
                
                // ズーム制限
                const newScale = matrix.a * delta;
                if (newScale >= 0.1 && newScale <= 5) {
                    // マウス位置を中心にズーム
                    const rect = svgElement.getBoundingClientRect();
                    const mouseX = event.clientX - rect.left;
                    const mouseY = event.clientY - rect.top;
                    
                    const viewBox = svgElement.getAttribute('viewBox').split(' ').map(Number);
                    const scaleX = viewBox[2] / rect.width;
                    const scaleY = viewBox[3] / rect.height;
                    
                    const svgMouseX = mouseX * scaleX;
                    const svgMouseY = mouseY * scaleY;
                    
                    // ズーム中心点を調整
                    const dx = svgMouseX - (svgMouseX * delta);
                    const dy = svgMouseY - (svgMouseY * delta);
                    
                    matrix.a *= delta;
                    matrix.d *= delta;
                    matrix.e += dx;
                    matrix.f += dy;
                }
            }
        }
    });
}

// 左の画像SVGにイベントリスナーを設定
$(leftImageSvg_js).on(start_event, function (event) {
    if (type === "imageMoveAndZoom") {
        handleImageMoveStart(event, leftImageSvg_js);
    }
});

// 右の画像SVGにイベントリスナーを設定
$(rightImageSvg_js).on(start_event, function (event) {
    if (type === "imageMoveAndZoom") {
        handleImageMoveStart(event, rightImageSvg_js);
    }
});

// マウスホイールズームを両方のSVGに追加
addMouseWheelZoom(leftImageSvg_js);
addMouseWheelZoom(rightImageSvg_js);

function handleImageMoveStart(event, targetSvg) {
    event.preventDefault();
    
    if (touchType === "move") {
        const target = event.target;
        if (target.tagName === 'image') {
            isDraggingImage = true;
            imageDragTarget = target;
            
            const coords = getImageSvgCoords(event, targetSvg);
            imageDragStartX = coords.x;
            imageDragStartY = coords.y;
            
            imageDragMatrix = target.transform.baseVal[0].matrix;
        }
    } else if (touchType === "zoom") {
        const target = event.target;
        if (target.tagName === 'image') {
            imageDragTarget = target;
            
            if (event.touches && event.touches.length === 2) {
                // 2本指ピンチズーム
                const touch1 = event.touches[0];
                const touch2 = event.touches[1];
                baseDistance = Math.sqrt(
                    Math.pow(touch2.clientX - touch1.clientX, 2) +
                    Math.pow(touch2.clientY - touch1.clientY, 2)
                );
                pointX = (touch1.clientX + touch2.clientX) / 2;
                pointY = (touch1.clientY + touch2.clientY) / 2;
            } else if (event.touches && event.touches.length === 1) {
                // 1本指縦スワイプズーム
                isSingleFingerZooming = true;
                singleFingerZoomStartY = event.touches[0].clientY;
            }
        }
    }
}

function handleImageMove(event) {
    if (isDraggingImage && imageDragTarget) {
        const targetSvg = imageDragTarget.closest('svg');
        const coords = getImageSvgCoords(event, targetSvg);
        const dx = coords.x - imageDragStartX;
        const dy = coords.y - imageDragStartY;
        
        imageDragMatrix.e += dx;
        imageDragMatrix.f += dy;
        
        imageDragStartX = coords.x;
        imageDragStartY = coords.y;
    } else if (touchType === "zoom" && event.touches && imageDragTarget) {
        if (event.touches.length === 2) {
            // 2本指ピンチズーム
            const touch1 = event.touches[0];
            const touch2 = event.touches[1];
            const currentDistance = Math.sqrt(
                Math.pow(touch2.clientX - touch1.clientX, 2) +
                Math.pow(touch2.clientY - touch1.clientY, 2)
            );
            
            if (baseDistance > 0) {
                const scale = currentDistance / baseDistance;
                const matrix = imageDragTarget.transform.baseVal[0].matrix;
                const newScale = matrix.a * scale;
                if (newScale >= 0.1 && newScale <= 5) {
                    matrix.a *= scale;
                    matrix.d *= scale;
                }
                baseDistance = currentDistance;
            }
        } else if (event.touches.length === 1 && isSingleFingerZooming) {
            // 1本指縦スワイプズーム
            const currentY = event.touches[0].clientY;
            const deltaY = singleFingerZoomStartY - currentY;
            const scale = 1 + (deltaY * 0.01);
            
            const matrix = imageDragTarget.transform.baseVal[0].matrix;
            const newScale = matrix.a * scale;
            if (newScale >= 0.1 && newScale <= 5) {
                matrix.a *= scale;
                matrix.d *= scale;
            }
            singleFingerZoomStartY = currentY;
        }
    }
}

function handleImageMoveEnd() {
    isDraggingImage = false;
    imageDragTarget = null;
    baseDistance = 0;
    isSingleFingerZooming = false;
}

// ===== 3. 統合描画機能 =====
function initializeUnifiedSvg() {
    const container = document.getElementById('unified-container');
    const containerRect = container.getBoundingClientRect();
    
    unifiedSvgElm_js.setAttribute("viewBox", `0 0 ${containerRect.width} ${finalSvgHeight}`);
}

function getUnifiedSvgCoords(event) {
    const svgRect = unifiedSvgElm_js.getBoundingClientRect();
    let clientX, clientY;
    
    if (event.touches && event.touches.length > 0) {
        // タッチイベントの場合
        clientX = event.touches[0].clientX;
        clientY = event.touches[0].clientY;
    } else {
        // マウスイベントの場合
        clientX = event.clientX;
        clientY = event.clientY;
    }
    
    const viewBox = unifiedSvgElm_js.getAttribute('viewBox').split(' ').map(Number);
    const viewBoxWidth = viewBox[2];
    const viewBoxHeight = viewBox[3];

    const scaleX = viewBoxWidth / svgRect.width;
    const scaleY = viewBoxHeight / svgRect.height;

    const svgX = (clientX - svgRect.left) * scaleX;
    const svgY = (clientY - svgRect.top) * scaleY;

    return { x: svgX, y: svgY };
}

// 統合SVGにイベントリスナーを設定
unifiedSvgElm.on(start_event, function (event) {
    if (event.target.classList && event.target.classList.contains('comment-box')) {
        return;
    }
    
    event.preventDefault();
    
    if (type === "pencil") {
        handleDrawingStart(event);
    }
});

function handleDrawingStart(event) {
    // 直線選択ロジック
    if (currentTool === 'line-draw' && event.target.tagName === 'line') {
        selectedLine = event.target;
        isMovingLine = true;
        isDrawing = false;
        
        const coords = getUnifiedSvgCoords(event);
        lineStartX = coords.x;
        lineStartY = coords.y;
        
        const x1 = parseFloat(selectedLine.getAttribute('x1'));
        const y1 = parseFloat(selectedLine.getAttribute('y1'));
        const x2 = parseFloat(selectedLine.getAttribute('x2'));
        const y2 = parseFloat(selectedLine.getAttribute('y2'));
        
        lineOffsetX = [x1 - lineStartX, x2 - lineStartX];
        lineOffsetY = [y1 - lineStartY, y2 - lineStartY];
        
        selectedLine.setAttribute('stroke-width', lineWidth * 2);
        return;
    }
    
    isDrawing = true;
    const coords = getUnifiedSvgCoords(event);
    startX = coords.x;
    startY = coords.y;
    
    switch (currentTool) {
        case 'line-draw':
            if (selectedLine) {
                selectedLine.setAttribute('stroke-width', lineWidth);
                selectedLine = null;
                isMovingLine = false;
            }
            
            tempLine = document.createElementNS("http://www.w3.org/2000/svg", "line");
            tempLine.setAttribute("x1", startX);
            tempLine.setAttribute("y1", startY);
            tempLine.setAttribute("x2", startX);
            tempLine.setAttribute("y2", startY);
            tempLine.setAttribute("stroke", lineColor);
            tempLine.setAttribute("stroke-width", lineWidth);
            unifiedSvgElm_js.appendChild(tempLine);
            break;
            
        case 'comment-add':
            const svgRect = unifiedSvgElm_js.getBoundingClientRect();
            let clickX, clickY;
            
            if (event.touches && event.touches.length > 0) {
                clickX = event.touches[0].clientX;
                clickY = event.touches[0].clientY;
            } else {
                clickX = event.clientX;
                clickY = event.clientY;
            }
            
            if (selectedComment) {
                selectedComment.style.outline = 'none';
            }
            
            const commentBox = document.createElement('div');
            commentBox.className = 'comment-box';
            commentBox.contentEditable = true;
            commentBox.style.position = 'absolute';
            commentBox.style.left = (clickX - svgRect.left) + 'px';
            commentBox.style.top = (clickY - svgRect.top) + 'px';
            commentBox.style.color = textColor;
            commentBox.style.backgroundColor = backgroundColor;
            commentBox.style.fontSize = fontSize + 'px';
            commentBox.style.zIndex = '1000';
            commentBox.textContent = 'コメントを入力';
            
            // リサイズハンドルを追加
            const resizeHandle = document.createElement('div');
            resizeHandle.className = 'comment-resize-handle';
            commentBox.appendChild(resizeHandle);
            
            setupCommentBoxEvents(commentBox);
            
            document.getElementById('unified-container').appendChild(commentBox);
            
            selectedComment = commentBox;
            commentBox.style.outline = '2px solid blue';
            commentBox.focus();
            
            const selection = window.getSelection();
            const range = document.createRange();
            range.selectNodeContents(commentBox);
            selection.removeAllRanges();
            selection.addRange(range);
            
            isDrawing = false;
            break;
            
        case 'eraser':
            eraseStartX = startX;
            eraseStartY = startY;
            
            eraseRect = document.createElementNS("http://www.w3.org/2000/svg", "rect");
            eraseRect.setAttribute("x", startX);
            eraseRect.setAttribute("y", startY);
            eraseRect.setAttribute("width", "0");
            eraseRect.setAttribute("height", "0");
            eraseRect.setAttribute("fill", "rgba(128, 128, 128, 0.2)");
            eraseRect.setAttribute("stroke", "rgba(128, 128, 128, 0.5)");
            eraseRect.setAttribute("stroke-width", "1");
            unifiedSvgElm_js.appendChild(eraseRect);
            break;
            
        case 'free-draw':
            currentPathElement = document.createElementNS("http://www.w3.org/2000/svg", "path");
            const path_attr = {
                "id": Math.random().toString(32).substring(2),
                "d": `M ${startX} ${startY} `,
                "fill": "none",
                "stroke": lineColor,
                "stroke-width": lineWidth,
                "stroke-linecap": "round"
            };
            for (let key in path_attr) {
                currentPathElement.setAttribute(key, path_attr[key]);
            }
            unifiedSvgElm_js.appendChild(currentPathElement);
            window.linetoStr = `M ${startX} ${startY} `;
            break;
    }
}

// 移動イベント処理
document.body.addEventListener(move_event, function (event) {
    if (type === "imageMoveAndZoom") {
        handleImageMove(event);
        return;
    }
    
    if (type !== "pencil" || (!isDrawing && !isMovingLine)) return;
    
    event.preventDefault();
    
    const coords = getUnifiedSvgCoords(event);
    const currentX = coords.x;
    const currentY = coords.y;
    
    if (isMovingLine && selectedLine) {
        selectedLine.setAttribute('x1', currentX + lineOffsetX[0]);
        selectedLine.setAttribute('y1', currentY + lineOffsetY[0]);
        selectedLine.setAttribute('x2', currentX + lineOffsetX[1]);
        selectedLine.setAttribute('y2', currentY + lineOffsetY[1]);
    } else if (currentTool === 'free-draw' && currentPathElement) {
        window.linetoStr = window.linetoStr + " L " + currentX + " " + currentY;
        currentPathElement.setAttribute("d", window.linetoStr);
    } else if (currentTool === 'line-draw' && tempLine) {
        tempLine.setAttribute("x2", currentX);
        tempLine.setAttribute("y2", currentY);
    } else if (currentTool === 'eraser' && eraseRect) {
        const width = currentX - eraseStartX;
        const height = currentY - eraseStartY;
        
        const x = width < 0 ? currentX : eraseStartX;
        const y = height < 0 ? currentY : eraseStartY;
        
        eraseRect.setAttribute("x", x);
        eraseRect.setAttribute("y", y);
        eraseRect.setAttribute("width", Math.abs(width));
        eraseRect.setAttribute("height", Math.abs(height));
    }
});

// 終了イベント処理
document.body.addEventListener(end_event, function (event) {
    if (type === "imageMoveAndZoom") {
        handleImageMoveEnd();
        return;
    }

    if (!isDrawing && !isMovingLine) return;
    event.preventDefault();

    if (currentTool === 'line-draw' && tempLine) {
        const x1 = parseFloat(tempLine.getAttribute("x1"));
        const y1 = parseFloat(tempLine.getAttribute("y1"));
        const x2 = parseFloat(tempLine.getAttribute("x2"));
        const y2 = parseFloat(tempLine.getAttribute("y2"));
        const length = Math.sqrt(Math.pow(x2 - x1, 2) + Math.pow(y2 - y1, 2));
        if (length < 5) {
            tempLine.remove();
        }
        tempLine = null;
    } else if (currentTool === 'eraser' && eraseRect) {
        // 消しゴム処理
        const eraseX = parseFloat(eraseRect.getAttribute("x"));
        const eraseY = parseFloat(eraseRect.getAttribute("y"));
        const eraseWidth = parseFloat(eraseRect.getAttribute("width"));
        const eraseHeight = parseFloat(eraseRect.getAttribute("height"));
        
        const allSvgElements = Array.from(unifiedSvgElm_js.querySelectorAll('path, line'));
        
        allSvgElements.forEach(element => {
            if (element === eraseRect) return;
            
            const bbox = element.getBBox();
            
            if (!(bbox.x + bbox.width < eraseX ||
                  bbox.x > eraseX + eraseWidth ||
                  bbox.y + bbox.height < eraseY ||
                  bbox.y > eraseY + eraseHeight)) {
                element.remove();
                if (selectedLine === element) {
                    selectedLine = null;
                    isMovingLine = false;
                }
                if (currentPathElement === element) {
                    currentPathElement = null;
                    window.linetoStr = '';
                }
            }
        });

        // コメントボックスの消去処理
        const commentBoxes = document.querySelectorAll('.comment-box');
        const svgRect = unifiedSvgElm_js.getBoundingClientRect();
        
        commentBoxes.forEach(box => {
            const boxRect = box.getBoundingClientRect();
            const boxRelativeX = boxRect.left - svgRect.left;
            const boxRelativeY = boxRect.top - svgRect.top;
            
            const viewBox = unifiedSvgElm_js.getAttribute('viewBox').split(' ').map(Number);
            const scaleX = viewBox[2] / svgRect.width;
            const scaleY = viewBox[3] / svgRect.height;
            
            const boxSvgX = boxRelativeX * scaleX;
            const boxSvgY = boxRelativeY * scaleY;
            const boxSvgWidth = box.offsetWidth * scaleX;
            const boxSvgHeight = box.offsetHeight * scaleY;
            
            if (!(boxSvgX + boxSvgWidth < eraseX ||
                  boxSvgX > eraseX + eraseWidth ||
                  boxSvgY + boxSvgHeight < eraseY ||
                  boxSvgY > eraseY + eraseHeight)) {
                box.remove();
                if (selectedComment === box) {
                    selectedComment = null;
                }
            }
        });

        eraseRect.remove();
        eraseRect = null;
    }

    isDrawing = false;
    isMovingLine = false;
    if (selectedLine) {
        selectedLine.setAttribute('stroke-width', lineWidth);
        selectedLine = null;
    }
    currentPathElement = null;
    tempLine = null;
});

// ===== UI イベントハンドラー =====

// ツールボタンのクリックイベント
document.querySelectorAll('.tool-button').forEach(button => {
    button.addEventListener('click', () => {
        document.querySelectorAll('.tool-button').forEach(b => {
            b.classList.remove('active');
            b.classList.remove('btn-primary');
            b.classList.add('btn-outline-secondary');
        });
        button.classList.add('active');
        button.classList.remove('btn-outline-secondary');
        button.classList.add('btn-primary');
        
        if (currentTool === 'eraser') {
            document.body.style.cursor = 'auto';
            if (eraseRect) {
                eraseRect.remove();
                eraseRect = null;
            }
        }
        
        currentTool = button.id;
        
        // 各ツールに対応するUIの表示切替
        if (currentTool === 'comment-add') {
            document.getElementById('comment-color-picker').style.display = 'flex';
            document.getElementById('line-color-picker').style.display = 'none';
        } else if (currentTool === 'line-draw') {
            document.getElementById('comment-color-picker').style.display = 'none';
            document.getElementById('line-color-picker').style.display = 'flex';
        } else {
            document.getElementById('comment-color-picker').style.display = 'none';
            document.getElementById('line-color-picker').style.display = 'none';
        }
    });
});

// カラーピッカーのイベントリスナー
document.getElementById('text-color').addEventListener('change', function(e) {
    textColor = e.target.value;
    if (selectedComment) {
        selectedComment.style.color = textColor;
    }
});

document.getElementById('background-color').addEventListener('change', function(e) {
    backgroundColor = e.target.value;
    if (selectedComment) {
        selectedComment.style.backgroundColor = backgroundColor;
    }
});

document.getElementById('line-color').addEventListener('change', function(e) {
    lineColor = e.target.value;
    localStorage.setItem('lineColor', lineColor);
    if (tempLine) {
        tempLine.setAttribute('stroke', lineColor);
    }
});

// 書き込みモードの切り替え
document.getElementById("board-type").addEventListener("click", function (e) {
    if (type === "imageMoveAndZoom") {
        type = "pencil";
        this.classList.add("btn-primary");
        this.classList.remove("btn-outline-primary");
        this.textContent = "書き込みモードON";
        document.getElementById("drawing-toolbar").style.display = "flex";
        
        // 統合SVGを描画モードに
        unifiedSvgElm_js.classList.add('drawing-mode');
        
        document.querySelectorAll('.tool-button').forEach(b => {
            b.classList.remove('active');
            b.classList.remove('btn-primary');
            b.classList.add('btn-outline-secondary');
        });
        document.getElementById('free-draw').classList.add('active');
        document.getElementById('free-draw').classList.remove('btn-outline-secondary');
        document.getElementById('free-draw').classList.add('btn-primary');
        currentTool = 'free-draw';
    } else {
        type = "imageMoveAndZoom";
        this.classList.remove("btn-primary");
        this.classList.add("btn-outline-primary");
        this.textContent = "書き込みモードOFF";
        document.getElementById("drawing-toolbar").style.display = "none";
        document.getElementById("comment-color-picker").style.display = "none";
        document.getElementById("line-color-picker").style.display = "none";
        
        // 統合SVGを非描画モードに
        unifiedSvgElm_js.classList.remove('drawing-mode');
        
        if (selectedLine) {
            selectedLine.setAttribute('stroke-width', lineWidth);
            selectedLine = null;
            isMovingLine = false;
        }
        
        if (selectedComment) {
            selectedComment.style.outline = 'none';
            selectedComment = null;
        }
    }
});

// フォントサイズ変更ボタンのイベントリスナー
document.getElementById('increase-font-size').addEventListener('click', function() {
    if (fontSize < 36) {
        fontSize += 2;
        updateFontSizeDisplay();
        if (selectedComment) {
            selectedComment.style.fontSize = fontSize + 'px';
        }
    }
});

document.getElementById('decrease-font-size').addEventListener('click', function() {
    if (fontSize > 8) {
        fontSize -= 2;
        updateFontSizeDisplay();
        if (selectedComment) {
            selectedComment.style.fontSize = fontSize + 'px';
        }
    }
});

function updateFontSizeDisplay() {
    document.getElementById('font-size-value').textContent = fontSize;
}

// タッチモード切り替え
document.getElementById("touch-type").addEventListener("click", function () {
    if (touchType === "move") {
        touchType = "zoom";
        this.textContent = "ズームモード";
        this.classList.add("btn-primary");
        this.classList.remove("btn-outline-primary");
    } else {
        touchType = "move";
        this.textContent = "ムーブモード";
        this.classList.remove("btn-primary");
        this.classList.add("btn-outline-primary");
    }
});

// 画像回転機能
document.getElementById("rotate-left-image-left").addEventListener("click", function () {
    leftRotation = (leftRotation - 10 + 360) % 360;
    const leftImage = document.getElementById("left-image");
    if (leftImage) {
        applyRotationTransform(leftImage, leftRotation);
    }
});

document.getElementById("rotate-left-image-right").addEventListener("click", function () {
    leftRotation = (leftRotation + 10) % 360;
    const leftImage = document.getElementById("left-image");
    if (leftImage) {
        applyRotationTransform(leftImage, leftRotation);
    }
});

document.getElementById("rotate-right-image-left").addEventListener("click", function () {
    rightRotation = (rightRotation - 10 + 360) % 360;
    const rightImage = document.getElementById("right-image");
    if (rightImage) {
        applyRotationTransform(rightImage, rightRotation);
    }
});

document.getElementById("rotate-right-image-right").addEventListener("click", function () {
    rightRotation = (rightRotation + 10) % 360;
    const rightImage = document.getElementById("right-image");
    if (rightImage) {
        applyRotationTransform(rightImage, rightRotation);
    }
});

function applyRotationTransform(imageElement, angle) {
    const bbox = imageElement.getBBox();
    const cx = bbox.x + bbox.width / 2;
    const cy = bbox.y + bbox.height / 2;
    const transformList = imageElement.transform.baseVal;
    let rotateTransform = imageElement.ownerSVGElement.createSVGTransform();

    rotateTransform.setRotate(angle, cx, cy);

    if (transformList.numberOfItems > 1) {
        transformList.removeItem(1);
    }
    transformList.appendItem(rotateTransform);
}

// コメントボックスのイベント設定 - リサイズ機能を復活
function setupCommentBoxEvents(commentBox) {
    commentBox.addEventListener('click', function(e) {
        e.stopPropagation();
        if (selectedComment && selectedComment !== commentBox) {
            selectedComment.style.outline = 'none';
        }
        selectedComment = commentBox;
        selectedComment.style.outline = '2px solid blue';
        document.getElementById('text-color').value = rgbToHex(window.getComputedStyle(commentBox).color);
        document.getElementById('background-color').value = rgbToHex(window.getComputedStyle(commentBox).backgroundColor);
        const currentFontSize = parseInt(window.getComputedStyle(commentBox).fontSize);
        fontSize = currentFontSize;
        updateFontSizeDisplay();
        
        // ツールをコメントに切り替え
        document.querySelectorAll('.tool-button').forEach(b => {
            b.classList.remove('active');
            b.classList.remove('btn-primary');
            b.classList.add('btn-outline-secondary');
        });
        const commentButton = document.getElementById('comment-add');
        commentButton.classList.add('active');
        commentButton.classList.remove('btn-outline-secondary');
        commentButton.classList.add('btn-primary');
        currentTool = 'comment-add';
        document.getElementById('comment-color-picker').style.display = 'flex';
        document.getElementById('line-color-picker').style.display = 'none';
    });
    
    // ドラッグ機能を追加（タッチ対応）
    let isDraggingComment = false;
    let startDragX, startDragY;
    let startLeft, startTop;
    
    // マウスイベント
    commentBox.addEventListener('mousedown', function(e) {
        if (e.target.classList.contains('comment-resize-handle')) return; // リサイズハンドルの場合はドラッグしない
        
        if (e.target === commentBox) {
            isDraggingComment = true;
            startDragX = e.clientX;
            startDragY = e.clientY;
            startLeft = parseInt(commentBox.style.left) || 0;
            startTop = parseInt(commentBox.style.top) || 0;
            document.addEventListener('mousemove', dragMove);
            document.addEventListener('mouseup', dragEnd);
            commentBox.style.cursor = 'grabbing';
        }
    });
    
    // タッチイベント
    commentBox.addEventListener('touchstart', function(e) {
        if (e.target.classList.contains('comment-resize-handle')) return; // リサイズハンドルの場合はドラッグしない
        
        if (e.target === commentBox && e.touches.length === 1) {
            isDraggingComment = true;
            startDragX = e.touches[0].clientX;
            startDragY = e.touches[0].clientY;
            startLeft = parseInt(commentBox.style.left) || 0;
            startTop = parseInt(commentBox.style.top) || 0;
            document.addEventListener('touchmove', dragMoveTouch);
            document.addEventListener('touchend', dragEndTouch);
        }
    });
    
    function dragMove(e) {
        if (!isDraggingComment) return;
        const dx = e.clientX - startDragX;
        const dy = e.clientY - startDragY;
        commentBox.style.left = (startLeft + dx) + 'px';
        commentBox.style.top = (startTop + dy) + 'px';
    }
    
    function dragMoveTouch(e) {
        if (!isDraggingComment || e.touches.length !== 1) return;
        e.preventDefault();
        const dx = e.touches[0].clientX - startDragX;
        const dy = e.touches[0].clientY - startDragY;
        commentBox.style.left = (startLeft + dx) + 'px';
        commentBox.style.top = (startTop + dy) + 'px';
    }
    
    function dragEnd() {
        isDraggingComment = false;
        document.removeEventListener('mousemove', dragMove);
        document.removeEventListener('mouseup', dragEnd);
        commentBox.style.cursor = 'move';
    }
    
    function dragEndTouch() {
        isDraggingComment = false;
        document.removeEventListener('touchmove', dragMoveTouch);
        document.removeEventListener('touchend', dragEndTouch);
    }

    // リサイズ機能を復活
    const resizeHandle = commentBox.querySelector('.comment-resize-handle');
    if (resizeHandle) {
        let isResizing = false;
        let originalWidth, originalHeight, originalX, originalY;
        
        resizeHandle.addEventListener('mousedown', function(e) {
            e.stopPropagation();
            isResizing = true;
            originalWidth = commentBox.offsetWidth;
            originalHeight = commentBox.offsetHeight;
            originalX = e.clientX;
            originalY = e.clientY;
            document.addEventListener('mousemove', resizeMove);
            document.addEventListener('mouseup', resizeEnd);
        });
        
        resizeHandle.addEventListener('touchstart', function(e) {
            e.stopPropagation();
            if (e.touches.length === 1) {
                isResizing = true;
                originalWidth = commentBox.offsetWidth;
                originalHeight = commentBox.offsetHeight;
                originalX = e.touches[0].clientX;
                originalY = e.touches[0].clientY;
                document.addEventListener('touchmove', resizeMoveTouch);
                document.addEventListener('touchend', resizeEndTouch);
            }
        });
        
        function resizeMove(e) {
            if (isResizing) {
                const deltaX = e.clientX - originalX;
                const deltaY = e.clientY - originalY;
                const newWidth = Math.max(100, originalWidth + deltaX);
                const newHeight = Math.max(50, originalHeight + deltaY);
                commentBox.style.width = newWidth + 'px';
                commentBox.style.height = newHeight + 'px';
            }
        }
        
        function resizeMoveTouch(e) {
            if (isResizing && e.touches.length === 1) {
                e.preventDefault();
                const deltaX = e.touches[0].clientX - originalX;
                const deltaY = e.touches[0].clientY - originalY;
                const newWidth = Math.max(100, originalWidth + deltaX);
                const newHeight = Math.max(50, originalHeight + deltaY);
                commentBox.style.width = newWidth + 'px';
                commentBox.style.height = newHeight + 'px';
            }
        }
        
        function resizeEnd() {
            isResizing = false;
            document.removeEventListener('mousemove', resizeMove);
            document.removeEventListener('mouseup', resizeEnd);
        }
        
        function resizeEndTouch() {
            isResizing = false;
            document.removeEventListener('touchmove', resizeMoveTouch);
            document.removeEventListener('touchend', resizeEndTouch);
        }
    }
}

// RGB色をHEX形式に変換する関数
function rgbToHex(rgb) {
    if (rgb.startsWith('#')) {
        return rgb;
    }
    
    const rgbMatch = rgb.match(/^rgb\s*$$\s*(\d+)\s*,\s*(\d+)\s*,\s*(\d+)\s*$$$/i);
    if (rgbMatch) {
        const r = parseInt(rgbMatch[1], 10);
        const g = parseInt(rgbMatch[2], 10);
        const b = parseInt(rgbMatch[3], 10);
        return '#' + ((1 << 24) + (r << 16) + (g << 8) + b).toString(16).slice(1);
    }
    
    return '#000000';
}

// ウィンドウリサイズ処理
function resizeWindow() {
    loadAndDisplayImages(); 
}
window.onresize = resizeWindow;

// プレビューとダウンロード機能
var previewFlag = false;

document.getElementById("preview-btn").addEventListener("click", async function () {
    if (!previewFlag) {
        await previewImage();
        this.classList.add("btn-primary");
        this.classList.remove("btn-outline-primary");
        this.textContent = "画像プレビューOFF";
    } else {
        document.getElementById("preview").style.display = "none";
        this.classList.remove("btn-primary");
        this.classList.add("btn-outline-primary");
        this.textContent = "画像プレビューON";
    }
    previewFlag = !previewFlag;
});

document.getElementById("submit").addEventListener("click", async function () {
    await downloadBoard();
});

async function previewImage() {
    try {
        const unifiedSvg = document.getElementById('unifiedDrawingCanvas');
        const leftImageSvg = document.getElementById('leftImageCanvas');
        const rightImageSvg = document.getElementById('rightImageCanvas');
        
        const canvas = document.createElement("canvas");
        const containerRect = document.getElementById('unified-container').getBoundingClientRect();
        
        // 日付エリアを含む高さで計算
        const totalHeight = finalSvgHeight + 60; // 日付エリア分を追加
        canvas.width = containerRect.width;
        canvas.height = totalHeight;

        const ctx = canvas.getContext("2d");
        
        ctx.fillStyle = 'white';
        ctx.fillRect(0, 0, canvas.width, canvas.height);
        
        // 左の画像SVGを描画
        const leftSvgData = new XMLSerializer().serializeToString(leftImageSvg);
        const leftSvgBlob = new Blob([leftSvgData], { type: "image/svg+xml;charset=utf-8" });
        const leftUrl = URL.createObjectURL(leftSvgBlob);
        const leftImage = await loadImage(leftUrl);
        ctx.drawImage(leftImage, 0, 0, containerRect.width / 2, finalSvgHeight);
        URL.revokeObjectURL(leftUrl);
        
        // 右の画像SVGを描画
        const rightSvgData = new XMLSerializer().serializeToString(rightImageSvg);
        const rightSvgBlob = new Blob([rightSvgData], { type: "image/svg+xml;charset=utf-8" });
        const rightUrl = URL.createObjectURL(rightSvgBlob);
        const rightImage = await loadImage(rightUrl);
        ctx.drawImage(rightImage, containerRect.width / 2, 0, containerRect.width / 2, finalSvgHeight);
        URL.revokeObjectURL(rightUrl);
        
        // 統合された描画レイヤーを描画
        const unifiedSvgData = new XMLSerializer().serializeToString(unifiedSvg);
        const unifiedSvgBlob = new Blob([unifiedSvgData], { type: "image/svg+xml;charset=utf-8" });
        const unifiedUrl = URL.createObjectURL(unifiedSvgBlob);
        const unifiedImage = await loadImage(unifiedUrl);
        ctx.drawImage(unifiedImage, 0, 0, containerRect.width, finalSvgHeight);
        URL.revokeObjectURL(unifiedUrl);

        // コメントボックスを描画
        const commentBoxes = document.querySelectorAll('.comment-box');
        const svgRect = unifiedSvg.getBoundingClientRect();

        commentBoxes.forEach(comment => {
            const padding = 8;
            const commentRect = comment.getBoundingClientRect();
            const commentLeft = commentRect.left - svgRect.left;
            const commentTop = commentRect.top - svgRect.top;
            const commentWidth = comment.offsetWidth - padding * 2;
            const commentHeight = comment.offsetHeight;
            
            ctx.fillStyle = window.getComputedStyle(comment).backgroundColor;
            ctx.fillRect(commentLeft, commentTop, commentWidth, commentHeight);
            
            const computedStyle = window.getComputedStyle(comment);
            const fontSize = computedStyle.fontSize;
            const fontFamily = computedStyle.fontFamily;

            ctx.font = `${fontSize} ${fontFamily}`;
            ctx.fillStyle = computedStyle.color;
            ctx.textBaseline = 'middle';
            ctx.textAlign = 'left';
            
            const text = comment.innerText;
            const lines = text.split(/\r?\n/);
            const lineHeight = parseInt(fontSize) * 1.2;
            const totalTextHeight = lines.length * lineHeight;
            const startY = commentTop + (commentHeight / 2) - (totalTextHeight / 2) + (lineHeight / 2);

            lines.forEach((line, index) => {
                const y = startY + index * lineHeight;
                ctx.fillText(line, commentLeft + padding, y, commentWidth - padding * 2);
            });
        });

        // 日付テキストを描画（SVGエリア外）
        const leftDate = document.getElementById("target-image-date").value;
        const rightDate = document.getElementById("subject-image-date").value;
        ctx.font = "bold 16px sans-serif";
        ctx.fillStyle = "black";
        ctx.textAlign = "center";
        const dateY = finalSvgHeight + 35; // 日付の位置を調整
        ctx.fillText(leftDate, containerRect.width / 4, dateY);
        ctx.fillText(rightDate, containerRect.width * 3 / 4, dateY);

        const pngDataUrl = canvas.toDataURL('image/png');
        document.getElementById("preview").src = pngDataUrl;
        document.getElementById("preview").style.display = "block";
    } catch (error) {
        console.error('Error converting SVG to canvas for preview:', error);
    }
}

async function downloadBoard() {
    try {
        await previewImage();
        const pngDataUrl = document.getElementById("preview").src;
        uploadImageToServer(pngDataUrl);
    } catch (error) {
        console.error('Error converting SVG to canvas for download:', error);
    }
}

function uploadImageToServer(pngDataUrl) {
    const formData = new FormData();
    const staff_id = document.getElementById("staff_id").value;
    const comment = document.getElementById("staff_comment").value;
    const target_image = "<?= $param["target_id"] ?>"
    const subject_image = "<?= $param["subject_id"] ?>"
    const user = "<?= $param["user"] ?>"
    formData.append('image', pngDataUrl);
    formData.append('staff_id', staff_id);
    formData.append('staff_comment', comment);
    formData.append('target_image', target_image);
    formData.append('subject_image', subject_image);
    formData.append('user_id', user);
    const nextURL = "/staff/compared-images?start_report_date=&end_report_date=&user_id=" + user;
    $.ajax({
        type: 'POST',
                url: "<?= $this->Url->build(['controller' => 'ComparedImages', 'action' => 'uploadComparedImage', 'prefix' => 'Staff']); ?>",
        data: formData,
        headers: {
            'X-CSRF-Token': "<?= $this->request->getAttribute('csrfToken') ?>",
        },
        cache: false,
        contentType: false,
        processData: false,
        success: function (data, dataType) {
            document.getElementById("error").innerHTML = data;
            location.href = nextURL;
        },
        error: function (e) {
            document.getElementById("error").innerHTML = e.responseText;
        }
    });
}
});
</script>