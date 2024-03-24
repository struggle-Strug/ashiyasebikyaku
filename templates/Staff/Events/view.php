<?php $this->assign('title', '個別コンサルティング'); ?>
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
                    <th nowrap="">タイトル</th>
                    <td>
                        <?= h($event->title) ?>
                    </td>
                </tr>
                <tr>
                    <th nowrap="">詳細</th>
                    <td>
                        <?= $this->Link->linkify(h($event->content)) ?>
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
                        <?php foreach ($eventSchedule->event_entries as $eventEntry): ?>
                            <div><small>・<?= h($eventEntry->user->last_name) ?> <?= h($eventEntry->user->first_name) ?></small></div>
                        <?php endforeach ?>
                    </td>
                    <td>
                        <?= h($eventSchedule->cancel_datetime) ?>
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
