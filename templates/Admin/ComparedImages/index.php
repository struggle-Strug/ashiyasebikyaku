<?php $this->assign('title', '比較画像一覧'); ?>

<?= $this->Form->create(null, ['type' => 'get']) ?>
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-4 mt-2">
                <label>測定日</label>
                <div class="input-group">
                    <?= $this->Form->date('start_report_date', ['class' => 'form-control', 'placeholder' => '', 'default' => $param['start_report_date']]) ?>
                    <div class="input-group-prepend">
                        <span class="input-group-text">
                            〜
                        </span>
                    </div>
                    <?= $this->Form->date('end_report_date', ['class' => 'form-control', 'placeholder' => '', 'default' => $param['end_report_date']]) ?>
                </div>
            </div>
            <div class="col-md-3 mt-2">
                <label>会員名</label>
                <?= $this->Form->select('user_id', $users, ['class' => 'form-control', 'empty' => '-', 'default' => $param['user_id']]) ?>
            </div>
            <div class="col-md-12 text-right mt-2">
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-magnifying-glass"></i> 検索</button>
            </div>
        </div>
    </div>
</div>
<?= $this->Form->end() ?>

<div class="card">
    <div class="card-header">
        <a href="<?= $this->Url->build(['action' => 'add']); ?>" class="btn btn-outline-primary">新規比較画像作成</a>
    </div>
    <div class="card-body">
    <?php if (!$comparedImages || count($comparedImages) == 0) : ?>

<?php if (empty($param['user_id'])) : ?>
     会員を選択してください。
 <?php else : ?>
     該当のデータはありません。
 <?php endif ?>

<?php else : ?>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th nowrap>作成日</th>
                    <th nowrap>画像</th>
                    <th nowrap>コメント</th>
                    <th nowrap></th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($comparedImages as $comparedImage): ?>
                <tr>
                    <td>
                    <?= h($comparedImage->created) ?>
                    </td>
                    <td><img src="<?= h($comparedImage->image) ?>" width="300px" /></td>
                    <td><?= h($comparedImage->comment) ?></td>
                    <td><?= $this->Form->postLink(__('削除'), ['action' => 'delete', $comparedImage->id], ['class' => 'btn btn-outline-danger btn-sm', 'confirm' => __('削除してもよろしいでしょうか？', $comparedImage->id)]) ?></td>
                </tr>
            <?php endforeach ?>
            </tbody>
        </table>
        <?php endif ?>
    </div>
    <!-- /.card-body -->
    <div class="card-footer clearfix">
    </div>
</div>
<!-- /.card -->

<?= $this->Form->create(null, ['id' => 'ajax-form']) ?>
<?= $this->Form->end() ?>
<script>
</script>