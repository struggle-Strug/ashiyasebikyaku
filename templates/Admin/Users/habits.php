<?php $this->assign('title', '生活習慣・既往歴'); ?>

<!-- Default box -->
<div class="card">
    <?= $this->Form->create($user, ['class' => 'form-horizontal', 'id' => 'member-form']) ?>
    <div class="card-body">
        <div class="form-group row">
            <label for="" class="col-sm-2 col-form-label text-md-right">職業</label>
            <div class="col-sm-6">
                <?= $this->Form->text('occupation', ['class' => 'form-control', 'placeholder' => $mockUserData['occupation']]) ?>
                <span class="text-danger"><?= $this->Form->error('occupation') ?></span>
            </div>
        </div>
        <div class="form-group row">
            <label for="" class="col-sm-2 col-form-label text-md-right">食事習慣</label>
            <div class="col-sm-6">
                <?= $this->Form->textarea('eating_habits', ['class' => 'form-control', 'placeholder' => $mockUserData['eating_habits']]) ?>
                <span class="text-danger"><?= $this->Form->error('eating_habits') ?></span>
            </div>
        </div>
        <div class="form-group row">
            <label for="" class="col-sm-2 col-form-label text-md-right">睡眠</label>
            <div class="col-sm-6">
                <?= $this->Form->textarea('sleep', ['class' => 'form-control', 'placeholder' => $mockUserData['sleep']]) ?>
                <span class="text-danger"><?= $this->Form->error('course_id') ?></span>
            </div>
        </div>
        <div class="form-group row">
            <label for="" class="col-sm-2 col-form-label text-md-right">ストレス要因</label>
            <div class="col-sm-6">
                <?= $this->Form->textarea('stress_factors', ['class' => 'form-control', 'placeholder' => $mockUserData['stress_factors']]) ?>
                <span class="text-danger"><?= $this->Form->error('stress_factors') ?></span>
            </div>
        </div>
        <div class="form-group row">
            <label for="" class="col-sm-2 col-form-label text-md-right">運動歴</label>
            <div class="col-sm-6">
                <?= $this->Form->textarea('exercise_history', ['class' => 'form-control', 'placeholder' => $mockUserData['exercise_history']]) ?>
                <span class="text-danger"><?= $this->Form->error('exercise_history') ?></span>
            </div>
        </div>
        <div class="form-group row">
            <label for="" class="col-sm-2 col-form-label text-md-right">既往歴</label>
            <div class="col-sm-6">
                <?= $this->Form->textarea('medical_history', ['class' => 'form-control', 'placeholder' => $mockUserData['medical_history']]) ?>
                <span class="text-danger"><?= $this->Form->error('medical_history') ?></span>
            </div>
        </div>
        <div class="form-group row">
            <label for="" class="col-sm-2 col-form-label text-md-right">その他注意点</label>
            <div class="col-sm-6">
                <?= $this->Form->textarea('other_notes', ['class' => 'form-control', 'placeholder' => $mockUserData['other_notes']]) ?>
                <span class="text-danger"><?= $this->Form->error('other_notes') ?></span>
            </div>
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