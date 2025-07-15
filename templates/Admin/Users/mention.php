<?php $this->assign('title', '心理・モチベーション'); ?>

<!-- Default box -->
<div class="card">
    <?= $this->Form->create($user, ['class' => 'form-horizontal', 'id' => 'mention-form']) ?>
    <div class="card-body">
        <div class="form-group row">
            <label for="" class="col-sm-2 col-form-label text-md-right">性格傾向</label>
            <div class="col-sm-6">
                <?= $this->Form->text('personality_tendency', ['class' => 'form-control', 'placeholder' => $mockUserData['personality_tendency']]) ?>
                <span class="text-danger"><?= $this->Form->error('personality_tendency') ?></span>
            </div>
        </div>
        <div class="form-group row">
            <label for="" class="col-sm-2 col-form-label text-md-right">モチベーション源泉</label>
            <div class="col-sm-6">
                <?= $this->Form->textarea('motivation_source', ['class' => 'form-control', 'placeholder' => $mockUserData['motivation_source']]) ?>
                <span class="text-danger"><?= $this->Form->error('motivation_source') ?></span>
            </div>
        </div>
        <div class="form-group row">
            <label for="" class="col-sm-2 col-form-label text-md-right">離脱リスク</label>
            <div class="col-sm-6">
                <?= $this->Form->textarea('dropout_risk', ['class' => 'form-control', 'placeholder' => $mockUserData['dropout_risk']]) ?>
                <span class="text-danger"><?= $this->Form->error('dropout_risk') ?></span>
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