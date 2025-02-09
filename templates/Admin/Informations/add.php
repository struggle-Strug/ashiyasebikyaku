<?php
use Cake\Core\Configure;

$this->assign('title', '事務局からのお知らせ登録'); ?>

<!-- Default box -->
<div class="card">
    <?= $this->Form->create($information, ['class' => 'form-horizontal']) ?>
    <div class="card-body">
        <div class="form-group row">
            <label for="" class="col-sm-2 col-form-label text-md-right">表示開始日時 *</label>
            <div class="col-sm-4">
                <?= $this->Form->datetime('start_datetime', ['class' => 'form-control', 'step' => 60]) ?>
                <span class="text-danger"><?= $this->Form->error('start_datetime') ?></span>
            </div>
            <label for="" class="col-sm-2 col-form-label text-md-right">表示終了日時</label>
            <div class="col-sm-4">
                <?= $this->Form->datetime('end_datetime', ['class' => 'form-control', 'step' => 60]) ?>
                <span class="text-danger"><?= $this->Form->error('end_datetime') ?></span>
            </div>
        </div>
        <div class="form-group row">
            <label for="" class="col-sm-2 col-form-label text-md-right">表示対象</label>
            <div class="col-sm-4">
                <?= $this->Form->select('target', ['1' => '全員', '2' => 'スタッフのみ', '3' => '利用者のみ'], ['class' => 'form-control', 'default' => '1']) ?>
                <span class="text-danger"><?= $this->Form->error('target') ?></span>
            </div>
        </div>
        <div class="form-group row">
            <label for="" class="col-sm-2 col-form-label text-md-right">カテゴリー</label>
            <div class="col-sm-4">
                <?= $this->Form->select('category', Configure::read('INFORMATION_CATEGORY'), ['class' => 'form-control', 'default' => '1']) ?>
                <span class="text-danger"><?= $this->Form->error('category') ?></span>
            </div>
        </div>
        <div class="form-group row">
            <label for="" class="col-sm-2 col-form-label text-md-right">お知らせ内容</label>
            <div class="col-sm-10">
                <?= $this->Form->textarea('content', ['class' => 'form-control', 'placeholder' => '']) ?>
                <span class="text-danger"><?= $this->Form->error('content') ?></span>
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
