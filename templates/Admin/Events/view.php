<?php $this->assign('title', 'イベント管理'); ?>
<?php
    $week = [
        '日', //0
        '月', //1
        '火', //2
        '水', //3
        '木', //4
        '金', //5
        '土', //6
    ];
?>

<!-- Default box -->
<div class="card">
    <div class="card-header">
        <a href="<?= $this->Url->build(['action' => 'edit', $event->id]); ?>" class="btn btn-outline-primary btn-sm">編集</a>
        <a href="javascript:void(0);" onclick="var ok=confirm('応募も全てキャンセルされます。本当に削除してよろしいですか？');if (ok) location.href='<?= $this->Url->build(['action' => 'delete', $event->id]); ?>'; return false;" class="btn btn-outline-secondary btn-sm">イベントを削除</a>
    </div>
    <div class="card-body">
        <table class="table table-bordered table-striped">
            <tbody id="sortable">
                <tr>
                    <th style="width:20%">募集日時</th>
                    <td>
                        <?= h($event->start_datetime) ?> 〜 <?= h($event->end_datetime) ?>
                    </td>
                </tr>
                <tr>
                    <th nowrap="">分類</th>
                    <td>
                        <?= ($event->type) ? $eventType[$event->type] : '' ?>
                    </td>
                </tr>
                <tr>
                    <th nowrap="">タイトル</th>
                    <td>
                        <?= h($event->title) ?>
                    </td>
                </tr>
                <tr>
                    <th nowrap="">詳細</th>
                    <td>
                        <?=$this->Link->linkify(h($event->content)) ?>
                    </td>
                </tr>
                <tr>
                    <th nowrap="">担当講師</th>
                    <td>
                        <?= ($event->staff) ? h($event->staff->name) : '' ?>
                    </td>
                </tr>
            </tbody>
        </table>
        <h6 class="mt-3">スケジュール</h6>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th nowrap>開催日時</th>
                    <th nowrap>定員</th>
                    <th nowrap>応募</th>
                    <th nowrap>キャンセル期限</th>
                    <th style="width:80px"></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($event->event_schedules as $eventSchedule): ?>
                <tr>
                    <td>
                        <?= $eventSchedule->event_date->format('Y年n月j日') ?>(<?= $week[$eventSchedule->event_date->format('w')] ?>) <?= h($eventSchedule->start_time) ?> 〜 <?= h($eventSchedule->end_time) ?>
                    </td>
                    <td>
                        <?= h($eventSchedule->capacity) ?>名
                    </td>
                    <td>
                        <?= ($eventSchedule->event_entries) ? count($eventSchedule->event_entries) : '0' ?>名<br>
                        <?php foreach ($eventSchedule->event_entries as $eventEntry) : ?>
                            <div><small>・<?= h($eventEntry->user->last_name) ?> <?= h($eventEntry->user->first_name) ?></small></div>
                        <?php endforeach ?>
                    </td>
                    <td>
                        <?= h($eventSchedule->cancel_datetime) ?>
                    </td>
                    <td>
                        <a href="javascript:void(0);" onclick="var ok=confirm('応募は全てキャンセルされます。本当に削除してよろしいですか？');if (ok) location.href='<?= $this->Url->build(['action' => 'delete-schedule', $event->id, $eventSchedule->id]); ?>'; return false;" class="btn btn-outline-secondary btn-sm">削除</a>
                    </td>
                </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>
    <!-- /.card-body -->
    <div class="card-footer">
        <a class="btn btn-outline-secondary" href="<?= $this->Url->build(['action' => 'index']); ?>"><i class="fas fa-arrow-circle-left"></i> 戻る</a>
    </div>
</div>
