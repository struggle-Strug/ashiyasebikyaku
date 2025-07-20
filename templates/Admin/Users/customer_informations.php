<?php $this->assign('title', 'カルテ'); ?>
<div class="card">

    <!-- USER INFO FORM -->
    <?= $this->Form->create($user, [
        'url' => ['controller' => 'Users', 'action' => 'customerMain', $user->id],
        'type' => 'post'
    ]) ?>
    <div class="card-body">
        <h4>基本情報</h4>
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

    <!-- HABITS FORM -->
    <?= $this->Form->create($habit, [
        'url' => ['controller' => 'Users', 'action' => 'habits', $user->id],
        'type' => 'post'
    ]) ?>
    <div class="card-body">
        <h4>生活習慣・既往歴</h4>
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

    <!-- PURPOSE FORM -->
    <?= $this->Form->create($purpose, [
        'url' => ['controller' => 'Users', 'action' => 'purpose', $user->id],
        'type' => 'post'
    ]) ?>
    <div class="card-body">
        <h4>目標設定シート</h4>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>メール</th>
                    <th>名前</th>
                    <th>現在の見た目の悩み</th>
                    <th>現在の機能の悩み</th>
                    <th>見た目の目標</th>
                    <th>機能の目標</th>
                    <th>未来の自己イメージ</th>
                    <th>次の2週間の課題</th>
                    <th>相談内容</th>
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

    <!-- BODYDATA FORM -->
    <?= $this->Form->create(null, [
        'url' => ['controller' => 'Users', 'action' => 'bodyData', $user->id],
        'type' => 'post'
    ]) ?>
    <div class="card-body">
        <h4>ボディデータ</h4>
        <button type="button" id="add-body-row" class="btn btn-outline-primary btn-sm">＋ 追加</button>
        <table class="table table-bordered table-striped" id="body-data-table" style="margin-top: 4px;">
            <thead>
                <tr>
                    <th>日付</th>
                    <th>足首</th>
                    <th>甲</th>
                    <th>縦アーチ</th>
                    <th>横アーチ</th>
                    <th>つま先</th>
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
        
        <button type="submit" class="btn btn-success save-btn" style="display:none; margin-top: 4px;">保存</button>
    </div>
    <?= $this->Form->end() ?>

</div>

<script>
$(document).ready(function() {
    const courses = <?= json_encode($courses) ?>;
    const staffs = <?= json_encode($staffs) ?>;
    const bodyOptions = ['◎', '◯', '△', '✕'];

    const showSaveButton = ($td) => $td.closest('form').find('.save-btn').show();

    $('.editable, .editable-habit, .editable-purpose').on('click', function() {
        const $td = $(this);
        if ($td.find('input, select, textarea').length > 0) return;
        const field = $td.data('field');
        const value = $td.text().trim();
        let input = value.length > 50 ? `<textarea name="${field}" class="form-control">${value}</textarea>` : `<input name="${field}" class="form-control" value="${value}">`;
        $td.html(input);
        showSaveButton($td);
    });

    $('.editable').on('click', function() {
        const $td = $(this);
        const field = $td.data('field');
        let html = '';
        switch (field) {
            case 'course_id':
                html = '<select name="course_id" class="form-control">' + Object.entries(courses).map(([id, name]) => `<option value="${id}">${name}</option>`).join('') + '</select>';
                break;
            case 'name_kanji':
                html = `<input name="last_name" class="form-control mb-1" value="<?= h($user->last_name) ?>" placeholder="姓"><input name="first_name" class="form-control" value="<?= h($user->first_name) ?>" placeholder="名">`;
                break;
            case 'name_kana':
                html = `<input name="last_name_kana" class="form-control mb-1" value="<?= h($user->last_name_kana) ?>" placeholder="姓カナ"><input name="first_name_kana" class="form-control" value="<?= h($user->first_name_kana) ?>" placeholder="名カナ">`;
                break;
            case 'birthday':
                html = `<input type="date" name="birthday" class="form-control" value="<?= h($user->birthday) ?>">`;
                break;
            case 'address':
                html = `<input name="zipcode" class="form-control mb-1" value="<?= h($user->zipcode) ?>" placeholder="郵便番号"><input name="prefecture" class="form-control mb-1" value="<?= h($user->prefecture) ?>" placeholder="都道府県"><input name="city" class="form-control mb-1" value="<?= h($user->city) ?>" placeholder="市区町村"><input name="address" class="form-control" value="<?= h($user->address) ?>" placeholder="住所詳細">`;
                break;
            case 'start_datetime':
                html = `<input type="date" name="start_datetime" class="form-control" value="<?= date('Y-m-d', strtotime($user->start_datetime)) ?>">`;
                break;
            case 'staff_id':
                html = '<select name="staff_id" class="form-control">' + Object.entries(staffs).map(([id, name]) => `<option value="${id}">${name}</option>`).join('') + '</select>';
                break;
        }
        $td.html(html);
        showSaveButton($td);
    });

    $('#add-body-row').on('click', function() {
        const index = $('#body-data-table tbody tr').length;
        const row = `<tr>
            <td class="editable-body" data-field="date" data-index="${index}"><span></span></td>
            ${['ankle', 'instep', 'vertical_arch', 'horizontal_arch', 'toes'].map(f => `<td class="editable-body" data-field="${f}" data-index="${index}"><span></span></td>`).join('')}
            <td class="editable-body" data-field="note" data-index="${index}"><span></span></td>
            <td></td>
        </tr>`;
        $('#body-data-table tbody').append(row);
    });

    $(document).on('click', '.editable-body', function() {
        const $td = $(this);
        if ($td.find('input, select').length > 0) return;
        const field = $td.data('field');
        const index = $td.data('index');
        const value = $td.text().trim();
        let input = field === 'date'
            ? `<input type="date" name="bodyData[${index}][${field}]" class="form-control" value="${value}">`
            : `<select name="bodyData[${index}][${field}]" class="form-control">${bodyOptions.map(opt => `<option value="${opt}" ${opt === value ? 'selected' : ''}>${opt}</option>`).join('')}</select>`;
        if (field === 'note') {
            input = `<input name="bodyData[${index}][note]" class="form-control" value="${value}">`;
        }
        $td.html(input);
        showSaveButton($td);
    });
});
</script>
