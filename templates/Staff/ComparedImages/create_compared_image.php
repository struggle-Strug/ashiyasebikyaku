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

/* カラーピッカーコンテナのスタイル */
.color-picker-container {
    display: none;
    margin-left: 10px;
    align-items: center;
    gap: 10px;
}

.color-picker-label {
    font-size: 12px;
    margin-right: 5px;
}

/* リサイズハンドルのスタイル - 完全に非表示に変更 */
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
}

/* フォントサイズ変更ツールのスタイル */
.font-size-tools {
    display: none;
    margin-left: 10px;
    align-items: center;
    gap: 5px;
}

.font-size-label {
    font-size: 12px;
    margin-right: 5px;
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

.font-size-value {
    font-size: 12px;
    min-width: 20px;
    text-align: center;
}

/* 新しいスタイル：個別のfigure要素用 */
.image-container {
    display: flex;
    gap: 0; /* 画像間のギャップを削除 */
    flex-wrap: nowrap; /* 画像が同じ行に留まるように折り返しを防止 */
    align-items: flex-start;
}

.image-figure {
    flex: 1;
    min-width: 300px;
    position: relative;
    border: solid 1px rgba(0,0,0,0.1);
    background-color: white;
    padding-bottom: 30px; /* 日付テキスト用のスペースを追加 */
}

@media only screen and (max-width: 767px) {
    .image-container {
        gap: 0; /* モバイルでの画像間のギャップを削除 */
    }
    
    .image-figure {
        min-width: 50%; /* 各画像が正確に半分の幅を占めるようにする */
        max-width: 50%;
        flex: 1;
        padding-bottom: 40px; /* モバイルでの日付テキスト用のスペースを増やす */
    }
    
    /* モバイルでのフィット感を高めるためにSVGの高さを縮小 */
    .image-figure svg {
        height: 300px !important; /* モバイルで400pxから300pxに縮小 */
    }
}


/* 新しいスタイル：個別のfigure要素用 */
.date-text {
    font-weight: bold;
    white-space: nowrap;
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
        <div class="p-sm-2 p-md-4 py-2 mx-2"><button class="btn btn-outline-primary" id="touch-type">ムーブモード</button>
        </div>
    </div>
    
    <!-- 修正：2つの個別のfigure要素 -->
    <div class="image-container p-sm-2 p-md-4">
        <figure id="left-board" class="image-figure" style="position: relative">
            <svg id="leftDrawingCanvas"
                style="width: 100%; height: 400px">
                <defs>
                    <clipPath id="clipleft">
                        <rect x="20" y="20" width="260" height="360" id="clip-left-rect" />
                    </clipPath>
                </defs>
            </svg>
        </figure>
        
        <figure id="right-board" class="image-figure" style="position: relative">
            <svg id="rightDrawingCanvas"
                style="width: 100%; height: 400px">
                <defs>
                    <clipPath id="clipright">
                        <rect x="20" y="20" width="260" height="360" id="clip-right-rect" />
                    </clipPath>
                </defs>
            </svg>
        </figure>
    </div>
    
    <div class="p-sm-2 p-md-4 py-2"><button class="btn btn-outline-primary" id="preview-btn">画像プレビューON</button>
    </div>
        <div class="p-sm-2 p-md-4 py-2"><img src="" id="preview" /></div>
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

    <div class="card-footer">
        <a class="btn btn-outline-secondary" href="<?= $this->Url->build(['action' => 'add', '?' => $param]); ?>"><i
            class="fas fa-arrow-circle-left"></i> 戻る</a>
        <span class="float-right">
            <button type="submit" class="btn btn-primary" id="submit">保存</button>
        </span>
    </div>

</div>
<img src="<?= $targetImage["image_url"] ?>" style="display: none" id="target_image_url" crossorigin="anonymous" />
<img src="<?= $subjectImage["image_url"] ?>" style="display: none" id="subject_image_url" crossorigin="anonymous" />
<div id="error"></div>
<input type="hidden" value="">
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
    let csrf = $('#ajax-form').find('input[name=_csrfToken]').val();

    // イベントタイプを定義 (タッチデバイスとマウスデバイスで切り替え)
    var start_event = 'ontouchstart' in window ? "touchstart" : "mousedown";
    var move_event = 'ontouchstart' in window ? "touchmove" : "mousemove";
    var end_event = 'ontouchstart' in window ? "touchend" : "mouseup";
    var out_event = "mouseleave";
    
    // 両方のSVG要素を取得
    var leftSvgElm = $("#leftDrawingCanvas");
    var rightSvgElm = $("#rightDrawingCanvas");
    var leftSvgElm_js = document.getElementById("leftDrawingCanvas");
    var rightSvgElm_js = document.getElementById("rightDrawingCanvas");
    var currentSvg = null; // 現在操作中のSVG
    var currentSvg_js = null;
    
    var type = "imageMoveAndZoom"; // 現在のモード: "imageMoveAndZoom" (画像移動・ズーム) または "pencil" (描画)
    var touchType = "move"; // タッチデバイスでのサブモード: "move" (移動) または "zoom" (ズーム)
    
    // 描画状態管理のためのグローバル変数
    var currentSvg = null; // 現在アクティブなSVGのjQueryオブジェクト
    var currentSvg_js = null; // 現在アクティブなSVGのDOMオブジェクト
    var currentPathElement = null; // 自由描画 (path要素)
    var tempLine = null; // 直線描画 (line要素)
    var startX, startY; // 現在の描画セグメントの開始点
    var lastSvg_js = null; // 前回の移動イベントからのSVGを追跡

    // ツール関連の変数
    var currentTool = "free-draw";
    var isDrawing = false;
    var startX, startY;
    var tempLine = null;
    var textColor = "#000000";
    var backgroundColor = "#ffffff";
        var lineColor = "#ff0000"; // 初期値
        var lineWidth = 3; // 固定値として残す
        var selectedComment = null; // 選択中のコメント要素
        var fontSize = 14; // デフォルトのフォントサイズ

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

    // Global variables for image dimensions and heights
    let finalSvgHeight = 400; // Default or initial height for SVGs
    let calculatedLeftImageHeight = 0;
    let calculatedRightImageHeight = 0;
    const dateAreaHeight = 40; // Height reserved for date text below the SVG

    // 初期化時にカラーピッカーの値を設定
    $(document).ready(function() {
        document.getElementById('line-color').value = lineColor;
       
        // ローカルストレージから色を復元（存在する場合）
        if (localStorage.getItem('lineColor')) {
            lineColor = localStorage.getItem('lineColor');
            document.getElementById('line-color').value = lineColor;
        }

        // Initial load and display of images
        loadAndDisplayImages(); // <--- ADDED THIS LINE
    });

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
            
            // 以前のツールが消しゴムだった場合、消しゴムモードを終了
            if (currentTool === 'eraser') {
                    // 消しゴムモードを終了する際にカーソルを元に戻す
                document.body.style.cursor = 'auto';
                
                    // 範囲選択要素があれば削除
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
                document.getElementById('font-size-tools').style.display = 'flex';
            } else if (currentTool === 'line-draw') {
                document.getElementById('comment-color-picker').style.display = 'none';
                document.getElementById('line-color-picker').style.display = 'flex';
                document.getElementById('font-size-tools').style.display = 'none';
            } else if (currentTool === 'eraser') {
                document.getElementById('comment-color-picker').style.display = 'none';
                document.getElementById('line-color-picker').style.display = 'none';
                document.getElementById('font-size-tools').style.display = 'none';
            } else {
                document.getElementById('comment-color-picker').style.display = 'none';
                document.getElementById('line-color-picker').style.display = 'none';
                document.getElementById('font-size-tools').style.display = 'none';
            }
        });
    });

    // カラーピッカーのイベントリスナー
        document.getElementById('text-color').addEventListener('change', function (e) {
        textColor = e.target.value;
            // 選択中のコメントがあれば、その色を変更
        if (selectedComment) {
            selectedComment.style.color = textColor;
        }
    });

        document.getElementById('background-color').addEventListener('change', function (e) {
        backgroundColor = e.target.value;
        // 選択中のコメントがあれば、その背景色を変更
        if (selectedComment) {
            selectedComment.style.backgroundColor = backgroundColor;
        }
    });

    // 線の色のイベントリスナー
        document.getElementById('line-color').addEventListener('change', function (e) {
        lineColor = e.target.value;
            // 色の値をローカルストレージに保存して永続化
        localStorage.setItem('lineColor', lineColor);
            // 選択中の線があれば、その色を変更
        if (tempLine) {
            tempLine.setAttribute('stroke', lineColor);
        }
    });

    // 書き込みモードの切り替え
    document.getElementById("board-type").addEventListener("click", function (e) {
        if (type === "imageMoveAndZoom") {
            type = "pencil" 
            this.classList.add("btn-primary");
            this.classList.remove("btn-outline-primary")
            this.textContent = "書き込みモードON";
            document.getElementById("drawing-toolbar").style.display = "flex";
            document.getElementById("comment-color-picker").style.display = "none";
            document.getElementById("line-color-picker").style.display = "none";
            document.getElementById("font-size-tools").style.display = "none";
            
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
            type = "imageMoveAndZoom"
            this.classList.remove("btn-primary");
            this.classList.add("btn-outline-primary")
            this.textContent = "書き込みモードOFF";
            document.getElementById("drawing-toolbar").style.display = "none";
            document.getElementById("comment-color-picker").style.display = "none";
            document.getElementById("line-color-picker").style.display = "none";
            document.getElementById("font-size-tools").style.display = "none"; 
            
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
        document.getElementById('increase-font-size').addEventListener('click', function () {
            if (fontSize < 36) { // 最大サイズを36pxに制限
            fontSize += 2;
            updateFontSizeDisplay();
            
                // 選択中のコメントがあれば、そのフォントサイズを変更
            if (selectedComment) {
                selectedComment.style.fontSize = fontSize + 'px';
            }
        }
    });
    
        document.getElementById('decrease-font-size').addEventListener('click', function () {
            if (fontSize > 8) { // 最小サイズを8pxに制限
            fontSize -= 2;
            updateFontSizeDisplay();
            
                // 選択中のコメントがあれば、そのフォントサイズを変更
            if (selectedComment) {
                selectedComment.style.fontSize = fontSize + 'px';
            }
        }
    });
    
        // フォントサイズの表示を更新する関数
    function updateFontSizeDisplay() {
        document.getElementById('font-size-value').textContent = fontSize;
    }

    // タッチモード切り替え (モバイル用: 移動/ズーム)
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

    // どちらのSVGがクリックされたかを判定する関数
    function getSvgFromEvent(event) {
        const target = event.target;
        const leftSvg = document.getElementById('leftDrawingCanvas');
        const rightSvg = document.getElementById('rightDrawingCanvas');
        
        if (leftSvg.contains(target) || target === leftSvg) {
            return { svg: leftSvgElm, svg_js: leftSvgElm_js, side: 'left' };
        } else if (rightSvg.contains(target) || target === rightSvg) {
            return { svg: rightSvgElm, svg_js: rightSvgElm_js, side: 'right' };
        }
        return null;
    }

    function screenToSvgCoords(clientX, clientY, svgElement) {
        const svgRect = svgElement.getBoundingClientRect();
        const viewBox = svgElement.getAttribute('viewBox').split(' ').map(Number);
        const viewBoxWidth = viewBox[2];
        const viewBoxHeight = viewBox[3];
        const viewBoxMinX = viewBox[0];
        const viewBoxMinY = viewBox[1];

        const scaleX = viewBoxWidth / svgRect.width;
        const scaleY = viewBoxHeight / svgRect.height;

        const svgX = (clientX - svgRect.left) * scaleX + viewBoxMinX;
        const svgY = (clientY - svgRect.top) * scaleY + viewBoxMinY;

        return { x: svgX, y: svgY };
    }

    // 両方のSVGにイベントリスナーを設定
    function setupSvgEvents(svgElm, svgElm_js) {
        svgElm.on(start_event, function (event) {
            // コメント要素が描画開始をトリガーしないようにする
            if (event.target.classList && event.target.classList.contains('comment-box')) {
                return;
            }
            
            // 現在アクティブなSVGを設定
            const svgInfo = getSvgFromEvent(event);
            if (!svgInfo) return; // イベントがSVG上で発生している場合は発生しないはず

            currentSvg = svgInfo.svg;
            currentSvg_js = svgInfo.svg_js;
            lastSvg_js = currentSvg_js; // mousedown時にlastSvg_jsを初期化

            event.preventDefault();
            // Ensure global listeners are attached for all image manipulation starts (mouse or touch)
            if (type === "imageMoveAndZoom") {
                if (event.touches) {
                    if (event.touches.length === 2) { // 2本指ピンチズーム
                        isDraggingImage = false; // 移動のためのドラッグではない
                        isSingleFingerZooming = false; // 1本指ズームではない
                        baseDistance = Math.sqrt(
                            Math.pow(event.touches[1].pageX - event.touches[0].pageX, 2) +
                            Math.pow(event.touches[1].pageY - event.touches[0].pageY, 2)
                        );
                        const x1 = getPositionXOnSvgByX(event.touches[0].pageX, svgInfo.svg, svgInfo.svg_js);
                        const y1 = getPositionYOnSvgByY(event.touches[0].pageY, svgInfo.svg, svgInfo.svg_js);
                        const x2 = getPositionXOnSvgByX(event.touches[1].pageX, svgInfo.svg, svgInfo.svg_js);
                        const y2 = getPositionYOnSvgByY(event.touches[1].pageY, svgInfo.svg, svgInfo.svg_js);
                        pointX = (x1 + x2) / 2;
                        pointY = (y1 + y2) / 2;

                        const currentPointerX = parseInt(getPositionXOnSvg(event, svgInfo.svg, svgInfo.svg_js));
                        const currentPointerY = parseInt(getPositionYOnSvg(event, svgInfo.svg, svgInfo.svg_js));
                        const margin = 20;
                        const svgWidth = svgInfo.svg_js.clientWidth;
                        const svgHeight = svgInfo.svg_js.clientHeight;
                        if (currentPointerX > margin && currentPointerX < svgWidth - margin &&
                            currentPointerY > margin && currentPointerY < svgHeight - margin) {
                            if (svgInfo.side === 'left') {
                                imageDragTarget = document.getElementById("left-image");
                            } else {
                                imageDragTarget = document.getElementById("right-image");
                            }
                        }
                    } else if (event.touches.length === 1) { // 1本指タッチ
                        if (touchType === "move") {
                            moveContentByMousedown(event); // This sets isDraggingImage = true
                        } else if (touchType === "zoom") {
                            isDraggingImage = false; // Not dragging for move
                            isSingleFingerZooming = true;
                            singleFingerZoomStartY = getPositionYOnSvg(event, svgInfo.svg, svgInfo.svg_js);
                            const currentPointerX = parseInt(getPositionXOnSvg(event, svgInfo.svg, svgInfo.svg_js));
                            const currentPointerY = parseInt(getPositionYOnSvg(event, svgInfo.svg, svgInfo.svg_js));
                            const margin = 20;
                            const svgWidth = svgInfo.svg_js.clientWidth;
                            const svgHeight = svgInfo.svg_js.clientHeight;
                            if (currentPointerX > margin && currentPointerX < svgWidth - margin &&
                                currentPointerY > margin && currentPointerY < svgHeight - margin) {
                                if (svgInfo.side === 'left') {
                                    imageDragTarget = document.getElementById("left-image");
                                } else {
                                    imageDragTarget = document.getElementById("right-image");
                                }
                            }
                            // Set reference point to the top-left of the image
                            if (imageDragTarget) {
                                pointX = parseFloat(imageDragTarget.getAttribute("x"));
                                pointY = parseFloat(imageDragTarget.getAttribute("y"));
                            } else {
                                // Fallback if imageDragTarget is not found (shouldn't happen if logic is correct)
                                pointX = currentPointerX;
                                pointY = currentPointerY;
                            }
                        }
                    }
                } else { // マウスイベント
                    moveContentByMousedown(event); // This sets isDraggingImage = true
                }

                // Always attach global listeners if any image manipulation mode is active
                if (isDraggingImage || (event.touches && event.touches.length === 2) || isSingleFingerZooming) {
                    document.body.addEventListener(move_event, handleImageMoveAndZoomMove, { passive: false });
                    document.body.addEventListener(end_event, handleImageMoveAndZoomEnd);
                }
            } else if (type === "pencil") {
                // 直線選択ロジック (既存)
                if (currentTool === 'line-draw' && event.target.tagName === 'line') {
                    selectedLine = event.target;
                    isMovingLine = true;
                    isDrawing = false; // 新しい線を描画するのではなく、既存の線を移動する
                    
                    lineStartX = getPositionXOnSvg(event, currentSvg, currentSvg_js);
                    lineStartY = getPositionYOnSvg(event, currentSvg, currentSvg_js);
                    
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
                startX = getPositionXOnSvg(event, currentSvg, currentSvg_js);
                startY = getPositionYOnSvg(event, currentSvg, currentSvg_js);
                
                switch (currentTool) {
                    case 'line-draw':
                        // 以前に選択された線を非選択にする
                        if (selectedLine) {
                            selectedLine.setAttribute('stroke-width', lineWidth);
                            selectedLine = null;
                            isMovingLine = false;
                        }
                        
                        // 新しい直線の描画を開始
                        tempLine = document.createElementNS("http://www.w3.org/2000/svg", "line");
                        tempLine.setAttribute("x1", startX);
                        tempLine.setAttribute("y1", startY);
                        tempLine.setAttribute("x2", startX);
                        tempLine.setAttribute("y2", startY);
                        tempLine.setAttribute("stroke", lineColor);
                        tempLine.setAttribute("stroke-width", lineWidth);
                        currentSvg_js.appendChild(tempLine);
                        break;
                    case 'comment-add':
                        // 既存のコメント追加ロジック
                        const x = getPositionXOnSvg(event, currentSvg, currentSvg_js);
                        const y = getPositionYOnSvg(event, currentSvg, currentSvg_js);
                        
                        if (selectedComment) {
                            selectedComment.style.outline = 'none';
                        }
                        
                        const commentBox = document.createElement('div');
                        commentBox.className = 'comment-box';
                        commentBox.contentEditable = true;
                        commentBox.style.position = 'absolute';
                        const svgRect = currentSvg_js.getBoundingClientRect();
                        const clickX = event.clientX || (event.touches && event.touches[0].clientX);
                        const clickY = event.clientY || (event.touches && event.touches[0].clientY);
                        commentBox.style.left = (clickX - svgRect.left) + 'px';
                        commentBox.style.top = (clickY - svgRect.top) + 'px';
                        commentBox.style.color = textColor;
                        commentBox.style.backgroundColor = backgroundColor;
                            commentBox.style.fontSize = fontSize + 'px'; // 現在のフォントサイズを設定
                        commentBox.style.zIndex = '1000';
                        commentBox.textContent = 'コメントを入力';
                        
                        const resizeHandle = document.createElement('div');
                        resizeHandle.className = 'comment-resize-handle';
                        resizeHandle.style.position = 'absolute';
                        resizeHandle.style.bottom = '0';
                        resizeHandle.style.right = '0';
                        resizeHandle.style.transform = 'translate(50%, 50%)';
                        
                        // コメントボックスをクリックしたときの処理
                            commentBox.addEventListener('click', function (e) {
                            e.stopPropagation();
                            
                                // 既存の選択を解除
                            if (selectedComment && selectedComment !== commentBox) {
                                selectedComment.style.outline = 'none';
                            }
                            
                                // このコメントを選択状態にする
                            selectedComment = commentBox;
                            selectedComment.style.outline = '2px solid blue';
                            
                                // カラーピッカーの値を更新
                            document.getElementById('text-color').value = rgbToHex(window.getComputedStyle(commentBox).color);
                            document.getElementById('background-color').value = rgbToHex(window.getComputedStyle(commentBox).backgroundColor);
                            
                                // フォントサイズの値を更新
                            const currentFontSize = parseInt(window.getComputedStyle(commentBox).fontSize);
                            fontSize = currentFontSize;
                            updateFontSizeDisplay();
                            
                                // コメントツールを選択状態にする
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
                            
                                // カラーピッカーとフォントサイズツールを表示
                            document.getElementById('comment-color-picker').style.display = 'flex';
                            document.getElementById('line-color-picker').style.display = 'none';
                            document.getElementById('font-size-tools').style.display = 'flex';
                        });
                        
                        let isDraggingComment = false; // コメントドラッグ中フラグ
                        let startDragX, startDragY;
                        let startLeft, startTop;
                        
                            commentBox.addEventListener('mousedown', function (e) {
                                // リサイズハンドルでの操作時は処理しない
                            if (e.target === resizeHandle) return;
                            
                            isDragging = true;
                            startDragX = e.clientX;
                            startDragY = e.clientY;
                            startLeft = parseInt(commentBox.style.left) || 0;
                            startTop = parseInt(commentBox.style.top) || 0;
                            
                                // ドラッグ中のイベント
                            document.addEventListener('mousemove', dragMove);
                            document.addEventListener('mouseup', dragEnd);
                            
                                // カーソルをドラッグ形式に変更
                            commentBox.style.cursor = 'grabbing';
                        });
                        
                        function dragMove(e) {
                            if (!isDraggingComment) return;
                            const dx = e.clientX - startDragX;
                            const dy = e.clientY - startDragY;
                            commentBox.style.left = (startLeft + dx) + 'px';
                            commentBox.style.top = (startTop + dy) + 'px';
                        }
                        
                        function dragEnd() {
                            isDraggingComment = false;
                            document.removeEventListener('mousemove', dragMove);
                            document.removeEventListener('mouseup', dragEnd);
                            commentBox.style.cursor = 'auto';
                        }
                        
                        let isResizing = false; // リサイズ中フラグ
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
                        
                        function resizeEnd() {
                            isResizing = false;
                            document.removeEventListener('mousemove', resizeMove);
                            document.removeEventListener('mouseup', resizeEnd);
                        }
                        
                        currentSvg.parent().append(commentBox);
                        commentBox.appendChild(resizeHandle);
                        
                        selectedComment = commentBox;
                        selectedComment.style.outline = '2px solid blue';
                        commentBox.focus();
                        
                        const selection = window.getSelection();
                        const range = document.createRange();
                        range.selectNodeContents(commentBox);
                        selection.removeAllRanges();
                        selection.addRange(range);
                        
                        isDrawing = false; // コメント追加は連続的な描画アクションではない
                        break;
                    case 'eraser':
                        // 既存の消しゴム開始ロジック
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
                        currentSvg_js.appendChild(eraseRect);
                        break;
                    case 'free-draw':
                        // 自由描画のための新しいパスを初期化
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
                        currentSvg_js.appendChild(currentPathElement);
                        window.linetoStr = `M ${startX} ${startY} `; // 新しいパスのためにlinetoStrをリセット
                        break;
                    default:
                        // ツールが不明な場合の自由描画へのフォールバック
                        currentPathElement = document.createElementNS("http://www.w3.org/2000/svg", "path");
                        const default_path_attr = {
                            "id": Math.random().toString(32).substring(2),
                            "d": `M ${startX} ${startY} `,
                            "fill": "none",
                            "stroke": lineColor,
                            "stroke-width": lineWidth,
                            "stroke-linecap": "round"
                        };
                        for (let key in default_path_attr) {
                            currentPathElement.setAttribute(key, default_path_attr[key]);
                        }
                        currentSvg_js.appendChild(currentPathElement);
                        window.linetoStr = `M ${startX} ${startY} `; // 新しいパスのためにlinetoStrをリセット
                        break;
                }
            }
        });
    }

    // 両方のSVGにイベントリスナーを設定
    setupSvgEvents(leftSvgElm, leftSvgElm_js);
    setupSvgEvents(rightSvgElm, rightSvgElm_js);

    // SVGをまたぐ連続描画のためのグローバル移動イベントリスナー
    var svgCrossings = []; // SVG間の交差点を記録するための変数
    document.body.addEventListener(move_event, function (event) {
        // "pencil"モードの場合のみ描画/消去を処理
        if (type !== "pencil") {
            return;
        }

        if (!isDrawing) return; // 描画アクションが開始されている場合のみ続行
        event.preventDefault(); // デフォルトのブラウザ動作 (例: タッチでのスクロール) を防止

        const newSvgInfo = getSvgFromEvent(event); // 現在マウスがどのSVG上にあるかを判断
        const mouseXGlobal = event.clientX || (event.touches && event.touches[0].clientX);
        const mouseYGlobal = event.clientY || (event.touches && event.touches[0].clientY);

        // マウスが現在描画中のSVGとは異なるSVGに移動した場合
        if (newSvgInfo && newSvgInfo.svg_js !== currentSvg_js) {
            // マウスが新しいSVGに交差した

            let intersectionXGlobal;
            const leavingSvgRect = currentSvg_js.getBoundingClientRect();
            const enteringSvgRect = newSvgInfo.svg_js.getBoundingClientRect();

            // 交差している境界線の正確なグローバルX座標を決定
            if (leavingSvgRect.right < enteringSvgRect.left) { // 左から右へ移動
                intersectionXGlobal = leavingSvgRect.right;
            } else if (leavingSvgRect.left > enteringSvgRect.right) { // 右から左へ移動
                intersectionXGlobal = leavingSvgRect.left;
            } else {
                // SVGが重なっているか、明確な「ギャップ」なしで直接隣接している場合のフォールバック
                intersectionXGlobal = mouseXGlobal;
            }
            const intersectionYGlobal = mouseYGlobal; // Y座標は連続

            // 1. *離れる*SVG上の描画要素を確定
            const leavingSvgCoords = screenToSvgCoords(intersectionXGlobal, intersectionYGlobal, currentSvg_js);
            // プレビューのための交差点を記録
            if (currentTool === 'free-draw' || currentTool === 'line-draw') {
                svgCrossings.push({
                    from: currentSvg_js === leftSvgElm_js ? 'left' : 'right',
                    to: newSvgInfo.svg_js === leftSvgElm_js ? 'left' : 'right',
                    leaving: { x: leavingSvgCoords.x, y: leavingSvgCoords.y, svg: currentSvg_js },
                    entering: null, // 後で埋める
                    global: { x: intersectionXGlobal, y: intersectionYGlobal }
                });
            }

            if (currentTool === 'free-draw' && currentPathElement && currentPathElement.ownerSVGElement === currentSvg_js) {
                window.linetoStr = window.linetoStr + " L " + leavingSvgCoords.x + " " + leavingSvgCoords.y;
                currentPathElement.setAttribute("d", window.linetoStr);
                currentPathElement = null; // このSVG上での描画を終了としてマーク
                window.linetoStr = ''; // 次のパスのためにリセット
            } else if (currentTool === 'line-draw' && tempLine && tempLine.ownerSVGElement === currentSvg_js) {
                tempLine.setAttribute("x2", leavingSvgCoords.x);
                tempLine.setAttribute("y2", leavingSvgCoords.y);
                tempLine = null; // このSVG上での描画を終了としてマーク
            }

            // 2. アクティブなSVGコンテキストを切り替える
            currentSvg = newSvgInfo.svg;
            currentSvg_js = newSvgInfo.svg_js;
            lastSvg_js = currentSvg_js;

            // 3. 交差点から*入る*SVG上で新しい描画要素を開始
            const enteringSvgCoords = screenToSvgCoords(intersectionXGlobal, intersectionYGlobal, currentSvg_js);
            // 交差記録を完了
            if (svgCrossings.length > 0 && svgCrossings[svgCrossings.length-1].entering === null) {
                svgCrossings[svgCrossings.length-1].entering = { x: enteringSvgCoords.x, y: enteringSvgCoords.y, svg: currentSvg_js };
            }

            startX = enteringSvgCoords.x;
            startY = enteringSvgCoords.y;

            if (currentTool === 'free-draw') {
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
                currentSvg_js.appendChild(currentPathElement);
                window.linetoStr = `M ${startX} ${startY} `;
            } else if (currentTool === 'line-draw') {
                tempLine = document.createElementNS("http://www.w3.org/2000/svg", "line");
                tempLine.setAttribute("x1", startX);
                tempLine.setAttribute("y1", startY);
                tempLine.setAttribute("x2", startX); // 次の移動イベントで更新される
                tempLine.setAttribute("y2", startY);
                tempLine.setAttribute("stroke", lineColor);
                tempLine.setAttribute("stroke-width", lineWidth);
                currentSvg_js.appendChild(tempLine);
            }
        } else if (!newSvgInfo && (currentPathElement || tempLine || eraseRect)) {
            // 描画/消去中にマウスが両方のSVGの外に移動した。
            // 最後にアクティブだったSVG上の現在の描画要素を確定する。
            const currentX = getPositionXOnSvg(event, currentSvg, currentSvg_js);
            const currentY = getPositionYOnSvg(event, currentSvg, currentSvg_js);

            if (currentTool === 'line-draw' && tempLine) {
                tempLine.setAttribute("x2", currentX);
                tempLine.setAttribute("y2", currentY);
                tempLine = null;
            } else if (currentTool === 'free-draw' && currentPathElement) {
                currentPathElement = null;
                window.linetoStr = '';
            }
            // 消しゴムロジックはmouseupで処理される
            isDrawing = false; // 描画を停止
            return;
        }

        // 現在の (または新しく切り替わった) SVG上で描画を続行
        const currentX = getPositionXOnSvg(event, currentSvg, currentSvg_js);
        const currentY = getPositionYOnSvg(event, currentSvg, currentSvg_js);

        if (currentTool === 'free-draw' && currentPathElement) {
            window.linetoStr = window.linetoStr + " L " + currentX + " " + currentY;
            currentPathElement.setAttribute("d", window.linetoStr);
        } else if (currentTool === 'line-draw' && tempLine) {
            tempLine.setAttribute("x2", currentX);
            tempLine.setAttribute("y2", currentY);
        } else if (currentTool === 'eraser' && eraseRect) {
            // 消しゴム範囲を更新
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

    // 描画アクションのためのグローバル終了イベントリスナー
    document.body.addEventListener(end_event, function (event) {
        // imageMoveAndZoomモードの場合、その特定のmouseupハンドラーに管理させる。
        if (type === "imageMoveAndZoom") {
            handleImageMoveAndZoomEnd(); // 画像移動・ズームの終了を処理
            return;
        }

        if (!isDrawing && !isMovingLine) return; // 描画/移動アクションがアクティブだった場合のみ続行
        event.preventDefault();

        // 現在のツールに基づいて描画を確定
        if (currentTool === 'line-draw' && tempLine) {
            // 非常に短い線を削除
            const x1 = parseFloat(tempLine.getAttribute("x1"));
            const y1 = parseFloat(tempLine.getAttribute("y1"));
            const x2 = parseFloat(tempLine.getAttribute("x2"));
            const y2 = parseFloat(tempLine.getAttribute("y2"));
            const length = Math.sqrt(Math.pow(x2 - x1, 2) + Math.pow(y2 - y1, 2));
            if (length < 5) {
                tempLine.remove();
            }
            tempLine = null; // tempLine参照をクリア
        } else if (currentTool === 'eraser' && eraseRect) {
            // 現在のSVGのバウンディングクライアント矩形を取得 (eraseRectが描画された場所)
            const currentSvgBounds = currentSvg_js.getBoundingClientRect();

            // グローバルスクリーン座標で消去矩形を計算
            const globalEraseRect = {
                left: currentSvgBounds.left + parseFloat(eraseRect.getAttribute("x")),
                top: currentSvgBounds.top + parseFloat(eraseRect.getAttribute("y")),
                right: currentSvgBounds.left + parseFloat(eraseRect.getAttribute("x")) + parseFloat(eraseRect.getAttribute("width")),
                bottom: currentSvgBounds.top + parseFloat(eraseRect.getAttribute("y")) + parseFloat(eraseRect.getAttribute("height"))
            };

            // 両方のSVGから描画可能なSVG要素をすべて収集
            const allSvgElements = Array.from(leftSvgElm_js.querySelectorAll('path, line')).concat(Array.from(rightSvgElm_js.querySelectorAll('path, line')));

            allSvgElements.forEach(element => {
                // eraseRect自体はスキップ
                if (element === eraseRect) return;

                const ownerSvgElm_js = element.ownerSVGElement;
                const ownerSvgBounds = ownerSvgElm_js.getBoundingClientRect();

                // SVG要素のバウンディングボックスを自身のSVG座標系で取得
                const bbox = element.getBBox();

                // 要素のbboxをグローバルスクリーン座標に変換
                const elementGlobalRect = {
                    left: ownerSvgBounds.left + (bbox.x - parseFloat(ownerSvgElm_js.getAttribute('viewBox').split(' ')[0])) * (ownerSvgBounds.width / parseFloat(ownerSvgElm_js.getAttribute('viewBox').split(' ')[2])),
                    top: ownerSvgBounds.top + (bbox.y - parseFloat(ownerSvgElm_js.getAttribute('viewBox').split(' ')[1])) * (ownerSvgBounds.height / parseFloat(ownerSvgElm_js.getAttribute('viewBox').split(' ')[3])),
                    right: ownerSvgBounds.left + (bbox.x + bbox.width - parseFloat(ownerSvgElm_js.getAttribute('viewBox').split(' ')[0])) * (ownerSvgBounds.width / parseFloat(ownerSvgElm_js.getAttribute('viewBox').split(' ')[2])),
                    bottom: ownerSvgBounds.top + (bbox.y + bbox.height - parseFloat(ownerSvgElm_js.getAttribute('viewBox').split(' ')[1])) * (ownerSvgBounds.height / parseFloat(ownerSvgElm_js.getAttribute('viewBox').split(' ')[3]))
                };

                // 交差をチェック
                if (!(elementGlobalRect.right < globalEraseRect.left ||
                        elementGlobalRect.left > globalEraseRect.right ||
                        elementGlobalRect.bottom < globalEraseRect.top ||
                        elementGlobalRect.top > globalEraseRect.bottom)) {
                    element.remove();
                    if (selectedLine === element) {
                        selectedLine = null;
                        isMovingLine = false;
                    }
                    if (currentPathElement === element) { // 現在描画中のパスが消去された場合
                        currentPathElement = null;
                        window.linetoStr = '';
                    }
                }
            });

            // コメントボックスはすでにHTML要素なので、getBoundingClientRectは直接グローバル座標で取得できる
            const commentBoxes = document.querySelectorAll('.comment-box');
            commentBoxes.forEach(box => {
                const boxRect = box.getBoundingClientRect();
                if (!(boxRect.right < globalEraseRect.left ||
                        boxRect.left > globalEraseRect.right ||
                        boxRect.bottom < globalEraseRect.top ||
                        boxRect.top > globalEraseRect.bottom)) {
                    box.remove();
                    if (selectedComment === box) {
                        selectedComment = null;
                    }
                }
            });

            eraseRect.remove();
            eraseRect = null; // eraseRect参照をクリア
        }

        // 描画状態をリセット
        isDrawing = false;
        isMovingLine = false;
        selectedLine = null; // 移動中の線を非選択にする
        currentPathElement = null; // パス要素参照をクリア
        tempLine = null; // tempLine参照をクリア
        lastSvg_js = null; // mouseup時に最後のSVGをリセット
    });

    // SVGのクリックイベントを修正して、コメント選択を解除する
    [leftSvgElm, rightSvgElm].forEach(svgElm => {
        svgElm.on('click', function(event) {
            // クリックターゲットがコメントボックスまたはそのリサイズハンドルである場合、非選択にしない
            if (event.target.classList && (event.target.classList.contains('comment-box') || 
                                            event.target.classList.contains('comment-resize-handle'))) {
                return;
            }
            
            // コメント選択を解除
            if (selectedComment) {
                selectedComment.style.outline = 'none';
                selectedComment = null;
            }
        });
    });

    var browserDrawingData = {};
    var browserDrawData = {};

    // 両方のSVGを初期化
    function initializeSvg(svgElm_js) {
        var svgElm_js_height = svgElm_js.clientHeight
        var svgElm_js_width = svgElm_js.clientWidth
        var init_viewBox = "0 0 " + String(svgElm_js_width) + " " + String(svgElm_js_height)
        svgElm_js.setAttribute("viewBox", init_viewBox)
    }

    // Initial SVG initialization (before images are loaded)
    initializeSvg(leftSvgElm_js);
    initializeSvg(rightSvgElm_js);

    function resizeWindow() {
        [leftSvgElm_js, rightSvgElm_js].forEach(svgElm_js => {
            var svgElm_js_height = svgElm_js.clientHeight
            var svgElm_js_width = svgElm_js.clientWidth
            var [minX, minY, width, height] = svgElm_js.getAttribute('viewBox').split(' ')
                .map(s => parseFloat(s));
            var init_viewBox = String(minX) + " " + String(minY) + " " + String(svgElm_js_width) + " " + String(svgElm_js_height)
            svgElm_js.setAttribute("viewBox", init_viewBox)
        });
    }
    window.onresize = resizeWindow;

    const clipRightRect = document.getElementById("clip-right-rect")
    const clipLeftRect = document.getElementById("clip-left-rect")

    function init() {
        // 修正：個別のSVGに合わせてクリップ領域を調整
        const leftSvgWidth = leftSvgElm_js.clientWidth;
        const rightSvgWidth = rightSvgElm_js.clientWidth;
        const margin = 20;
        
        clipLeftRect.width.baseVal.value = leftSvgWidth - margin * 2;
        clipRightRect.width.baseVal.value = rightSvgWidth - margin * 2;
        clipLeftRect.x.baseVal.value = margin;
        clipRightRect.x.baseVal.value = margin;
        clipLeftRect.y.baseVal.value = margin;
        clipRightRect.y.baseVal.value = margin;
        clipLeftRect.height.baseVal.value = 360;
        clipRightRect.height.baseVal.value = 360;
    }

    init();

    function pencilByMousedown(event) {
        movetoX = parseInt(getPositionXOnSvg(event, currentSvg, currentSvg_js));
        movetoY = parseInt(getPositionYOnSvg(event, currentSvg, currentSvg_js));

        id = Math.random().toString(32).substring(2);

            // 自由入力の色を赤色に固定
        browserDrawData = {
            "id": id,
            'type': "draw",
            "d": "",
            "fill": "none",
                "stroke": "red", // 色を赤色に固定
            "strokeSize": lineWidth,
            "stroke-linecap": "round",
            "x": 0,
            "y": 0
        };
        
        var pathElm = document.createElementNS("http://www.w3.org/2000/svg", "path");
        var path_attr = {
            "id": browserDrawData.id,
            "d": "",
            "fill": "none",
            "stroke": browserDrawData.stroke,
            "stroke-width": browserDrawData.strokeSize,
            "stroke-linecap": "round"
        };
        
        for (let key in path_attr) {
            pathElm.setAttribute(key, path_attr[key]);
        }

        currentSvg_js.appendChild(pathElm);
        
            // グローバルスコープで使えるように変数を初期化
        window.linetoStr = 'M ' + movetoX + ' ' + movetoY + ' ';
    }

    function pencilByMouseup(event) {
        // イベントリスナーの削除処理を削除（main内で処理されるため）
    }

    function getViewBox(svgElm_js) {
        var [minX, minY, width, height] = svgElm_js.getAttribute('viewBox')
            .split(' ')
            .map(s => parseFloat(s));
        return [minX, minY, width, height]
    }

    function getPageX(e) {
        var pageX = 0;
        if (e.changedTouches) {
            pageX = e.changedTouches[0].pageX;
        } else {
            pageX = e.pageX;
        }
        return pageX;
    }

    // SVGを指定できるように変更
    function getPositionXOnSvg(e, svgElm, svgElm_js) {
        var x = getPageX(e)
        var left = svgElm.position().left
        var svg_w = svgElm_js.clientWidth
        var viewBox = getViewBox(svgElm_js)
        var pageX_aspect = viewBox[2] / svg_w
        var viewBox_x = viewBox[0]
        var result = (x - left) * pageX_aspect + viewBox_x;
        return result
    }

    function getPageY(e) {
        var pageY = 0;
        if (e.changedTouches) {
            pageY = e.changedTouches[0].pageY;
        } else {
            pageY = e.pageY;
        }
        return pageY;
    }

    // SVGを指定できるように変更
    function getPositionYOnSvg(e, svgElm, svgElm_js) {
        var y = getPageY(e)
        var top = svgElm.position().top
        var svg_w = svgElm_js.clientWidth
        var viewBox = getViewBox(svgElm_js)
        var svg_h = svgElm_js.clientHeight
        var pageY_aspect = viewBox[3] / svg_h
        var viewBox_y = viewBox[1]
        var result = (y - top) * pageY_aspect + viewBox_y;
        return result
    }

    // タッチ座標変換のためのヘルパー関数
    function getPositionXOnSvgByX(pageX, svgElm, svgElm_js) {
        var left = svgElm.position().left
        var svg_w = svgElm_js.clientWidth
        var viewBox = getViewBox(svgElm_js)
        var pageX_aspect = viewBox[2] / svg_w
        var viewBox_x = viewBox[0]
        var result = (pageX - left) * pageX_aspect + viewBox_x;
        return result;
    }

    function getPositionYOnSvgByY(pageY, svgElm, svgElm_js) {
        var top = svgElm.position().top
        var svg_w = svgElm_js.clientWidth
        var viewBox = getViewBox(svgElm_js)
        var svg_h = svgElm_js.clientHeight
        var pageY_aspect = viewBox[3] / svg_h
        var viewBox_y = viewBox[1]
        var result = (pageY - top) * pageY_aspect + viewBox_y;
        return result;
    }

    function createImage(cx, cy, x, y, w, h, href, id, clipPath, targetSvg) {
        var image_elm = document.createElementNS("http://www.w3.org/2000/svg", "image");
        image_elm.setAttribute("href", href);
        image_elm.setAttribute("id", id);
        image_elm.setAttribute("width", w);
        image_elm.setAttribute("height", h);
        image_elm.setAttribute("x", cx);
        image_elm.setAttribute("y", cy);
        image_elm.setAttribute("clip-path", clipPath)
        image_elm.setAttribute("crossorigin", "anonymous");
        var transform = image_elm.transform.baseVal.createSVGTransformFromMatrix(SVGMatrixCreate())
        if (!image_elm.hasAttribute("transform")) {
            image_elm.transform.baseVal.appendItem(transform);
        }
        var Matrix = image_elm.transform.baseVal[0].matrix;
        Matrix.e += x;
        Matrix.f += y;
        targetSvg.appendChild(image_elm);
        initImageLoad()
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

    // Unified function to display an image in an SVG
    function displayImageInSvg(imgElement, svgElm, svgElm_js, imageId, clipPathId, aspectRatio, dateTextContent, side, actualImageHeight) {
        const svgWidth = svgElm_js.clientWidth;
        const imageWidth = svgWidth - 40; // 20px left + 20px right margin
        
        const x = 20; // Left margin for the image within SVG
        const y = 20; // Top margin for the image within SVG

        // Update viewBox to match the new SVG height
        svgElm_js.setAttribute("viewBox", `0 0 ${svgWidth} ${finalSvgHeight}`);

        // Convert image to base64 for SVG embedding
        const canvas = document.createElement('canvas');
        const ctx = canvas.getContext('2d');
        canvas.width = imgElement.naturalWidth;
        canvas.height = imgElement.naturalHeight;
        ctx.drawImage(imgElement, 0, 0);
        const base64ImageData = canvas.toDataURL('image/png');

        // Remove existing image if any
        const existingImage = document.getElementById(imageId);
        if (existingImage) {
            existingImage.remove();
        }

        createImage(x, y, 0, 0, imageWidth, actualImageHeight, base64ImageData, imageId, clipPathId, svgElm_js);

        // Update or create date text
        let dateTextDiv = document.getElementById(`${side}-date-text`);
        if (!dateTextDiv) {
            dateTextDiv = document.createElement('div');
            dateTextDiv.className = 'date-text';
            dateTextDiv.id = `${side}-date-text`;
            dateTextDiv.style.position = 'absolute';
            dateTextDiv.style.left = '50%';
            dateTextDiv.style.transform = 'translateX(-50%)';
            dateTextDiv.style.font = "bold 18px sans-serif"; // Keep original font styles
            dateTextDiv.style.fontSize = '16px'; // Keep original font size
            dateTextDiv.style.color = 'black'; // Keep original color
            svgElm.parent().append(dateTextDiv);
        }
        dateTextDiv.textContent = dateTextContent;
        dateTextDiv.style.bottom = '10px'; // Position relative to the bottom of the figure
    }


    function loadImage(url) {
        return new Promise((resolve, reject) => {
            const image = new Image();
                image.crossOrigin = "anonymous"; // CORS 問題を回避するために追加
            image.onload = () => resolve(image);
            image.onerror = () => reject(new Error(`Failed to load image: ${url}`));
            image.src = url;
        });
    }

    // 修正：両方のSVGを結合してプレビュー画像を作成
    async function previewImage() {
        try {
            const leftSvg = document.getElementById('leftDrawingCanvas');
            const rightSvg = document.getElementById('rightDrawingCanvas');

            await Promise.all([
                loadImage(document.getElementById("target_image_url").src),
                loadImage(document.getElementById("subject_image_url").src)
            ]);
            
            // 両方のSVGデータを取得
            const leftSvgData = new XMLSerializer().serializeToString(leftSvg);
            const rightSvgData = new XMLSerializer().serializeToString(rightSvg);
            
            const canvas = document.createElement("canvas");
            
            // キャンバスサイズを設定（両方のSVGを横に並べる）
            const leftWidth = leftSvg.clientWidth;
            const rightWidth = rightSvg.clientWidth;
            const leftHeight = leftSvg.clientHeight;
            const rightHeight = rightSvg.clientHeight;
            const maxHeight = Math.max(leftHeight, rightHeight);
            
            const pixelRatio = window.devicePixelRatio || 1;
            
            canvas.width = (leftWidth + rightWidth + 20) * pixelRatio;
            canvas.height = (maxHeight + dateAreaHeight) * pixelRatio;
            canvas.style.width = (leftWidth + rightWidth + 20) + "px";
            canvas.style.height = (maxHeight + dateAreaHeight) + "px";

            const ctx = canvas.getContext("2d");
            ctx.setTransform(pixelRatio, 0, 0, pixelRatio, 0, 0);
            
            // 背景を白で塗りつぶす
            ctx.fillStyle = 'white';
            ctx.fillRect(0, 0, leftWidth + rightWidth + 20, maxHeight + dateAreaHeight);
            
            // 左のSVGを描画
            const leftSvgBlob = new Blob([leftSvgData], { type: "image/svg+xml;charset=utf-8" });
            const leftUrl = URL.createObjectURL(leftSvgBlob);
            const leftImage = await loadImage(leftUrl);
            ctx.drawImage(leftImage, 0, 0, leftWidth, leftHeight);
            URL.revokeObjectURL(leftUrl);
            
            const rightSvgBlob = new Blob([rightSvgData], { type: "image/svg+xml;charset=utf-8" });
            const rightUrl = URL.createObjectURL(rightSvgBlob);
            const rightImage = await loadImage(rightUrl);
            ctx.drawImage(rightImage, leftWidth + 20, 0, rightWidth, rightHeight);
            URL.revokeObjectURL(rightUrl);

            // Each image's date text
            const leftDate = document.getElementById("target-image-date").value;
            const rightDate = document.getElementById("subject-image-date").value;
            ctx.font = "bold 18px sans-serif";
            ctx.fillStyle = "black";
            ctx.textAlign = "center";
            const dateY = maxHeight + 25; // Position below the SVG content
            ctx.fillText(leftDate, leftWidth / 2, dateY);
            ctx.fillText(rightDate, leftWidth + 20 + rightWidth / 2, dateY);

            // Comment drawing code (existing logic)
            const commentBoxes = document.querySelectorAll('.comment-box');
            const leftSvgRect = leftSvg.getBoundingClientRect();
            const rightSvgRect = rightSvg.getBoundingClientRect();

            commentBoxes.forEach(comment => {
                const commentRect = comment.getBoundingClientRect();
                
                let commentLeft, commentTop;
                
                if (commentRect.left >= leftSvgRect.left && commentRect.left <= leftSvgRect.right) {
                    commentLeft = commentRect.left - leftSvgRect.left;
                    commentTop = commentRect.top - leftSvgRect.top;
                } else if (commentRect.left >= rightSvgRect.left && commentRect.left <= rightSvgRect.right) {
                    commentLeft = (commentRect.left - rightSvgRect.left) + leftWidth + 20;
                    commentTop = commentRect.top - rightSvgRect.top;
                } else {
                    return;
                }
                
                const commentWidth = comment.offsetWidth;
                const commentHeight = comment.offsetHeight;
                const padding = 16; // From .comment-box CSS: p-4 = 16px
                
                // Draw comment background
                ctx.fillStyle = window.getComputedStyle(comment).backgroundColor;
                ctx.fillRect(commentLeft, commentTop, commentWidth, commentHeight);
                
                // Draw comment border if any
                const borderWidth = parseInt(window.getComputedStyle(comment).borderWidth) || 0;
                if (borderWidth > 0) {
                    ctx.strokeStyle = window.getComputedStyle(comment).borderColor;
                    ctx.lineWidth = borderWidth;
                    ctx.strokeRect(commentLeft, commentTop, commentWidth, commentHeight);
                }
                
                // Draw blue outline for selected comment
                if (comment.style.outline && comment.style.outline.includes('solid blue')) {
                    ctx.strokeStyle = 'blue';
                    ctx.lineWidth = 2;
                    ctx.strokeRect(commentLeft - 1, commentTop - 1, commentWidth + 2, commentHeight + 2);
                }
                
                // コメントのテキストを描画
                const fontSize = window.getComputedStyle(comment).fontSize;
                const fontFamily = window.getComputedStyle(comment).fontFamily;
                ctx.font = `${fontSize} ${fontFamily}`;
                ctx.fillStyle = window.getComputedStyle(comment).color;
                ctx.textBaseline = 'top';
                
                    // テキストの内容を取得し、改行で分割
                const text = comment.innerText;
                const lines = text.split(/\r?\n/);
                
                    // 各行のテキストを描画（単純な行分割）
                    const lineHeight = parseInt(fontSize) * 1.2; // おおよその行の高さ
                    let yPos = commentTop + 8; // パディングの分だけオフセット
                    
                    ctx.textAlign = 'center'; // 中央寄せを設定
                lines.forEach(line => {
                        ctx.fillText(line, commentLeft + commentWidth / 2, yPos); // パディングの分だけオフセット
                    yPos += lineHeight;
                });
            });

            // PNGとして出力
            const pngDataUrl = canvas.toDataURL('image/png');
            document.getElementById("preview").src = pngDataUrl;
            document.getElementById("preview").style.display = "block";
        } catch (error) {
            console.error('Error converting SVG to canvas for preview:', error);
        }
    }

    async function initImageLoad() {
        // 両方のSVGを初期化
        [leftSvgElm_js, rightSvgElm_js].forEach(async (svg) => {
            var svgData = new XMLSerializer().serializeToString(svg);
            var canvas = document.createElement("canvas");
            canvas.width = svg.clientWidth;
            canvas.height = svg.clientHeight;

            const ctx = canvas.getContext("2d");
            const svgBlob = new Blob([svgData], { type: "image/svg+xml;charset=utf-8" });
            const url = URL.createObjectURL(svgBlob);

            const image = await loadImage(url);
            ctx.drawImage(image, 0, 0);
            URL.revokeObjectURL(url);

            const pngDataUrl = canvas.toDataURL('image/png');
        });
    }

    // 修正：両方のSVGを結合してダウンロード用画像を作成
    async function downloadBoard() {
        try {
            const leftSvg = document.getElementById('leftDrawingCanvas');
            const rightSvg = document.getElementById('rightDrawingCanvas');

            // Both SVGs must be fully rendered with images before capturing
            await Promise.all([
                loadImage(document.getElementById("target_image_url").src),
                loadImage(document.getElementById("subject_image_url").src)
            ]);
            
            const leftSvgData = new XMLSerializer().serializeToString(leftSvg);
            const rightSvgData = new XMLSerializer().serializeToString(rightSvg);
            
            const commentBoxes = document.querySelectorAll('.comment-box');
            
            const canvas = document.createElement("canvas");
            
            const leftWidth = leftSvg.clientWidth;
            const rightWidth = rightSvg.clientWidth;
            const leftHeight = leftSvg.clientHeight;
            const rightHeight = rightSvg.clientHeight;
            const maxHeight = Math.max(leftHeight, rightHeight);
            
            const pixelRatio = 0.75; // 解像度を半分に下げる
            
            canvas.width = (leftWidth + rightWidth + 20) * pixelRatio; // 20px gap
            canvas.height = (maxHeight + dateAreaHeight) * pixelRatio;
            canvas.style.width = (leftWidth + rightWidth + 20) + "px";
            canvas.style.height = (maxHeight + dateAreaHeight) + "px";

                // 2Dコンテキストを取得して高解像度対応
            const ctx = canvas.getContext("2d");
            ctx.setTransform(pixelRatio, 0, 0, pixelRatio, 0, 0);
            
            // 背景を白で塗りつぶす
            ctx.fillStyle = 'white';
            ctx.fillRect(0, 0, leftWidth + rightWidth + 20, maxHeight + dateAreaHeight);
            
            // 左のSVGを描画
            const leftSvgBlob = new Blob([leftSvgData], { type: "image/svg+xml;charset=utf-8" });
            const leftUrl = URL.createObjectURL(leftSvgBlob);
            const leftImage = await loadImage(leftUrl);
            ctx.drawImage(leftImage, 0, 0, leftWidth, leftHeight);
            URL.revokeObjectURL(leftUrl);
            
            // 右のSVGを描画
            const rightSvgBlob = new Blob([rightSvgData], { type: "image/svg+xml;charset=utf-8" });
            const rightUrl = URL.createObjectURL(rightSvgBlob);
            const rightImage = await loadImage(rightUrl);
            ctx.drawImage(rightImage, leftWidth + 20, 0, rightWidth, rightHeight);
            URL.revokeObjectURL(rightUrl);

            // コメント要素を描画
            const leftSvgRect = leftSvg.getBoundingClientRect();
            const rightSvgRect = rightSvg.getBoundingClientRect();
            
            commentBoxes.forEach((comment) => {
                const commentRect = comment.getBoundingClientRect();
                
                let commentLeft, commentTop;
                
                if (commentRect.left >= leftSvgRect.left && commentRect.left <= leftSvgRect.right) {
                    commentLeft = commentRect.left - leftSvgRect.left;
                    commentTop = commentRect.top - leftSvgRect.top;
                } else if (commentRect.left >= rightSvgRect.left && commentRect.left <= rightSvgRect.right) {
                    commentLeft = (commentRect.left - rightSvgRect.left) + leftWidth + 20;
                    commentTop = commentRect.top - rightSvgRect.top;
                } else {
                    return;
                }
                
                const commentWidth = comment.offsetWidth;
                const commentHeight = comment.offsetHeight;
                const padding = 16; // From .comment-box CSS: p-4 = 16px
                
                // コメントの背景を描画
                ctx.fillStyle = window.getComputedStyle(comment).backgroundColor;
                ctx.fillRect(commentLeft, commentTop, commentWidth, commentHeight);
                
                // コメントの境界線（あれば）
                const borderWidth = parseInt(window.getComputedStyle(comment).borderWidth) || 0;
                if (borderWidth > 0) {
                    ctx.strokeStyle = window.getComputedStyle(comment).borderColor;
                    ctx.lineWidth = borderWidth;
                    ctx.strokeRect(commentLeft, commentTop, commentWidth, commentHeight);
                }
                
                // Draw blue outline for selected comment (if outline exists)
                if (comment.style.outline && comment.style.outline.includes('solid blue')) {
                    ctx.strokeStyle = 'blue';
                    ctx.lineWidth = 2;
                    ctx.strokeRect(commentLeft - 1, commentTop - 1, commentWidth + 2, commentHeight + 2);
                }
                
                // コメントのテキストを描画
                const fontSize = window.getComputedStyle(comment).fontSize;
                const fontFamily = window.getComputedStyle(comment).fontFamily;
                ctx.font = `${fontSize} ${fontFamily}`;
                ctx.fillStyle = window.getComputedStyle(comment).color;
                ctx.textBaseline = 'top';
                
                    // テキストの内容を取得し、改行で分割
                const text = comment.innerText;
                const lines = text.split(/\r?\n/);
                
                    // 各行のテキストを描画（単純な行分割）
                    const lineHeight = parseInt(fontSize) * 1.2; // おおよその行の高さ
                    let yPos = commentTop + 8; // パディングの分だけオフセット
                    
                    ctx.textAlign = 'center'; // 中央寄せを設定

                lines.forEach(line => {
                        ctx.fillText(line, commentLeft + commentWidth / 2, yPos); // パディングの分だけオフセット
                    yPos += lineHeight;
                });
            });

            // PNGとして出力
            const pngDataUrl = canvas.toDataURL('image/png');
            uploadImageToServer(pngDataUrl);
        } catch (error) {
            console.error('Error during SVG to canvas conversion:', error);
        }
    }

    var previewFlag = false;

    initImageLoad();
    document.getElementById("preview-btn").addEventListener("click", async function () {
        if (!previewFlag) {
            await previewImage()
            this.classList.add("btn-primary");
            this.classList.remove("btn-outline-primary")
            this.textContent = "画像プレビューOFF";
        } else {
            document.getElementById("preview").style.display = "none";
            this.classList.remove("btn-primary");
            this.classList.add("btn-outline-primary")
            this.textContent = "画像プレビューON";
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
                document.getElementById("error").innerHTML = data
                location.href = nextURL
            },
            error: function (e) {
                document.getElementById("error").innerHTML = e.responseText
            }
        });
    }

    // 画像の移動処理を両方のSVGに対応
    function moveContentByMousedown(event) {
        const svgInfo = getSvgFromEvent(event);
        if (!svgInfo) return;

        const currentPointerX = parseInt(getPositionXOnSvg(event, svgInfo.svg, svgInfo.svg_js));
        const currentPointerY = parseInt(getPositionYOnSvg(event, svgInfo.svg, svgInfo.svg_js));

        const margin = 20;
        const svgWidth = svgInfo.svg_js.clientWidth;
        const svgHeight = svgInfo.svg_js.clientHeight;

        let targetImage = null;
        // SVG内の画像領域をクリックしたかチェック
        if (currentPointerX > margin && currentPointerX < svgWidth - margin &&
            currentPointerY > margin && currentPointerY < svgHeight - margin) {
            
            if (svgInfo.side === 'left') {
                targetImage = document.getElementById("left-image");
            } else {
                targetImage = document.getElementById("right-image"); 
            }
        }

        if (!targetImage) return;

        isDraggingImage = true;
        imageDragTarget = targetImage;
        imageDragStartX = currentPointerX;
        imageDragStartY = currentPointerY;
        imageDragMatrix = imageDragTarget.transform.baseVal.getItem(0).matrix;

        svgInfo.svg.css('cursor', 'grabbing'); 
    }

    // 画像移動・ズーム中のmoveイベントハンドラー
    function handleImageMoveAndZoomMove(event) {
        if (!isDraggingImage && !isSingleFingerZooming && !(event.touches && event.touches.length === 2)) return;
        event.preventDefault(); 

        const svgInfo = getSvgFromEvent(event);
        if (!svgInfo || (imageDragTarget && svgInfo.svg_js !== imageDragTarget.ownerSVGElement)) {
            return;
        }

        const currentPointerX = parseInt(getPositionXOnSvg(event, svgInfo.svg, svgInfo.svg_js));
        const currentPointerY = parseInt(getPositionYOnSvg(event, svgInfo.svg, svgInfo.svg_js));

        if (event.type !== "touchmove") { // Mouse move (always move mode)
            const x_length = currentPointerX - imageDragStartX;
            const y_length = currentPointerY - imageDragStartY;

            imageDragMatrix.e += x_length;
            imageDragMatrix.f += y_length;
            imageDragTarget.transform.baseVal.getItem(0).setMatrix(imageDragMatrix);

            imageDragStartX = currentPointerX;
            imageDragStartY = currentPointerY;
        } else { // Touch move
            if (event.touches.length === 2) { // 2-finger pinch
                const page_x1 = event.touches[0].pageX;
                const page_y1 = event.touches[0].pageY;
                const page_x2 = event.touches[1].pageX;
                const page_y2 = event.touches[1].pageY;
                const distance = Math.sqrt(Math.pow(page_x2 - page_x1, 2) + Math.pow(page_y2 - page_y1, 2));
                if (baseDistance !== 0) {
                    const scale = distance / baseDistance;
                    let matrix = imageDragTarget.transform.baseVal.getItem(0).matrix;
                    matrix = matrix.translate(-pointX, -pointY);
                    matrix = matrix.scale(scale);
                    matrix = matrix.translate(pointX, pointY);
                    imageDragTarget.transform.baseVal.getItem(0).setMatrix(matrix);
                }
                baseDistance = distance;
            } else if (event.touches.length === 1 && touchType === "move") { // 1-finger move (pan)
                const x_length = currentPointerX - imageDragStartX;
                const y_length = currentPointerY - imageDragStartY;
                
                imageDragMatrix.e += x_length;
                imageDragMatrix.f += y_length;
                imageDragTarget.transform.baseVal.getItem(0).setMatrix(imageDragMatrix);
                
                imageDragStartX = currentPointerX;
                imageDragStartY = currentPointerY;
            } else if (event.touches.length === 1 && touchType === "zoom" && isSingleFingerZooming) { // 1-finger vertical drag zoom
                const y_length = currentPointerY - singleFingerZoomStartY;
                // Scale based on vertical movement (up to zoom out, down to zoom in)
                const scale = y_length < 0 ? 0.99 : 1.01; 

                let matrix = imageDragTarget.transform.baseVal.getItem(0).matrix;

                // Zoom around the initial touch point (pointX, pointY)
                matrix = matrix.translate(-pointX, -pointY);
                matrix = matrix.scale(scale);
                matrix = matrix.translate(pointX, pointY);

                imageDragTarget.transform.baseVal.getItem(0).setMatrix(matrix);
                
                // Update singleFingerZoomStartY for continuous zooming
                singleFingerZoomStartY = currentPointerY;
            }
        }
    }

    // 画像移動・ズームの終了イベントハンドラー
    function handleImageMoveAndZoomEnd() {
        isDraggingImage = false;
        isSingleFingerZooming = false; 
        baseDistance = 0; 
        if (imageDragTarget) {
            $(imageDragTarget.ownerSVGElement).css('cursor', 'default'); 
        }
        imageDragTarget = null;
        // Remove global listeners
        document.body.removeEventListener(move_event, handleImageMoveAndZoomMove);
        document.body.removeEventListener(end_event, handleImageMoveAndZoomEnd);
    }

    var wheel_event = 'ontouchstart' in window ? "touchmove" : "wheel";
    // 修正：ズーム機能を両方のSVGに対応
    function zoomAction() {
        [leftSvgElm_js, rightSvgElm_js].forEach((svgElm_js, index) => {
            svgElm_js.addEventListener(wheel_event, function (e) {
                // Do not allow zoom in "pencil" mode
                if (type === "pencil") return;
                
                // If it's a touch event, it's handled by handleImageMoveAndZoomMove, so do nothing here
                if (e.touches && e.touches.length > 0) {
                    return;
                }

                // Only process for mouse wheel
                if (e.type === "wheel") {
                    e.preventDefault(); // Prevent default scroll

                    const dy = e.deltaY;
                    const scale = dy < 0 ? 1.05 : 0.95; // Adjust mouse wheel sensitivity
                    
                    const svgElm = index === 0 ? leftSvgElm : rightSvgElm;
                    let target = null;
                    const margin = 20;
                    const svgWidth = svgElm_js.clientWidth;
                    const svgHeight = svgElm_js.clientHeight;

                    // Get mouse position in SVG coordinates
                    const pointer_x = getPositionXOnSvg(e, svgElm, svgElm_js);
                    const pointer_y = getPositionYOnSvg(e, svgElm, svgElm_js);

                    if (pointer_x > margin && pointer_x < svgWidth - margin &&
                        pointer_y > margin && pointer_y < svgHeight - margin) {
                        if (index === 0) {
                            target = document.getElementById("left-image");
                        } else {
                            target = document.getElementById("right-image");
                        }
                    }

                    if (target) {
                        let imageTransform = target.transform.baseVal.getItem(0);
                        let matrix = imageTransform.matrix;

                        // Translate to origin, scale, then translate back
                        matrix = matrix.translate(-pointer_x, -pointer_y);
                        matrix = matrix.scale(scale);
                        matrix = matrix.translate(pointer_x, pointer_y);

                        imageTransform.setMatrix(matrix);
                    }
                }
            }, { passive: false }); // Ensure passive: false for preventDefault
        });
    }
    zoomAction()

    let leftRotation = 0;
    let rightRotation = 0;

    // 左の画像：10度左回転
    document.getElementById("rotate-left-image-left").addEventListener("click", function () {
        leftRotation = (leftRotation - 10 + 360) % 360;
        const leftImage = document.getElementById("left-image");
        if (leftImage) {
            applyRotationTransform(leftImage, leftRotation);
        }
    });

    // 左の画像：10度右回転
    document.getElementById("rotate-left-image-right").addEventListener("click", function () {
        leftRotation = (leftRotation + 10) % 360;
        const leftImage = document.getElementById("left-image");
        if (leftImage) {
            applyRotationTransform(leftImage, leftRotation);
        }
    });

    // 右の画像：10度左回転
    document.getElementById("rotate-right-image-left").addEventListener("click", function () {
        rightRotation = (rightRotation - 10 + 360) % 360;
        const rightImage = document.getElementById("right-image");
        if (rightImage) {
            applyRotationTransform(rightImage, rightRotation);
        }
    });

    // 右の画像：10度右回転
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
        let rotateTransform = leftSvgElm_js.createSVGTransform();

        rotateTransform.setRotate(angle, cx, cy);

        if (transformList.numberOfItems > 1) {
            transformList.removeItem(1);
        }
        transformList.appendItem(rotateTransform);
    }

    // 点と線分の最短距離を計算する関数
    function distanceToLine(x, y, x1, y1, x2, y2) {
        const A = x - x1;
        const B = y - y1;
        const C = x2 - x1;
        const D = y2 - y1;

        const dot = A * C + B * D;
        const len_sq = C * C + D * D;
        let param = -1;
        
            if (len_sq !== 0) { // 線分の長さが0でない場合
            param = dot / len_sq;
        }

        let xx, yy;

        if (param < 0) {
            xx = x1;
            yy = y1;
        } else if (param > 1) {
            xx = x2;
            yy = y2;
        } else {
            xx = x1 + param * C;
            yy = y1 + param * D;
        }

        const dx = x - xx;
        const dy = y - yy;
        
        return Math.sqrt(dx * dx + dy * dy);
    }

    // RGB色をHEX形式に変換する関数
    function rgbToHex(rgb) {
            // すでにHEX形式の場合はそのまま返す
        if (rgb.startsWith('#')) {
            return rgb;
        }
        
            // rgb(r, g, b)形式から数値を抽出
            const rgbMatch = rgb.match(/^rgb\s*\(\s*(\d+)\s*,\s*(\d+)\s*,\s*(\d+)\s*\)$/i);
        if (rgbMatch) {
            const r = parseInt(rgbMatch[1], 10);
            const g = parseInt(rgbMatch[2], 10);
            const b = parseInt(rgbMatch[3], 10);
            return '#' + ((1 << 24) + (r << 16) + (g << 8) + b).toString(16).slice(1);
        }
        
            // 変換できない場合はデフォルト値を返す
        return '#000000';
    }
});
</script>
