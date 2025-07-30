<?php $this->assign('title', 'カルテ'); ?>
<style>
    td .form-control {
        min-width: 150px;
        max-width: 100%;
        width: 100%;
        font-size: 14px;
        resize: vertical;
    }
    .table-responsive {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }
    .table-responsive table {
        min-width: 1000px;
    }
</style>

<div class="card">

    <!-- 1. 基本情報 -->
    <?= $this->Form->create($user, [
        'url' => ['controller' => 'Users', 'action' => 'customerMain', $user->id],
        'type' => 'post'
    ]) ?>
    <div class="card-body">
        <h4>1.基本情報</h4>
        <div class="table-responsive">
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
                        <td>
                            <select name="course_id" class="form-control">
                                <?php foreach ($courses as $id => $name): ?>
                                    <option value="<?= $id ?>" <?= $user->course_id == $id ? 'selected' : '' ?>><?= h($name) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                        <td>
                            <input name="last_name" class="form-control mb-1" value="<?= h($user->last_name) ?>" placeholder="姓">
                            <input name="first_name" class="form-control" value="<?= h($user->first_name) ?>" placeholder="名">
                        </td>
                        <td>
                            <input name="last_name_kana" class="form-control mb-1" value="<?= h($user->last_name_kana) ?>" placeholder="姓カナ">
                            <input name="first_name_kana" class="form-control" value="<?= h($user->first_name_kana) ?>" placeholder="名カナ">
                        </td>
                        <td><input type="date" name="birthday" class="form-control" value="<?= date('Y-m-d', strtotime($user->birthday)) ?>"></td>
                        <td>
                            <input name="zipcode" class="form-control mb-1" value="<?= h($user->zipcode) ?>" placeholder="郵便番号">
                            <input name="prefecture" class="form-control mb-1" value="<?= h($user->prefecture) ?>" placeholder="都道府県">
                            <input name="city" class="form-control mb-1" value="<?= h($user->city) ?>" placeholder="市区町村">
                            <input name="address" class="form-control" value="<?= h($user->address) ?>" placeholder="住所詳細">
                        </td>
                        <td><input type="date" name="start_datetime" class="form-control" value="<?= date('Y-m-d', strtotime($user->start_datetime)) ?>"></td>
                        <td>
                            <select name="staff_id" class="form-control">
                                <?php foreach ($staffs as $id => $name): ?>
                                    <option value="<?= $id ?>" <?= $user->staff_id == $id ? 'selected' : '' ?>><?= h($name) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                        <td><button type="submit" class="btn btn-success">保存</button></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <?= $this->Form->end() ?>

    <!-- 2. 生活習慣 -->
    <?= $this->Form->create($habit, [
        'url' => ['controller' => 'Users', 'action' => 'habits', $user->id],
        'type' => 'post'
    ]) ?>
    <div class="card-body">
        <h4>2.生活習慣・既往歴</h4>
        <div class="table-responsive">
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
                            <td><textarea name="<?= $field ?>" class="form-control"><?= h($habit->$field) ?></textarea></td>
                        <?php endforeach; ?>
                        <td><button type="submit" class="btn btn-success">保存</button></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <?= $this->Form->end() ?>

    <!-- 3. 目標設定 -->
    <?= $this->Form->create($purpose, [
        'url' => ['controller' => 'Users', 'action' => 'purpose', $user->id],
        'type' => 'post'
    ]) ?>
    <div class="card-body">
        <h4>3.目標設定シート</h4>
        <div class="table-responsive">
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
                            <td><textarea name="<?= $field ?>" class="form-control"><?= h($purpose->$field) ?></textarea></td>
                        <?php endforeach; ?>
                        <td><button type="submit" class="btn btn-success">保存</button></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <?= $this->Form->end() ?>

    <!-- 4. 身体データ -->
    <?= $this->Form->create(null, [
        'url' => ['controller' => 'Users', 'action' => 'bodyData', $user->id],
        'type' => 'post'
    ]) ?>
    <div class="card-body">
        <h4>4.身体データ（定性指標）</h4>
        <button type="button" id="add-body-row" class="btn btn-outline-primary btn-sm">＋ 追加</button>
        <div class="table-responsive">
            <table class="table table-bordered table-striped" id="body-data-table">
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
                        <tr>
                            <!-- Hidden ID field for updates -->
                            <input type="hidden" name="bodyData[<?= $index ?>][id]" value="<?= $body->id ?>">

                            <td>
                                <input type="date" 
                                    name="bodyData[<?= $index ?>][date]" 
                                    class="form-control" 
                                    value="<?= $body->date ? h($body->date->format('Y-m-d')) : '' ?>">
                            </td>
                            <?php foreach (['ankle', 'instep', 'vertical_arch', 'horizontal_arch', 'toes'] as $field): ?>
                                <td>
                                    <select name="bodyData[<?= $index ?>][<?= $field ?>]" class="form-control">
                                        <?php foreach (['◎', '◯', '△', '✕'] as $opt): ?>
                                            <option value="<?= $opt ?>" <?= $opt === $body[$field] ? 'selected' : '' ?>>
                                                <?= $opt ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                            <?php endforeach; ?>
                            <td><input name="bodyData[<?= $index ?>][note]" class="form-control" value="<?= h($body->note) ?>"></td>
                            <td></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <button type="submit" class="btn btn-success save-btn mt-2">保存</button>
    </div>
    <?= $this->Form->end() ?>

    <!-- 5. 心理・モチベーション -->
    <?= $this->Form->create($mention, [
        'url' => ['controller' => 'Users', 'action' => 'mention', $user->id],
        'type' => 'post'
    ]) ?>
    <div class="card-body">
        <h4>5.心理・モチベーション</h4>
        <div class="table-responsive">
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
                            <td><textarea name="<?= $field ?>" class="form-control"><?= h($mention->$field) ?></textarea></td>
                        <?php endforeach; ?>
                        <td><button type="submit" class="btn btn-success">保存</button></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <?= $this->Form->end() ?>

    <!-- 6. レッスン -->
    <?= $this->Form->create(null, [
        'url' => ['controller' => 'Users', 'action' => 'consults', $user->id],
        'type' => 'post'
    ]) ?>
    <div class="card-body">
        <h4>6.レッスン・コンサル時の指導履歴</h4>
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>開催日時</th>
                        <th>区分</th>
                        <th>テーマ</th>
                        <th>指導内容</th>
                        <th>注意点</th>
                        <th>申し送り事項</th>
                        <th>担当講師</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($consults as $index => $consultItem): ?>
                        <tr>
                            <!-- Hidden ID field for updates -->
                            <input type="hidden" name="consults[<?= $index ?>][id]" value="<?= $consultItem->id ?>">

                            <td>
                                <input type="date" 
                                    name="consults[<?= $index ?>][date]" 
                                    class="form-control" 
                                    value="<?= $consultItem->date ? h($consultItem->date->format('Y-m-d')) : '' ?>">
                            </td>
                            <td>
                                <select name="consults[<?= $index ?>][category]" class="form-control">
                                    <?php foreach ($eventType as $val => $label): ?>
                                        <option value="<?= $val ?>" <?= $consultItem->category == $val ? 'selected' : '' ?>>
                                            <?= h($label) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                            <?php foreach (['theme', 'instruction', 'note', 'next_step'] as $field): ?>
                                <td>
                                    <textarea name="consults[<?= $index ?>][<?= $field ?>]" class="form-control">
                                        <?= h($consultItem->$field) ?>
                                    </textarea>
                                </td>
                            <?php endforeach; ?>
                            <td>
                                <select name="consults[<?= $index ?>][staff_id]" class="form-control">
                                    <?php foreach ($staffs as $id => $name): ?>
                                        <option value="<?= $id ?>" <?= $consultItem->staff_id == $id ? 'selected' : '' ?>>
                                            <?= h($name) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <button type="submit" class="btn btn-success mt-2">保存</button>
    </div>
    <?= $this->Form->end() ?>


    <!-- 7. 自己認識 -->
    <?= $this->Form->create(null, [
        'url' => ['controller' => 'Users', 'action' => 'selfData', $user->id],
        'type' => 'post'
    ]) ?>
    <div class="card-body">
        <h4>7.課題の自己認識結果</h4>
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>登録日</th>
                        <th>A</th>
                        <th>B</th>
                        <th>C</th>
                        <th>D</th>
                        <th>E</th>
                        <th>F</th>
                        <th>指導内容</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($selfdata as $index => $self): ?>
                        <tr>
                            <td>
                                <input type="date" 
                                    name="selfdata[<?= $index ?>][date]" 
                                    class="form-control" 
                                    value="<?= $self->date ? h($self->date->format('Y-m-d')) : '' ?>">
                            </td>
                            <?php foreach (['good_changes', 'work_review', 'no_change_parts', 'current_state', 'next_two_weeks_work', 'consult_topics', 'consult_content'] as $field): ?>
                                <td><textarea name="selfData[<?= $index ?>][<?= $field ?>]" class="form-control"><?= h($self[$field]) ?></textarea></td>
                            <?php endforeach; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <button type="submit" class="btn btn-success mt-1">保存</button>
    </div>
    <?= $this->Form->end() ?>

    <!-- 8. 差異結果 -->
    <?= $this->Form->create(null, [
        'url' => ['controller' => 'Users', 'action' => 'differences', $user->id],
        'type' => 'post'
    ]) ?>
    <div class="card-body">
        <h4>8.標準モデルとの差異結果</h4>
        <button type="button" id="add-difference-row" class="btn btn-outline-primary btn-sm">＋ 追加</button>
        <div class="table-responsive">
            <table class="table table-bordered table-striped" id="difference-table">
                <thead>
                    <tr>
                        <th>マイルストーン</th>
                        <th>成果カテゴリ</th>
                        <th>標準達成目安</th>
                        <th>評価</th>
                        <th>評価コメント</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($differences as $index => $diff): ?>
                        <tr>
                            <input type="hidden" name="differences[<?= $index ?>][id]" value="<?= $diff['id'] ?? '' ?>">

                            <td><input name="differences[<?= $index ?>][milestone]" class="form-control" value="<?= h($diff['milestone']) ?>"></td>
                            <td><input name="differences[<?= $index ?>][category]" class="form-control" value="<?= h($diff['category']) ?>"></td>
                            <td>
                                <textarea name="differences[<?= $index ?>][target1]" class="form-control mb-1"><?= h($diff['target1']) ?></textarea>
                                <textarea name="differences[<?= $index ?>][target2]" class="form-control"><?= h($diff['target2']) ?></textarea>
                            </td>
                            <td>
                                <select name="differences[<?= $index ?>][evaluation1]" class="form-control mb-1">
                                    <?php foreach (['◎', '◯', '△', '✕'] as $opt): ?>
                                        <option value="<?= $opt ?>" <?= $opt == $diff['evaluation1'] ? 'selected' : '' ?>><?= $opt ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <select name="differences[<?= $index ?>][evaluation2]" class="form-control">
                                    <?php foreach (['◎', '◯', '△', '✕'] as $opt): ?>
                                        <option value="<?= $opt ?>" <?= $opt == $diff['evaluation2'] ? 'selected' : '' ?>><?= $opt ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                            <td>
                                <textarea name="differences[<?= $index ?>][comment1]" class="form-control mb-1"><?= h($diff['comment1']) ?></textarea>
                                <textarea name="differences[<?= $index ?>][comment2]" class="form-control"><?= h($diff['comment2']) ?></textarea>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <button type="submit" class="btn btn-success mt-1">保存</button>
    </div>
    <?= $this->Form->end() ?>
