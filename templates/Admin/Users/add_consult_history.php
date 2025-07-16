<?php $this->assign('title', 'レッスン・コンサル時の指導履歴登録'); ?>

<!-- Default box -->
<div class="card">
    <?= $this->Form->create($user, ['class' => 'form-horizontal', 'id' => 'body-data-form']) ?>
    <div class="card-body">
        <div class="form-group row">
            <label for="" class="col-sm-1 col-form-label text-md-right">開催日時</label>
            <div class="col-sm-4">
                <?= $this->Form->datetime('date', ['class' => 'form-control', 'step' => 60]) ?>
                <span class="text-danger"><?= $this->Form->error('date') ?></span>
            </div>
        </div>
        <div class="form-group row">
            <?= $this->Form->hidden('category', ['value' => '実践レッスン']) ?>
            <?= $this->Form->hidden('theme', ['value' => '歩き方（仙骨ウォーク）']) ?>
            <label for="" class="col-sm-1 col-form-label text-md-right">指導内容<br />（やったこと）</label>
            <div class="col-sm-6">
                <?= $this->Form->textarea('instruction', ['class' => 'form-control', 'placeholder' => '']) ?>
                <span class="text-danger"><?= $this->Form->error('instruction') ?></span>
            </div>
        </div>
        <div class="form-group row">
            <label for="" class="col-sm-1 col-form-label text-md-right">注意点<br />（分かったこと）</label>
            <div class="col-sm-6">
                <?= $this->Form->textarea('note', ['class' => 'form-control', 'placeholder' => '']) ?>
                <span class="text-danger"><?= $this->Form->error('note') ?></span>
            </div>
        </div>
        <div class="form-group row">
            <label for="" class="col-sm-1 col-form-label text-md-right">申し送り事項<br />（次やること）</label>
            <div class="col-sm-6">
                <?= $this->Form->textarea('next_step', ['class' => 'form-control', 'placeholder' => '']) ?>
                <span class="text-danger"><?= $this->Form->error('next_step') ?></span>
            </div>
        </div>
        <div class="form-group row">
            <label for="" class="col-sm-1 col-form-label text-md-right">担当講師</label>
            <div class="col-sm-1">
                <?= $this->Form->select('instructor', $staffs, ['class' => 'form-control', 'placeholder' => '']) ?>
                <span class="text-danger"><?= $this->Form->error('staff_id') ?></span>
            </div>
        </div>
    </div>
    <!-- /.card-body -->
    <div class="card-footer">
        <a class="btn btn-outline-secondary" href="<?= $this->Url->build(['action' => 'consultHistory', $user -> id, '?' => $param]) ?>" id="back-btn">
            <i class="fas fa-arrow-circle-left"></i> 戻る
        </a>
        <span class="float-right">
            <button type="submit" class="btn btn-primary" id="register-btn">登録</button>
        </span>
    </div>
    <?= $this->Form->end() ?>
</div>
