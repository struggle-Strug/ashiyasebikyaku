<?php $this->assign('title', '標準モデルとの差異結果登録'); ?>

<!-- Default box -->
<div class="card">
    <?= $this->Form->create($user, ['class' => 'form-horizontal', 'id' => 'body-data-form']) ?>
    <div class="card-body">
        <div class="form-group row">
            <label for="" class="col-sm-1 col-form-label text-md-right">マイルストーン</label>
            <div class="col-sm-4">
                <?= $this->Form->text('milestone', ['class' => 'form-control', 'placeholder' => '']) ?>
                <span class="text-danger"><?= $this->Form->error('milestone') ?></span>
            </div>
        </div>
        <div class="form-group row">
            <label for="" class="col-sm-1 col-form-label text-md-right">成果カテゴリ</label>
            <div class="col-sm-6">
                <?= $this->Form->text('category', ['class' => 'form-control', 'placeholder' => '']) ?>
                <span class="text-danger"><?= $this->Form->error('category') ?></span>
            </div>
        </div>
        <div class="form-group row">
            <label for="" class="col-sm-1 col-form-label text-md-right">標準達成目安1</label>
            <div class="col-sm-6">
                <?= $this->Form->textarea('target1', ['class' => 'form-control', 'placeholder' => '']) ?>
                <span class="text-danger"><?= $this->Form->error('target1') ?></span>
            </div>
        </div>
        <div class="form-group row">
            <label for="" class="col-sm-1 col-form-label text-md-right">標準達成目安2</label>
            <div class="col-sm-6">
                <?= $this->Form->textarea('target2', ['class' => 'form-control', 'placeholder' => '']) ?>
                <span class="text-danger"><?= $this->Form->error('target2') ?></span>
            </div>
        </div>
        <div class="form-group row">
            <label for="" class="col-sm-1 col-form-label text-md-right">評価1</label>
            <div class="col-sm-6">
                <?= $this->Form->textarea('evaluation1', ['class' => 'form-control', 'placeholder' => '']) ?>
                <span class="text-danger"><?= $this->Form->error('evaluation1') ?></span>
            </div>
        </div>
        <div class="form-group row">
            <label for="" class="col-sm-1 col-form-label text-md-right">評価2</label>
            <div class="col-sm-6">
                <?= $this->Form->textarea('evaluation2', ['class' => 'form-control', 'placeholder' => '']) ?>
                <span class="text-danger"><?= $this->Form->error('evaluation2') ?></span>
            </div>
        </div>
        <div class="form-group row">
            <label for="" class="col-sm-1 col-form-label text-md-right">評価コメント1</label>
            <div class="col-sm-6">
                <?= $this->Form->textarea('comment1', ['class' => 'form-control', 'placeholder' => '']) ?>
                <span class="text-danger"><?= $this->Form->error('comment1') ?></span>
            </div>
        </div>
        <div class="form-group row">
            <label for="" class="col-sm-1 col-form-label text-md-right">評価コメント2</label>
            <div class="col-sm-6">
                <?= $this->Form->textarea('comment2', ['class' => 'form-control', 'placeholder' => '']) ?>
                <span class="text-danger"><?= $this->Form->error('comment2') ?></span>
            </div>
        </div>
    </div>
    <!-- /.card-body -->
    <div class="card-footer">
        <a class="btn btn-outline-secondary" href="<?= $this->Url->build(['action' => 'difference', $user -> id, '?' => $param]) ?>" id="back-btn">
            <i class="fas fa-arrow-circle-left"></i> 戻る
        </a>
        <span class="float-right">
            <button type="submit" class="btn btn-primary" id="register-btn">登録</button>
        </span>
    </div>
    <?= $this->Form->end() ?>
</div>
