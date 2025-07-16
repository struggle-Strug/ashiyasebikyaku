<?php $this->assign('title', 'カルテ'); ?>
<div class="card">
    <div class="card-body">
        <div style="margin: 15px; font-weight: bold; font-size:24px;">
        <a href="<?= $this->Url->build(['action' => 'customerMain', $user->id, '?' => $param]); ?>">基本情報</a>
        </div>
        <div style="margin: 15px; font-weight: bold; font-size:24px;">
        <a href="<?= $this->Url->build(['action' => 'habits', $user->id, '?' => $param]); ?>">生活習慣・既住歴</a>
        </div>
        <div style="margin: 15px; font-weight: bold; font-size:24px;">
        <a href="<?= $this->Url->build(['action' => 'purpose', $user->id, '?' => $param]); ?>">目標設定シート</a>
        </div>
        <div style="margin: 15px; font-weight: bold; font-size:24px;">
        <a href="<?= $this->Url->build(['action' => 'bodyData', $user->id, '?' => $param]); ?>">身体データ（定性）</a>
        </div>
        <div style="margin: 15px; font-weight: bold; font-size:24px;">
        <a href="<?= $this->Url->build(['action' => 'mention', $user->id, '?' => $param]); ?>">心理・モチベーション</a>
        </div>
        <div style="margin: 15px; font-weight: bold; font-size:24px;">
        <a href="<?= $this->Url->build(['action' => 'consultHistory', $user->id, '?' => $param]); ?>">レッスン・コンサル時の指導履歴</a>
        </div>
        <div style="margin: 15px; font-weight: bold; font-size:24px;">
        <a href="<?= $this->Url->build(['action' => 'selfUnderstand', $user->id, '?' => $param]); ?>">課題の自己認識結果</a>
        </div>
        <div style="margin: 15px; font-weight: bold; font-size:24px;">
        <a href="<?= $this->Url->build(['action' => 'difference', $user->id, '?' => $param]); ?>">標準モデルとの差異結果</a>
        </div>
    </div>
</div>
