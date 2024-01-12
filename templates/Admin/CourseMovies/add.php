<?php $this->assign('title', h($course->name) . ' 動画登録'); ?>

<!-- Default box -->
<div class="card">
    <?= $this->Form->create($courseMovie, ['class' => 'form-horizontal', 'id' => 'form', 'type' => 'file']) ?>
    <div class="card-body">
        <div class="form-group row">
            <label for="" class="col-sm-2 col-form-label text-md-right">動画名 *</label>
            <div class="col-sm-4">
                <?= $this->Form->text('name', ['class' => 'form-control']) ?>
                <span class="text-danger"><?= $this->Form->error('name') ?></span>
            </div>
            <label for="" class="col-sm-2 col-form-label text-md-right">サムネイル画像</label>
            <div class="col-sm-4">
                <a href="javascript:void(0)" class="btn btn-default" id="select-image">画像を選択</a>
                <input type="file" id="image_file" name="image_file" accept="image/jpeg,image/x-png" style="display:none">
                <div style="max-width:90%">
                    <img src="<?= ($courseMovie) ? h($courseMovie->image_url) : ''; ?>" id="selected-image" style="max-width:90%;<?php if (!$courseMovie || !$courseMovie->image_url) : ?>display:none<?php endif; ?>">
                </div>
                <span class="text-danger"><?= $this->Form->error('image_file') ?></span>
            </div>
        </div>
        <div class="form-group row">
            <label for="" class="col-sm-2 col-form-label text-md-right">動画URL *</label>
            <div class="col-sm-10">
                <?= $this->Form->url('movie_url', ['class' => 'form-control', 'placeholder' => 'https://player.vimeo.com/video/xxxxxxxx']) ?>
                <span class="text-danger"><?= $this->Form->error('movie_url') ?></span>
            </div>
        </div>
        <div class="form-group row">
            <label for="" class="col-sm-2 col-form-label text-md-right">解説動画URL</label>
            <div class="col-sm-10">
                <?= $this->Form->url('info_movie_url', ['class' => 'form-control', 'placeholder' => 'https://player.vimeo.com/video/xxxxxxxx']) ?>
                <span class="text-danger"><?= $this->Form->error('info_movie_url') ?></span>
            </div>
        </div>
        <div class="form-group row">
            <label for="" class="col-sm-2 col-form-label text-md-right">概要説明</label>
            <div class="col-sm-10">
                <?= $this->Form->textarea('note', ['class' => 'form-control']) ?>
                <span class="text-danger"><?= $this->Form->error('note') ?></span>
            </div>
        </div>
    </div>
    <!-- /.card-body -->
    <div class="card-footer">
        <a class="btn btn-outline-secondary" href="<?= $this->Url->build(['action' => 'index', $course->id]); ?>"><i class="fas fa-arrow-circle-left"></i> 戻る</a>
        <span class="float-right">
            <button type="submit" class="btn btn-primary">登録</button>
        </span>
    </div>
    <?= $this->Form->end() ?>
</div>
<!-- /.card -->

<script>
    $(function(){
        // 画像選択
        $('#select-image').click(function(){
          $('#image_file').click();
        });

        $("#image_file").on('change', function(e) {
          var reader = new FileReader();
          reader.onload = function (e) {
            $("#selected-image").show();
            $("#selected-image").attr('src', e.target.result);
          }
          reader.readAsDataURL(e.target.files[0]);
        });
    });
</script>
