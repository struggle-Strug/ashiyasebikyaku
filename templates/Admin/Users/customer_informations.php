<?php $this->assign('title', 'カルテ'); ?>
<style>

    td .form-control {
        min-width: 150px;
        max-width: 100%;
        width: 100%;
        font-size: 14px;
        resize: vertical;
    }

</style>
<div class="card">

    <!-- 基本情報 -->
    <?= $this->Form->create($user, [
        'url' => ['controller' => 'Users', 'action' => 'customerMain', $user->id],
        'type' => 'post'
    ]) ?>
    <div class="card-body">
        <h4>1.基本情報</h4>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>コース名</th>
                    <th>氏名（漢字）</th>
                    <th>氏名（カナ）</th>
                    <th>生年月日</th>
                    <th>住所</th>
                    <th>プログラム開始日</th>
                    <th>担当講師</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="editable" data-field="course_id"><span><?= h($courses[$user->course_id] ?? '') ?></span></td>
                    <td class="editable" data-field="name_kanji"><span><?= h($user->last_name) ?> <?= h($user->first_name) ?></span></td>
                    <td class="editable" data-field="name_kana"><span><?= h($user->last_name_kana) ?> <?= h($user->first_name_kana) ?></span></td>
                    <td class="editable" data-field="birthday"><span><?= h($user->birthday) ?></span></td>
                    <td class="editable" data-field="address"><span><?= h($user->zipcode) ?> <?= h($user->prefecture) ?> <?= h($user->city) ?> <?= h($user->address) ?></span></td>
                    <td class="editable" data-field="start_datetime"><span><?= h($user->start_datetime) ?></span></td>
                    <td class="editable" data-field="staff_id"><span><?= h($staffs[$user->staff_id] ?? '') ?></span></td>
                    <td><button type="submit" class="btn btn-success save-btn" style="display:none;">保存</button></td>
                </tr>
            </tbody>
        </table>
    </div>
    <?= $this->Form->end() ?>

    <!-- 生活習慣・既往歴 -->
    <?= $this->Form->create($habit, [
        'url' => ['controller' => 'Users', 'action' => 'habits', $user->id],
        'type' => 'post'
    ]) ?>
    <div class="card-body">
        <h4>2.生活習慣・既往歴</h4>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>職業</th>
                    <th>食事習慣</th>
                    <th>睡眠</th>
                    <th>ストレス要因</th>
                    <th>運動歴</th>
                    <th>既往歴</th>
                    <th>その他注意点</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <?php foreach (['occupation', 'eating_habits', 'sleep', 'stress_factors', 'exercise_history', 'medical_history', 'other_notes'] as $field): ?>
                        <td class="editable-habit" data-field="<?= $field ?>"><span><?= h($habit->$field) ?></span></td>
                    <?php endforeach; ?>
                    <td><button type="submit" class="btn btn-success save-btn" style="display:none;">保存</button></td>
                </tr>
            </tbody>
        </table>
    </div>
    <?= $this->Form->end() ?>

    <!-- 目標設定シート -->
    <?= $this->Form->create($purpose, [
        'url' => ['controller' => 'Users', 'action' => 'purpose', $user->id],
        'type' => 'post'
    ]) ?>
    <div class="card-body">
        <h4>3.目標設定シート</h4>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>メール</th>
                    <th>名前</th>
                    <th>現在の悩み(容姿面)</th>
                    <th>現在の悩み(機能面)</th>
                    <th>期間中の目標(容姿面)</th>
                    <th>期間中の目標(機能面)</th>
                    <th>未来のセルフイメージ</th>
                    <th>次の2週間でやるワーク</th>
                    <th>個別コンサルで相談したいこと</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <?php foreach (['email', 'name', 'current_concerns_appearance', 'current_concerns_function', 'goal_appearance', 'goal_function', 'future_self_image', 'next_two_weeks_work', 'consulting_topics'] as $field): ?>
                        <td class="editable-purpose" data-field="<?= $field ?>"><span><?= h($purpose->$field) ?></span></td>
                    <?php endforeach; ?>
                    <td><button type="submit" class="btn btn-success save-btn" style="display:none;">保存</button></td>
                </tr>
            </tbody>
        </table>
    </div>
    <?= $this->Form->end() ?>

    <!-- 身体データ（定性指標） -->
    <?= $this->Form->create(null, [
        'url' => ['controller' => 'Users', 'action' => 'bodyData', $user->id],
        'type' => 'post'
    ]) ?>
    <div class="card-body">
        <h4>4.身体データ（定性指標）</h4>
        <button type="button" id="add-body-row" class="btn btn-outline-primary btn-sm">＋ 追加</button>
        <table class="table table-bordered table-striped" id="body-data-table" style="margin-top: 4px;">
            <thead>
                <tr>
                    <th>日付</th>
                    <th>足首</th>
                    <th>足の甲</th>
                    <th>縦アーチ</th>
                    <th>横アーチ</th>
                    <th>足指</th>
                    <th>備考</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($bodyDatas as $index => $body): ?>
                    <input type="hidden" name="bodyData[<?= $index ?>][id]" value="<?= h($body->id) ?>">
                    <tr>
                        <?php foreach (['date', 'ankle', 'instep', 'vertical_arch', 'horizontal_arch', 'toes', 'note'] as $field): ?>
                            <td class="editable-body" data-field="<?= $field ?>" data-index="<?= $index ?>"><span><?= h($body[$field]) ?></span></td>
                        <?php endforeach; ?>
                        <td></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <button type="submit" class="btn btn-success save-btn" style="margin-top: 4px;">保存</button>
    </div>
    <?= $this->Form->end() ?>

    <!-- 心理・モチベーション -->
    <?= $this->Form->create(null, [
        'url' => ['controller' => 'Users', 'action' => 'mention', $user->id],
        'type' => 'post'
    ]) ?>
    <div class="card-body">
        <h4>5.心理・モチベーション</h4>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>性格傾向</th>
                    <th>モチベーション源泉</th>
                    <th>離脱リスク</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <?php foreach (['personality_tendency', 'motivation_source', 'dropout_risk'] as $field): ?>
                        <td class="editable-mention" data-field="<?= $field ?>"><span><?= h($mention[$field]) ?></span></td>
                    <?php endforeach; ?>
                    <td><button type="submit" class="btn btn-success save-btn" style="display:none;">保存</button></td>
                </tr>
            </tbody>
        </table>
    </div>
    <?= $this->Form->end() ?>

    <!-- lesson -->
    <?= $this->Form->create(null, [
        'url' => ['controller' => 'Users', 'action' => 'consults', $user->id],
        'type' => 'post'
    ]) ?>
    <div class="card-body">
        <h4>6.レッスン・コンサル時の指導履歴</h4>
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

                    <td class="editable-consult" data-field="date" data-index="<?= $index ?>">
                        <span><?= h($consultItem->date->format('Y/m/d')) ?></span>
                    </td>
                    <td class="editable-consult" data-field="category" data-index="<?= $index ?>">
                        <span>
                            <?= $consultItem->category == 1 ? '全体イベント' : ($consultItem->category == 2 ? '個別コンサルティング' : 'グループレッスン') ?>
                        </span>
                    </td>


                    <td class="editable-consult" data-field="theme" data-index="<?= $index ?>">
                        <span><?= h($consultItem->theme) ?></span>
                        <input type="hidden" name="consults[<?= $index ?>][theme]" value="<?= h($consultItem->theme) ?>">
                    </td>
                    <td class="editable-consult" data-field="instruction" data-index="<?= $index ?>">
                        <span><?= h($consultItem->instruction) ?></span>
                        <input type="hidden" name="consults[<?= $index ?>][instruction]" value="<?= h($consultItem->instruction) ?>">
                    </td>
                    <td class="editable-consult" data-field="note" data-index="<?= $index ?>">
                        <span><?= h($consultItem->note) ?></span>
                        <input type="hidden" name="consults[<?= $index ?>][note]" value="<?= h($consultItem->note) ?>">
                    </td>
                    <td class="editable-consult" data-field="next_step" data-index="<?= $index ?>">
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
        <button type="submit" class="btn btn-success mt-2">保存</button>
    </div>

    <?= $this->Form->end() ?>

    <!-- 自己記録 -->
    <?= $this->Form->create(null, [
        'url' => ['controller' => 'Users', 'action' => 'selfData', $user->id],
        'type' => 'post'
    ]) ?>
    <div class="card-body">
        <h4>7.課題の自己認識結果</h4>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>登録日</th>
                    <th>A.前回から今回までの良い変化・成長</th>
                    <th>B.前回設定したワークの振り返り</th>
                    <th>C.まだ変化を感じられていない部分</th>
                    <th>D.今の自分の状態</th>
                    <th>E.次の2週間でやりたいワーク</th>
                    <th>F.個別コンサルで相談したいこと</th>
                    <th>指導内容</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($selfdata as $index => $self): ?>
                    <input type="hidden" name="selfData[<?= $index ?>][id]" value="<?= h($self['id']) ?>">
                    <tr>
                        <?php foreach ([
                            'date',
                            'good_changes',
                            'work_review',
                            'no_change_parts',
                            'current_state',
                            'next_two_weeks_work',
                            'consult_topics',
                            'consult_content'
                        ] as $field): ?>
                            <td class="editable-self" data-field="<?= $field ?>" data-index="<?= $index ?>">
                                <span><?= h($self[$field]) ?></span>
                                <input type="hidden" name="selfData[<?= $index ?>][<?= $field ?>]" value="<?= h($self[$field]) ?>">
                            </td>
                        <?php endforeach; ?>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <button type="submit" class="btn btn-success save-btn mt-1">保存</button>
    </div>
    <?= $this->Form->end() ?>

    <!-- 成果差分（マイルストーン表） -->
    <?= $this->Form->create(null, [
        'url' => ['controller' => 'Users', 'action' => 'differences', $user->id],
        'type' => 'post'
    ]) ?>
    <div class="card-body">
        <h4>8.標準モデルとの差異結果</h4>
        <button type="button" id="add-difference-row" class="btn btn-outline-primary btn-sm" style="margin-bottom: 4px;">＋ 追加</button>

        <table class="table table-bordered table-striped" id="difference-table">
            <thead>
                <tr>
                    <th>マイルストーン</th>
                    <th>成果カテゴリ</th>
                    <th>標準達成目安</th>
                    <th>評価</th>
                    <th>評価コメント</th>
                    <th>Before/After写真</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($differences as $index => $diff): ?>
                    <input type="hidden" name="differences[<?= $index ?>][id]" value="<?= h($diff['id']) ?>">
                    <tr>
                        <td class="editable-difference" data-field="milestone" data-index="<?= $index ?>"><span><?= h($diff['milestone']) ?></span></td>
                        <td class="editable-difference" data-field="category" data-index="<?= $index ?>"><span><?= h($diff['category']) ?></span></td>
                        <td class="editable-difference" data-field="targets" data-index="<?= $index ?>"><span><?= h($diff['target1']) ?><br><?= h($diff['target2']) ?></span></td>
                        <td class="editable-difference" data-field="evaluations" data-index="<?= $index ?>"><span><?= h($diff['evaluation1']) ?><br><?= h($diff['evaluation2']) ?></span></td>
                        <td class="editable-difference" data-field="comments" data-index="<?= $index ?>"><span><?= h($diff['comment1']) ?><br><?= h($diff['comment2']) ?></span></td>
                        <td>
                            <?php if($index == 0) : ?>
                                <a href="<?= $this->Url->build(['action' => 'comparedImages', $user->id]); ?>" class="btn btn-outline-primary btn-sm" target="_blank">画像比較確認</a>
                            <?php endif ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <button type="submit" class="btn btn-success" style="margin-top: 4px;">保存</button>
    </div>
    <?= $this->Form->end() ?>
