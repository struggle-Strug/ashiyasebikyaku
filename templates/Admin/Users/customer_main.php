<?php $this->assign('title', '基本情報編集'); ?>

<!-- Default box -->
<div class="card">
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
<!-- /.card -->

<script>
$(document).ready(function() {
    // スクロール位置の管理 - インデックスページと同じキー
    const SCROLL_STORAGE_KEY = 'memberIndexScrollPosition';

    // スクロール位置の保存機能（インデックスページの機能に対応）
    function saveScrollPosition() {
        // 現在のスクロール位置を取得する
        const windowScrollTop = $(window).scrollTop();

        // インデックス・ページから来た場合、テーブル・コンテナのスクロール位置を取得する。
        const scrollData = {
            tableTop: 0, 
            tableLeft: 0, 
            windowTop: windowScrollTop,
            timestamp: Date.now()
        };

        // 既存のテーブルスクロール位置がある場合は、それを保持する
        const existingScrollData = sessionStorage.getItem(SCROLL_STORAGE_KEY);
        if (existingScrollData) {
            try {
                const existingData = JSON.parse(existingScrollData);
                scrollData.tableTop = existingData.tableTop || 0;
                scrollData.tableLeft = existingData.tableLeft || 0;
            } catch (e) {
            }
        }

        sessionStorage.setItem(SCROLL_STORAGE_KEY, JSON.stringify(scrollData));
    }

    // 戻るボタンをクリックしたときのスクロール位置を保存
    $('#back-btn').on('click', function() {
        saveScrollPosition();
    });

    // フォーム送信時のスクロール位置の保存（登録ボタン）
    $('#member-form').on('submit', function() {
        saveScrollPosition();
    });

    // 代替案 登録ボタンがクリックされたときのスクロール位置を保存する
    $('#register-btn').on('click', function() {
        saveScrollPosition();
    });

    // ページがアンロードされる前にスクロール位置を保存する
    $(window).on('beforeunload', function() {
        saveScrollPosition();
    });
});
</script>