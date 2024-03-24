<?php $this->assign('title', '応募したイベント'); ?>
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

<div class="col-lg-12">
    <div class="card">
        <div class="card-content">
            <div class="nav-tabs-navigation">
                <div class="nav-tabs-wrapper">
                    <ul id="tabs" class="nav nav-tabs">
                        <li><a href="<?= $this->Url->build(['action' => 'index']); ?>">募集中のイベント</a></li>
                        <li class="active"><a href="<?= $this->Url->build(['action' => 'history']); ?>">応募したイベント</a>
                        </li>
                    </ul>
                </div>
            </div>

            <table class="table">
                <tbody>
                    <?php foreach ($eventEntries as $eventEntry): ?>

                    <tr>
                        <td>
                            <?php if ($eventEntry->event->type == '1') : ?>
                                <span class="label label-info"><?= h($eventType[$eventEntry->event->type]) ?></span>
                            <?php elseif ($eventEntry->event->type == '2') : ?>
                                <span class="label label-warning"><?= h($eventType[$eventEntry->event->type]) ?></span>
                            <?php elseif ($eventEntry->event->type == '3') : ?>
                                <span class="label label-success"><?= h($eventType[$eventEntry->event->type]) ?></span>
                            <?php endif ?>
                            <h5><?= h($eventEntry->event->title) ?></h5>
                            <b>
                                <?= date('Y年n月j日', strtotime($eventEntry->event_schedule->event_date)) ?>(<?= $week[$eventEntry->event_schedule->event_date->format('w')] ?>)
                                <br>
                                <?= h($eventEntry->event_schedule->start_time) ?> 〜 <?= h($eventEntry->event_schedule->end_time) ?>
                            </b>
                            <div style="margin-top:15px">キャンセル期限：<br><?= date('Y年n月j日 H:i', strtotime($eventEntry->event_schedule->cancel_datetime)) ?> まで</div>
                        </td>
                        <td nowrap>
                            <div style="margin-bottom:10px">
                                <button type="button" class="btn btn-sm" data-toggle="modal" data-target="#modal-<?= $eventEntry->id ?>">
                                    <i class="fa-regular fa-window-restore"></i> 詳細
                                </button>
                                <div class="modal fade" id="modal-<?= $eventEntry->id ?>">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title"><?= h($eventEntry->event->title) ?></h4>
                                            </div>
                                            <div class="modal-body">
                                                <p>
                                                    <?= $this->Link->linkify(h($eventEntry->event->content)) ?>
                                                </p>
                                            </div>
                                            <div class="modal-footer justify-content-between">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">閉じる</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php if ($eventEntry->event_schedule->cancel_datetime->format('YmdHi') >= date('YmdHi')) : ?>
                                <a href="javascript:void(0);" onclick="var ok=confirm('本当にキャンセルしてよろしいですか？');if (ok) location.href='<?= $this->Url->build(['action' => 'cancel', $eventEntry->id]); ?>'; return false;" class="btn btn-sm btn-primary">キャンセル</a>
                            <?php endif ?>
                        </td>
                    </tr>

                    <?php endforeach ?>
                </tbody>
            </table>

        </div>
    </div>
</div>

<div class="col-md-12 text-center">
    <?= $this->element('pagination'); ?>
</div>
