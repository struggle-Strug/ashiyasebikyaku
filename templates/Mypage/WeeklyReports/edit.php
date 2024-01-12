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

            <?php if ($requestPhotoNumber < 18) : ?>
                <div class="col-md-6" style="margin-top:10px">
                    <label class="control-label">
                        見本
                    </label>
                    <div class="text-center" style="max-width:90%">
                        <img src="/img/model/image0<?= sprintf('%02d', $requestPhotoNumber) ?>.png" style="max-width:100%">
                    </div>
                </div>
            <?php endif ?>

            <?= $this->Form->create($photo, ['class' => 'form-horizontal', 'id' => 'form', 'type' => 'file']) ?>

            <?= $this->Form->hidden('weekly_report_id', ['value' => $weeklyReport->id]) ?>
            <?= $this->Form->hidden('photo_number', ['value' => $requestPhotoNumber]) ?>

                <div class="col-md-6" style="margin-top:10px">
                    <label class="control-label">
                        撮影
                        <?php if ((!$firstWeeklyReport && $requestPhotoNumber >= 18) || ($firstWeeklyReport && ($requestPhotoNumber == 6 || $requestPhotoNumber >= 11))) : ?>
                            <small>※スキップできます</small>
                        <?php else : ?>
                            <small>※必ず選択してください</small>
                        <?php endif ?>
                    </label>
                    <div class="text-center">
                        <?php if ($requestPhotoNumber == 15 || $requestPhotoNumber == 17) : ?>
                            <a href="javascript:void(0)" class="btn btn-default" id="select-image">動画を選択</a>
                            <input type="file" id="image_file" name="image_file" accept="video/mp4,video/m4v,video/3gpp,video/quicktime" style="display:none">
                        <?php elseif ($requestPhotoNumber >= 18) : ?>
                            <a href="javascript:void(0)" class="btn btn-default" id="select-image">写真または動画を選択</a>
                            <input type="file" id="image_file" name="image_file" accept="video/mp4,video/m4v,video/3gpp,video/quicktime,image/jpeg,image/x-png" style="display:none">
                        <?php else : ?>
                            <a href="javascript:void(0)" class="btn btn-default" id="select-image">写真を撮影</a>
                            <input type="file" id="image_file" name="image_file" accept="image/jpeg,image/x-png" style="display:none">
                        <?php endif ?>
                        <div style="margin-top:10px;max-width:100%">
                            <span id="selected-movie" style="display:none">ファイルが選択されました</span>
                            <?php if ($photo && (strstr($photo->image_url, '.mp4') === false && strstr($photo->image_url, '.m4v') === false && strstr($photo->image_url, '.3gp') === false && strstr($photo->image_url, '.mov') === false)) : ?>
                                <img src="<?= ($photo) ? h($photo->image_url) : ''; ?>" id="selected-image"
                                    style="max-width:90%;<?php if (!$photo || !$photo->image_url) : ?>display:none<?php endif; ?>">
                            <?php else : ?>
                                <video src="<?= $photo->image_url ?>" style="max-width:100%;" controls></video>
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
            <?php if ((!$firstWeeklyReport && $nowPhotoNumber >= 17) || ($firstWeeklyReport && $nowPhotoNumber >= 10)) : // 指定枚数撮影済み ?>
                <div class="text-center" style="margin:30px">
                    <a href="<?= $this->Url->build(['action' => 'confirm', $weeklyReport->id]); ?>" class="btn btn-info btn-fill btn-wd btn-send">最終確認画面へ進む</a>
                </div>
            <?php endif ?>
            <?php if ($requestPhotoNumber > 1) : ?>
                <div class="text-center" style="margin:30px">
                    <a href="<?= $this->Url->build(['action' => 'edit', $weeklyReport->id, ($requestPhotoNumber - 1)]); ?>" class="btn btn-default">前の撮影に戻る</a>
                </div>
            <?php endif ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
<!-- /.card -->

<script>
$(function() {
    // データを送信する
    $('.btn-send').click(function() {
        $('#is_confirm').val('1');
        $('#form').submit();
    });

    // 画像選択
    $('#select-image').click(function() {
        $('#image_file').click();
    });

    $("#image_file").on('change', function(e) {
        var fileList = document.getElementById("image_file").files;
        file_type = fileList[0].type;

        // console.log(file_type);

        if (file_type == 'video/mp4' || file_type == 'video/m4v' || file_type == 'video/3gpp' || file_type == 'video/quicktime') {
            $("#selected-movie").show();
            return false;
        }

        var reader = new FileReader();
        reader.onload = function(e) {
            $("#selected-image").show();
            $("#selected-image").attr('src', e.target.result);
        }
        reader.readAsDataURL(e.target.files[0]);
    });
});
</script>
