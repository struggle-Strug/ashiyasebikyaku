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
        gap: 0; /* Remove gap between images */
        flex-wrap: nowrap; /* Prevent wrapping to keep images on same line */
        align-items: flex-start;
    }

    .image-figure {
        flex: 1;
        min-width: 300px;
        position: relative;
        border: solid 1px rgba(0,0,0,0.1);
        background-color: white;
        padding-bottom: 30px; /* Add space for date text */
    }

    @media only screen and (max-width: 767px) {
        .image-container {
            gap: 0; /* Remove gap between images on mobile */
        }
        
        .image-figure {
            min-width: 50%; /* Make each image take up exactly half the width */
            max-width: 50%;
            flex: 1;
            padding-bottom: 40px; /* Add more space for date text on mobile */
        }
        
        /* Reduce SVG height on mobile for better fit */
        .image-figure svg {
            height: 300px !important; /* Reduce from 400px to 300px on mobile */
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
                <textarea class="form-control" rows="5" name="admin_comment" id="admin_comment"></textarea>
                <span class="text-danger">
                    <?= $this->Form->error('admin_comment') ?>
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
<img src="<?= $targetImage["image_url"] ?>" style="display: none" id="target_image_url" crossorigin="anonymous" />
<img src="<?= $subjectImage["image_url"] ?>" style="display: none" id="subject_image_url" crossorigin="anonymous" />
<div id="error"></div>
<input type="hidden" value="">
<?php if ($targetImage["created"] === null): ?>
    <input type="hidden" value="" id="target-image-date">
<?php else: ?>
    <input type="hidden" value="<?= $targetImageDate->format('Y年m月d日') ?>" id="target-image-date">
<?php endif ?>
<?php if ($subjectImage["created"] === null): ?>
    <input type="hidden" value="" id="subject-image-date">
<?php else: ?>
    <input type="hidden" value="<?= $subjectImageDate->format('Y年m月d日') ?>" id="subject-image-date">
<?php endif ?>

<script defer>
    $(function () {
        let csrf = $('#ajax-form').find('input[name=_csrfToken]').val();

        var start_event = 'ontouchstart' in window ? "touchstart" : "mousedown";
        var move_event = 'ontouchstart' in window ? "touchmove" : "mousemove";
        var end_event = 'ontouchstart' in window ? "touchend" : "mouseup";
        var out_event = "mouseleave";
        
        // 修正：両方のSVG要素を取得
        var leftSvgElm = $("#leftDrawingCanvas");
        var rightSvgElm = $("#rightDrawingCanvas");
        var leftSvgElm_js = document.getElementById("leftDrawingCanvas");
        var rightSvgElm_js = document.getElementById("rightDrawingCanvas");
        var currentSvg = null; // 現在操作中のSVG
        var currentSvg_js = null;
        
        var type = "imageMoveAndZoom";
        var touchType = "move";
        
        // ツール関連の変数
        var currentTool = "free-draw";
        var isDrawing = false;
        var startX, startY;
        var tempLine = null;
        var textColor = "#000000";
        var backgroundColor = "#ffffff";
        var lineColor = "#ff0000";
        var lineWidth = 3;
        var selectedComment = null;
        var fontSize = 14;

        // 直線移動のための変数を追加
        var isMovingLine = false;
        var selectedLine = null;
        var lineStartX, lineStartY;
        var lineOffsetX, lineOffsetY;

        // 消しゴム機能用のグローバル変数を追加
        var eraseRect = null;
        var eraseStartX, eraseStartY;

        // 初期化時にカラーピッカーの値を設定
        $(document).ready(function() {
            document.getElementById('line-color').value = lineColor;
           
            // ローカルストレージから色を復元（存在する場合）
            if (localStorage.getItem('lineColor')) {
                lineColor = localStorage.getItem('lineColor');
                document.getElementById('line-color').value = lineColor;
            }
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
        document.getElementById('text-color').addEventListener('change', function(e) {
            textColor = e.target.value;
            // 選択中のコメントがあれば、その色を変更
            if (selectedComment) {
                selectedComment.style.color = textColor;
            }
        });

        document.getElementById('background-color').addEventListener('change', function(e) {
            backgroundColor = e.target.value;
            // 選択中のコメントがあれば、その背景色を変更
            if (selectedComment) {
                selectedComment.style.backgroundColor = backgroundColor;
            }
        });

        // 線の色のイベントリスナー
        document.getElementById('line-color').addEventListener('change', function(e) {
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
        document.getElementById('increase-font-size').addEventListener('click', function() {
            if (fontSize < 36) { // 最大サイズを36pxに制限
                fontSize += 2;
                updateFontSizeDisplay();
                
                // 選択中のコメントがあれば、そのフォントサイズを変更
                if (selectedComment) {
                    selectedComment.style.fontSize = fontSize + 'px';
                }
            }
        });
        
        document.getElementById('decrease-font-size').addEventListener('click', function() {
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

        // 修正：どちらのSVGがクリックされたかを判定する関数
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

        // 修正：両方のSVGにイベントリスナーを設定
        function setupSvgEvents(svgElm, svgElm_js) {
            svgElm.on(start_event, function (event) {
                // 現在操作中のSVGを設定
                const svgInfo = getSvgFromEvent(event);
                if (svgInfo) {
                    currentSvg = svgInfo.svg;
                    currentSvg_js = svgInfo.svg_js;
                }

                // コメント要素上でのクリックは処理しない
                if (event.target.classList && event.target.classList.contains('comment-box')) {
                    return;
                }
                
                event.preventDefault();
                if (type === "imageMoveAndZoom") {
                    moveContentByMousedown(event);
                } else if (type === "pencil") {
                    // 直線を選択する処理
                    if (currentTool === 'line-draw' && event.target.tagName === 'line') {
                        // 既存の線をクリックした場合
                        selectedLine = event.target;
                        isMovingLine = true;
                        isDrawing = false;
                        
                        lineStartX = getPositionXOnSvg(event, currentSvg, currentSvg_js);
                        lineStartY = getPositionYOnSvg(event, currentSvg, currentSvg_js);
                        
                        // 線の始点と終点を取得
                        const x1 = parseFloat(selectedLine.getAttribute('x1'));
                        const y1 = parseFloat(selectedLine.getAttribute('y1'));
                        const x2 = parseFloat(selectedLine.getAttribute('x2'));
                        const y2 = parseFloat(selectedLine.getAttribute('y2'));
                        
                        // クリック位置と線の始点との差分を計算
                        lineOffsetX = [x1 - lineStartX, x2 - lineStartX];
                        lineOffsetY = [y1 - lineStartY, y2 - lineStartY];
                        
                        // 選択された線を強調表示
                        selectedLine.setAttribute('stroke-width', lineWidth * 2);
                        return;
                    }
                    
                    isDrawing = true;
                    startX = getPositionXOnSvg(event, currentSvg, currentSvg_js);
                    startY = getPositionYOnSvg(event, currentSvg, currentSvg_js);
                    
                    switch (currentTool) {
                        case 'line-draw':
                            // 選択中の線があれば選択解除
                            if (selectedLine) {
                                selectedLine.setAttribute('stroke-width', lineWidth);
                                selectedLine = null;
                                isMovingLine = false;
                            }
                            
                            // 直線描画の開始
                            tempLine = document.createElementNS("http://www.w3.org/2000/svg", "line");
                            tempLine.setAttribute("x1", startX);
                            tempLine.setAttribute("y1", startY);
                            tempLine.setAttribute("x2", startX);
                            tempLine.setAttribute("y2", startY);
                            // 現在設定されている線の色を使用
                            tempLine.setAttribute("stroke", lineColor);
                            tempLine.setAttribute("stroke-width", lineWidth);
                            currentSvg_js.appendChild(tempLine);
                            break;
                        case 'comment-add':
                            const x = getPositionXOnSvg(event, currentSvg, currentSvg_js);
                            const y = getPositionYOnSvg(event, currentSvg, currentSvg_js);
                            
                            // 既存のコメント選択を解除
                            if (selectedComment) {
                                selectedComment.style.outline = 'none';
                            }
                            
                            // 新しいコメントボックスを作成
                            const commentBox = document.createElement('div');
                            commentBox.className = 'comment-box';
                            commentBox.contentEditable = true;
                            commentBox.style.position = 'absolute';
                            // クリックした位置に正確に配置するための座標計算
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
                            
                            // リサイズハンドルを追加
                            const resizeHandle = document.createElement('div');
                            resizeHandle.className = 'comment-resize-handle';
                            resizeHandle.style.position = 'absolute';
                            resizeHandle.style.bottom = '0';
                            resizeHandle.style.right = '0';
                            resizeHandle.style.transform = 'translate(50%, 50%)';
                            
                            // コメントボックスをクリックしたときの処理
                            commentBox.addEventListener('click', function(e) {
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
                            
                            // ドラッグ機能の実装
                            let isDragging = false;
                            let startDragX, startDragY;
                            let startLeft, startTop;
                            
                            commentBox.addEventListener('mousedown', function(e) {
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
                                if (!isDragging) return;
                                
                                const dx = e.clientX - startDragX;
                                const dy = e.clientY - startDragY;
                                
                                commentBox.style.left = (startLeft + dx) + 'px';
                                commentBox.style.top = (startTop + dy) + 'px';
                            }
                            
                            function dragEnd() {
                                isDragging = false;
                                document.removeEventListener('mousemove', dragMove);
                                document.removeEventListener('mouseup', dragEnd);
                                
                                // カーソルを元に戻す
                                commentBox.style.cursor = 'auto';
                            }
                            
                            // リサイズ機能の実装
                            let isResizing = false;
                            let originalWidth, originalHeight, originalX, originalY;
                            
                            resizeHandle.addEventListener('mousedown', function(e) {
                                e.stopPropagation();
                                isResizing = true;
                                originalWidth = commentBox.offsetWidth;
                                originalHeight = commentBox.offsetHeight;
                                originalX = e.clientX;
                                originalY = e.clientY;
                                
                                // マウス移動イベントを追加
                                document.addEventListener('mousemove', resizeMove);
                                document.addEventListener('mouseup', resizeEnd);
                            });
                            
                            function resizeMove(e) {
                                if (isResizing) {
                                    const deltaX = e.clientX - originalX;
                                    const deltaY = e.clientY - originalY;
                                    
                                    // 最小サイズを設定
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
                            
                            // SVGの親要素にコメントボックスを追加
                            currentSvg.parent().append(commentBox);
                            commentBox.appendChild(resizeHandle);
                            
                            // 作成直後に選択状態にする
                            selectedComment = commentBox;
                            selectedComment.style.outline = '2px solid blue';
                            commentBox.focus();
                            
                            // テキスト全選択
                            const selection = window.getSelection();
                            const range = document.createRange();
                            range.selectNodeContents(commentBox);
                            selection.removeAllRanges();
                            selection.addRange(range);
                            
                            isDrawing = false;
                            break;
                        case 'eraser':
                            // 消しゴムの範囲選択開始
                            eraseStartX = startX;
                            eraseStartY = startY;
                            
                            // 範囲表示用の矩形を作成
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
                            pencilByMousedown(event);
                            break;
                        default:
                            pencilByMousedown(event);
                            break;
                    }
                }
            }).on(move_event, function (event) {
                event.preventDefault();
                if (type === "imageMoveAndZoom") {
                    // moveContentByMousedown(event);
                } else if (type === "pencil") {
                    // 線の移動処理
                    if (isMovingLine && selectedLine) {
                        const currentX = getPositionXOnSvg(event, currentSvg, currentSvg_js);
                        const currentY = getPositionYOnSvg(event, currentSvg, currentSvg_js);
                        
                        // 線の新しい位置を設定
                        selectedLine.setAttribute("x1", lineOffsetX[0] + currentX);
                        selectedLine.setAttribute("y1", lineOffsetY[0] + currentY);
                        selectedLine.setAttribute("x2", lineOffsetX[1] + currentX);
                        selectedLine.setAttribute("y2", lineOffsetY[1] + currentY);
                        return;
                    }
                    
                    if (!isDrawing) return;
                    
                    const currentX = getPositionXOnSvg(event, currentSvg, currentSvg_js);
                    const currentY = getPositionYOnSvg(event, currentSvg, currentSvg_js);
                    
                    switch (currentTool) {
                        case 'line-draw':
                            // 直線の更新
                            if (tempLine) {
                                tempLine.setAttribute("x2", currentX);
                                tempLine.setAttribute("y2", currentY);
                            }
                            break;
                        case 'eraser':
                            // 消しゴムの範囲更新
                            if (eraseRect) {
                                const width = currentX - eraseStartX;
                                const height = currentY - eraseStartY;
                                
                                // 範囲の開始位置を調整（負の値の場合）
                                const x = width < 0 ? currentX : eraseStartX;
                                const y = height < 0 ? currentY : eraseStartY;
                                
                                // 範囲の大きさを設定（絶対値を使用）
                                eraseRect.setAttribute("x", x);
                                eraseRect.setAttribute("y", y);
                                eraseRect.setAttribute("width", Math.abs(width));
                                eraseRect.setAttribute("height", Math.abs(height));
                            }
                            break;
                        case 'free-draw':
                            pencilByMousemove(event);
                            break;
                        default:
                            // デフォルトは自由描画の移動処理
                            pencilByMousemove(event);
                            break;
                    }
                }
            }).on(end_event, function (event) {
                event.preventDefault();
                if (type === "imageMoveAndZoom") {
                    moveContentByMouseup(event);
                } else if (type === "pencil") {
                    // 線の移動終了処理
                    if (isMovingLine && selectedLine) {
                        isMovingLine = false;
                        return;
                    }
                    
                    // 直線描画完了の処理
                    if (currentTool === 'line-draw' && tempLine) {
                        // 短すぎる線は削除
                        const x1 = parseFloat(tempLine.getAttribute("x1"));
                        const y1 = parseFloat(tempLine.getAttribute("y1"));
                        const x2 = parseFloat(tempLine.getAttribute("x2"));
                        const y2 = parseFloat(tempLine.getAttribute("y2"));
                        const length = Math.sqrt(Math.pow(x2 - x1, 2) + Math.pow(y2 - y1, 2));
                        if (length < 5) {
                            tempLine.remove();
                        }
                        tempLine = null;
                    }
                    
                    // 消しゴムの処理
                    if (currentTool === 'eraser' && eraseRect) {
                        // 選択範囲の座標を取得
                        const eraseX = parseFloat(eraseRect.getAttribute("x"));
                        const eraseY = parseFloat(eraseRect.getAttribute("y"));
                        const eraseWidth = parseFloat(eraseRect.getAttribute("width"));
                        const eraseHeight = parseFloat(eraseRect.getAttribute("height"));
                        // SVG内の全要素をチェック
                        const elements = Array.from(currentSvg_js.children);
                        for (let i = 0; i < elements.length; i++) {
                            const element = elements[i];
                            // 消去対象の要素かチェック（eraseRect自体は除外）
                            if (element !== eraseRect && (element.tagName === 'path' || 
                                element.tagName === 'line')) {
                                
                                let isIntersecting = false;
                                
                                if (element.tagName === 'path') {
                                    // パスの場合、全ての点をチェック
                                    const points = element.getAttribute('d').split(' ');
                                    for (let j = 0; j < points.length; j++) {
                                        if (points[j] === 'M' || points[j] === 'L') {
                                            if (j + 2 < points.length) {
                                                const pointX = parseFloat(points[j + 1]);
                                                const pointY = parseFloat(points[j + 2]);
                                                if (pointX >= eraseX && pointX <= eraseX + eraseWidth &&
                                                    pointY >= eraseY && pointY <= eraseY + eraseHeight) {
                                                    isIntersecting = true;
                                                    break;
                                                }
                                            }
                                        }
                                    }
                                } else if (element.tagName === 'line') {
                                    // 直線の場合、始点と終点をチェック
                                    const x1 = parseFloat(element.getAttribute('x1'));
                                    const y1 = parseFloat(element.getAttribute('y1'));
                                    const x2 = parseFloat(element.getAttribute('x2'));
                                    const y2 = parseFloat(element.getAttribute('y2'));
                                    
                                    if ((x1 >= eraseX && x1 <= eraseX + eraseWidth &&
                                         y1 >= eraseY && y1 <= eraseY + eraseHeight) ||
                                        (x2 >= eraseX && x2 <= eraseX + eraseWidth &&
                                         y2 >= eraseY && y2 <= eraseY + eraseHeight)) {
                                        isIntersecting = true;
                                    }
                                }
                                
                                // 交差している要素を削除
                                if (isIntersecting) {
                                    element.remove();
                                    
                                    // 選択中の線が削除された場合、参照をクリア
                                    if (selectedLine === element) {
                                        selectedLine = null;
                                        isMovingLine = false;
                                    }
                                }
                            }
                        }
                        
                        // コメントボックスも削除
                        const commentBoxes = document.querySelectorAll('.comment-box');
                        const svgRect = currentSvg_js.getBoundingClientRect();
                        
                        commentBoxes.forEach(box => {
                            const boxRect = box.getBoundingClientRect();
                        // SVG座標系に変換    
                            const boxX = (boxRect.left - svgRect.left) * (parseFloat(currentSvg_js.getAttribute('viewBox').split(' ')[2]) / svgRect.width);
                            const boxY = (boxRect.top - svgRect.top) * (parseFloat(currentSvg_js.getAttribute('viewBox').split(' ')[3]) / svgRect.height);
                            
                            // ボックスの中心が選択範囲内にあるかチェック
                            if (boxX >= eraseX && boxX <= eraseX + eraseWidth &&
                                boxY >= eraseY && boxY <= eraseY + eraseHeight) {
                                box.remove();
                                
                                // 選択中のコメントが削除された場合、参照をクリア
                                if (selectedComment === box) {
                                    selectedComment = null;
                                }
                            }
                        });
                        
                        // 消しゴムの範囲表示を削除
                        eraseRect.remove();
                        eraseRect = null;
                    }
                    
                    isDrawing = false;
                    if (currentTool === 'free-draw') {
                        pencilByMouseup(event);
                    }
                    tempLine = null;
                }
            });
        }

        // 両方のSVGにイベントリスナーを設定
        setupSvgEvents(leftSvgElm, leftSvgElm_js);
        setupSvgEvents(rightSvgElm, rightSvgElm_js);

        // タッチタイプの切り替え（スマートフォン用）
        document.getElementById("touch-type").addEventListener("click", function (e) {
            if (touchType === "move") {
                touchType = "zoom"
                this.classList.add("btn-primary");
                this.classList.remove("btn-outline-primary")
                this.textContent = "ズームモード";
            } else {
                touchType = "move"
                this.classList.remove("btn-primary");
                this.classList.add("btn-outline-primary")
                this.textContent = "ムーブモード";
            }
        })

        var browserDrawingData = {};
        var browserDrawData = {};

        // 修正：両方のSVGを初期化
        function initializeSvg(svgElm_js) {
            var svgElm_js_height = svgElm_js.clientHeight
            var svgElm_js_width = svgElm_js.clientWidth
            var init_viewBox = "0 0 " + String(svgElm_js_width) + " " + String(svgElm_js_height)
            svgElm_js.setAttribute("viewBox", init_viewBox)
        }

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

            browserDrawData = {
                "id": id,
                'type': "draw",
                "d": "",
                "fill": "none",
                "stroke": "red",
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
                "stroke-linecap": browserDrawData["stroke-linecap"]
            };
            
            for (let key in path_attr) {
                pathElm.setAttribute(key, path_attr[key]);
            }

            currentSvg_js.appendChild(pathElm);
            
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

        // 修正：SVGを指定できるように変更
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

        // 修正：SVGを指定できるように変更
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

        // Helper functions for touch coordinate conversion
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

        // 修正：右の画像を右のSVGに表示
        function rightDisplay(aspectRatio, image) {
            const rightWidth = rightSvgElm_js.clientWidth;
            const imageWidth = rightWidth - 40; // マージンを考慮
            const imageHeight = imageWidth / aspectRatio;
            const x = 20; // マージン
            const y = 20;
            
            // SVGの高さを調整
            rightSvgElm_js.style.height = (imageHeight + 40) + 'px';
            clipRightRect.height.baseVal.value = imageHeight;
            
            var init_viewBox = "0 0 " + String(rightWidth) + " " + String(imageHeight + 40);
            rightSvgElm_js.setAttribute("viewBox", init_viewBox);
            
            createImage(x, y, 0, 0, imageWidth, imageHeight, image, "right-image", "url(#clipright)", rightSvgElm_js);
            createTextBox(x, y + imageHeight + 20, 0, 0, "right-date", document.getElementById("subject-image-date").value, 16, "black", rightSvgElm_js);
        }

        // 修正：左の画像を左のSVGに表示
        function leftDisplay(aspectRatio, image) {
            const leftWidth = leftSvgElm_js.clientWidth;
            const imageWidth = leftWidth - 40; // マージンを考慮
            const imageHeight = imageWidth / aspectRatio;
            const x = 20; // マージン
            const y = 20;
            
            // SVGの高さを調整
            leftSvgElm_js.style.height = (imageHeight + 40) + 'px';
            clipLeftRect.height.baseVal.value = imageHeight;
            
            var init_viewBox = "0 0 " + String(leftWidth) + " " + String(imageHeight + 40);
            leftSvgElm_js.setAttribute("viewBox", init_viewBox);
            
            createImage(x, y, 0, 0, imageWidth, imageHeight, image, "left-image", "url(#clipleft)", leftSvgElm_js);
            createTextBox(x, y + imageHeight + 20, 0, 0, "left-date", document.getElementById("target-image-date").value, 16, "black", leftSvgElm_js);
        }

        var aspectRatio;
        var maxHeight = 0;

        function createImageAspectTarget(image) {
            const imageUrl = image;
            const img = new Image();
            img.onload = function (result) {
                aspectRatio = img.naturalWidth / img.naturalHeight;
                const canvas = document.createElement('canvas');
                const ctx = canvas.getContext('2d');
                canvas.width = img.width;
                canvas.height = img.height;
                ctx.drawImage(img, 0, 0);
                const base64ImageData = canvas.toDataURL('image/png');
                leftDisplay(aspectRatio, base64ImageData);
            };
            img.onerror = function () {
                console.error('画像を読み込めませんでした。');
            };
            img.crossOrigin = 'Anonymous';
            img.src = imageUrl;
        }

        function createImageAspectSubject(image) {
            const imageUrl = image;
            const img = new Image();
            img.onload = function () {
                const aspectRatio = img.naturalWidth / img.naturalHeight;
                const canvas = document.createElement('canvas');
                const ctx = canvas.getContext('2d');
                canvas.width = img.width;
                canvas.height = img.height;
                ctx.drawImage(img, 0, 0);
                const base64ImageData = canvas.toDataURL('image/png');
                rightDisplay(aspectRatio, base64ImageData);
                createImageAspectTarget(document.getElementById("target_image_url").src);
            };
            img.onerror = function () {
                console.error('画像を読み込めませんでした。');
            };
            img.crossOrigin = 'Anonymous';
            img.src = imageUrl;
        }

        createImageAspectSubject(document.getElementById("subject_image_url").src);

        function createTextBox(cx, cy, x, y, id, text, size, color, targetSvg) {
            var text_box = document.createElementNS("http://www.w3.org/2000/svg", "text");
            text_box.setAttribute("x", cx);
            text_box.setAttribute("y", cy);
            text_box.setAttribute("font-size", size);
            text_box.setAttribute("fill", color);
            text_box.setAttribute("id", id);
            text_box.textContent = text;
            var transform = text_box.transform.baseVal.createSVGTransformFromMatrix(SVGMatrixCreate())
            if (!text_box.hasAttribute("transform")) {
                text_box.transform.baseVal.appendItem(transform);
            }
            var Matrix = text_box.transform.baseVal[0].matrix;
            Matrix.e += x;
            Matrix.f += y;

            targetSvg.appendChild(text_box);
        }

        function loadImage(url) {
            return new Promise((resolve, reject) => {
                const image = new Image();
                image.crossOrigin = "anonymous";
                image.onload = () => resolve(image);
                image.onerror = () => reject(new Error(`Failed to load image: ${url}`));
                image.src = url;
            });
        }

        // New helper function to draw text with wrapping and vertical centering
        
        function drawTextWithWrapping(ctx, text, x, y, maxWidth, lineHeight, textColor, fontSize, fontFamily, boxHeight, padding) {
            ctx.font = `${fontSize}px ${fontFamily}`;
            ctx.fillStyle = textColor;
            ctx.textBaseline = 'top';

            const explicitLines = text.split('\n'); // Split by explicit newlines first
            const wrappedLines = [];

            explicitLines.forEach(explicitLine => {
                const words = explicitLine.split(' ');
                let currentLine = '';
                for (let i = 0; i < words.length; i++) {
                    const testLine = currentLine + words[i] + ' ';
                    const metrics = ctx.measureText(testLine);
                    const testWidth = metrics.width;

                    if (testWidth > maxWidth && i > 0) {
                        wrappedLines.push(currentLine.trim());
                        currentLine = words[i] + ' ';
                    } else {
                        currentLine = testLine;
                    }
                }
                wrappedLines.push(currentLine.trim());
            });

            const totalTextHeight = wrappedLines.length * lineHeight;
            const verticalOffset = (boxHeight - totalTextHeight - (2 * padding)) / 2;

            let currentY = y + padding + verticalOffset;

            wrappedLines.forEach(line => {
                const textWidth = ctx.measureText(line).width;
                const textX = x + padding + (maxWidth - textWidth) / 2;
                ctx.fillText(line, textX, currentY);
                currentY += lineHeight;
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
                
                canvas.width = (leftWidth + rightWidth + 20) * pixelRatio; // 20pxの間隔
                canvas.height = maxHeight * pixelRatio;
                canvas.style.width = (leftWidth + rightWidth + 20) + "px";
                canvas.style.height = maxHeight + "px";

                const ctx = canvas.getContext("2d");
                ctx.setTransform(pixelRatio, 0, 0, pixelRatio, 0, 0);
                
                // 背景を白で塗りつぶす
                ctx.fillStyle = 'white';
                ctx.fillRect(0, 0, leftWidth + rightWidth + 20, maxHeight);
                
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
                const commentBoxes = document.querySelectorAll('.comment-box');
                
                commentBoxes.forEach(comment => {
                    const commentRect = comment.getBoundingClientRect();
                    
                    // どちらのSVGに属するかを判定
                    const leftSvgRect = leftSvg.getBoundingClientRect();
                    const rightSvgRect = rightSvg.getBoundingClientRect();
                    
                    let commentLeft, commentTop;
                    
                    if (commentRect.left >= leftSvgRect.left && commentRect.left <= leftSvgRect.right) {
                        // 左のSVGに属する
                        commentLeft = commentRect.left - leftSvgRect.left;
                        commentTop = commentRect.top - leftSvgRect.top;
                    } else if (commentRect.left >= rightSvgRect.left && commentRect.left <= rightSvgRect.right) {
                        // 右のSVGに属する
                        commentLeft = (commentRect.left - rightSvgRect.left) + leftWidth + 20;
                        commentTop = commentRect.top - rightSvgRect.top;
                    } else {
                        return; // どちらにも属さない場合はスキップ
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
                    
                    // 選択されたコメントの青い枠（outlineがある場合）
                    if (comment.style.outline && comment.style.outline.includes('solid blue')) {
                        ctx.strokeStyle = 'blue';
                        ctx.lineWidth = 2;
                        ctx.strokeRect(commentLeft - 1, commentTop - 1, commentWidth + 2, commentHeight + 2);
                    }
                    
                    // コメントのテキストを描画
                    const fontSize = parseInt(window.getComputedStyle(comment).fontSize);
                    const fontFamily = window.getComputedStyle(comment).fontFamily;
                    const lineHeight = fontSize * 1.2;
                    
                    const textContent = comment.innerText.trim();
                    const placeholderContent = window.getComputedStyle(comment, ':empty::before').content;
                    
                    if (textContent === '' && placeholderContent !== 'none') {
                        
                        ctx.font = `${fontSize}px ${fontFamily}`;
                        ctx.fillStyle = window.getComputedStyle(comment, ':empty::before').color;
                        ctx.textAlign = 'center';
                        ctx.textBaseline = 'middle';
                        ctx.fillText(placeholderContent.replace(/['"]+/g, ''), commentLeft + commentWidth / 2, commentTop + commentHeight / 2);
                    } else if (textContent !== '') {
                        
                        drawTextWithWrapping(
                            ctx,
                            textContent,
                            commentLeft,
                            commentTop,
                            commentWidth - (2 * padding), // Max width for text, considering padding
                            lineHeight,
                            window.getComputedStyle(comment).color,
                            fontSize,
                            fontFamily,
                            commentHeight,
                            padding
                        );
                    }
                });

                // PNGとして出力
                const pngDataUrl = canvas.toDataURL('image/png');
                document.getElementById("preview").src = pngDataUrl;
                document.getElementById("preview").style.display = "block";
            } catch (error) {
                console.error('Error during SVG to canvas conversion:', error);
            }
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
                
                const pixelRatio = window.devicePixelRatio || 1;
                
                canvas.width = (leftWidth + rightWidth + 20) * pixelRatio;
                canvas.height = maxHeight * pixelRatio;
                canvas.style.width = (leftWidth + rightWidth + 20) + "px";
                canvas.style.height = maxHeight + "px";

                const ctx = canvas.getContext("2d");
                ctx.setTransform(pixelRatio, 0, 0, pixelRatio, 0, 0);
                
                // 背景を白で塗りつぶす
                ctx.fillStyle = 'white';
                ctx.fillRect(0, 0, leftWidth + rightWidth + 20, maxHeight);
                
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
                
                commentBoxes.forEach((comment, index) => {
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
                    
                    // 選択されたコメントの青い枠（outlineがある場合）
                    if (comment.style.outline && comment.style.outline.includes('solid blue')) {
                        ctx.strokeStyle = 'blue';
                        ctx.lineWidth = 2;
                        ctx.strokeRect(commentLeft - 1, commentTop - 1, commentWidth + 2, commentHeight + 2);
                    }
                    
                    // コメントのテキストを描画
                    const fontSize = parseInt(window.getComputedStyle(comment).fontSize);
                    const fontFamily = window.getComputedStyle(comment).fontFamily;
                    const lineHeight = fontSize * 1.2; // Approximate line height
                    
                    const textContent = comment.innerText.trim();
                    const placeholderContent = window.getComputedStyle(comment, ':empty::before').content;
                    
                    if (textContent === '' && placeholderContent !== 'none') {
                        
                        ctx.font = `${fontSize}px ${fontFamily}`;
                        ctx.fillStyle = window.getComputedStyle(comment, ':empty::before').color;
                        ctx.textAlign = 'center';
                        ctx.textBaseline = 'middle';
                        ctx.fillText(placeholderContent.replace(/['"]+/g, ''), commentLeft + commentWidth / 2, commentTop + commentHeight / 2);
                    } else if (textContent !== '') {
                        
                        drawTextWithWrapping(
                            ctx,
                            textContent,
                            commentLeft,
                            commentTop,
                            commentWidth - (2 * padding), // Max width for text, considering padding
                            lineHeight,
                            window.getComputedStyle(comment).color,
                            fontSize,
                            fontFamily,
                            commentHeight,
                            padding
                        );
                    }
                });

                // PNGとして出力
                const pngDataUrl = canvas.toDataURL('image/png');
                uploadImageToServer(pngDataUrl);
            } catch (error) {
                console.error('Error during SVG to canvas conversion:', error);
            }
        }

        var previewFlag = false;

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
            previewFlag = !previewFlag;
        })

        document.getElementById("submit").addEventListener("click", async function () {
            await downloadBoard()
        })

        function uploadImageToServer(pngDataUrl) {
            const formData = new FormData();
            const comment = document.getElementById("admin_comment").value;
            const target_image = "<?= $param["target_id"] ?>"
            const subject_image = "<?= $param["subject_id"] ?>"
            const user = "<?= $param["user"] ?>"
            const user_name = "<?= $param["user_name"] ?>"
            formData.append('image', pngDataUrl);
            formData.append('admin_comment', comment);
            formData.append('target_image', target_image);
            formData.append('subject_image', subject_image);
            formData.append('user_id', user);
            formData.append('user_name', user_name);

            const nextURL = "/admin/compared-images?start_report_date=&end_report_date=&user_id=" + user + "&user_name=" + user_name;
            $.ajax({
                type: 'POST',
                url: "<?= $this->Url->build(['action' => 'uploadComparedImage']); ?>",
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

        var id;
        var x;
        var y;
        var pointX;
        var pointY;

        // 修正：画像の移動処理を両方のSVGに対応
        function moveContentByMousedown(event) {
            var target = null;
            let isDragging = false;

            const svgInfo = getSvgFromEvent(event);
            if (!svgInfo) return;

            pointX = parseInt(getPositionXOnSvg(event, svgInfo.svg, svgInfo.svg_js));
            pointY = parseInt(getPositionYOnSvg(event, svgInfo.svg, svgInfo.svg_js));

            const margin = 20;
            const svgWidth = svgInfo.svg_js.clientWidth;
            const svgHeight = svgInfo.svg_js.clientHeight;

            // SVG内の画像領域をクリックしたかチェック
            if (pointX > margin && pointX < svgWidth - margin &&
                pointY > margin && pointY < svgHeight - margin) {
                
                if (svgInfo.side === 'left') {
                    target = document.getElementById("left-image");
                } else {
                    target = document.getElementById("right-image");
                }
            }

            if (!target) return;

            isDragging = true;
            var mouse_move_x = parseInt(getPositionXOnSvg(event, svgInfo.svg, svgInfo.svg_js));
            var mouse_move_y = parseInt(getPositionYOnSvg(event, svgInfo.svg, svgInfo.svg_js));

            var x = parseInt(target.getAttribute("x"));
            var y = parseInt(target.getAttribute("y"));
            var dx = 0;
            var dy = 0;
            var transform = target.transform.baseVal.createSVGTransformFromMatrix(SVGMatrixCreate())
            if (!target.hasAttribute("transform")) {
                target.transform.baseVal.appendItem(transform);
            }
            id = target.id;
            var targetContent = document.getElementById(id);
            x = parseInt(targetContent.getAttribute("x"));
            y = parseInt(targetContent.getAttribute("y"));
            var Matrix = target.transform.baseVal[0].matrix;

            svgInfo.svg.on(move_event, function (event) {
                if (!isDragging) return;
                if (move_event !== "touchmove") {
                    var mouse_moving_x = parseInt(getPositionXOnSvg(event, svgInfo.svg, svgInfo.svg_js));
                    var mouse_moving_y = parseInt(getPositionYOnSvg(event, svgInfo.svg, svgInfo.svg_js));
                    var x_length = mouse_moving_x - mouse_move_x;
                    var y_length = mouse_moving_y - mouse_move_y;

                    Matrix.e += x_length;
                    Matrix.f += y_length;

                    mouse_move_x = mouse_moving_x;
                    mouse_move_y = mouse_moving_y;
                } else {
                    if (touchType === "move") {
                        var mouse_moving_x = parseInt(getPositionXOnSvg(event, svgInfo.svg, svgInfo.svg_js));
                        var mouse_moving_y = parseInt(getPositionYOnSvg(event, svgInfo.svg, svgInfo.svg_js));
                        var x_length = mouse_moving_x - mouse_move_x;
                        var y_length = mouse_moving_y - mouse_move_y;
                        
                        Matrix.e += x_length;
                        Matrix.f += y_length;
                        
                        mouse_move_x = mouse_moving_x;
                        mouse_move_y = mouse_moving_y;
                    } else if (touchType === "zoom") {
                        var mouse_moving_x = parseInt(getPositionXOnSvg(event, svgInfo.svg, svgInfo.svg_js));
                        var mouse_moving_y = parseInt(getPositionYOnSvg(event, svgInfo.svg, svgInfo.svg_js));
                        var x_length = mouse_moving_x - mouse_move_x;
                        var y_length = mouse_moving_y - mouse_move_y;
                        mouse_move_x = mouse_moving_x;
                        mouse_move_y = mouse_moving_y;
                        var scale = y_length < 0 ? 0.99 : 1.01
                        target.width.baseVal.value *= scale;
                        target.height.baseVal.value *= scale;
                    }
                }
            });
            svgInfo.svg.on(end_event, function () {
                isDragging = false;
            });
        }

        function moveContentByMouseup() {
            leftSvgElm.css('cursor', 'default');
            rightSvgElm.css('cursor', 'default');
            isDragging = false;
        }

        var wheel_event = 'ontouchstart' in window ? "touchmove" : "wheel";
        var baseDistance = 0;
        var pointX = 0;
        var pointY = 0;

        // 修正：ズーム機能を両方のSVGに対応
        function zoomAction() {
            [leftSvgElm_js, rightSvgElm_js].forEach((svgElm_js, index) => {
                svgElm_js.addEventListener(wheel_event, function (e) {
                    if (type === "pencil" && type !== "move") return;
                    
                    const svgElm = index === 0 ? leftSvgElm : rightSvgElm;
                    var target;
                    pointX = parseInt(getPositionXOnSvg(event, svgElm, svgElm_js));
                    pointY = parseInt(getPositionYOnSvg(event, svgElm, svgElm_js));
                    
                    const margin = 20;
                    const svgWidth = svgElm_js.clientWidth;
                    const svgHeight = svgElm_js.clientHeight;
                    
                    if (pointX > margin && pointX < svgWidth - margin &&
                        pointY > margin && pointY < svgHeight - margin) {
                        if (index === 0) {
                            target = document.getElementById("left-image");
                        } else {
                            target = document.getElementById("right-image");
                        }
                    }
                    
                    var scale = 1
                    e.preventDefault()
                    var pointer_x = 0
                    var pointer_y = 0
                    if (wheel_event == "touchmove") {
                        var touches = e.changedTouches;
                        if (touches.length > 1) {
                            try {
                                var page_x1 = touches[0].pageX;
                                var page_y1 = touches[0].pageY;
                                // 座標2 (2本目の指)
                                var page_x2 = touches[1].pageX
                                var page_y2 = touches[1].pageY

                                var x1 = getPositionXOnSvgByX(touches[0].pageX, svgElm, svgElm_js);
                                var y1 = getPositionYOnSvgByY(touches[0].pageY, svgElm, svgElm_js);
                                // 座標2 (2本目の指)
                                var x2 = getPositionXOnSvgByX(touches[1].pageX, svgElm, svgElm_js);
                                var y2 = getPositionYOnSvgByY(touches[1].pageY, svgElm, svgElm_js);
                                // 2点間の距離
                                var distance = Math.sqrt(Math.pow(page_x2 - page_x1, 2) + Math.pow(page_y2 - page_y1, 2));
                                if (baseDistance != 0) {
                                    scale = baseDistance / distance;
                                }
                                pointer_x = (x1 + x2) / 2;
                                pointer_y = (y1 + y2) / 2;
                                baseDistance = distance
                            } catch (error) {
                                console.error(error);
                            }
                        }
                    } else {
                        var dy = e.deltaY
                        scale = dy < 0 ? 0.99 : 1.01
                        pointer_x = getPositionXOnSvg(e, svgElm, svgElm_js)
                        pointer_y = getPositionYOnSvg(e, svgElm, svgElm_js)
                    }
                    if (target) {
                        target.width.baseVal.value *= scale;
                        target.height.baseVal.value *= scale;
                    }
                }, { passive: false })
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

        function pencilByMousemove(event) {
            var linetoX = parseInt(getPositionXOnSvg(event, currentSvg, currentSvg_js));
            var linetoY = parseInt(getPositionYOnSvg(event, currentSvg, currentSvg_js));
            window.linetoStr = window.linetoStr + " L " + linetoX + " " + linetoY;

           
            var pathElm = currentSvg_js.querySelector("path:last-child");
            if (pathElm) {
                pathElm.setAttribute("d", window.linetoStr);
            } else {
                console.error("最後のパス要素が見つかりません");
            }
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

        // SVGのクリックイベントを修正して、コメント選択を解除する
        [leftSvgElm, rightSvgElm].forEach(svgElm => {
            svgElm.on('click', function(event) {
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

    });
</script>
