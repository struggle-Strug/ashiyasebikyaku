<?php $this->assign('title', '講師管理'); ?>

<!-- Default box -->
<div class="card">
    <div class="card-header">
        <a href="<?= $this->Url->build(['action' => 'add']); ?>" class="btn btn-outline-primary">新規講師登録</a>
    </div>
    <div class="card-body">
        <div class="table-responsive-md">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th nowrap>ログインID</th>
                        <th nowrap>メールアドレス</th>
                        <th nowrap>名前</th>
                        <th nowrap>親スタッフ</th>
                        <th style="width:100px"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($staffs as $staff): ?>
                    <tr>
                        <td><?= h($staff->loginid) ?></td>
                        <td><?= h($staff->email) ?></td>
                        <td><?= h($staff->name) ?></td>
                        <td><?= h($loginidNameMap[$staff->parent] ?? '') ?></td>                        <td nowrap>
                            <a href="<?= $this->Url->build(['action' => 'edit', $staff->id]); ?>" class="btn btn-outline-primary btn-sm">編集</a>
                            <?= $this->Form->postLink(__('削除'), ['action' => 'delete', $staff->id], ['class' => 'btn btn-outline-secondary btn-sm', 'confirm' => __('削除してよろしいですか？', $staff->id)]) ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <!-- /.card-body -->
</div>
<!-- /.card -->
