<?php
$this->assign('title', '事務局からのお知らせ管理');
?>

<!-- Default box -->
<div class="card">
    <div class="card-header">
        <a href="<?= $this->Url->build(['action' => 'add']); ?>" class="btn btn-outline-primary">新規お知らせ登録</a>
    </div>
    <div class="card-body">
        <div class="table-responsive-md">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th nowrap>お知らせ内容</th>
                        <th nowrap>表示対象</th>
                        <th nowrap>カテゴリー</th>
                        <th nowrap>表示開始日時</th>
                        <th nowrap>表示終了日時</th>
                        <th style="width:100px"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($informations as $information): ?>
                    <tr>
                        <td>
                            <small><?= $this->Link->linkify(h($information->content)) ?></small>
                        </td>
                        <td>
                            <?php if ($information->target == '1') : ?>
                                全員
                            <?php elseif ($information->target == '2') : ?>
                                スタッフのみ
                            <?php else : ?>
                                利用者のみ
                            <?php endif ?>
                        </td>
                        <td>
                            <?php if (!empty($information->category) && isset($categories[$information->category])): ?>
                                <?= h($categories[$information->category]) ?>
                            <?php endif; ?>
                        </td>
                        <td><?= h($information->start_datetime) ?></td>
                        <td><?= h($information->end_datetime) ?></td>
                        <td nowrap>
                            <a href="<?= $this->Url->build(['action' => 'edit', $information->id]); ?>" class="btn btn-outline-primary btn-sm">編集</a>
                            <?= $this->Form->postLink(__('削除'), ['action' => 'delete', $information->id], ['class' => 'btn btn-outline-secondary btn-sm', 'confirm' => __('退会は編集から「利用終了日時」を設定してください。本当に削除してよろしいですか？', $information->id)]) ?>
                        </td>
                    </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>
    </div>
    <!-- /.card-body -->
    <div class="card-footer clearfix">
        <?= $this->element('pagination'); ?>
    </div>
</div>
<!-- /.card -->
