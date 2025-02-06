<?php $this->assign('title', '撮影：' . $photoName[$requestPhotoNumber]); ?>
<div class="col-lg-12">
    <div class="card">
        <div class="card-content">
            <div class="nav-tabs-navigation">
                <div class="nav-tabs-wrapper">
                    <ul id="tabs" class="nav nav-tabs">
                        <li class="active"><a href="<?= $this->Url->build(['action' => 'index']); ?>">登録</a></li>
                        <li><a href="<?= $this->Url->build(['action' => 'history']); ?>">過去の記録</a></li>
                    </ul>
                </div>
            </div>
            <h5 class="text-center"><?= $photoName[$requestPhotoNumber] ?>を撮影してください</h5>

            <div class="row">

                <?php if ($requestPhotoNumber < 18): ?>
                    <div class="col-md-6" style="margin-top:10px">
                        <label class="control-label">
                            見本
                        </label>
                        <div class="text-center" style="max-width:90%">
                            <img src="/img/model/image0<?= sprintf('%02d', $requestPhotoNumber) ?>.png"
                                style="max-width:100%">
                        </div>
                    </div>
                <?php endif ?>

                <?= $this->Form->create($photo, ['class' => 'form-horizontal', 'id' => 'form', 'type' => 'file']) ?>

                <?= $this->Form->hidden('weekly_report_id', ['value' => $weeklyReport->id]) ?>
                <?= $this->Form->hidden('photo_number', ['value' => $requestPhotoNumber]) ?>

                <div class="col-md-6" style="margin-top:10px">
                    <label class="control-label">
                        撮影
                        <?php if ((!$firstWeeklyReport && $requestPhotoNumber >= 18) || ($firstWeeklyReport && ($requestPhotoNumber == 6 || $requestPhotoNumber >= 11))): ?>
                            <small>※スキップできます</small>
                        <?php else: ?>
                            <small>※必ず選択してください</small>
                        <?php endif ?>
                    </label>
                    <div class="text-center">
                        <?php if ($requestPhotoNumber == 15 || $requestPhotoNumber == 17): ?>
                            <a href="javascript:void(0)" class="btn btn-default" id="select-image">動画を選択</a>
                            <input type="file" id="image_file" name="image_file"
                                accept="video/mp4,video/m4v,video/3gpp,video/quicktime,video/webm" style="display:none">
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal"
                                id="capture-image">
                                動画を撮影（β版）
                            </button>
                            <div id="notification"></div>
                            <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog"
                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel"
                                                style="display: flex; justify-content: center; margin: 16px 0px; font-size: 24px; font-weight: bold;">
                                                カメラで動画撮影（10秒間）
                                            </h5>
                                            <div
                                                style="display: flex; justify-content: center; margin: 16px 0px; font-size: 20px; font-weight: bold;">
                                                <span id="countdown"
                                                    style="font-size: 20px; color: red;">5</span><span>秒後に撮影開始</span></div>
                                        </div>
                                        <div class="modal-body" style="position: relative;">
                                            <video style="width: 100%; height: auto;" id="video" autoplay
                                                playsinline></video>
                                            <!-- <video  controls
                                                style="width: 100%; height: auto; display: none;"></video> -->
                                            <img id="photo"
                                                style="position: absolute; top: 0; left: 0; object-fit: fill; width: 100%; height: 100%"
                                                src="/img/weekly-reports-img/weekly-report-<?= $requestPhotoNumber ?>.png">
                                            <div><?= $captureComment[$requestPhotoNumber] ?></div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-primary" id="switchCamera">カメラ切り替え</button>
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal"
                                                id="cancel-capture-movie">キャンセル</button>
                                            <button type="button" class="btn btn-primary" id="capture-video"
                                                onclick="CaptureVideo()">撮影</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php elseif ($requestPhotoNumber >= 18): ?>
                            <a href="javascript:void(0)" class="btn btn-default" id="select-image">写真または動画を選択</a>

                            <input type="file" id="image_file" name="image_file"
                                accept="video/mp4,video/m4v,video/3gpp,video/quicktime,image/jpeg,image/x-png"
                                style="display:none">

                        <?php else: ?>
                            <a href="javascript:void(0)" class="btn btn-default" id="select-image">写真を選択</a>
                            <input type="file" id="image_file" name="image_file" accept="image/jpeg,image/x-png"
                                style="display:none">
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal"
                                id="capture-image">
                                写真を撮影（β版）
                            </button>
                            <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog"
                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel"
                                                style="display: flex; justify-content: center; margin: 16px 0px; font-size: 24px; font-weight: bold;">
                                                カメラで撮影
                                            </h5>
                                            <div
                                                style="display: flex; justify-content: center; margin: 16px 0px; font-size: 20px; font-weight: bold;">
                                                <span id="countdown"
                                                    style="font-size: 20px; color: red;">5</span><span>秒後に撮影開始</span></div>
                                        </div>
                                        <div class="modal-body" style="position: relative;">
                                            <video style="width: 100%; height: auto;" id="video" autoplay
                                                playsinline></video>
                                            <img id="photo"
                                                style="position: absolute; top: 0; left: 0; object-fit: fill; width: 100%; height: 100%"
                                                src="/img/weekly-reports-img/weekly-report-<?= $requestPhotoNumber ?>.png">
                                            <div><?= $captureComment[$requestPhotoNumber] ?></div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-primary" id="switchCamera">カメラ切り替え</button>
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal"
                                                id="cancel-capture">キャンセル</button>
                                            <button type="button" class="btn btn-primary" id="capture"
                                                onclick="CaptureImage()">撮影</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <img id="photo" src="#" alt="写真がここに表示されます" style="display: none;" />
                        <?php endif ?>
                        <div style="margin-top:10px;max-width:100%">
                            <span id="selected-movie" style="display:none">ファイルが選択されました</span>
                            <?php if ($photo->image_url): ?>
                                <?php if (strstr($photo->image_url, '.mp4') === false && strstr($photo->image_url, '.m4v') === false && strstr($photo->image_url, '.3gp') === false && strstr($photo->image_url, '.mov') === false && strstr($photo->image_url, '.webm') === false): ?>
                                    <img src="<?= ($photo) ? h($photo->image_url) : ''; ?>" id="selected-image"
                                        style="max-width:90%;<?php if (!$photo || !$photo->image_url): ?>display:none<?php endif; ?>">
                                <?php else: ?>
                                    <video src="<?= $photo->image_url ?>" style="max-width:100%;" id="recordedVideo" controls
                                        playsinline></video>
                                <?php endif ?>
                            <?php else: ?>
                                <?php if ($requestPhotoNumber == 15 || $requestPhotoNumber == 17): ?>
                                    <video style="max-width:100%;" id="recordedVideo" controls
                                        playsinline></video>
                                <?php else: ?>
                                <img src="<?= ($photo) ? h($photo->image_url) : ''; ?>" id="selected-image"
                                        style="max-width:90%;<?php if (!$photo || !$photo->image_url): ?>display:none<?php endif; ?>">
                                <?php endif ?>
                            <?php endif ?>
                        </div>
                        <span class="text-danger"><?= $this->Form->error('image_file') ?></span>
                    </div>
                </div>

            </div>

            <div class="text-center" style="margin:30px">
                <input type="hidden" name="is_confirm" id="is_confirm" value="">
                <button type="submit" class="btn btn-info btn-fill btn-wd">保存して次の撮影に進む</button>
            </div>
            <?php if ((!$firstWeeklyReport && $nowPhotoNumber >= 17) || ($firstWeeklyReport && $nowPhotoNumber >= 10)): // 指定枚数撮影済み ?>
                <div class="text-center" style="margin:30px">
                    <a href="<?= $this->Url->build(['action' => 'confirm', $weeklyReport->id]); ?>"
                        class="btn btn-info btn-fill btn-wd btn-send">最終確認画面へ進む</a>
                </div>
            <?php endif ?>
            <?php if ($requestPhotoNumber > 1): ?>
                <div class="text-center" style="margin:30px">
                    <a href="<?= $this->Url->build(['action' => 'edit', $weeklyReport->id, ($requestPhotoNumber - 1)]); ?>"
                        class="btn btn-default">前の撮影に戻る</a>
                </div>
            <?php endif ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
