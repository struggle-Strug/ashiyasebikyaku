<?php $this->assign('title', '指導内容登録'); ?>

<!-- Default box -->
<div class="card">
    <?= $this->Form->create($user, ['class' => 'form-horizontal', 'id' => 'consult-content-form']) ?>
    <div class="card-body">
        <div class="form-group row">
            <label for="" class="col-sm-1 col-form-label text-md-right">指導内容</label>
            <div class="col-sm-6">
                <?= $this->Form->textarea('consult_content', ['class' => 'form-control', 'placeholder' => '']) ?>
                <span class="text-danger"><?= $this->Form->error('consult_content') ?></span>
            </div>
        </div>
    </div>
    <!-- /.card-body -->
    <div class="card-footer">
        <a class="btn btn-outline-secondary" href="<?= $this->Url->build(['action' => 'selfUnderstand', $user -> id, '?' => $param]) ?>" id="back-btn">
            <i class="fas fa-arrow-circle-left"></i> 戻る
        </a>
        <span class="float-right">
            <button type="submit" class="btn btn-primary" id="register-btn">登録</button>
        </span>
    </div>
    <?= $this->Form->end() ?>
</div>
