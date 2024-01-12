<?php $this->assign('title', '事務局からのお知らせ'); ?>

<!-- <div class="col-lg-12" style="height: 200px;overflow-y: scroll;"> -->
<div class="col-lg-12">
    <?php foreach ($informations as $information): ?>
        <div class="alert alert-info alert-with-icon" data-notify="container">
            <span data-notify="icon" class="ti-alert"></span>
            <span data-notify="message">[<?= h($information->start_datetime) ?>]<br><?= nl2br($information->content) ?></span>
        </div>
    <?php endforeach ?>
</div>
