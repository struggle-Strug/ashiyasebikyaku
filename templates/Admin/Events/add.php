<?php $this->assign('title', 'イベント登録'); ?>

<!-- Default box -->
<div class="card">
    <?= $this->Form->create($event, ['class' => 'form-horizontal']) ?>
    <div class="card-body">
        <div class="form-group row">
            <label for="" class="col-sm-2 col-form-label text-md-right">募集開始日時 *</label>
            <div class="col-sm-4">
                <?= $this->Form->datetime('start_datetime', ['class' => 'form-control', 'step' => 60]) ?>
                <span class="text-danger"><?= $this->Form->error('start_datetime') ?></span>
            </div>
            <label for="" class="col-sm-2 col-form-label text-md-right">募集終了日時</label>
            <div class="col-sm-4">
                <?= $this->Form->datetime('end_datetime', ['class' => 'form-control', 'step' => 60]) ?>
                <span class="text-danger"><?= $this->Form->error('end_datetime') ?></span>
            </div>
        </div>
        <div class="form-group row">
            <label for="" class="col-sm-2 col-form-label text-md-right">分類</label>
            <div class="col-sm-4">
                <?= $this->Form->select('type', $eventType, ['class' => 'form-control', 'empty' => '-']) ?>
                <span class="text-danger"><?= $this->Form->error('type') ?></span>
            </div>
        </div>
        <div class="form-group row">
            <label for="" class="col-sm-2 col-form-label text-md-right">イベントタイトル *</label>
            <div class="col-sm-10">
                <?= $this->Form->text('title', ['class' => 'form-control']) ?>
                <span class="text-danger"><?= $this->Form->error('title') ?></span>
            </div>
        </div>
        <div class="form-group row">
            <label for="" class="col-sm-2 col-form-label text-md-right">イベント内容 *</label>
            <div class="col-sm-10">
                <?= $this->Form->textarea('content', ['class' => 'form-control', 'placeholder' => '']) ?>
                <span class="text-danger"><?= $this->Form->error('content') ?></span>
            </div>
        </div>
        <div class="form-group row">
            <label for="" class="col-sm-2 col-form-label text-md-right">担当講師</label>
            <div class="col-sm-4">
                <?= $this->Form->select('staff_id', $staffs, ['class' => 'form-control', 'placeholder' => '']) ?>
                <span class="text-danger"><?= $this->Form->error('staff_id') ?></span>
                <code>コンサルティング予約の場合は選択してください</code>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                スケジュール
            </div>
            <div class="card-body" id="schedule-div">
                <!-- スケジュール追加部分 -->
                <?php if ($event->event_schedules) : ?>
                    <?php foreach ($event->event_schedules as $key => $eventSchedule): ?>
                        <div class="row mt-2" id="schedule-<?= $key + 1 ?>">
                            <input type="hidden" name="id_<?= $key ?>" value="<?= $eventSchedule->id ?>">
                            <div class="form-group col-sm-2">
                                <label>開催日</label>
                                <input type="date" name="event_date[]" class="form-control" required="required" value="<?= $eventSchedule->event_date->format('Y-m-d') ?>">
                            </div>
                            <div class="form-group col-sm-2">
                                <label>開始時間</label>
                                <input type="time" name="start_time[]" class="form-control" step="60" required="required" value="<?= h($eventSchedule->start_time) ?>">
                            </div>
                            <div class="form-group col-sm-2">
                                <label>終了時間</label>
                                <input type="time" name="end_time[]" class="form-control" step="60" required="required" value="<?= h($eventSchedule->end_time) ?>">
                            </div>
                            <div class="form-group col-sm-2">
                                <label>定員</label>
                                <div class="input-group">
                                    <input type="munber" name="capacity[]" class="form-control" min="0" required="required" value="<?= h($eventSchedule->capacity) ?>">
                                    <div class="input-group-append">
                                        <span class="input-group-text">名</span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-sm-3">
                                <label>キャンセル可能日時</label>
                                <input type="datetime-local" name="cancel_datetime[]" class="form-control" step="60" required="required" value="<?= h($eventSchedule->cancel_datetime->format('Y-m-d H:i')) ?>">
                            </div>
                            <div class="form-group col-sm-1">
                                <label>&nbsp;</label>
                            </div>
                        </div>
                    <?php endforeach ?>
                <?php endif ?>
            </div>
            <button type="button" class="btn btn-default btn-schedule-add"><i class="fa-solid fa-plus"></i> スケジュールを追加する</button>
        </div>
    </div>
    <!-- /.card-body -->
    <div class="card-footer">
        <?php if (empty($event->id)) : // 新規登録 ?>
            <a class="btn btn-outline-secondary" href="<?= $this->Url->build(['action' => 'index']); ?>"><i class="fas fa-arrow-circle-left"></i> 戻る</a>
        <?php else : // 編集 ?>
            <a class="btn btn-outline-secondary" href="<?= $this->Url->build(['action' => 'view', $event->id]); ?>"><i class="fas fa-arrow-circle-left"></i> 戻る</a>
        <?php endif ?>
        <span class="float-right">
            <button type="submit" class="btn btn-primary">登録</button>
        </span>
    </div>
    <?= $this->Form->end() ?>
</div>
<!-- /.card -->

<!-- スケジュールテンプレート -->
<div class="row mt-2" id="schedule-template" style="display:none">
    <div class="form-group col-sm-2">
        <label>開催日</label>
        <input type="date" name="event_date[]" class="form-control" required="required">
    </div>
    <div class="form-group col-sm-2">
        <label>開始時間</label>
        <input type="time" name="start_time[]" class="form-control" step="60" required="required">
    </div>
    <div class="form-group col-sm-2">
        <label>終了時間</label>
        <input type="time" name="end_time[]" class="form-control" step="60" required="required">
    </div>
    <div class="form-group col-sm-2">
        <label>定員</label>
        <div class="input-group">
            <input type="munber" name="capacity[]" class="form-control" min="0" required="required">
            <div class="input-group-append">
                <span class="input-group-text">名</span>
            </div>
        </div>
    </div>
    <div class="form-group col-sm-3">
        <label>キャンセル可能日時</label>
        <input type="datetime-local" name="cancel_datetime[]" class="form-control" step="60" required="required">
    </div>
    <div class="form-group col-sm-1">
        <label>&nbsp;</label>
        <div class="input-group">
            <button type="button" class="btn btn-sm btn-secondary btn-schedule-delete" data-target=""><i class="fa-solid fa-xmark"></i> 削除</button>
        </div>
    </div>
</div>

<script>
    $(function(){
        let counter = <?= ($event->event_schedules) ? count($event->event_schedules) : '0' ?>;

        // スケジュールを追加する
        $('.btn-schedule-add').click(function() {
            counter++;

            let schedule = $('#schedule-template').clone();

            schedule.attr('id', 'schedule-' + counter);
            schedule.find('.btn-schedule-delete').attr('data-target', 'schedule-' + counter);
            schedule.show();
            schedule.appendTo('#schedule-div');
        });

        // スケジュールを削除する
        $(document).on("click", ".btn-schedule-delete", function () {
            counter--;
            $('#' + $(this).data('target')).remove();
        });

        <?php if (empty($event->id)) : // 新規登録 ?>
            $('.btn-schedule-add').click();
        <?php endif ?>
    });
</script>
