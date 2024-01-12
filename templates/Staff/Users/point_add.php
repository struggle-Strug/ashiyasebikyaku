<?php $this->assign('title', '美脚進捗度登録'); ?>

<!-- Default box -->
<div class="card">
    <?= $this->Form->create($userPoint, ['class' => 'form-horizontal']) ?>
    <div class="card-header">
        <h4 class="card-title">氏名：<?= h($user->last_name) ?> <?= h($user->first_name) ?></h4>
    </div>
    <div class="card-body">
        <div class="form-group row">
            <label for="" class="col-sm-1 col-form-label text-md-right">仙骨</label>
            <div class="col-sm-1">
                <?= $this->Form->number('point_1', ['class' => 'form-control', 'min' => 0, 'max' => 20]) ?>
                <span class="text-danger"><?= $this->Form->error('point_1') ?></span>
            </div>
            <label for="" class="col-sm-1 col-form-label text-md-right">距骨</label>
            <div class="col-sm-1">
                <?= $this->Form->number('point_2', ['class' => 'form-control', 'min' => 0, 'max' => 20]) ?>
                <span class="text-danger"><?= $this->Form->error('point_2') ?></span>
            </div>
            <label for="" class="col-sm-1 col-form-label text-md-right">股関節</label>
            <div class="col-sm-1">
                <?= $this->Form->number('point_3', ['class' => 'form-control', 'min' => 0, 'max' => 20]) ?>
                <span class="text-danger"><?= $this->Form->error('point_3') ?></span>
            </div>
            <label for="" class="col-sm-2 col-form-label text-md-right">背骨・肋骨</label>
            <div class="col-sm-1">
                <?= $this->Form->number('point_4', ['class' => 'form-control', 'min' => 0, 'max' => 20]) ?>
                <span class="text-danger"><?= $this->Form->error('point_4') ?></span>
            </div>
            <label for="" class="col-sm-2 col-form-label text-md-right">横隔膜・鎖骨</label>
            <div class="col-sm-1">
                <?= $this->Form->number('point_5', ['class' => 'form-control', 'min' => 0, 'max' => 20]) ?>
                <span class="text-danger"><?= $this->Form->error('point_5') ?></span>
            </div>
        </div>
    </div>
    <!-- /.card-body -->
    <div class="card-footer">
        <a class="btn btn-outline-secondary" href="<?= $this->Url->build(['action' => 'point', $user->id]); ?>"><i class="fas fa-arrow-circle-left"></i> 戻る</a>
        <span class="float-right">
            <button type="submit" class="btn btn-primary">登録</button>
        </span>
    </div>
    <?= $this->Form->end() ?>
</div>
<!-- /.card -->
