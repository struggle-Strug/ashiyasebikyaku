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
        <a href="<?= $this->Url->build(['action' => 'add']); ?>" class="btn btn-outline-primary">新規イベント登録</a>
    </div>
    <div class="card-body">
        <div class="table-responsive-md">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <tr>
                            <th nowrap>募集開始日時</th>
                            <th nowrap>募集終了日時</th>
                            <th nowrap>タイトル</th>
                            <th nowrap>分類</th>
                            <th nowrap>開催日時</th>
                            <th nowrap>担当</th>
                            <th style="width:80px"></th>
                        </tr>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($events as $event): ?>
                    <tr>
                        <td><?= h($event->start_datetime) ?></td>
                        <td><?= h($event->end_datetime) ?></td>
                        <td><?= h($event->title) ?></td>
                        <td><?= ($event->type) ? $eventType[$event->type] : '' ?></td>
                        <td>
                            <?php foreach ($event->event_schedules as $eventSchedule): ?>
                                <div>
                                    <?= $eventSchedule->event_date->format('Y年n月j日') ?>(<?= $week[$eventSchedule->event_date->format('w')] ?>) <?= h($eventSchedule->start_time) ?> 〜 <?= h($eventSchedule->end_time) ?>
                                </div>
                            <?php endforeach ?>
                        </td>
                        <td><?= ($event->staff) ? h($event->staff->name) : '' ?></td>
                        <td nowrap>
                            <a href="<?= $this->Url->build(['action' => 'view', $event->id]); ?>" class="btn btn-outline-primary btn-sm">詳細</a>
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
