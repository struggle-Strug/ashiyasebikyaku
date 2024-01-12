<?php $this->assign('title', '表示メッセージ'); ?>

<blockquote class="quote-secondary">
    <small>ここで登録したメッセージが、会員マイページトップに表示されます</small>
</blockquote>

<!-- Default box -->
<div class="card">
    <?= $this->Form->create($user, ['class' => 'form-horizontal']) ?>
    <div class="card-header">
        <h4 class="card-title">氏名：<?= h($user->last_name) ?> <?= h($user->first_name) ?></h4>
    </div>
    <div class="card-body">
        <div class="form-group row">
            <label for="" class="col-sm-2 col-form-label text-md-right">今週のワーク</label>
            <div class="col-sm-10">
                <?= $this->Form->textarea('staff_week_message', ['class' => 'form-control', 'placeholder' => '']) ?>
                <span class="text-danger"><?= $this->Form->error('staff_week_message') ?></span>
            </div>
        </div>
        <div class="form-group row">
            <label for="" class="col-sm-2 col-form-label text-md-right">講師コメント</label>
            <div class="col-sm-10">
                <?= $this->Form->textarea('staff_message', ['class' => 'form-control', 'placeholder' => '']) ?>
                <span class="text-danger"><?= $this->Form->error('staff_message') ?></span>
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
