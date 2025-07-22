<?php $this->assign('title', 'レッスン管理（登録画面）'); ?>

<div class="card">
    <div class="card-body">
        <h4>氏名: <?= h($user->last_name) ?> <?= h($user->first_name) ?></h4>

        <?= $this->Form->create(null, [
            'url' => ['controller' => 'Lessons', 'action' => 'saveConsult', $user->id],
            'type' => 'post'
        ]) ?>
        <table class="table table-bordered table-striped" id="consult-table">
            <thead>
                <tr>
                    <th>開催日時</th>
                    <th>区分</th>
                    <th>テーマ</th>
                    <th>指導内容（やったこと）</th>
                    <th>注意点（分かったこと）</th>
                    <th>申し送り事項（次やること）</th>
                    <th>担当講師</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($consults as $index => $consultItem): ?>
                <tr>
                    <input type="hidden" name="consults[<?= $index ?>][id]" value="<?= h($consultItem['id']) ?>">
                    <input type="hidden" name="consults[<?= $index ?>][date]" value="<?= h($consultItem->date->format('Y-m-d')) ?>">
                    <input type="hidden" name="consults[<?= $index ?>][category]" value="<?= h($consultItem->category) ?>">

                    <td><?= h($consultItem->date->format('Y/m/d')) ?></td>
                    <td>
                        <?php if ($consultItem->category == 1): ?>
                            全体イベント
                        <?php elseif ($consultItem->category == 2): ?>
                            個別コンサルティング
                        <?php else: ?>
                            グループレッスン
                        <?php endif ?>
                    </td>

                    <td class="editable" data-field="theme" data-index="<?= $index ?>">
                        <span><?= h($consultItem->theme) ?></span>
                        <input type="hidden" name="consults[<?= $index ?>][theme]" value="<?= h($consultItem->theme) ?>">
                    </td>
                    <td class="editable" data-field="instruction" data-index="<?= $index ?>">
                        <span><?= h($consultItem->instruction) ?></span>
                        <input type="hidden" name="consults[<?= $index ?>][instruction]" value="<?= h($consultItem->instruction) ?>">
                    </td>
                    <td class="editable" data-field="note" data-index="<?= $index ?>">
                        <span><?= h($consultItem->note) ?></span>
                        <input type="hidden" name="consults[<?= $index ?>][note]" value="<?= h($consultItem->note) ?>">
                    </td>
                    <td class="editable" data-field="next_step" data-index="<?= $index ?>">
                        <span><?= h($consultItem->next_step) ?></span>
                        <input type="hidden" name="consults[<?= $index ?>][next_step]" value="<?= h($consultItem->next_step) ?>">
                    </td>
                    <td class="editable-staff" data-field="staff_id" data-index="<?= $index ?>">
                        <span><?= h($staffs[$consultItem->staff_id] ?? '') ?></span>
                        <input type="hidden" name="consults[<?= $index ?>][staff_id]" value="<?= h($consultItem->staff_id) ?>">
                    </td>
                </tr>
                <?php endforeach ?>
            </tbody>
        </table>

        <div class="text-right mt-2">
            <button type="button" class="btn btn-secondary" id="add-row">行追加</button>
            <button type="submit" class="btn btn-success ml-2">保存</button>
        </div>

        <?= $this->Form->end() ?>
    </div>
</div>

<script>
$(document).ready(function() {
    const staffs = <?= json_encode($staffs) ?>;

    // Editable text fields
    $(document).on('click', '.editable', function() {
        const $td = $(this);
        const index = $td.data('index');
        const field = $td.data('field');
        const value = $td.find('span').text().trim();

        // ✅ Ignore only visible input, allow hidden ones
        if ($td.find('input:not([type="hidden"]), textarea').length > 0) return;

        const input = $('<input>', {
            class: 'form-control',
            value: value
        });

        input.on('input', function() {
            $(`input[name="consults[${index}][${field}]"]`).val($(this).val());
        });

        $td.find('span').hide();
        $td.append(input);
    });

    // Editable staff select
    $(document).on('click', '.editable-staff', function() {
        const $td = $(this);
        const index = $td.data('index');
        const field = $td.data('field');
        const currentValue = $td.find('span').text().trim();

        if ($td.find('select').length > 0) return;

        const select = $('<select>', {
            class: 'form-control'
        });

        $.each(staffs, function(id, name) {
            select.append($('<option>', {
                value: id,
                text: name,
                selected: name === currentValue
            }));
        });

        select.on('change', function() {
            $(`input[name="consults[${index}][${field}]"]`).val($(this).val());
        });

        $td.find('span').hide();
        $td.append(select);
    });

    // Add new row
    $('#add-row').on('click', function() {
        const table = $('#consult-table tbody');
        const index = table.find('tr').length;
        const today = '<?= date('Y/m/d') ?>';
        const todayVal = '<?= date('Y-m-d') ?>';
        const newRow = `<tr>
            <input type="hidden" name="consults[${index}][date]" value="${todayVal}">
            <input type="hidden" name="consults[${index}][category]" value="2">
            <td>${today}</td>
            <td>個別コンサルティング</td>
            <td class="editable" data-field="theme" data-index="${index}"><span></span>
                <input type="hidden" name="consults[${index}][theme]" value="">
            </td>
            <td class="editable" data-field="instruction" data-index="${index}"><span></span>
                <input type="hidden" name="consults[${index}][instruction]" value="">
            </td>
            <td class="editable" data-field="note" data-index="${index}"><span></span>
                <input type="hidden" name="consults[${index}][note]" value="">
            </td>
            <td class="editable" data-field="next_step" data-index="${index}"><span></span>
                <input type="hidden" name="consults[${index}][next_step]" value="">
            </td>
            <td class="editable-staff" data-field="staff_id" data-index="${index}"><span></span>
                <input type="hidden" name="consults[${index}][staff_id]" value="">
            </td>
        </tr>`;
        table.append(newRow);
    });
});
</script>