<script>
    $(function () {
        $('.btn-send').click(function () {
            $('#is_confirm').val('1');
            $('#form').submit();
        });

        $('#select-image').click(function () {
            $('#image_file').click();
        });

        console.log('写真データ:', <?= $photo && (strstr($photo->image_url, '.mp4') === false && strstr($photo->image_url, '.m4v') === false && strstr($photo->image_url, '.3gp') === false && strstr($photo->image_url, '.mov') === false && strstr($photo->image_url, '.webm') === false) ?>);

        const video = document.getElementById('video');
        const photo = document.getElementById('photo');

        video.addEventListener('loadedmetadata', () => {
            photo.style.height = `${video.clientHeight}px`;
        });

        $("#image_file").on('change', function (e) {
            var fileList = document.getElementById("image_file").files;
            file_type = fileList[0].type;

            if (file_type == 'video/mp4' || file_type == 'video/m4v' || file_type == 'video/3gpp' || file_type == 'video/quicktime') {
                $("#selected-movie").show();
                $("#recordedVideo").attr('src', URL.createObjectURL(fileList[0]));
                $("#recordedVideo").get(0).play();
                return false;
            }

            var reader = new FileReader();
            reader.onload = function (e) {
                $("#selected-image").show();
                $("#selected-image").attr('src', e.target.result);
            }
            reader.readAsDataURL(e.target.files[0]);
        });

        document.getElementById('capture-image').addEventListener('click', function () {
            const video = document.getElementById('video');

            navigator.mediaDevices.getUserMedia({ video: true })
                .then(function (stream) {
                    video.srcObject = stream;
                    video.play();
                })
                .catch(function (err) {
                    console.log("カメラへのアクセスに失敗しました: " + err);
                });
        });
        // document.querySelector('.close').addEventListener('click', function () {
        //     const modal = document.getElementById('cameraModal');
        //     modal.style.display = 'none';
        //     stopVideoStream();
        // });
    });
    function CaptureVideo() {
        let countdown = 5;
        const countdownElement = document.getElementById('countdown');
        const video = document.getElementById('video');
        const recordedVideo = document.getElementById('recordedVideo');
        const videoFileInput = document.getElementById('image_file');
        let mediaRecorder;
        let recordedChunks = [];

        console.log(recordedVideo)

        // カウントダウン開始
        const countdownInterval = setInterval(() => {
            countdownElement.textContent = countdown;
            countdown--;

            if (countdown < 0) {
                clearInterval(countdownInterval); // カウントダウン終了
                countdownElement.textContent = ''; // カウントダウン表示をクリア

                // 動画の録画を開始
                startRecording();
            }
        }, 1000); // 1秒ごとにカウントダウンを更新

        // 録画を開始する関数
        function startRecording() {
            navigator.mediaDevices.getUserMedia({ video: true, audio: true })
                .then(stream => {
                    video.srcObject = stream;
                    video.play();

                    // MediaRecorderを初期化
                    mediaRecorder = new MediaRecorder(stream);
                    mediaRecorder.ondataavailable = event => {
                        if (event.data.size > 0) {
                            recordedChunks.push(event.data);
                        }
                    };

                    mediaRecorder.onstop = () => {
                        // 録画データをBlobに変換
                        const blob = new Blob(recordedChunks, { type: 'video/webm' });
                        const url = URL.createObjectURL(blob);

                        // プレビュー用のvideo要素に設定
                        recordedVideo.src = url;
                        recordedVideo.style.display = 'block';

                        // ダウンロードリンクの作成（任意）
                        const downloadLink = document.createElement('a');
                        downloadLink.href = url;
                        downloadLink.download = 'recorded-video.webm';
                        downloadLink.textContent = 'ダウンロード';
                        document.body.appendChild(downloadLink);
                        // Fileオブジェクトを作成
                        const file = new File([blob], "recorded-video.webm", { type: "video/webm" });

                        // Fileオブジェクトをinput要素に設定
                        const dataTransfer = new DataTransfer();
                        dataTransfer.items.add(file);
                        videoFileInput.files = dataTransfer.files;
                        recordedVideo.src = url;
                        recordedVideo.style.display = 'block';

                        if (videoFileInput.files.length > 0) {
                            document.getElementById("notification").innerHTML = "動画が登録されました。";
                        } else {
                            document.getElementById("notification").innerHTML = "動画が登録されていませんでした。";
                        }


                        document.getElementById('cancel-capture-movie').click();

                        // ビデオストリームを停止
                        stopVideoStream(stream);
                    };

                    // 録画を開始し、5秒後に停止
                    mediaRecorder.start();
                    setTimeout(() => {
                        mediaRecorder.stop();
                    }, 10000); // 10秒間録画
                })
                .catch(err => console.error('カメラにアクセスできません:', err));
        }

        // ストリームを停止する関数
        function stopVideoStream(stream) {
            const tracks = stream.getTracks();
            tracks.forEach(track => track.stop());
        }
    }

    let currentStream = null; // 現在のビデオストリーム
    let isFrontCamera = true;

    function startVideo(facingMode = 'user') {
        if (currentStream) {
            // 既存のストリームを停止
            currentStream.getTracks().forEach(track => track.stop());
        }

        navigator.mediaDevices.getUserMedia({
            video: { facingMode: facingMode },
            audio: false // 必要に応じて音声を有効化
        })
            .then(stream => {
                currentStream = stream;
                const videoElement = document.getElementById('video');
                videoElement.srcObject = stream;
                videoElement.play();
            })
            .catch(error => {
                console.error('カメラにアクセスできません:', error);
            });
    }

    startVideo();

    document.getElementById('switchCamera').addEventListener('click', () => {
        isFrontCamera = !isFrontCamera; // フロント/バックを切り替え
        startVideo(isFrontCamera ? 'user' : 'environment'); // facingModeを切り替え
    });


    function CaptureImage() {
        let countdown = 5;
        const countdownElement = document.getElementById('countdown');
        const selectedImage = document.getElementById('selected-image'); // 追加

        // カウントダウンを開始
        const countdownInterval = setInterval(() => {
            countdownElement.textContent = countdown;
            countdown--;

            if (countdown < 0) {
                clearInterval(countdownInterval); // カウントダウンを停止
                countdownElement.textContent = ''; // カウントダウン表示をクリア

                // 5秒後にキャプチャ処理を実行
                const canvas = document.createElement('canvas');
                const video = document.getElementById('video');
                const photo = document.getElementById('photo');
                const imageFileInput = document.getElementById('image_file');

                // キャプチャした画像をcanvasに描画
                canvas.width = video.videoWidth;
                canvas.height = video.videoHeight;
                const context = canvas.getContext('2d');
                context.drawImage(video, 0, 0, canvas.width, canvas.height);

                // データURLを取得
                const dataURL = canvas.toDataURL('image/png');

                // データURLをFileオブジェクトに変換
                fetch(dataURL)
                    .then(res => res.blob())
                    .then(blob => {
                        const file = new File([blob], "captured-image.png", { type: "image/png" });

                        console.log(file)

                        // Fileオブジェクトをinput要素に設定
                        const dataTransfer = new DataTransfer();
                        dataTransfer.items.add(file);
                        imageFileInput.files = dataTransfer.files;

                        selectedImage.src = URL.createObjectURL(blob);
                        selectedImage.style.display = 'block';

                        document.getElementById('cancel-capture').click();
                    });

                // ビデオストリームを停止
                stopVideoStream();
            }
        }, 1000); // 1秒ごとにカウントダウンを更新


        function stopVideoStream() {
            const video = document.getElementById('video');
            const stream = video.srcObject;
            if (stream) {
                const tracks = stream.getTracks();
                tracks.forEach(track => track.stop());
            }
        }
    }
</script>