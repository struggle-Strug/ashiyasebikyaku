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
</style>
<div class="card">
    <div class="card-body">
        <div style="display: flex;">
            <div class="p-sm-2 p-md-4 py-2"><button class="btn btn-outline-primary" id="board-type">書き込みモードOFF</button>
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
        var type = "imageMoveAndZoom";
        var touchType = "move";


        function main() {
            $("#drawingCanvas").on(start_event, function (event) {
                event.preventDefault();
                if (type === "imageMoveAndZoom") {
                    moveContentByMousedown(event)
                } else if (type === "pencil") {
                    pencilByMousedown(event);
                } else {
                }
            }).on(end_event, function (event) {
                event.preventDefault();
                if (type === "imageMoveAndZoom") {
                    moveContentByMouseup(event)
                } else if (type === "pencil") {
                    pencilByMouseup(event);
                } else {
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
            } else {
                type = "imageMoveAndZoom"
                this.classList.remove("btn-primary");
                this.classList.add("btn-outline-primary")
                this.textContent = "書き込みモードOFF";
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

        var svgElm_js = document.getElementById("drawingCanvas")
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

        function pencilByMousedown() {
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

            $("#drawingCanvas").append(pathElm);
            var linetoX = [],
                linetoY = [],
                cntMoveto = 0;
            linetoStr = 'M ' + movetoX + ' ' + movetoY + ' ';

            /* ドラッグ中 */
            $("#drawingCanvas").on(move_event, function (event) {
                event.preventDefault();
                linetoX[cntMoveto] = parseInt(getPositionXOnSvg(event)); //SVG上のマウス座標(横方向)の取得
                linetoY[cntMoveto] = parseInt(getPositionYOnSvg(event)); //SVG上のマウス座標(縦方向)の取得
                linetoStr = linetoStr + " L " + linetoX[cntMoveto] + " " + linetoY[cntMoveto]; //動いた後の新たなマウス座標を描画点として追加

                // svgElm.find("path:last").attr("d", linetoStr); //pathデータ(d属性)の値を更新
                browserDrawingData = {
                    'id': pathElm.id,
                    'type': "drawing",
                    "d": linetoStr
                }
                pathElm.setAttribute("d", browserDrawingData.d);
                cntMoveto++;
                $("#drawingCanvas").on(out_event, function (event) {
                    pencilByMouseup()
                })
            });
        }

        function pencilByMouseup() {
            browserDrawData["d"] = linetoStr;
            $("#drawingCanvas").off(move_event); //pathの描画を終了
            $("#drawingCanvas").off(out_event);
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
            clipRightRect.height.baseVal.value = imageHeight
            const textAspect = 45 / 1069;
            const textSize = svgElm_js_width * textAspect;
            const y = 20;
            createTextBox(20, y + imageHeight + textSize, 0, 0, "jgnjakfonk", document.getElementById("subject-image-date").value, textSize, "black")
            createImage(x, y, 0, 0, imageWidth, imageHeight, image, "left-image", "url(#clipleft)")
        }

        function leftDisplay(aspectRatio, image) {
            const leftWidth = svgElm_js_width / 2;
            const imageWidth = leftWidth;
            const imageHeight = imageWidth / aspectRatio;
            const centerPosX = (leftWidth / 2) + leftWidth;
            const imageWidthHalf = imageWidth / 2;
            const x = leftWidth;
            console.log(svgElm_js_width)
            const textAspect = 45 / 1069;
            const textSize = svgElm_js_width * textAspect;
            clipLeftRect.height.baseVal.value = imageHeight
            svgElm_js.style.height = imageHeight + 100
            var [minX, minY, width, height] = svgElm_js.getAttribute('viewBox').split(' ')
                .map(s => parseFloat(s));
            var init_viewBox = String(minX) + " " + String(minY) + " " + String(svgElm_js_width) + " " + String(imageHeight + 100)
            svgElm_js.setAttribute("viewBox", init_viewBox)

            const y = 20;
            createTextBox(x, y + imageHeight + textSize, 0, 0, "jgnjakfonk", document.getElementById("target-image-date").value, textSize, "black")
            createImage(x, y, 0, 0, imageWidth, imageHeight, image, "right-image", "url(#clipright)")
        }

        var aspectRatio;

        function createImageAspectTarget(image) {
            const imageUrl = image;
            const img = new Image();
            img.onload = function (result) {
                aspectRatio = img.naturalWidth / img.naturalHeight;
                console.log(`Aspect Ratio: ${aspectRatio} (${img.naturalWidth} / ${img.naturalHeight})`);
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
                console.log(`Aspect Ratio: ${aspectRatio} (${img.naturalWidth} / ${img.naturalHeight})`);
                const canvas = document.createElement('canvas');
                const ctx = canvas.getContext('2d');
                canvas.width = img.width;
                canvas.height = img.height;
                ctx.drawImage(img, 0, 0);
                const base64ImageData = canvas.toDataURL('image/png');
                rightDisplay(aspectRatio, base64ImageData);
            };
            img.onerror = function () {
                console.error('画像を読み込めませんでした。');
            };
            img.crossOrigin = 'Anonymous';
            img.src = imageUrl;
        }

        createImageAspectTarget(document.getElementById("target_image_url").src);
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

        // function downloadBoard() {
        //     var svg = document.querySelector("svg");
        //     var svgData = new XMLSerializer().serializeToString(svg);
        //     var canvas = document.createElement("canvas");
        //     canvas.width = svg.width.baseVal.value;
        //     canvas.height = svg.height.baseVal.value;

        //     var ctx = canvas.getContext("2d");
        //     var image = new Image;
        //     image.onload = function () {
        //         ctx.drawImage(image, 0, 0);
        //         const pngDataUrl = canvas.toDataURL('image/png');
        //         document.getElementById("preview").src = pngDataUrl;
        //         uploadImageToServer(pngDataUrl);
        //     }
        //     image.src = "data:image/svg+xml;charset=utf-8;base64," + btoa(unescape(encodeURIComponent(svgData)));
        // }

        // function previewImage() {
        //     var svg = document.querySelector("svg");
        //     var svgData = new XMLSerializer().serializeToString(svg);
        //     var canvas = document.createElement("canvas");
        //     canvas.width = svg.width.baseVal.value;
        //     canvas.height = svg.height.baseVal.value;

        //     var ctx = canvas.getContext("2d");
        //     var image = new Image;
        //     image.onload = function () {
        //         ctx.drawImage(image, 0, 0);
        //         const pngDataUrl = canvas.toDataURL('image/png');
        //         document.getElementById("preview").src = pngDataUrl;
        //         document.getElementById("preview").style.display = "block";
        //     }
        //     image.src = "data:image/svg+xml;charset=utf-8;base64," + btoa(unescape(encodeURIComponent(svgData)));
        // }

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
                const svg = document.querySelector("svg");
                const svgData = new XMLSerializer().serializeToString(svg);
                const canvas = document.createElement("canvas");

                const width = svg.width.baseVal.value;
                const height = svg.height.baseVal.value;

                const ctx = canvas.getContext("2d");
                const pixelRatio = window.devicePixelRatio || 1;
                console.log(pixelRatio)
                canvas.width = svg.width.baseVal.value * pixelRatio;
                canvas.height = svg.height.baseVal.value * pixelRatio;
                canvas.style.width = svg.width.baseVal.value + "px";
                canvas.style.height = svg.height.baseVal.value + "px";

                ctx.setTransform(pixelRatio, 0, 0, pixelRatio, 0, 0);
                const svgBlob = new Blob([svgData], { type: "image/svg+xml;charset=utf-8" });
                const url = URL.createObjectURL(svgBlob);

                const image = await loadImage(url);

                console.log(parseInt(canvas.width))
                console.log(parseInt(canvas.style.width))
                console.log(aspectRatio)

                ctx.drawImage(image, 0, 0, parseInt(canvas.style.width) / pixelRatio, parseInt(canvas.style.height)/ pixelRatio);
                URL.revokeObjectURL(url);

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
                const canvas = document.createElement("canvas");

                const ctx = canvas.getContext("2d");
                const pixelRatio = window.devicePixelRatio || 1;
                const viewBox = svg.viewBox.baseVal;
                canvas.width = viewBox.width * pixelRatio;
                canvas.height = viewBox.height * pixelRatio;

                // スタイルの幅と高さを設定
                canvas.style.width = viewBox.width + "px";
                canvas.style.height = viewBox.height + "px";

                ctx.setTransform(pixelRatio, 0, 0, pixelRatio, 0, 0);
                const svgBlob = new Blob([svgData], { type: "image/svg+xml;charset=utf-8" });
                const url = URL.createObjectURL(svgBlob);

                const image = await loadImage(url);
                ctx.drawImage(image, 0, 0, parseInt(canvas.style.width), parseInt(canvas.style.height));
                URL.revokeObjectURL(url);

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
        // }
        // });

        document.getElementById("submit").addEventListener("click", async function () {
            await downloadBoard()
        })


        function uploadImageToServer(pngDataUrl) {
            const formData = new FormData();
            const comment = document.getElementById("comment").value;
            const target_image = "<?= $param["target_id"] ?>"
            const subject_image = "<?= $param["subject_id"] ?>"
            const user = "<?= $param["user"] ?>"
            formData.append('image', pngDataUrl);
            formData.append('comment', comment);
            formData.append('target_image', target_image);
            formData.append('subject_image', subject_image);
            formData.append('user_id', user);
            const nextURL = "/admin/compared-images?start_report_date=&end_report_date=&user_id=" + user;
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
                if (move_event !== "touchmove") {
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
        }

        function moveContentByMouseup() {
            svgElm.css('cursor', 'default');
            $("#drawingCanvas").off(move_event);
            $("#drawingCanvas").off(out_event);
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

    });
</script>