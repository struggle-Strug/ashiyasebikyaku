<?php $this->assign('title', '身体データ登録'); ?>

<!-- Default box -->
<div class="card">
    <?= $this->Form->create($user, ['class' => 'form-horizontal', 'id' => 'body-data-form']) ?>
    <div class="card-body">
        <div class="form-group row">
            <label for="" class="col-sm-1 col-form-label text-md-right">測定日</label>
            <div class="col-sm-4">
                <?= $this->Form->datetime('date', ['class' => 'form-control', 'step' => 60]) ?>
                <span class="text-danger"><?= $this->Form->error('date') ?></span>
            </div>
        </div>
        <div class="form-group row">
            <label for="" class="col-sm-1 col-form-label text-md-right">足首</label>
            <div class="col-sm-1">
                <?= $this->Form->select('ankle', $bodyDataOptions, ['class' => 'form-control', 'placeholder' => '', 'default' => '']) ?>
                <span class="text-danger"><?= $this->Form->error('ankle') ?></span>
            </div>
            <label for="" class="col-sm-1 col-form-label text-md-right">足の甲</label>
            <div class="col-sm-1">
                <?= $this->Form->select('instep', $bodyDataOptions, ['class' => 'form-control', 'placeholder' => '', 'default' => '']) ?>
                <span class="text-danger"><?= $this->Form->error('instep') ?></span>
            </div>
            <label for="" class="col-sm-1 col-form-label text-md-right">縦アーチ</label>
            <div class="col-sm-1">
                <?= $this->Form->select('vertical_arch', $bodyDataOptions, ['class' => 'form-control', 'placeholder' => '', 'default' => '']) ?>
                <span class="text-danger"><?= $this->Form->error('vertical_arch') ?></span>
            </div>
            <label for="" class="col-sm-1 col-form-label text-md-right">横アーチ</label>
            <div class="col-sm-1">
                <?= $this->Form->select('horizontal_arch', $bodyDataOptions, ['class' => 'form-control', 'placeholder' => '', 'default' => '']) ?>
                <span class="text-danger"><?= $this->Form->error('horizontal_arch') ?></span>
            </div>
            <label for="" class="col-sm-1 col-form-label text-md-right">足指</label>
            <div class="col-sm-1">
                <?= $this->Form->select('toes', $bodyDataOptions, ['class' => 'form-control', 'placeholder' => '', 'default' => '']) ?>
                <span class="text-danger"><?= $this->Form->error('toes') ?></span>
            </div>
        </div>
        <div class="form-group row">
            <label for="" class="col-sm-1 col-form-label text-md-right">備考</label>
            <div class="col-sm-6">
                <?= $this->Form->textarea('note', ['class' => 'form-control', 'placeholder' => '']) ?>
                <span class="text-danger"><?= $this->Form->error('note') ?></span>
            </div>
        </div>
    </div>
    <!-- /.card-body -->
    <div class="card-footer">
        <a class="btn btn-outline-secondary" href="<?= $this->Url->build(['action' => 'bodyData', $user -> id, '?' => $param]) ?>" id="back-btn">
            <i class="fas fa-arrow-circle-left"></i> 戻る
        </a>
        <span class="float-right">
            <button type="submit" class="btn btn-primary" id="register-btn">登録</button>
        </span>
    </div>
    <?= $this->Form->end() ?>
</div>
