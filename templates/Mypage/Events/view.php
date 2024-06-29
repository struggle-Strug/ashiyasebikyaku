<?php $this->assign('title', h($event->title)); ?>
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

$today = date('Y-m-d');
?>

<div class="col-lg-12">
    <div class="card">
        <div class="card-content" style="padding-bottom:0">
            <div class="nav-tabs-navigation">
                <div class="nav-tabs-wrapper">
                    <ul id="tabs" class="nav nav-tabs">
                        <li class="active"><a href="<?= $this->Url->build(['action' => 'index']); ?>">募集中のイベント</a></li>
                        <li><a href="<?= $this->Url->build(['action' => 'history']); ?>">応募したイベント</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="card-header" style="padding-top:0">
            <?php if ($event->type == '1'): ?>
                <span class="label label-info" style="margin-bottom:5px"><?= h($eventType[$event->type]) ?></span>
            <?php elseif ($event->type == '2'): ?>
                <span class="label label-warning" style="margin-bottom:5px"><?= h($eventType[$event->type]) ?></span>
            <?php elseif ($event->type == '3'): ?>
                <span class="label label-success" style="margin-bottom:5px"><?= h($eventType[$event->type]) ?></span>
            <?php endif ?>
            <h4 class="card-title"><?= h($event->title) ?></h4>
            <p class="category">募集期間：<br><?= date('Y年n月j日 H:i', strtotime($event->start_datetime)) ?> 〜
                <?= ($event->end_datetime) ? date('Y年n月j日 H:i', strtotime($event->end_datetime)) : '' ?></p>
            <br>
            <?= $this->Link->linkify(h($event->content)) ?>
        </div>
        <div style="margin-top:20px">
            <table class="table">
                <thead>
                    <tr>
                        <th nowrap><small>スケジュール</small></th>
                        <th nowrap><small>定員</small></th>
                        <th nowrap><small>残り</small></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($event->event_schedules as $eventSchedule): ?>
                        <?php if ($eventSchedule->event_date->format('Y-m-d') >= $today): ?>
                            <tr>
                                <td>
                                    <?= date('Y年n月j日', strtotime($eventSchedule->event_date)) ?>(<?= $week[$eventSchedule->event_date->format('w')] ?>)<br><?= h($eventSchedule->start_time) ?>
                                    〜 <?= h($eventSchedule->end_time) ?>
                                </td>
                                <td nowrap>
                                    <?= h($eventSchedule->capacity) ?>名
                                </td>
                                <td nowrap>
                                    <?= ($eventSchedule->event_entries) ? $eventSchedule->capacity - count($eventSchedule->event_entries) : $eventSchedule->capacity ?>名
                                </td>
                                <td nowrap>
                                    <?php
                                    // 応募判定
                                    $isEntry = false;
                                    foreach ($eventSchedule->event_entries as $eventEntry) {
                                        if ($eventEntry->user_id == $this->Identity->get('id')) {
                                            $isEntry = true;
                                            break;
                                        }
                                    }
                                    // 定員判定
                                    $isLimit = true;
                                    if ($eventSchedule->capacity > count($eventSchedule->event_entries)) {
                                        $isLimit = false;
                                    }
                                    ?>
                                    <?php if ($isEntry): ?>
                                        <small>応募済み</small>
                                    <?php else: ?>
                                        <?php if (!$isLimit): ?>
                                            <a href="javascript:void(0);"
                                                onclick="var ok=confirm('本当に応募してよろしいですか？');if (ok) location.href='<?= $this->Url->build(['action' => 'entry', $event->id, $eventSchedule->id]); ?>'; return false;"
                                                class="btn btn-sm">応募</a>
                                        <?php endif ?>
                                    <?php endif ?>
                                </td>
                            </tr>
                        <?php else: ?>
                        <?php endif ?>

                    <?php endforeach ?>
                </tbody>
            </table>
        </div>
    </div>
</div>