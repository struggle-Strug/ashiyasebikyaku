<?php $this->assign('title', 'カルテ編集'); ?>
<div class="card">
    <div class="card-body">
    <?= $this->Form->create($user, ['class' => 'form-horizontal', 'id' => 'member-form']) ?>
    <div class="card-body">
        <div class="form-group row">
            <label for="" class="col-sm-2 col-form-label text-md-right">コース</label>
            <div class="col-sm-4">
                <?= $this->Form->select('course_id', $courses, ['class' => 'form-control', 'placeholder' => '', 'default' => 2]) ?>
                <span class="text-danger"><?= $this->Form->error('course_id') ?></span>
            </div>
        </div>
        <div class="form-group row">
            <label for="" class="col-sm-2 col-form-label text-md-right">氏名 *</label>
            <div class="col-sm-4">
                <?= $this->Form->text('last_name', ['class' => 'form-control', 'placeholder' => '姓']) ?>
                <?= $this->Form->text('first_name', ['class' => 'form-control', 'placeholder' => '名']) ?>
            </div>
            <label for="" class="col-sm-2 col-form-label text-md-right">氏名（ふりがな）</label>
            <div class="col-sm-4">
                <?= $this->Form->text('last_name_kana', ['class' => 'form-control', 'placeholder' => '姓']) ?>
                <?= $this->Form->text('first_name_kana', ['class' => 'form-control', 'placeholder' => '名']) ?>
            </div>
        </div>
        <div class="form-group row">
            <label for="" class="col-sm-2 col-form-label text-md-right">生年月日</label>
            <div class="col-sm-4">
                <?= $this->Form->date('birthday', ['class' => 'form-control', 'placeholder' => '']) ?>
                <span class="text-danger"><?= $this->Form->error('birthday') ?></span>
            </div>
        </div>
        <div class="form-group row h-adr">
            <span class="p-country-name" style="display:none;">Japan</span>
            <label for="" class="col-sm-2 col-form-label text-md-right">住所</label>
            <div class="col-sm-2">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">〒</span>
                    </div>
                    <?= $this->Form->number('zipcode', ['class' => 'form-control p-postal-code', 'placeholder' => '数字のみ', 'maxlength' => 7]) ?>
                    <span class="text-danger"><?= $this->Form->error('zipcode') ?></span>
                </div>
            </div>
            <div class="col-sm-2">
                <?= $this->Form->select('prefectures', $prefectures, ['class' => 'custom-select p-region', 'empty' => '都道府県']) ?>
            </div>
            <div class="col-sm-2">
                <?= $this->Form->text('city', ['class' => 'form-control p-locality', 'placeholder' => '市区町村']) ?>
                <span class="text-danger"><?= $this->Form->error('city') ?></span>
            </div>
            <div class="col-sm-4">
                <?= $this->Form->text('address', ['class' => 'form-control p-street-address', 'placeholder' => '地区番地']) ?>
                <span class="text-danger"><?= $this->Form->error('address') ?></span>
            </div>
        </div>
        <div class="form-group row">
            <label for="" class="col-sm-2 col-form-label text-md-right">利用開始日時 *</label>
            <div class="col-sm-4">
                <?= $this->Form->datetime('start_datetime', ['class' => 'form-control', 'step' => 60]) ?>
                <span class="text-danger"><?= $this->Form->error('start_datetime') ?></span>
            </div>
            <label for="" class="col-sm-2 col-form-label text-md-right">担当講師</label>
            <div class="col-sm-4">
                <?= $this->Form->select('staff_id', $staffs, ['class' => 'form-control', 'placeholder' => '']) ?>
                <span class="text-danger"><?= $this->Form->error('staff_id') ?></span>
            </div>
        </div>
        <div class="form-group row">
            <label for="" class="col-sm-2 col-form-label text-md-right">職業</label>
            <div class="col-sm-6">
                <?= $this->Form->text('occupation', ['class' => 'form-control', 'placeholder' => $mockHabitsData['occupation']]) ?>
                <span class="text-danger"><?= $this->Form->error('occupation') ?></span>
            </div>
        </div>
        <div class="form-group row">
            <label for="" class="col-sm-2 col-form-label text-md-right">食事習慣</label>
            <div class="col-sm-6">
                <?= $this->Form->textarea('eating_habits', ['class' => 'form-control', 'placeholder' => $mockHabitsData['eating_habits']]) ?>
                <span class="text-danger"><?= $this->Form->error('eating_habits') ?></span>
            </div>
        </div>
        <div class="form-group row">
            <label for="" class="col-sm-2 col-form-label text-md-right">睡眠</label>
            <div class="col-sm-6">
                <?= $this->Form->textarea('sleep', ['class' => 'form-control', 'placeholder' => $mockHabitsData['sleep']]) ?>
                <span class="text-danger"><?= $this->Form->error('course_id') ?></span>
            </div>
        </div>
        <div class="form-group row">
            <label for="" class="col-sm-2 col-form-label text-md-right">ストレス要因</label>
            <div class="col-sm-6">
                <?= $this->Form->textarea('stress_factors', ['class' => 'form-control', 'placeholder' => $mockHabitsData['stress_factors']]) ?>
                <span class="text-danger"><?= $this->Form->error('stress_factors') ?></span>
            </div>
        </div>
        <div class="form-group row">
            <label for="" class="col-sm-2 col-form-label text-md-right">運動歴</label>
            <div class="col-sm-6">
                <?= $this->Form->textarea('exercise_history', ['class' => 'form-control', 'placeholder' => $mockHabitsData['exercise_history']]) ?>
                <span class="text-danger"><?= $this->Form->error('exercise_history') ?></span>
            </div>
        </div>
        <div class="form-group row">
            <label for="" class="col-sm-2 col-form-label text-md-right">既往歴</label>
            <div class="col-sm-6">
                <?= $this->Form->textarea('medical_history', ['class' => 'form-control', 'placeholder' => $mockHabitsData['medical_history']]) ?>
                <span class="text-danger"><?= $this->Form->error('medical_history') ?></span>
            </div>
        </div>
        <div class="form-group row">
            <label for="" class="col-sm-2 col-form-label text-md-right">その他注意点</label>
            <div class="col-sm-6">
                <?= $this->Form->textarea('other_notes', ['class' => 'form-control', 'placeholder' => $mockHabitsData['other_notes']]) ?>
                <span class="text-danger"><?= $this->Form->error('other_notes') ?></span>
            </div>
        </div>
        <div class="form-group row">
            <label for="" class="col-sm-2 col-form-label text-md-right">メールアドレス</label>
            <div class="col-sm-6">
                <?= $this->Form->text('email', ['class' => 'form-control', 'placeholder' => $mockPurposeData['email']]) ?>
                <span class="text-danger"><?= $this->Form->error('email') ?></span>
            </div>
        </div>
        <div class="form-group row">
            <label for="" class="col-sm-2 col-form-label text-md-right">名前</label>
            <div class="col-sm-6">
                <?= $this->Form->text('name', ['class' => 'form-control', 'placeholder' => $mockPurposeData['name']]) ?>
                <span class="text-danger"><?= $this->Form->error('name') ?></span>
            </div>
        </div>
        <div class="form-group row">
            <label for="" class="col-sm-2 col-form-label text-md-right">現在の悩み(容姿面)</label>
            <div class="col-sm-6">
                <?= $this->Form->textarea('current_concerns_appearance', ['class' => 'form-control', 'placeholder' => $mockPurposeData['current_concerns_appearance']]) ?>
                <span class="text-danger"><?= $this->Form->error('current_concerns_appearance') ?></span>
            </div>
        </div>
        <div class="form-group row">
            <label for="" class="col-sm-2 col-form-label text-md-right">現在の悩み(機能面)</label>
            <div class="col-sm-6">
                <?= $this->Form->textarea('current_concerns_function', ['class' => 'form-control', 'placeholder' => $mockPurposeData['current_concerns_function']]) ?>
                <span class="text-danger"><?= $this->Form->error('current_concerns_function') ?></span>
            </div>
        </div>
        <div class="form-group row">
            <label for="" class="col-sm-2 col-form-label text-md-right">期間中の目標(容姿面)</label>
            <div class="col-sm-6">
                <?= $this->Form->textarea('goal_appearance', ['class' => 'form-control', 'placeholder' => $mockPurposeData['goal_appearance']]) ?>
                <span class="text-danger"><?= $this->Form->error('goal_appearance') ?></span>
            </div>
        </div>
        <div class="form-group row">
            <label for="" class="col-sm-2 col-form-label text-md-right">期間中の目標(機能面)</label>
            <div class="col-sm-6">
                <?= $this->Form->textarea('goal_function', ['class' => 'form-control', 'placeholder' => $mockPurposeData['goal_function']]) ?>
                <span class="text-danger"><?= $this->Form->error('goal_function') ?></span>
            </div>
        </div>
        <div class="form-group row">
            <label for="" class="col-sm-2 col-form-label text-md-right">未来のセルフイメージ</label>
            <div class="col-sm-6">
                <?= $this->Form->textarea('future_self_image', ['class' => 'form-control', 'placeholder' => $mockPurposeData['future_self_image']]) ?>
                <span class="text-danger"><?= $this->Form->error('future_self_image') ?></span>
            </div>
        </div>
        <div class="form-group row">
            <label for="" class="col-sm-2 col-form-label text-md-right">次の2週間でやるワーク</label>
            <div class="col-sm-6">
                <?= $this->Form->textarea('next_two_weeks_work', ['class' => 'form-control', 'placeholder' => $mockPurposeData['next_two_weeks_work']]) ?>
                <span class="text-danger"><?= $this->Form->error('next_two_weeks_work') ?></span>
            </div>
        </div>
        <div class="form-group row">
            <label for="" class="col-sm-2 col-form-label text-md-right">個別コンサルで相談したいこと</label>
            <div class="col-sm-6">
                <?= $this->Form->textarea('consulting_topics', ['class' => 'form-control', 'placeholder' => $mockPurposeData['consulting_topics']]) ?>
                <span class="text-danger"><?= $this->Form->error('consulting_topics') ?></span>
            </div>
        </div>
        <div class="form-group row">
            <label for="" class="col-sm-2 col-form-label text-md-right">測定日</label>
            <div class="col-sm-4">
                <?= $this->Form->datetime('date', ['class' => 'form-control', 'step' => 60]) ?>
                <span class="text-danger"><?= $this->Form->error('date') ?></span>
            </div>
        </div>
        <div class="form-group row">
            <label for="" class="col-sm-2 col-form-label text-md-right">足首</label>
            <div class="col-sm-2">
                <?= $this->Form->select('ankle', $bodyDataOptions, ['class' => 'form-control', 'placeholder' => '', 'default' => '']) ?>
                <span class="text-danger"><?= $this->Form->error('ankle') ?></span>
            </div>
            <label for="" class="col-sm-2 col-form-label text-md-right">足の甲</label>
            <div class="col-sm-2">
                <?= $this->Form->select('instep', $bodyDataOptions, ['class' => 'form-control', 'placeholder' => '', 'default' => '']) ?>
                <span class="text-danger"><?= $this->Form->error('instep') ?></span>
            </div>
            <label for="" class="col-sm-2 col-form-label text-md-right">縦アーチ</label>
            <div class="col-sm-2">
                <?= $this->Form->select('vertical_arch', $bodyDataOptions, ['class' => 'form-control', 'placeholder' => '', 'default' => '']) ?>
                <span class="text-danger"><?= $this->Form->error('vertical_arch') ?></span>
            </div>
            <label for="" class="col-sm-2 col-form-label text-md-right">横アーチ</label>
            <div class="col-sm-2" style="margin-top: 12px;">
                <?= $this->Form->select('horizontal_arch', $bodyDataOptions, ['class' => 'form-control', 'placeholder' => '', 'default' => '']) ?>
                <span class="text-danger"><?= $this->Form->error('horizontal_arch') ?></span>
            </div>
            <label for="" class="col-sm-2 col-form-label text-md-right">足指</label>
            <div class="col-sm-2" style="margin-top: 12px;">
                <?= $this->Form->select('toes', $bodyDataOptions, ['class' => 'form-control', 'placeholder' => '', 'default' => '']) ?>
                <span class="text-danger"><?= $this->Form->error('toes') ?></span>
            </div>
        </div>
        <div class="form-group row">
            <label for="" class="col-sm-2 col-form-label text-md-right">備考</label>
            <div class="col-sm-6">
                <?= $this->Form->textarea('note', ['class' => 'form-control', 'placeholder' => '']) ?>
                <span class="text-danger"><?= $this->Form->error('note') ?></span>
            </div>
        </div>
        <div class="form-group row">
            <label for="" class="col-sm-2 col-form-label text-md-right">性格傾向</label>
            <div class="col-sm-6">
                <?= $this->Form->text('personality_tendency', ['class' => 'form-control', 'placeholder' => $mockMentionData['personality_tendency']]) ?>
                <span class="text-danger"><?= $this->Form->error('personality_tendency') ?></span>
            </div>
        </div>
        <div class="form-group row">
            <label for="" class="col-sm-2 col-form-label text-md-right">モチベーション源泉</label>
            <div class="col-sm-6">
                <?= $this->Form->textarea('motivation_source', ['class' => 'form-control', 'placeholder' => $mockMentionData['motivation_source']]) ?>
                <span class="text-danger"><?= $this->Form->error('motivation_source') ?></span>
            </div>
        </div>
        <div class="form-group row">
            <label for="" class="col-sm-2 col-form-label text-md-right">離脱リスク</label>
            <div class="col-sm-6">
                <?= $this->Form->textarea('dropout_risk', ['class' => 'form-control', 'placeholder' => $mockMentionData['dropout_risk']]) ?>
                <span class="text-danger"><?= $this->Form->error('dropout_risk') ?></span>
            </div>
        </div>
        <div class="form-group row">
            <label for="" class="col-sm-2 col-form-label text-md-right">開催日時</label>
            <div class="col-sm-4">
                <?= $this->Form->datetime('date', ['class' => 'form-control', 'step' => 60]) ?>
                <span class="text-danger"><?= $this->Form->error('date') ?></span>
            </div>
        </div>
        <div class="form-group row">
            <?= $this->Form->hidden('category', ['value' => '実践レッスン']) ?>
            <?= $this->Form->hidden('theme', ['value' => '歩き方（仙骨ウォーク）']) ?>
            <label for="" class="col-sm-2 col-form-label text-md-right">指導内容<br />（やったこと）</label>
            <div class="col-sm-6">
                <?= $this->Form->textarea('instruction', ['class' => 'form-control', 'placeholder' => '']) ?>
                <span class="text-danger"><?= $this->Form->error('instruction') ?></span>
            </div>
        </div>
        <div class="form-group row">
            <label for="" class="col-sm-2 col-form-label text-md-right">注意点<br />（分かったこと）</label>
            <div class="col-sm-6">
                <?= $this->Form->textarea('note', ['class' => 'form-control', 'placeholder' => '']) ?>
                <span class="text-danger"><?= $this->Form->error('note') ?></span>
            </div>
        </div>
        <div class="form-group row">
            <label for="" class="col-sm-2 col-form-label text-md-right">申し送り事項<br />（次やること）</label>
            <div class="col-sm-6">
                <?= $this->Form->textarea('next_step', ['class' => 'form-control', 'placeholder' => '']) ?>
                <span class="text-danger"><?= $this->Form->error('next_step') ?></span>
            </div>
        </div>
        <div class="form-group row">
            <label for="" class="col-sm-2 col-form-label text-md-right">担当講師</label>
            <div class="col-sm-2">
                <?= $this->Form->select('instructor', $staffs, ['class' => 'form-control', 'placeholder' => '']) ?>
                <span class="text-danger"><?= $this->Form->error('staff_id') ?></span>
            </div>
        </div>
        <div class="form-group row">
            <label for="" class="col-sm-2 col-form-label text-md-right">指導内容</label>
            <div class="col-sm-6">
                <?= $this->Form->textarea('consult_content', ['class' => 'form-control', 'placeholder' => '']) ?>
                <span class="text-danger"><?= $this->Form->error('consult_content') ?></span>
            </div>
        </div>
        <div class="form-group row">
            <label for="" class="col-sm-2 col-form-label text-md-right">マイルストーン</label>
            <div class="col-sm-4">
                <?= $this->Form->text('milestone', ['class' => 'form-control', 'placeholder' => '']) ?>
                <span class="text-danger"><?= $this->Form->error('milestone') ?></span>
            </div>
        </div>
        <div class="form-group row">
            <label for="" class="col-sm-2 col-form-label text-md-right">成果カテゴリ</label>
            <div class="col-sm-6">
                <?= $this->Form->text('category', ['class' => 'form-control', 'placeholder' => '']) ?>
                <span class="text-danger"><?= $this->Form->error('category') ?></span>
            </div>
        </div>
        <div class="form-group row">
            <label for="" class="col-sm-2 col-form-label text-md-right">標準達成目安1</label>
            <div class="col-sm-6">
                <?= $this->Form->textarea('target1', ['class' => 'form-control', 'placeholder' => '']) ?>
                <span class="text-danger"><?= $this->Form->error('target1') ?></span>
            </div>
        </div>
        <div class="form-group row">
            <label for="" class="col-sm-2 col-form-label text-md-right">標準達成目安2</label>
            <div class="col-sm-6">
                <?= $this->Form->textarea('target2', ['class' => 'form-control', 'placeholder' => '']) ?>
                <span class="text-danger"><?= $this->Form->error('target2') ?></span>
            </div>
        </div>
        <div class="form-group row">
            <label for="" class="col-sm-2 col-form-label text-md-right">評価1</label>
            <div class="col-sm-6">
                <?= $this->Form->textarea('evaluation1', ['class' => 'form-control', 'placeholder' => '']) ?>
                <span class="text-danger"><?= $this->Form->error('evaluation1') ?></span>
            </div>
        </div>
        <div class="form-group row">
            <label for="" class="col-sm-2 col-form-label text-md-right">評価2</label>
            <div class="col-sm-6">
                <?= $this->Form->textarea('evaluation2', ['class' => 'form-control', 'placeholder' => '']) ?>
                <span class="text-danger"><?= $this->Form->error('evaluation2') ?></span>
            </div>
        </div>
        <div class="form-group row">
            <label for="" class="col-sm-2 col-form-label text-md-right">評価コメント1</label>
            <div class="col-sm-6">
                <?= $this->Form->textarea('comment1', ['class' => 'form-control', 'placeholder' => '']) ?>
                <span class="text-danger"><?= $this->Form->error('comment1') ?></span>
            </div>
        </div>
        <div class="form-group row">
            <label for="" class="col-sm-2 col-form-label text-md-right">評価コメント2</label>
            <div class="col-sm-6">
                <?= $this->Form->textarea('comment2', ['class' => 'form-control', 'placeholder' => '']) ?>
                <span class="text-danger"><?= $this->Form->error('comment2') ?></span>
            </div>
        </div>
        <!-- /.card-body -->
        <div class="card-footer">
            <a class="btn btn-outline-secondary" href="<?= $this->Url->build(['action' => 'customerInformations', $user -> id, '?' => $param]) ?>" id="back-btn">
                <i class="fas fa-arrow-circle-left"></i> 戻る
            </a>
            <span class="float-right">
                <button type="submit" class="btn btn-primary" id="register-btn">登録</button>
            </span>
        </div>
    <?= $this->Form->end() ?>
    </div>
</div>
