<?php $this->assign('title', 'レッスン管理'); ?>

<?= $this->Form->create(null, ['type' => 'get']) ?>
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-4 mt-2">
                <label>開催日時</label>
                <div class="input-group">
                    <?= $this->Form->date('event_date', ['class' => 'form-control', 'placeholder' => '', 'default' => $param['event_date']]) ?>
                </div>
            </div>
            <div class="col-md-3 mt-2">
                <label>区分</label>
                <div class="input-group">
                    <?= $this->Form->select('list_id', $listOptions, ['class' => 'form-control', 'empty' => '-', 'default' => $param['list_id']]) ?>
                </div>
            </div>
            <div class="col-md-3 mt-2">
                <label>講師</label>
                <div class="input-group">
                    <?= $this->Form->select('staff_id', $staffs, ['class' => 'form-control', 'empty' => '-', 'default' => $param['staff_id']]) ?>
                </div>
            </div>
            <div class="col-md-12 text-right mt-2">
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-magnifying-glass"></i> 検索</button>
            </div>
        </div>
    </div>
</div>
<?= $this->Form->end() ?>

<style>
.scrollbar::-webkit-scrollbar {
  width: 10px;
  height: 10px;
}
/* スクロールバーの形を設定する */
.scrollbar-thumb-rounded::-webkit-scrollbar-thumb {
  border-radius: 0.25rem;
}
/* スクロールバーの色を設定する */
.scrollbar-thumb-black::-webkit-scrollbar-thumb {
  --bg-opacity: 1;
  background-color: rgba(0, 0, 0, 0.5);
}

.uniform-image {
  width: 100px;
  display: block;
  margin: 0;
  padding: 0;
  border: none;
}
</style>

<div class="card">
    <div class="card-body">
    <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th nowrap>開催日時</th>
                    <th nowrap>区分</th>
                    <th nowrap>担当講師</th>
                    <th nowrap>会員参加者</th>
                    <th style="width:200px">指導履歴登録</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($eventSchedules as $schedule): ?>
                <?php foreach ($schedule->event_entries as $entry): ?>
                    <tr>
                        <td><?= $schedule->event_date->format('Y/n/j') ?></td>
                        <td>
                            <?php if ($schedule->event->type == 1) : ?>
                                全体イベント
                            <?php elseif ($schedule->event->type == 2) : ?>
                                個別コンサルティング
                            <?php else : ?>
                                グループレッスン
                            <?php endif ?>
                        </td>
                        <td><?= h($schedule->event->staff->name ?? '') ?></td>
                        <td><?= h($entry->user->last_name) ?> <?= h($entry->user->first_name) ?></td>
                        <td>
                            <a href="<?= $this->Url->build([
                                'action' => 'addConsult',
                                $entry->user->id,
                                '?' => [
                                    'event_date' => $schedule->event_date->format('Y-m-d'),
                                    'event_type' => $schedule->event->type,
                                ]
                            ]); ?>" class="btn btn-outline-primary btn-sm save-scroll-link">登録</a>
                        </td>
                    </tr>
                <?php endforeach ?>
            <?php endforeach ?>

            </tbody>
        </table>
    </div>
    <div class="card-footer clearfix">
        <?= $this->element('pagination'); ?>
    </div>
</div>
<!-- /.card -->
<?= $this->Form->end() ?>
<script>
</script>
