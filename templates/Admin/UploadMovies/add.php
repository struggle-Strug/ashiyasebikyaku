<?php $this->assign('title', '新規動画追加'); ?>

<!-- Default box -->
<div class="card">
    <?= $this->Form->create($uploadMovie, ['class' => 'form-horizontal', 'id' => 'form', 'type' => 'file']) ?>
    <div class="card-body">
        <div class="form-group row">
            <label for="" class="col-sm-2 col-form-label text-md-right">動画名 *</label>
            <div class="col-sm-4">
                <?= $this->Form->text('name', ['class' => 'form-control']) ?>
                <span class="text-danger"><?= $this->Form->error('name') ?></span>
            </div>
        </div>
        <div class="form-group row">
            <label for="" class="col-sm-2 col-form-label text-md-right">アップロード動画 *</label>
            <div class="col-sm-4">
                <a href="javascript:void(0)" class="btn btn-default" id="select-video">ファイルを選択</a>
                <input type="file" id="video_file" name="video_file" accept="video/*" style="display:none">
                <!-- <div style="max-width:90%">
                    <p src="<?= ($uploadMovie) ? h($uploadMovie->video_url) : ''; ?>" id="selected-video" style="max-width:90%;<?php if (!$uploadMovie || !$uploadMovie->video_url) : ?>display:none<?php endif; ?>">
                </div> -->
                <p id="video_name"></p>
                <span class="text-danger"><?= $this->Form->error('video_file') ?></span>
            </div>
        </div>
    </div>
    <!-- /.card-body -->
    <div class="card-footer">
        <a class="btn btn-outline-secondary" href="<?= $this->Url->build(['action' => 'index']); ?>"><i class="fas fa-arrow-circle-left"></i> 戻る</a>
        <span class="float-right">
            <button type="submit" class="btn btn-primary">登録</button>
        </span>
    </div>
    <?= $this->Form->end() ?>
</div>
<!-- /.card -->

<script>
    $(function(){
        // ファイルを選択
        $('#select-video').click(function(){
          $('#video_file').click();
        });

        $("#video_file").on('change', function(e) {
          var reader = new FileReader();
          reader.onload = function (e) {
            $("#selected-video").show();
            $("#selected-video").attr('src', e.target.result);
          }
          reader.readAsDataURL(e.target.files[0]);
        });
    });

    document.getElementById('video_file').addEventListener('change', function(event) {
        var video_file = event.target;
        var video_name = video_file.files[0] ? video_file.files[0].name : 'ファイルが選択されていません';
        document.getElementById('video_name').textContent = video_name;
    });
</script>