</div>

<script>
$(document).ready(function() {
    const bodyOptions = ['◎', '◯', '△', '✕'];

    $('#add-body-row').on('click', function() {
        const index = $('#body-data-table tbody tr').length;
        const selectField = (name) => `
            <select name="bodyData[${index}][${name}]" class="form-control">
                ${bodyOptions.map(opt => `<option value="${opt}">${opt}</option>`).join('')}
            </select>`;
        $('#body-data-table tbody').append(`
            <tr>
                <td><input type="date" name="bodyData[${index}][date]" class="form-control"></td>
                <td>${selectField('ankle')}</td>
                <td>${selectField('instep')}</td>
                <td>${selectField('vertical_arch')}</td>
                <td>${selectField('horizontal_arch')}</td>
                <td>${selectField('toes')}</td>
                <td><input name="bodyData[${index}][note]" class="form-control"></td>
                <td></td>
            </tr>`);
    });

    $('#add-difference-row').on('click', function() {
        const index = $('#difference-table tbody tr').length;
        const evaluationSelect = (name) => `
            <select name="differences[${index}][${name}]" class="form-control mb-1">
                ${bodyOptions.map(opt => `<option value="${opt}">${opt}</option>`).join('')}
            </select>`;
        $('#difference-table tbody').append(`
            <tr>
                <td><input name="differences[${index}][milestone]" class="form-control"></td>
                <td><input name="differences[${index}][category]" class="form-control"></td>
                <td>
                    <textarea name="differences[${index}][target1]" class="form-control mb-1"></textarea>
                    <textarea name="differences[${index}][target2]" class="form-control"></textarea>
                </td>
                <td>${evaluationSelect('evaluation1')} ${evaluationSelect('evaluation2')}</td>
                <td>
                    <textarea name="differences[${index}][comment1]" class="form-control mb-1"></textarea>
                    <textarea name="differences[${index}][comment2]" class="form-control"></textarea>
                </td>
            </tr>`);
    });
});
</script>
