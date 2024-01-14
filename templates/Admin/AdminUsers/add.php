<?php $this->assign('title', '登録'); ?>

<!-- Default box -->
<div class="card">
    <?= $this->Form->create($adminUser, ['class' => 'form-horizontal']) ?>
    <div class="card-body">
        <div class="form-group row">
            <label for="" class="col-sm-2 col-form-label text-md-right">ログインID</label>
            <div class="col-sm-4">
                <?= $this->Form->text('loginid', ['class' => 'form-control', 'placeholder' => 'loginid']) ?>
                <span class="text-danger"><?= $this->Form->error('loginid') ?></span>
            </div>
            <label for="" class="col-sm-2 col-form-label text-md-right">名前</label>
            <div class="col-sm-4">
                <?= $this->Form->text('name', ['class' => 'form-control', 'placeholder' => 'name']) ?>
                <span class="text-danger"><?= $this->Form->error('name') ?></span>
            </div>
            <label for="" class="col-sm-2 col-form-label text-md-right">パスワード</label>
            <div class="col-sm-4">
                <?= $this->Form->password('password', ['class' => 'form-control', 'placeholder' => 'パスワード']) ?>
                <span class="text-danger"><?= $this->Form->error('password') ?></span>
            </div>
        </div>
    </div>
    <!-- /.card-body -->
    <div class="card-footer">
        <span class="float-right">
            <button type="submit" class="btn btn-primary">登録</button>
        </span>
    </div>
    <?= $this->Form->end() ?>
</div>
<!-- /.card -->
