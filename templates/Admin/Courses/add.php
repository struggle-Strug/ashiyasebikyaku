<?php $this->assign('title', '講座登録'); ?>

<!-- Default box -->
<div class="card">
    <?= $this->Form->create($course, ['class' => 'form-horizontal']) ?>
    <div class="card-body">
        <div class="form-group row">
            <label for="" class="col-sm-2 col-form-label text-md-right">講座タイプ *</label>
            <div class="col-sm-4">
                <?= $this->Form->select('type', $courseType, ['class' => 'form-control', 'placeholder' => '']) ?>
                <span class="text-danger"><?= $this->Form->error('type') ?></span>
            </div>
            <label for="" class="col-sm-2 col-form-label text-md-right">講座名 *</label>
            <div class="col-sm-4">
                <?= $this->Form->text('name', ['class' => 'form-control', 'placeholder' => '']) ?>
                <span class="text-danger"><?= $this->Form->error('name') ?></span>
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
        <a class="btn btn-outline-secondary" href="<?= $this->Url->build(['action' => 'index']); ?>"><i class="fas fa-arrow-circle-left"></i> 戻る</a>
        <span class="float-right">
            <button type="submit" class="btn btn-primary">登録</button>
        </span>
    </div>
    <?= $this->Form->end() ?>
</div>
<!-- /.card -->
