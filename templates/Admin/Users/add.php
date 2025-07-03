<?php $this->assign('title', '会員登録'); ?>

<!-- Default box -->
<div class="card">
    <?= $this->Form->create($user, ['class' => 'form-horizontal']) ?>
    <div class="card-body">
        <div class="form-group row">
            <label for="" class="col-sm-2 col-form-label text-md-right">利用開始日時 *</label>
            <div class="col-sm-4">
                <?= $this->Form->datetime('start_datetime', ['class' => 'form-control', 'step' => 60]) ?>
                <span class="text-danger"><?= $this->Form->error('start_datetime') ?></span>
            </div>
            <label for="" class="col-sm-2 col-form-label text-md-right">利用終了日時</label>
            <div class="col-sm-4">
                <?= $this->Form->datetime('end_datetime', ['class' => 'form-control', 'step' => 60]) ?>
                <span class="text-danger"><?= $this->Form->error('end_datetime') ?></span>
            </div>
        </div>
        <div class="form-group row">
            <label for="" class="col-sm-2 col-form-label text-md-right">会員ID *</label>
            <div class="col-sm-4">
                <?= $this->Form->text('member_number', ['class' => 'form-control', 'placeholder' => '']) ?>
                <span class="text-danger"><?= $this->Form->error('member_number') ?></span>
            </div>
            <label for="" class="col-sm-2 col-form-label text-md-right">新規/継続</label>
            <div class="col-sm-4">
                <?= $this->Form->select('is_continue', [0 => '新規', 1 => '継続'], ['class' => 'form-control']) ?>
                <span class="text-danger"><?= $this->Form->error('is_continue') ?></span>
            </div>
        </div>
        <div class="form-group row">
            <label for="" class="col-sm-2 col-form-label text-md-right">担当講師</label>
            <div class="col-sm-4">
                <?= $this->Form->select('staff_id', $staffs, ['class' => 'form-control', 'placeholder' => '']) ?>
                <span class="text-danger"><?= $this->Form->error('staff_id') ?></span>
            </div>
            <label for="" class="col-sm-2 col-form-label text-md-right">ライトプラン</label>
            <div class="col-sm-4">
            <?= $this->Form->select('light_plan', [0 => 'いいえ', 1 => 'はい'], ['class' => 'form-control']) ?>
                <span class="text-danger"><?= $this->Form->error('light_plan') ?></span>
            </div>
        </div>
        <div class="form-group row">
            <label for="" class="col-sm-2 col-form-label text-md-right">講師プラン</label>
            <div class="col-sm-4">
                <?= $this->Form->select('staff_plan', [0 => 'いいえ', 1 => 'はい'], ['class' => 'form-control']) ?>
                <span class="text-danger"><?= $this->Form->error('staff_plan') ?></span>
            </div>
            <label for="" class="col-sm-2 col-form-label text-md-right">コース</label>
            <div class="col-sm-4">
                <?= $this->Form->select('course_id', $courses, ['class' => 'form-control', 'placeholder' => '', 'default' => 1]) ?>
                <span class="text-danger"><?= $this->Form->error('course_id') ?></span>
            </div>
        </div>
        <div class="form-group row">
            <label for="" class="col-sm-2 col-form-label text-md-right">利用停止開始日時</label>
            <div class="col-sm-4">
                <?= $this->Form->datetime('pause_start', ['class' => 'form-control', 'step' => 60]) ?>
                <span class="text-danger"><?= $this->Form->error('pause_start') ?></span>
            </div>
            <label for="" class="col-sm-2 col-form-label text-md-right">利用停止終了日時</label>
            <div class="col-sm-4">
                <?= $this->Form->datetime('pause_end', ['class' => 'form-control', 'step' => 60]) ?>
                <span class="text-danger"><?= $this->Form->error('pause_end') ?></span>
            </div>
        </div>
        <hr>
        <div class="form-group row">
            <label for="" class="col-sm-2 col-form-label text-md-right">メールアドレス *</label>
            <div class="col-sm-4">
                <?= $this->Form->email('email', ['class' => 'form-control', 'placeholder' => '']) ?>
                <span class="text-danger"><?= $this->Form->error('email') ?></span>
            </div>
            <label for="" class="col-sm-2 col-form-label text-md-right">パスワード *</label>
            <div class="col-sm-4">
                <?= $this->Form->password(($user->password) ? 'new_password' : 'password', ['class' => 'form-control', 'placeholder' => ($user->password) ? '変更したい場合は入力してください' : '']) ?>
                <span class="text-danger"><?= $this->Form->error('password') ?></span>
                <code>設定したパスワードは管理画面には表示されませんので必ず控えておいてください。</code>
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
            <label for="" class="col-sm-2 col-form-label text-md-right">性別</label>
            <div class="col-sm-4">
                <?= $this->Form->select('sextype', ['1' => '女性', '2' => '男性'], ['class' => 'form-control']) ?>
                <span class="text-danger"><?= $this->Form->error('fax') ?></span>
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
            <label for="" class="col-sm-2 col-form-label text-md-right">電話番号</label>
            <div class="col-sm-4">
                <?= $this->Form->text('tel', ['class' => 'form-control', 'placeholder' => '']) ?>
                <span class="text-danger"><?= $this->Form->error('tel') ?></span>
            </div>
            <label for="" class="col-sm-2 col-form-label text-md-right">支払い方法</label>
            <div class="col-sm-4">
                <?= $this->Form->text('payment', ['class' => 'form-control', 'placeholder' => '']) ?>
                <span class="text-danger"><?= $this->Form->error('payment') ?></span>
            </div>
        </div>
        <div class="form-group row">
            <label for="" class="col-sm-2 col-form-label text-md-right">セールス履歴</label>
            <div class="col-sm-10">
                <?= $this->Form->textarea('sales_history', ['class' => 'form-control', 'placeholder' => '']) ?>
                <span class="text-danger"><?= $this->Form->error('sales_history') ?></span>
            </div>
        </div>
        <div class="form-group row">
            <label for="" class="col-sm-2 col-form-label text-md-right">特記事項</label>
            <div class="col-sm-10">
                <?= $this->Form->textarea('note', ['class' => 'form-control', 'placeholder' => '']) ?>
                <span class="text-danger"><?= $this->Form->error('note') ?></span>
            </div>
        </div>
    </div>
    <!-- /.card-body -->
    <div class="card-footer">
        <a class="btn btn-outline-secondary" href="<?= $this->Url->build(['action' => 'index', '?' => $param]) ?>">
            <i class="fas fa-arrow-circle-left"></i> 戻る
        </a>
        <span class="float-right">
            <button type="submit" class="btn btn-primary">登録</button>
        </span>
    </div>
    <?= $this->Form->end() ?>
</div>
<!-- /.card -->
