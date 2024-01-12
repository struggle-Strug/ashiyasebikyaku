<?php $this->assign('title', '美脚進捗度'); ?>

<div class="card">
    <div class="card-header">
        <h4 class="card-title">氏名：<?= h($user->last_name) ?> <?= h($user->first_name) ?></h4>
    </div>
    <div class="card-body">
        <a href="<?= $this->Url->build(['action' => 'pointAdd', $user->id]); ?>" class="btn btn-outline-primary mb-2">新規登録</a>
        <div class="table-responsive-md">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>登録日時</th>
                        <th style="width:10%">合計</th>
                        <th style="width:10%">仙骨</th>
                        <th style="width:10%">距骨</th>
                        <th style="width:10%">股関節</th>
                        <th style="width:10%">背骨・肋骨</th>
                        <th style="width:10%">横隔膜・鎖骨</th>
                        <th style="width:150px"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($user->user_points as $userPoint) : ?>
                        <tr>
                            <td>
                                <?= h($userPoint->created) ?>
                            </td>
                            <td style="text-align:center">
                                <b><?= h($userPoint->point_1 + $userPoint->point_2 + $userPoint->point_3 + $userPoint->point_4 + $userPoint->point_5) ?></b>
                            </td>
                            <td style="text-align:center">
                                <?= h($userPoint->point_1) ?>
                            </td>
                            <td style="text-align:center">
                                <?= h($userPoint->point_2) ?>
                            </td>
                            <td style="text-align:center">
                                <?= h($userPoint->point_3) ?>
                            </td>
                            <td style="text-align:center">
                                <?= h($userPoint->point_4) ?>
                            </td>
                            <td style="text-align:center">
                                <?= h($userPoint->point_5) ?>
                            </td>
                            <td nowrap>
                                <a href="<?= $this->Url->build(['action' => 'pointEdit', $user->id, $userPoint->id]); ?>" class="btn btn-outline-primary btn-sm">編集</a>
                                <?= $this->Form->postLink(__('削除'), ['action' => 'pointDelete', $user->id, $userPoint->id], ['class' => 'btn btn-outline-secondary btn-sm', 'confirm' => __('削除してよろしいですか？', $userPoint->id)]) ?>
                            </td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer">
        <a class="btn btn-outline-secondary" href="<?= $this->Url->build(['action' => 'index']); ?>"><i class="fas fa-arrow-circle-left"></i> 戻る</a>
    </div>
</div>



