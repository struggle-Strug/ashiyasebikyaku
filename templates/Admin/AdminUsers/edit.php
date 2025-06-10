<?php $this->assign('title', 'パスワード変更'); ?>

<!-- Default box -->
<div class="card">
    <?= $this->Form->create($adminUser, ['class' => 'form-horizontal']) ?>
    <div class="card-body">
        <div class="form-group row">
            <label for="" class="col-sm-2 col-form-label text-md-right">パスワード</label>
            <div class="col-sm-6">
                <?= $this->Form->password('password', ['class' => 'form-control', 'placeholder' => 'パスワード', 'default' => ""]) ?>
                <span class="text-danger"><?= $this->Form->error('password') ?></span>
            </div>
           
        </div>
        <div class="form-group row">
            <label for="" class="col-sm-2 col-form-label text-md-right">属性</label>
            <div class="col-sm-2">
                <?= $this->Form->select('role_id', $roles, ['class' => 'form-control']) ?>
                <span class="text-danger"><?= $this->Form->error('role_id') ?></span>
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
