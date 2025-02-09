<?php
use Cake\Core\Configure;
$this->assign('title', 'ブログ詳細');
?>

<style>
.header-container {
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.date-text {
    margin-top: 1%; 
    margin-left: 4%;
}
.btn-secondary {
    margin-right: 4%;
}
.content-wrapper {
    padding: 20px;
}

.content-area {
    margin: 30px auto 50px;  /* 上30px、左右auto（中央寄せ）、下50px */
    padding: 25px;
    border: 1px solid #dee2e6;
    border-radius: 4px;
    background-color: #fff;
    max-width: 90%;  /* 背景より小さくする */
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);  /* 軽い影をつけて浮き上がり感を出す */
}
.card-body {
    padding-bottom: 70px;  /* 下部の余白を追加 */
}
</style>

<div class="card">
    <div class="card-header">
        <div class="header-container">
            <h5 class="mb-0 date-text">
                <?= $information->start_datetime->format('Y年n月j日') ?>
            </h5>
            <?= $this->Html->link(
                '<i class="fa fa-arrow-left"></i> 戻る',
                ['action' => 'index'],
                ['class' => 'btn btn-secondary', 'escape' => false]
            ) ?>
        </div>
    </div>
    <div class="card-body">
        <div class="content-area">
            <?= $this->Link->linkify(nl2br(h($information->content))) ?>
        </div>
    </div>
</div> 