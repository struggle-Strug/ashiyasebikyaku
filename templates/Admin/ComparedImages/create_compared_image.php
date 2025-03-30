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
    
    .comment-box {
        background-color: white;
        border: none;
        border-radius: 4px;
        padding: 8px;
        min-width: 100px;
        min-height: 50px;
    }
    
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
    
    .comment-resize-handle {
        position: absolute;
        width: 10px;
        height: 10px;
        background-color: transparent; 
        border: none; 
        border-radius: 50%;
        cursor: nwse-resize;
        z-index: 1001;
    }
    
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
            </div>
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
                <textarea class="form-control" rows="5" name="comment" id="comment"></textarea>
                <span class="text-danger">
                    <?= $this->Form->error('comment') ?>
                </span>
            </div>
        </div>
    </div>
    <div class="card-footer">
        <a class="btn btn-outline-secondary" href="<?= $this->Url->build(['action' => 'index']); ?>"><i
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

        var start_event = 'ontouchstart' in window ? "touchstart" : "mousedown";
        var move_event = 'ontouchstart' in window ? "touchmove" : "mousemove";
        var end_event = 'ontouchstart' in window ? "touchend" : "mouseup";
        var out_event = "mouseleave";
        var svgElm = $("#drawingCanvas");
        var svgElm_js = document.getElementById("drawingCanvas");
        var type = "imageMoveAndZoom";
        var touchType = "move";
        
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

        var isResizing = false;
        var currentResizeElement = null;
        var startX, startY, startWidth, startHeight;
        var isErasing = false;
        var eraseStartX, eraseStartY;
        var eraseRect = null;

        var isMovingLine = false;
        var selectedLine = null;
        var lineStartX, lineStartY;
        var lineOffsetX, lineOffsetY;

        $(document).ready(function() {
            document.getElementById('line-color').value = lineColor;
           
            if (localStorage.getItem('lineColor')) {
                lineColor = localStorage.getItem('lineColor');
                document.getElementById('line-color').value = lineColor;
            }
        });

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
                
                if (selectedLine) {
                    selectedLine.setAttribute('stroke-width', lineWidth);
                    selectedLine = null;
                    isMovingLine = false;
                }
                
                currentTool = button.id;
                
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

        if (localStorage.getItem('lineColor')) {
            lineColor = localStorage.getItem('lineColor');
            document.getElementById('line-color').value = lineColor;
        }

        function main() {
            $("#drawingCanvas").on(start_event, function (event) {
                if (event.target.classList && event.target.classList.contains('comment-box')) {
                    return;
                }
                
                event.preventDefault();
                if (type === "imageMoveAndZoom") {
                    moveContentByMousedown(event)
                } else if (type === "pencil") {
                    if (currentTool === 'line-draw' && event.target.tagName === 'line') {
                        selectedLine = event.target;
                        isMovingLine = true;
                        isDrawing = false;
                        
                        lineStartX = getPositionXOnSvg(event);
                        lineStartY = getPositionYOnSvg(event);
                        
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
                    startX = getPositionXOnSvg(event);
                    startY = getPositionYOnSvg(event);
                    
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
                            svgElm_js.appendChild(tempLine);
                            // console.log("Line created at", startX, startY);
                            break;
                        case 'comment-add':
                            const x = getPositionXOnSvg(event);
                            const y = getPositionYOnSvg(event);
                            
                            if (selectedComment) {
                                selectedComment.style.outline = 'none';
                                selectedComment = null;
                            }
                            
                            const commentBox = document.createElement('div');
                            commentBox.className = 'comment-box';
                            commentBox.contentEditable = true;
                            commentBox.style.position = 'absolute';
                            const svgRect = svgElm_js.getBoundingClientRect();
                            const clickX = event.clientX || event.touches[0].clientX;
                            const clickY = event.clientY || event.touches[0].clientY;
                            commentBox.style.left = (clickX - svgRect.left) + 'px';
                            commentBox.style.top = (clickY - svgRect.top) + 'px';
                            commentBox.style.color = textColor;
                            commentBox.style.backgroundColor = backgroundColor;
                            commentBox.style.fontSize = fontSize + 'px'; 
                            commentBox.style.zIndex = '1000';
                            commentBox.textContent = 'コメントを入力';
                            
                            
                            const resizeHandle = document.createElement('div');
                            resizeHandle.className = 'comment-resize-handle';
                            resizeHandle.style.bottom = '0';
                            resizeHandle.style.right = '0';
                            resizeHandle.style.transform = 'translate(50%, 50%)';
                            
                            
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

                            function resizeMove(e) {
                                if (isResizing) {
                                    const deltaX = e.clientX - originalX;
                                    const deltaY = e.clientY - originalY;
                                    commentBox.style.width = (originalWidth + deltaX) + 'px';
                                    commentBox.style.height = (originalHeight + deltaY) + 'px';
                                }
                            }

                            function resizeEnd() {
                                isResizing = false;
                                document.removeEventListener('mousemove', resizeMove);
                                document.removeEventListener('mouseup', resizeEnd);
                            }

                            commentBox.addEventListener('click', function(e) {
                                e.stopPropagation();
                                
                                // 既存の選択を解除
                                if (selectedComment) {
                                    selectedComment.style.outline = 'none';
                                }
                                
                                selectedComment = commentBox;
                                selectedComment.style.outline = '2px solid blue';
                                
                                document.getElementById('text-color').value = rgbToHex(window.getComputedStyle(commentBox).color);
                                document.getElementById('background-color').value = rgbToHex(window.getComputedStyle(commentBox).backgroundColor);
                                
                                const currentFontSize = parseInt(window.getComputedStyle(commentBox).fontSize);
                                fontSize = currentFontSize;
                                updateFontSizeDisplay();
                                
                                document.querySelectorAll('.tool-button').forEach(b => b.classList.remove('active'));
                                document.getElementById('comment-add').classList.add('active');
                                currentTool = 'comment-add';
                                
                                document.getElementById('comment-color-picker').style.display = 'flex';
                                document.getElementById('line-color-picker').style.display = 'none';
                                document.getElementById('font-size-tools').style.display = 'flex';
                            });

                            svgElm.parent().append(commentBox);
                            commentBox.appendChild(resizeHandle);
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
                            svgElm_js.appendChild(eraseRect);
                            break;
                        case 'free-draw':
                        default:
                            pencilByMousedown(event);
                            break;
                    }
                }
            }).on(move_event, function (event) {
                event.preventDefault();
                if (type === "imageMoveAndZoom") {
                    if (typeof moveContentByMousemove === 'function') {
                        moveContentByMousemove(event);
                    }
                } else if (type === "pencil") {
                    if (isMovingLine && selectedLine) {
                        const currentX = getPositionXOnSvg(event);
                        const currentY = getPositionYOnSvg(event);
                        
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
                            if (tempLine) {
                                tempLine.setAttribute("x2", currentX);
                                tempLine.setAttribute("y2", currentY);
                            }
                            break;
                        case 'eraser':
                            if (eraseRect) {
                                const width = currentX - eraseStartX;
                                const height = currentY - eraseStartY;
                                
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
                        default:
                            pencilByMousemove(event);
                            break;
                    }
                }
            }).on(end_event, function (event) {
                event.preventDefault();
                if (type === "imageMoveAndZoom") {
                    moveContentByMouseup(event)
                } else if (type === "pencil") {
                    if (isMovingLine && selectedLine) {
                        isMovingLine = false;
                        return;
                    }
                    
                    if (currentTool === 'line-draw' && tempLine) {
                        // console.log("Line drawing completed");
                        const x1 = parseFloat(tempLine.getAttribute("x1"));
                        const y1 = parseFloat(tempLine.getAttribute("y1"));
                        const x2 = parseFloat(tempLine.getAttribute("x2"));
                        const y2 = parseFloat(tempLine.getAttribute("y2"));
                        const length = Math.sqrt(Math.pow(x2 - x1, 2) + Math.pow(y2 - y1, 2));
                        if (length < 5) {
                            tempLine.remove();
                        }
                    }
                    
                    if (currentTool === 'eraser' && eraseRect) {
                        const eraseX = parseFloat(eraseRect.getAttribute("x"));
                        const eraseY = parseFloat(eraseRect.getAttribute("y"));
                        const eraseWidth = parseFloat(eraseRect.getAttribute("width"));
                        const eraseHeight = parseFloat(eraseRect.getAttribute("height"));
                        
                        const elements = Array.from(svgElm_js.children);
                        for (let i = 0; i < elements.length; i++) {
                            const element = elements[i];
                            if (element !== eraseRect && (element.tagName === 'path' || 
                                element.tagName === 'line' || 
                                element.tagName === 'foreignObject')) {
                                
                                let isIntersecting = false;
                                
                                if (element.tagName === 'path') {
                                    const points = element.getAttribute('d').split(' ');
                                    for (let j = 0; j < points.length; j++) {
                                        if (points[j] === 'M' || points[j] === 'L') {
                                            const pointX = parseFloat(points[j + 1]);
                                            const pointY = parseFloat(points[j + 2]);
                                            if (pointX >= eraseX && pointX <= eraseX + eraseWidth &&
                                                pointY >= eraseY && pointY <= eraseY + eraseHeight) {
                                                isIntersecting = true;
                                                break;
                                            }
                                        }
                                    }
                                } else if (element.tagName === 'line') {
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
                                } else if (element.tagName === 'foreignObject') {
                                    const elemX = parseFloat(element.getAttribute('x'));
                                    const elemY = parseFloat(element.getAttribute('y'));
                                    if (elemX >= eraseX && elemX <= eraseX + eraseWidth &&
                                        elemY >= eraseY && elemY <= eraseY + eraseHeight) {
                                        isIntersecting = true;
                                    }
                                }
                                
                                if (isIntersecting) {
                                    element.remove();
                                }
                            }
                        }
                        
                        const commentBoxes = document.querySelectorAll('.comment-box');
                        const svgRect = svgElm_js.getBoundingClientRect();
                        
                        commentBoxes.forEach(box => {
                            const boxRect = box.getBoundingClientRect();
                            const boxX = (boxRect.left - svgRect.left) * (parseFloat(svgElm_js.getAttribute('viewBox').split(' ')[2]) / svgRect.width);
                            const boxY = (boxRect.top - svgRect.top) * (parseFloat(svgElm_js.getAttribute('viewBox').split(' ')[3]) / svgRect.height);
                            
                            if (boxX >= eraseX && boxX <= eraseX + eraseWidth &&
                                boxY >= eraseY && boxY <= eraseY + eraseHeight) {
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
                    if (currentTool === 'free-draw') {
                        pencilByMouseup(event);
                    }
                    tempLine = null;
                }
            })
        }

        main();

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
        })

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

            browserDrawData = {
                "id": id,
                'type': "draw",
                "d": "",
                "fill": "none",
                "stroke": "red",
                "strokeSize": "3",
                "stroke-linecap": "round",
                "x": 0,
                "y": 0
            }
            var pathElm = document.createElementNS("http://www.w3.org/2000/svg", "path");
            var path_attr = {
                "id": browserDrawData.id,
                "d": "",
                "fill": "none",
                "stroke": browserDrawData.stroke,
                "stroke-width": browserDrawData.strokeSize,
                "stroke-linecap": "round"
            }
            for (let key in path_attr) {
                pathElm.setAttribute(key, path_attr[key])
            }

            svgElm_js.appendChild(pathElm);
            
            var linetoX = [],
                linetoY = [],
                cntMoveto = 0;
            linetoStr = 'M ' + movetoX + ' ' + movetoY + ' ';
        }
        
        function pencilByMousemove(event) {
            var linetoX = [],
                linetoY = [],
                cntMoveto = 0;
                
            linetoX[cntMoveto] = parseInt(getPositionXOnSvg(event)); 
            linetoY[cntMoveto] = parseInt(getPositionYOnSvg(event)); 
            linetoStr = linetoStr + " L " + linetoX[cntMoveto] + " " + linetoY[cntMoveto]; 

            browserDrawingData = {
                'id': $("path:last").attr("id"),
                'type': "drawing",
                "d": linetoStr
            }
            $("path:last").attr("d", browserDrawingData.d);
            cntMoveto++;
        }

        function pencilByMouseup() {
            browserDrawData["d"] = linetoStr;
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

        function debugSvgElements() {
            const elements = svgElm_js.children;
            // console.log("SVG elements count:", elements.length);
            Array.from(elements).forEach((el, index) => {
                // console.log(`Element ${index}:`, el.tagName, el);
            });
        }
        
        setInterval(debugSvgElements, 5000);

        function rgbToHex(rgb) {
            if (rgb.startsWith('#')) {
                return rgb;
            }
            
            const rgbMatch = rgb.match(/^rgb\s*\(\s*(\d+)\s*,\s*(\d+)\s*,\s*(\d+)\s*\)$/i);
            if (rgbMatch) {
                const r = parseInt(rgbMatch[1], 10);
                const g = parseInt(rgbMatch[2], 10);
                const b = parseInt(rgbMatch[3], 10);
                return '#' + ((1 << 24) + (r << 16) + (g << 8) + b).toString(16).slice(1);
            }
            
            return '#000000';
        }

        $("#drawingCanvas").on('click', function(event) {
            if (event.target.classList && event.target.classList.contains('comment-box')) {
                return;
            }
            
            if (selectedComment) {
                selectedComment.style.outline = 'none';
                selectedComment = null;
            }
        });

        svgElm.on(start_event, function(event) {
            if (type === "draw") {
                if (currentTool === 'free-draw') {
                    pencilByMousedown(event);
                    isDrawing = true;
                } else if (currentTool === 'line-draw') {
                    startX = getPositionXOnSvg(event);
                    startY = getPositionYOnSvg(event);
                    
                    var currentLineColor = document.getElementById('line-color').value;
                    // console.log("描画開始時の線の色: " + currentLineColor);
                    
                    tempLine = document.createElementNS("http://www.w3.org/2000/svg", "line");
                    tempLine.setAttribute("x1", startX);
                    tempLine.setAttribute("y1", startY);
                    tempLine.setAttribute("x2", startX);
                    tempLine.setAttribute("y2", startY);
                    tempLine.setAttribute("stroke", currentLineColor);
                    tempLine.setAttribute("stroke-width", lineWidth);
                    tempLine.setAttribute("stroke-linecap", "round");
                    
                    svgElm_js.appendChild(tempLine);
                    isDrawing = true;
                } else if (currentTool === 'comment-add') {
                    const x = getPositionXOnSvg(event);
                    const y = getPositionYOnSvg(event);
                    
                    if (selectedComment) {
                        selectedComment.style.outline = 'none';
                        selectedComment = null;
                    }
                    
                    const commentBox = document.createElement('div');
                    commentBox.className = 'comment-box';
                    commentBox.contentEditable = true;
                    commentBox.style.position = 'absolute';
                    const svgRect = svgElm_js.getBoundingClientRect();
                    const clickX = event.clientX || event.touches[0].clientX;
                    const clickY = event.clientY || event.touches[0].clientY;
                    commentBox.style.left = (clickX - svgRect.left) + 'px';
                    commentBox.style.top = (clickY - svgRect.top) + 'px';
                    commentBox.style.color = textColor;
                    commentBox.style.backgroundColor = backgroundColor;
                    commentBox.style.fontSize = fontSize + 'px'; 
                    commentBox.style.zIndex = '1000';
                    commentBox.textContent = 'コメントを入力';
                    
                    const resizeHandle = document.createElement('div');
                    resizeHandle.className = 'comment-resize-handle';
                    resizeHandle.style.bottom = '0';
                    resizeHandle.style.right = '0';
                    resizeHandle.style.transform = 'translate(50%, 50%)';
                    
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

                    function resizeMove(e) {
                        if (isResizing) {
                            const deltaX = e.clientX - originalX;
                            const deltaY = e.clientY - originalY;
                            commentBox.style.width = (originalWidth + deltaX) + 'px';
                            commentBox.style.height = (originalHeight + deltaY) + 'px';
                        }
                    }

                    function resizeEnd() {
                        isResizing = false;
                        document.removeEventListener('mousemove', resizeMove);
                        document.removeEventListener('mouseup', resizeEnd);
                    }

                    commentBox.addEventListener('click', function(e) {
                        e.stopPropagation();
                        
                        if (selectedComment) {
                            selectedComment.style.outline = 'none';
                        }
                        
                        selectedComment = commentBox;
                        selectedComment.style.outline = '2px solid blue';
                        
                        document.getElementById('text-color').value = rgbToHex(window.getComputedStyle(commentBox).color);
                        document.getElementById('background-color').value = rgbToHex(window.getComputedStyle(commentBox).backgroundColor);
                        
                        const currentFontSize = parseInt(window.getComputedStyle(commentBox).fontSize);
                        fontSize = currentFontSize;
                        updateFontSizeDisplay();
                        
                        document.querySelectorAll('.tool-button').forEach(b => b.classList.remove('active'));
                        document.getElementById('comment-add').classList.add('active');
                        currentTool = 'comment-add';
                        
                        document.getElementById('comment-color-picker').style.display = 'flex';
                        document.getElementById('line-color-picker').style.display = 'none';
                        document.getElementById('font-size-tools').style.display = 'flex';
                    });

                    svgElm.parent().append(commentBox);
                    commentBox.appendChild(resizeHandle);
                } else if (currentTool === 'eraser') {
                    isDrawing = true;
                    
                    const x = getPositionXOnSvg(event);
                    const y = getPositionYOnSvg(event);
                    
                    eraseElementsAt(x, y);
                }
            }
        });

        svgElm.on(move_event, function(event) {
            if (type === "draw" && isDrawing) {
                if (currentTool === 'free-draw') {
                    pencilByMousemove(event);
                } else if (currentTool === 'line-draw' && tempLine) {
                    const currentX = getPositionXOnSvg(event);
                    const currentY = getPositionYOnSvg(event);
                    tempLine.setAttribute("x2", currentX);
                    tempLine.setAttribute("y2", currentY);
                } else if (currentTool === 'eraser') {
                    const x = getPositionXOnSvg(event);
                    const y = getPositionYOnSvg(event);
                    
                    eraseElementsAt(x, y);
                }
            }
        });

        svgElm.on(end_event, function() {
            if (type === "draw" && isDrawing) {
                if (currentTool === 'free-draw') {
                    pencilByMouseup();
                } else if (currentTool === 'line-draw' && tempLine) {
                    tempLine = null;
                }
                isDrawing = false;
            }
        });

        function eraserClick(e) {
            if (currentTool === 'eraser') {
                
                let target = e.target;
                let isCommentBox = false;
                
                while (target && !target.classList.contains('comment-box')) {
                    target = target.parentElement;
                }
                
                if (target && target.classList.contains('comment-box')) {
                    isCommentBox = true;
                    target.remove();
                    
                    if (selectedComment === target) {
                        selectedComment = null;
                    }
                }
                
                if (!isCommentBox) {
                    const eraserCursor = document.getElementById('eraser-cursor');
                    const eraserRect = eraserCursor.getBoundingClientRect();
                    const eraserX = eraserRect.left + eraserRect.width / 2;
                    const eraserY = eraserRect.top + eraserRect.height / 2;
                    
                    const commentBoxes = document.querySelectorAll('.comment-box');
                    
                    commentBoxes.forEach(box => {
                        const boxRect = box.getBoundingClientRect();
                        const boxCenterX = boxRect.left + boxRect.width / 2;
                        const boxCenterY = boxRect.top + boxRect.height / 2;
                        
                        const distance = Math.sqrt(
                            Math.pow(eraserX - boxCenterX, 2) + 
                            Math.pow(eraserY - boxCenterY, 2)
                        );
                        
                        if (distance < 30) {
                            box.remove();
                            
                            if (selectedComment === box) {
                                selectedComment = null;
                            }
                        }
                    });
                }
            }
        }

        function eraseElementsAt(x, y) {
            const elements = svgElm_js.querySelectorAll('path, line');
            const eraserRadius = 15; 
            
            elements.forEach(element => {
                if (element.tagName === 'line') {
                    const x1 = parseFloat(element.getAttribute('x1'));
                    const y1 = parseFloat(element.getAttribute('y1'));
                    const x2 = parseFloat(element.getAttribute('x2'));
                    const y2 = parseFloat(element.getAttribute('y2'));
                    
                    const distance = distanceToLine(x, y, x1, y1, x2, y2);
                    
                    if (distance < eraserRadius) {
                        element.remove();
                    }
                } else if (element.tagName === 'path') {
                    const d = element.getAttribute('d');
                    if (d) {
                        const match = d.match(/M\s*([0-9.]+)\s*,?\s*([0-9.]+)/);
                        if (match) {
                            const pathX = parseFloat(match[1]);
                            const pathY = parseFloat(match[2]);
                            const distance = Math.sqrt(Math.pow(x - pathX, 2) + Math.pow(y - pathY, 2));
                            
                            if (distance < eraserRadius) {
                                element.remove();
                            }
                        }
                    }
                }
            });
            
            const svgRect = svgElm_js.getBoundingClientRect();
            const clientX = svgRect.left + x;
            const clientY = svgRect.top + y;
            
            const commentBoxes = document.querySelectorAll('.comment-box');
            
            commentBoxes.forEach(box => {
                const boxRect = box.getBoundingClientRect();
                const boxCenterX = boxRect.left + boxRect.width / 2;
                const boxCenterY = boxRect.top + boxRect.height / 2;
                
                const distance = Math.sqrt(
                    Math.pow(clientX - boxCenterX, 2) + 
                    Math.pow(clientY - boxCenterY, 2)
                );
                
                if (distance < 30) {
                    box.remove();
                    
                    if (selectedComment === box) {
                        selectedComment = null;
                    }
                }
            });
        }

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

    });
</script>