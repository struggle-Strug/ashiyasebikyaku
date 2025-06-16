<?php $this->assign('title', '動画カテゴリー登録'); ?>

<!-- Default box -->
<div class="card">
    <?= $this->Form->create($category, ['class' => 'form-horizontal']) ?>
    <div class="card-body">
        <div class="form-group row">
            <label for="" class="col-sm-2 col-form-label text-md-right">カテゴリー名 *</label>
            <div class="col-sm-4">
                <?= $this->Form->text('name', ['class' => 'form-control', 'placeholder' => '']) ?>
                <span class="text-danger"><?= $this->Form->error('name') ?></span>
            </div>
        </div>
    </div>
    <!-- /.card-body -->
    <div class="card-footer">
        <a class="btn btn-outline-secondary" href="<?= $this->Url->build(['action' => 'index', '?' => $param]) ?>">
            <i class="fas fa-arrow-circle-left"></i> 戻る
        </a>
        <span class="float-right">
            <button type="submit" class="btn btn-primary">登録</button>
        </span>
    </div>
    <?= $this->Form->end() ?>
</div>
<!-- /.card -->
