<?php $this->assign('title', '講師登録'); ?>

<!-- Default box -->
<div class="card">
    <?= $this->Form->create($staff, ['class' => 'form-horizontal']) ?>
    <div class="card-body">
        <div class="form-group row">
            <label for="" class="col-sm-2 col-form-label text-md-right">ログインID *</label>
            <div class="col-sm-4">
                <?= $this->Form->text('loginid', ['class' => 'form-control', 'placeholder' => '']) ?>
                <span class="text-danger"><?= $this->Form->error('loginid') ?></span>
            </div>
            <label for="" class="col-sm-2 col-form-label text-md-right">パスワード *</label>
            <div class="col-sm-4">
                <?= $this->Form->password(($staff->password) ? 'new_password' : 'password', ['class' => 'form-control', 'placeholder' => ($staff->password) ? '変更したい場合は入力してください' : '']) ?>
                <span class="text-danger"><?= $this->Form->error('password') ?></span>
                <code>設定したパスワードは管理画面には表示されませんので必ず控えておいてください。</code>
            </div>
        </div>
        <div class="form-group row">
            <label for="" class="col-sm-2 col-form-label text-md-right">名前 *</label>
            <div class="col-sm-4">
                <?= $this->Form->text('name', ['class' => 'form-control', 'placeholder' => '']) ?>
                <span class="text-danger"><?= $this->Form->error('name') ?></span>
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
