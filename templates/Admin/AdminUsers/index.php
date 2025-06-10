<?php $this->assign('title', '管理者管理'); ?>
<!-- <?php phpinfo(); ?> -->
<?= $this->Form->create(null, ['type' => 'get']) ?>
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-3 mt-2">
                <label>管理者名</label>
                <?= $this->Form->text('name', ['class' => 'form-control', 'placeholder' => '', 'default' => $param['name']]) ?>
            </div>
            <div class="col-md-12 mt-2">
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-magnifying-glass"></i> 検索</button>
            </div>
        </div>
    </div>
</div>
<?= $this->Form->end() ?>

<div class="card">
<div class="card-header">
        <a href="<?= $this->Url->build(['action' => 'add']); ?>" class="btn btn-outline-primary">管理者追加</a>
    </div>
    <div class="card-body">
        <div class="table-responsive-md">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                    <tr>
                        <th style="width:150px">ID</th>
                        <th style="width:150px">属性</th>
                        <th nowrap>管理者名</th>
                        <th nowrap>ログインID</th>
                        <th style="width:150px"></th>
                    </tr>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($adminUsers as $admin): ?>
                    <tr>
        <td><?= h($admin->id) ?></td>
        <td><?= h($admin->role_id == 0 ? '管理者' : '運営') ?></td>
        <td><?= h($admin->name) ?></td>
        <td><?= h($admin->loginid) ?></td>
        <td nowrap>
        <a href="<?= $this->Url->build(['action' => 'edit', $admin->id]); ?>" class="btn btn-outline-primary btn-sm">編集</a>
                            <?= $this->Form->postLink(__('削除'), ['action' => 'delete', $admin->id], ['class' => 'btn btn-outline-secondary btn-sm', 'confirm' => __('削除してよろしいですか？', $admin->id)]) ?>
                        </td>
    </tr>
    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer clearfix">
        <?= $this->element('pagination'); ?>
    </div>
</div>