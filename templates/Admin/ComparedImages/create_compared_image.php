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
        border: none;
        border-radius: 4px;
        padding: 8px;
        min-width: 100px;
        min-height: 50px;
        /* リサイズはJavaScriptで制御するため、CSSのresizeプロパティは使用しない */
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
        <figure id="board" class="p-sm-2 p-md-4" style="position: relative">
            <svg id="drawingCanvas"
                style="border: solid 1px rgba(0,0,0,0.1); background-color: white; width: 100%; height: 800px">
                <defs>
                    <clipPath id="clipleft">
                        <rect x="50" y="50" width="300" height="500" id="clip-left-rect" />
                    </clipPath>
                </defs>
                <defs>
                    <clipPath id="clipright">
                        <rect x="370" y="50" width="300" height="500" id="clip-right-rect" />
                    </clipPath>
                </defs>
            </svg>
        </figure>
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
        var svgElm = $("#drawingCanvas");
        var svgElm_js = document.getElementById("drawingCanvas");
        var type = "imageMoveAndZoom";
        var touchType = "move";

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

        // 直線移動のための変数を追加
        var isMovingLine = false;
        var selectedLine = null;
        var lineStartX, lineStartY;
        var lineOffsetX, lineOffsetY;

        // 消しゴム機能用のグローバル変数を追加
        var eraseRect = null;
        var eraseStartX, eraseStartY;

        // 初期化時にカラーピッカーの値を設定
        $(document).ready(function () {
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

        function main() {
            $("#drawingCanvas").on(start_event, function (event) {
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

                        // 線の現在位置を保存
                        lineStartX = getPositionXOnSvg(event);
                        lineStartY = getPositionYOnSvg(event);

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
                    startX = getPositionXOnSvg(event);
                    startY = getPositionYOnSvg(event);

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
                            svgElm_js.appendChild(tempLine);
                            break;
                        case 'comment-add':
                            const x = getPositionXOnSvg(event);
                            const y = getPositionYOnSvg(event);

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
                            const svgRect = svgElm_js.getBoundingClientRect();
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

                            // ドラッグ機能の実装
                            let isDragging = false;
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

                            resizeHandle.addEventListener('mousedown', function (e) {
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
                            svgElm.parent().append(commentBox);
                            commentBox.appendChild(resizeHandle);

                            // 作成直後に選択状態にする
                            selectedComment = commentBox;
                            commentBox.style.outline = '2px solid blue';
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
                            svgElm_js.appendChild(eraseRect);
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
                        const currentX = getPositionXOnSvg(event);
                        const currentY = getPositionYOnSvg(event);

                        // 線の新しい位置を設定
                        selectedLine.setAttribute("x1", lineOffsetX[0] + currentX);
                        selectedLine.setAttribute("y1", lineOffsetY[0] + currentY);
                        selectedLine.setAttribute("x2", lineOffsetX[1] + currentX);
                        selectedLine.setAttribute("y2", lineOffsetY[1] + currentY);
                        return;
                    }

                    if (!isDrawing) return;

                    const currentX = getPositionXOnSvg(event);
                    const currentY = getPositionYOnSvg(event);

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
                        const elements = Array.from(svgElm_js.children);
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
                        const svgRect = svgElm_js.getBoundingClientRect();

                        commentBoxes.forEach(box => {
                            const boxRect = box.getBoundingClientRect();

                            // SVG座標系に変換
                            const boxX = (boxRect.left - svgRect.left) * (parseFloat(svgElm_js.getAttribute('viewBox').split(' ')[2]) / svgRect.width);
                            const boxY = (boxRect.top - svgRect.top) * (parseFloat(svgElm_js.getAttribute('viewBox').split(' ')[3]) / svgRect.height);

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

        main();

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

        var svgElm_js_height = svgElm_js.clientHeight
        var svgElm_js_width = svgElm_js.clientWidth
        var init_viewBox = "0 0 " + String(svgElm_js_width) + " " + String(svgElm_js_height)
        svgElm_js.setAttribute("viewBox", init_viewBox)
        function resizeWindow() {
            var svgElm_js_height = svgElm_js.clientHeight
            var svgElm_js_width = svgElm_js.clientWidth
            var [minX, minY, width, height] = svgElm_js.getAttribute('viewBox').split(' ')
                .map(s => parseFloat(s));
            var init_viewBox = String(minX) + " " + String(minY) + " " + String(svgElm_js_width) + " " + String(svgElm_js_height)

            svgElm_js.setAttribute("viewBox", init_viewBox)
        }
        window.onresize = resizeWindow;

        function resizeSvgElm() {
            var svgElm_js_height = svgElm_js.style.height
            var heightValue = parseFloat(svgElm_js_height.match(/\d+/)[0]);
            var svgElm_js_width = svgElm_js.style.width
            var widthValue = parseFloat(svgElm_js_width.match(/\d+/)[0]);
            var [minX, minY, width, height] = svgElm_js.getAttribute('viewBox').split(' ')
                .map(s => parseFloat(s));
            var init_viewBox = String(minX) + " " + String(minY) + " " + String(widthValue) + " " + String(heightValue)

            svgElm_js.setAttribute("viewBox", init_viewBox)
        }

        const svgWidth = svgElm_js.width.baseVal.value
        const svgWidthHalf = svgWidth / 2;
        const margin = 20;
        const clipRightRect = document.getElementById("clip-right-rect")
        const clipLeftRect = document.getElementById("clip-left-rect")

        function init() {
            clipRightRect.width.baseVal.value = svgWidthHalf - margin;
            clipLeftRect.width.baseVal.value = svgWidthHalf - margin;
            clipLeftRect.x.baseVal.value = margin;
            clipRightRect.x.baseVal.value = svgWidthHalf;
            clipLeftRect.y.baseVal.value = margin;
            clipRightRect.y.baseVal.value = margin;
        }

        init();

        function pencilByMousedown(event) {
            movetoX = parseInt(getPositionXOnSvg(event));
            movetoY = parseInt(getPositionYOnSvg(event));

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

            svgElm_js.appendChild(pathElm);

            // グローバルスコープで使えるように変数を初期化
            window.linetoStr = 'M ' + movetoX + ' ' + movetoY + ' ';
        }

        function pencilByMouseup(event) {
            // イベントリスナーの削除処理を削除（main内で処理されるため）
        }

        function getViewBox() {
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

        function getPositionXOnSvg(e) {
            var x = getPageX(e)
            var left = svgElm.position().left
            var svg_w = svgElm_js.clientWidth
            var viewBox = getViewBox()
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

        function getPositionYOnSvg(e) {
            var y = getPageY(e)
            var top = svgElm.position().top
            var svg_w = svgElm_js.clientWidth
            var viewBox = getViewBox()
            var svg_h = svgElm_js.clientHeight
            var pageY_aspect = viewBox[3] / svg_h
            var viewBox_y = viewBox[1]
            var result = (y - top) * pageY_aspect + viewBox_y;
            return result
        }


        function createImage(cx, cy, x, y, w, h, href, id, clipPath) {
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
            document.getElementById('drawingCanvas').appendChild(image_elm);
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

        function rightDisplay(aspectRatio, image) {
            const rightWidth = svgElm_js_width / 2;
            const imageWidth = rightWidth;
            const imageHeight = imageWidth / aspectRatio;
            const centerPosX = rightWidth / 2;
            const imageWidthHalf = imageWidth / 2;
            const x = centerPosX - imageWidthHalf;
            if (maxHeight < imageHeight) {
                maxHeight = imageHeight
            }
            clipRightRect.height.baseVal.value = maxHeight
            clipLeftRect.height.baseVal.value = maxHeight
            const textAspect = 45 / 1069;
            const textSize = svgElm_js_width * textAspect;
            svgElm_js.style.height = maxHeight + 100
            var [minX, minY, width, height] = svgElm_js.getAttribute('viewBox').split(' ')
                .map(s => parseFloat(s));
            var init_viewBox = String(minX) + " " + String(minY) + " " + String(svgElm_js_width) + " " + String(maxHeight + 100)
            svgElm_js.setAttribute("viewBox", init_viewBox)
            const y = 20;
            createImage(x, y, 0, 0, imageWidth, imageHeight, image, "left-image", "url(#clipleft)")
        }

        function leftDisplay(aspectRatio, image) {
            const leftWidth = svgElm_js_width / 2;
            const imageWidth = leftWidth;
            const imageHeight = imageWidth / aspectRatio;
            const centerPosX = (leftWidth / 2) + leftWidth;
            const imageWidthHalf = imageWidth / 2;
            const x = leftWidth;
            const textAspect = 45 / 1069;
            const textSize = svgElm_js_width * textAspect;
            if (maxHeight < imageHeight) {
                maxHeight = imageHeight
            }
            clipRightRect.height.baseVal.value = maxHeight
            clipLeftRect.height.baseVal.value = maxHeight
            svgElm_js.style.height = maxHeight + 100
            var [minX, minY, width, height] = svgElm_js.getAttribute('viewBox').split(' ')
                .map(s => parseFloat(s));
            var init_viewBox = String(minX) + " " + String(minY) + " " + String(svgElm_js_width) + " " + String(maxHeight + 100)
            svgElm_js.setAttribute("viewBox", init_viewBox)

            const y = 20;

            createImage(x, y, 0, 0, imageWidth, imageHeight, image, "right-image", "url(#clipright)")
            createTextBox(x, y + maxHeight + textSize, 0, 0, "jgnjakfonk", document.getElementById("target-image-date").value, textSize, "black")
            createTextBox(20, y + maxHeight + textSize, 0, 0, "jgnjakfonk", document.getElementById("subject-image-date").value, textSize, "black")
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

        function createTextBox(cx, cy, x, y, id, text, size, color) {
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

            svgElm.append(text_box);
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

        async function previewImage() {
            try {
                // SVG要素を取得
                const svg = document.querySelector("svg");
                const svgData = new XMLSerializer().serializeToString(svg);
                const canvas = document.createElement("canvas");
                const board = document.getElementById('board');

                // キャンバスサイズを設定
                const width = svg.width.baseVal.value;
                const height = svg.height.baseVal.value;
                const pixelRatio = window.devicePixelRatio || 1;

                canvas.width = width * pixelRatio;
                canvas.height = height * pixelRatio;
                canvas.style.width = width + "px";
                canvas.style.height = height + "px";

                // 2Dコンテキストを取得して高解像度対応
                const ctx = canvas.getContext("2d");
                ctx.setTransform(pixelRatio, 0, 0, pixelRatio, 0, 0);

                // 背景を白で塗りつぶす
                ctx.fillStyle = 'white';
                ctx.fillRect(0, 0, width, height);

                // SVGをBlobに変換
                const svgBlob = new Blob([svgData], { type: "image/svg+xml;charset=utf-8" });
                const url = URL.createObjectURL(svgBlob);

                // SVGを画像として読み込み
                const image = await loadImage(url);

                // SVGを描画
                ctx.drawImage(image, 0, 0, parseInt(canvas.style.width), parseInt(canvas.style.height));

                // 使用済みのBlobURLを解放
                URL.revokeObjectURL(url);

                // コメント要素を描画
                const commentBoxes = document.querySelectorAll('.comment-box');
                const svgRect = svg.getBoundingClientRect();

                commentBoxes.forEach(comment => {
                    const commentRect = comment.getBoundingClientRect();

                    // コメントの位置をSVG相対座標に変換
                    const commentLeft = commentRect.left - svgRect.left;
                    const commentTop = commentRect.top - svgRect.top;
                    const commentWidth = comment.offsetWidth;
                    const commentHeight = comment.offsetHeight;

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
                    const fontSize = window.getComputedStyle(comment).fontSize;
                    const fontFamily = window.getComputedStyle(comment).fontFamily;
                    ctx.font = `${fontSize} ${fontFamily}`;
                    ctx.fillStyle = window.getComputedStyle(comment).color;
                    ctx.textBaseline = 'top';

                    // テキストの内容を取得し、改行で分割
                    const text = comment.textContent;
                    const lines = text.split(/\r?\n/);

                    // 各行のテキストを描画（単純な行分割）
                    const lineHeight = parseInt(fontSize) * 1.2; // おおよその行の高さ
                    let yPos = commentTop + 8; // パディングの分だけオフセット

                    lines.forEach(line => {
                        ctx.fillText(line, commentLeft + 8, yPos); // パディングの分だけオフセット
                        yPos += lineHeight;
                    });
                });

                // PNGとして出力
                const pngDataUrl = canvas.toDataURL('image/png');
                document.getElementById("preview").src = pngDataUrl;
                document.getElementById("preview").style.display = "block";
            } catch (error) {
                console.error('Error during SVG to canvas conversion:', error);
            }
        }

        async function initImageLoad() {
            var svg = document.querySelector("svg");
            var svgData = new XMLSerializer().serializeToString(svg);
            var canvas = document.createElement("canvas");
            canvas.width = svg.width.baseVal.value;
            canvas.height = svg.height.baseVal.value;

            const ctx = canvas.getContext("2d");
            const svgBlob = new Blob([svgData], { type: "image/svg+xml;charset=utf-8" });
            const url = URL.createObjectURL(svgBlob);

            const image = await loadImage(url);
            ctx.drawImage(image, 0, 0);
            URL.revokeObjectURL(url);

            const pngDataUrl = canvas.toDataURL('image/png');
        }

        async function downloadBoard() {
            try {
                const svg = document.querySelector("svg");
                const svgData = new XMLSerializer().serializeToString(svg);

                // コメント要素の存在確認
                const commentBoxes = document.querySelectorAll('.comment-box');
                if (commentBoxes.length > 0) {
                }

                const canvas = document.createElement("canvas");

                const pixelRatio = window.devicePixelRatio || 1;
                const viewBox = svg.viewBox.baseVal;

                canvas.width = viewBox.width * pixelRatio;
                canvas.height = viewBox.height * pixelRatio;

                // スタイルの幅と高さを設定
                canvas.style.width = viewBox.width + "px";
                canvas.style.height = viewBox.height + "px";

                // 2Dコンテキストを取得して高解像度対応
                const ctx = canvas.getContext("2d");
                ctx.setTransform(pixelRatio, 0, 0, pixelRatio, 0, 0);

                // 背景を白で塗りつぶす
                ctx.fillStyle = 'white';
                ctx.fillRect(0, 0, viewBox.width, viewBox.height);

                // SVGをBlobに変換
                const svgBlob = new Blob([svgData], { type: "image/svg+xml;charset=utf-8" });
                const url = URL.createObjectURL(svgBlob);

                // SVGを画像として読み込み
                const image = await loadImage(url);

                // SVGを描画
                ctx.drawImage(image, 0, 0, parseInt(canvas.style.width), parseInt(canvas.style.height));

                // 使用済みのBlobURLを解放
                URL.revokeObjectURL(url);

                // ここで、コメント要素も描画する処理を追加（previewImage関数と同様）
                const svgRect = svg.getBoundingClientRect();

                commentBoxes.forEach((comment, index) => {
                    const commentRect = comment.getBoundingClientRect();

                    // コメントの位置をSVG相対座標に変換
                    const commentLeft = commentRect.left - svgRect.left;
                    const commentTop = commentRect.top - svgRect.top;
                    const commentWidth = comment.offsetWidth;
                    const commentHeight = comment.offsetHeight;


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
                    const fontSize = window.getComputedStyle(comment).fontSize;
                    const fontFamily = window.getComputedStyle(comment).fontFamily;
                    ctx.font = `${fontSize} ${fontFamily}`;
                    ctx.fillStyle = window.getComputedStyle(comment).color;
                    ctx.textBaseline = 'top';

                    // テキストの内容を取得し、改行で分割
                    const text = comment.textContent;
                    const lines = text.split(/\r?\n/);

                    // 各行のテキストを描画（単純な行分割）
                    const lineHeight = parseInt(fontSize) * 1.2; // おおよその行の高さ
                    let yPos = commentTop + 8; // パディングの分だけオフセット

                    lines.forEach(line => {
                        ctx.fillText(line, commentLeft + 8, yPos); // パディングの分だけオフセット
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

        function moveContentByMousedown(event) {
            var target = null;
            let isDragging = false;

            pointX = parseInt(getPositionXOnSvg(event));
            pointY = parseInt(getPositionYOnSvg(event));

                if (pointX > margin && pointX < clipRightRect.width.baseVal.value + margin &&
                    pointY > margin && pointY < clipRightRect.height.baseVal.value) {
                    target = document.getElementById("left-image");
                } else if (pointX > clipRightRect.width.baseVal.value + margin &&
                    pointX < clipRightRect.width.baseVal.value * 2 + margin * 3 &&
                    pointY > margin && pointY < clipRightRect.height.baseVal.value) {
                    target = document.getElementById("right-image");
                }

                if (!target) return;


                isDragging = true;
                var mouse_move_x = parseInt(getPositionXOnSvg(event));
                var mouse_move_y = parseInt(getPositionYOnSvg(event));

                var x = parseInt(target.getAttribute("x"));
                var y = parseInt(target.getAttribute("y"));
                var mouse_move_x = 0;
                var mouse_move_y = 0;
                var mouse_move_x = parseInt(getPositionXOnSvg(event)); //SVG上のマウス座標(横方向)の取得
                var mouse_move_y = parseInt(getPositionYOnSvg(event)); //SVG上のマウス座標(縦方向)の取得

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

                $("#drawingCanvas").on(move_event, function (event) {
                    if (!isDragging) return;
                    if (move_event !== "touchmove") {
                        var mouse_moving_x = parseInt(getPositionXOnSvg(event)); //SVG上のマウス座標(横方向)の取得
                        var mouse_moving_y = parseInt(getPositionYOnSvg(event)); //SVG上のマウス座標(縦方向)の取得
                        var x_length = mouse_moving_x - mouse_move_x;
                        var y_length = mouse_moving_y - mouse_move_y;

                        var mouse_moving_x = parseInt(getPositionXOnSvg(event));
                        var mouse_moving_y = parseInt(getPositionYOnSvg(event));

                        mouse_move_x = mouse_moving_x;
                        mouse_move_y = mouse_moving_y;
                        x += x_length;
                        y += y_length;
                        targetContent.setAttribute('x', x + "");
                        targetContent.setAttribute('y', y + "");
                    } else {
                        if (touchType === "move") {
                            var mouse_moving_x = parseInt(getPositionXOnSvg(event)); //SVG上のマウス座標(横方向)の取得
                            var mouse_moving_y = parseInt(getPositionYOnSvg(event)); //SVG上のマウス座標(縦方向)の取得
                            var x_length = mouse_moving_x - mouse_move_x;
                            var y_length = mouse_moving_y - mouse_move_y;
                            mouse_move_x = mouse_moving_x;
                            mouse_move_y = mouse_moving_y;
                            x += x_length;
                            y += y_length;
                            targetContent.setAttribute('x', x + "");
                            targetContent.setAttribute('y', y + "");
                        } else if (touchType === "zoom") {
                            var mouse_moving_x = parseInt(getPositionXOnSvg(event)); //SVG上のマウス座標(横方向)の取得
                            var mouse_moving_y = parseInt(getPositionYOnSvg(event)); //SVG上のマウス座標(縦方向)の取得
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
                $("#drawingCanvas").on(end_event, function () {
                    isDragging = false;
                    // $("#drawingCanvas").off(move_event);
                    // $("#drawingCanvas").off(end_event);
                });
            // }
        }


        function moveContentByMouseup() {
            svgElm.css('cursor', 'default');
            isDragging = false;  // ドラッグ状態を確実にリセット
        }

        var wheel_event = 'ontouchstart' in window ? "touchmove" : "wheel";
        var baseDistance = 0;
        var pointX = 0;
        var pointY = 0;

        function zoomAction() {

            document.getElementById("drawingCanvas").addEventListener(wheel_event, function (e) {
                if (type === "pencil" && type !== "move") return;
                var target;
                pointX = parseInt(getPositionXOnSvg(event));
                pointY = parseInt(getPositionYOnSvg(event));
                if (pointX > margin && pointX < clipRightRect.width.baseVal.value + margin) {
                    if (pointY > margin && pointY < clipRightRect.height.baseVal.value) {
                        target = document.getElementById("left-image");
                    }
                } else if (pointX > clipRightRect.width.baseVal.value + margin && pointX < clipRightRect.width.baseVal.value * 2 + margin * 3) {
                    if (pointY > margin && pointY < clipRightRect.height.baseVal.value) {
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

                            var x1 = getPositionXOnSvgByX(touches[0].pageX);
                            var y1 = getPositionYOnSvgByY(touches[0].pageY);

                            // 座標2 (2本目の指)
                            var x2 = getPositionXOnSvgByX(touches[1].pageX);
                            var y2 = getPositionYOnSvgByY(touches[1].pageY);

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
                    pointer_x = getPositionXOnSvg(e)
                    pointer_y = getPositionYOnSvg(e)
                }
                target.width.baseVal.value *= scale;
                target.height.baseVal.value *= scale;
            }, { passive: false })
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
            imageElement.setAttribute("transform", `rotate(${angle} ${cx} ${cy})`);
        }


        function pencilByMousemove(event) {
            var linetoX = parseInt(getPositionXOnSvg(event)); // SVG上のマウス座標(横方向)の取得
            var linetoY = parseInt(getPositionYOnSvg(event)); // SVG上のマウス座標(縦方向)の取得
            window.linetoStr = window.linetoStr + " L " + linetoX + " " + linetoY; // 動いた後の新たなマウス座標を描画点として追加

            // pathデータ(d属性)の値を更新
            var pathElm = document.querySelector("#drawingCanvas path:last-child");
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
        $("#drawingCanvas").on('click', function (event) {
            // コメント要素上でのクリックは処理しない
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
</script>