</div>


<script>
$(document).ready(function() {
    const courses = <?= json_encode($courses) ?>;
    const staffs = <?= json_encode($staffs) ?>;
    const eventType = <?= json_encode($eventType) ?>;
    const bodyOptions = ['◎', '◯', '△', '✕'];

    const showSaveButton = ($td) => {
        $td.closest('tr').find('.save-btn').show();
    };

    // 🔧 Collect current value safely
    const getCurrentValue = ($td) => {
        const html = $td.find('span').html() || '';
        return html.replace(/<br\s*\/?>/gi, '\n').trim();
    };

    const getCurrentValuesArray = ($td) => {
        const html = $td.find('span').html() || '';
        return html.split('<br>').map(v => v.trim());
    };

    // General handler
    $(document).on('click', '.editable, .editable-habit, .editable-purpose, .editable-mention', function() {
        const $td = $(this);
        if ($td.find('input, select, textarea').length > 0) return;
        const field = $td.data('field');
        const value = getCurrentValue($td);
        $td.html(`<textarea name="${field}" class="form-control">${value}</textarea>`);
        showSaveButton($td);
    });

    // Special handling for basic info
    $(document).on('click', '.editable', function() {
        const $td = $(this);
        const field = $td.data('field');
        const value = getCurrentValue($td);
        let html = '';
        switch (field) {
            case 'course_id':
                html = '<select name="course_id" class="form-control">' +
                    Object.entries(courses).map(([id, name]) =>
                        `<option value="${id}" ${name === value ? 'selected' : ''}>${name}</option>`).join('') +
                    '</select>';
                break;
            case 'name_kanji':
                html = `<input name="last_name" class="form-control mb-1" value="<?= h($user->last_name) ?>" placeholder="姓">
                        <input name="first_name" class="form-control" value="<?= h($user->first_name) ?>" placeholder="名">`;
                break;
            case 'name_kana':
                html = `<input name="last_name_kana" class="form-control mb-1" value="<?= h($user->last_name_kana) ?>" placeholder="姓カナ">
                        <input name="first_name_kana" class="form-control" value="<?= h($user->first_name_kana) ?>" placeholder="名カナ">`;
                break;
            case 'birthday':
                html = `<input type="date" name="birthday" class="form-control" value="${value}">`;
                break;
            case 'address':
                html = `<input name="zipcode" class="form-control mb-1" value="<?= h($user->zipcode) ?>" placeholder="郵便番号">
                        <input name="prefecture" class="form-control mb-1" value="<?= h($user->prefecture) ?>" placeholder="都道府県">
                        <input name="city" class="form-control mb-1" value="<?= h($user->city) ?>" placeholder="市区町村">
                        <input name="address" class="form-control" value="<?= h($user->address) ?>" placeholder="住所詳細">`;
                break;
            case 'start_datetime':
                html = `<input type="date" name="start_datetime" class="form-control" value="<?= date('Y-m-d', strtotime($user->start_datetime)) ?>">`;
                break;
            case 'staff_id':
                html = '<select name="staff_id" class="form-control">' +
                    Object.entries(staffs).map(([id, name]) =>
                        `<option value="${id}" ${name === value ? 'selected' : ''}>${name}</option>`).join('') +
                    '</select>';
                break;
        }
        $td.html(html);
        showSaveButton($td);
    });

    // BodyData editable
    $(document).on('click', '.editable-body', function() {
        const $td = $(this);
        if ($td.find('input, select').length > 0) return;
        const field = $td.data('field');
        const index = $td.data('index');
        const value = getCurrentValue($td);
        let input;
        if (field === 'date') {
            const formatted = value.replace(/\//g, '-');
            input = $(`<input type="date" class="form-control" value="${formatted}">`);
            input.on('change', function () {
                $(`input[name="consults[${index}][date]"]`).val(this.value);
            });
        } else if (field === 'note') {
            input = `<input name="bodyData[${index}][note]" class="form-control" value="${value}">`;
        } else {
            input = `<select name="bodyData[${index}][${field}]" class="form-control">
                ${bodyOptions.map(opt => `<option value="${opt}" ${opt === value ? 'selected' : ''}>${opt}</option>`).join('')}
            </select>`;
        }
        $td.html(input);
        showSaveButton($td);
    });

    $(document).on('click', '.editable-self', function () {
        const $td = $(this);
        const field = $td.data('field');
        const index = $td.data('index');
        const value = $td.find('span').text().trim();

        if ($td.find('input:not([type="hidden"]), textarea').length > 0) return;

        let input;

        if (field === 'date') {
            const formatted = value.replace(/\//g, '-');
            input = $(`<input type="date" class="form-control" value="${formatted}">`);
            input.on('change', function () {
                $(`input[name="consults[${index}][date]"]`).val(this.value);
            });
        } else {
            input = `<textarea name="selfData[${index}][${field}]" class="form-control">${value}</textarea>`;
        }

        $td.find('span').hide();
        $td.append(input);

        // Show table-level save button (outside of the row)
        $('.selfdata-save-btn').show();
    });


    // Difference table editable
    $(document).on('click', '.editable-difference', function() {
        const $td = $(this);
        if ($td.find('input, select, textarea').length > 0) return;

        const field = $td.data('field');
        const index = $td.data('index');
        const [val1, val2] = getCurrentValuesArray($td);
        let html = '';

        if (field === 'milestone' || field === 'category') {
            html = `<input name="differences[${index}][${field}]" class="form-control" value="${val1}">`;
        }
        else if (field === 'targets') {
            html = `<textarea name="differences[${index}][target1]" class="form-control mb-1" placeholder="目標①">${val1}</textarea>
                    <textarea name="differences[${index}][target2]" class="form-control" placeholder="目標②">${val2}</textarea>`;
        }
        else if (field === 'evaluations') {
            html = ['evaluation1', 'evaluation2'].map((ev, i) =>
                `<select name="differences[${index}][${ev}]" class="form-control mb-1">
                    ${bodyOptions.map(opt => `<option value="${opt}" ${opt === (i === 0 ? val1 : val2) ? 'selected' : ''}>${opt}</option>`).join('')}
                 </select>`).join('');
        }
        else if (field === 'comments') {
            html = `<textarea name="differences[${index}][comment1]" class="form-control mb-1" placeholder="コメント①">${val1}</textarea>
                    <textarea name="differences[${index}][comment2]" class="form-control" placeholder="コメント②">${val2}</textarea>`;
        }

        $td.html(html);
        showSaveButton($td);
    });

    // Add body row
    $('#add-body-row').on('click', function() {
        const index = $('#body-data-table tbody tr').length;
        const row = `<tr>
            <td class="editable-body" data-field="date" data-index="${index}"><span></span></td>
            ${['ankle', 'instep', 'vertical_arch', 'horizontal_arch', 'toes'].map(f =>
                `<td class="editable-body" data-field="${f}" data-index="${index}"><span></span></td>`).join('')}
            <td class="editable-body" data-field="note" data-index="${index}"><span></span></td>
            <td><button type="submit" class="btn btn-success save-btn" style="display:none;">保存</button></td>
        </tr>`;
        $('#body-data-table tbody').append(row);
    });

    // Add difference row
    $('#add-difference-row').on('click', function() {
        const index = $('#difference-table tbody tr').length;
        const row = `<tr>
            <td class="editable-difference" data-field="milestone" data-index="${index}"><span></span></td>
            <td class="editable-difference" data-field="category" data-index="${index}"><span></span></td>
            <td class="editable-difference" data-field="targets" data-index="${index}"><span></span></td>
            <td class="editable-difference" data-field="evaluations" data-index="${index}"><span></span></td>
            <td class="editable-difference" data-field="comments" data-index="${index}"><span></span></td>
            <td><button type="submit" class="btn btn-success save-btn" style="display:none;">保存</button></td>
        </tr>`;
        $('#difference-table tbody').append(row);
    });

    //lesson
    // Editable text fields
    $(document).on('click', '.editable-consult', function () {
        const $td = $(this);
        const index = $td.data('index');
        const field = $td.data('field');
        const value = $td.find('span').text().trim();

        if ($td.find('input:not([type="hidden"]), select').length > 0) return;

        let input;

        if (field === 'date') {
            const formatted = value.replace(/\//g, '-');
            input = $(`<input type="date" class="form-control" value="${formatted}">`);
            input.on('change', function () {
                $(`input[name="consults[${index}][date]"]`).val(this.value);
            });
        } else if (field === 'category') {
            input = $('<select class="form-control"></select>');
            $.each(eventType, function (val, label) {
                input.append($('<option>', {
                    value: val,
                    text: label,
                    selected: label === value
                }));
            });
            input.on('change', function () {
                $(`input[name="consults[${index}][category]"]`).val(this.value);
            });
        } else {
            // Default: editable text
            input = $('<input>', {
                class: 'form-control',
                value: value
            });
            input.on('input', function () {
                $(`input[name="consults[${index}][${field}]"]`).val($(this).val());
            });
        }

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
});
</script>




