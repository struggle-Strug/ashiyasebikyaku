<?php $this->assign('title', '生活習慣・既往歴'); ?>

<!-- Default box -->
<div class="card">
    <?= $this->Form->create($user, ['class' => 'form-horizontal', 'id' => 'member-form']) ?>
    <div class="card-body">
        <div class="form-group row">
            <label for="" class="col-sm-2 col-form-label text-md-right">メールアドレス</label>
            <div class="col-sm-6">
                <?= $this->Form->text('email', ['class' => 'form-control', 'placeholder' => $mockUserData['email']]) ?>
                <span class="text-danger"><?= $this->Form->error('email') ?></span>
            </div>
        </div>
        <div class="form-group row">
            <label for="" class="col-sm-2 col-form-label text-md-right">名前</label>
            <div class="col-sm-6">
                <?= $this->Form->text('name', ['class' => 'form-control', 'placeholder' => $mockUserData['name']]) ?>
                <span class="text-danger"><?= $this->Form->error('name') ?></span>
            </div>
        </div>
        <div class="form-group row">
            <label for="" class="col-sm-2 col-form-label text-md-right">現在の悩み(容姿面)</label>
            <div class="col-sm-6">
                <?= $this->Form->textarea('current_concerns_appearance', ['class' => 'form-control', 'placeholder' => $mockUserData['current_concerns_appearance']]) ?>
                <span class="text-danger"><?= $this->Form->error('current_concerns_appearance') ?></span>
            </div>
        </div>
        <div class="form-group row">
            <label for="" class="col-sm-2 col-form-label text-md-right">現在の悩み(機能面)</label>
            <div class="col-sm-6">
                <?= $this->Form->textarea('current_concerns_function', ['class' => 'form-control', 'placeholder' => $mockUserData['current_concerns_function']]) ?>
                <span class="text-danger"><?= $this->Form->error('current_concerns_function') ?></span>
            </div>
        </div>
        <div class="form-group row">
            <label for="" class="col-sm-2 col-form-label text-md-right">期間中の目標(容姿面)</label>
            <div class="col-sm-6">
                <?= $this->Form->textarea('goal_appearance', ['class' => 'form-control', 'placeholder' => $mockUserData['goal_appearance']]) ?>
                <span class="text-danger"><?= $this->Form->error('goal_appearance') ?></span>
            </div>
        </div>
        <div class="form-group row">
            <label for="" class="col-sm-2 col-form-label text-md-right">期間中の目標(機能面)</label>
            <div class="col-sm-6">
                <?= $this->Form->textarea('goal_function', ['class' => 'form-control', 'placeholder' => $mockUserData['goal_function']]) ?>
                <span class="text-danger"><?= $this->Form->error('goal_function') ?></span>
            </div>
        </div>
        <div class="form-group row">
            <label for="" class="col-sm-2 col-form-label text-md-right">未来のセルフイメージ</label>
            <div class="col-sm-6">
                <?= $this->Form->textarea('future_self_image', ['class' => 'form-control', 'placeholder' => $mockUserData['future_self_image']]) ?>
                <span class="text-danger"><?= $this->Form->error('future_self_image') ?></span>
            </div>
        </div>
        <div class="form-group row">
            <label for="" class="col-sm-2 col-form-label text-md-right">次の2週間でやるワーク</label>
            <div class="col-sm-6">
                <?= $this->Form->textarea('next_two_weeks_work', ['class' => 'form-control', 'placeholder' => $mockUserData['next_two_weeks_work']]) ?>
                <span class="text-danger"><?= $this->Form->error('next_two_weeks_work') ?></span>
            </div>
        </div>
        <div class="form-group row">
            <label for="" class="col-sm-2 col-form-label text-md-right">個別コンサルで相談したいこと</label>
            <div class="col-sm-6">
                <?= $this->Form->textarea('consulting_topics', ['class' => 'form-control', 'placeholder' => $mockUserData['consulting_topics']]) ?>
                <span class="text-danger"><?= $this->Form->error('consulting_topics') ?></span>
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