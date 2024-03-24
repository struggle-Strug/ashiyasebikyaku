<?php $this->assign('title', 'コミュニケーションツール'); ?>

<div class="card card-primary direct-chat direct-chat-info">
    <div class="card-body">
        <div class="direct-chat-messages" id="msg-board" style="max-height:70%;height:70vh;overflow:scroll;">
            <?php foreach ($messages as $message): ?>
                <?php if ($message->sender == '1') : // 利用者 ?>
                    <div class="direct-chat-msg">
                        <div class="direct-chat-infos clearfix">
                            <span class="direct-chat-name float-left"><?= h($message->user->last_name) ?> <?= h($message->user->first_name) ?></span>
                            <span class="direct-chat-timestamp float-right"><?= h($message->created) ?></span>
                        </div>
                        <img class="direct-chat-img" src="/img/face.png">
                        <div class="direct-chat-text">
                            <?= $this->Link->linkify(h($message->message)) ?>
                        </div>
                    </div>
                <?php elseif ($message->sender == '2') : // 講師 ?>
                    <div class="direct-chat-msg right">
                        <div class="direct-chat-infos clearfix">
                            <span class="direct-chat-name float-right"><?= h($message->staff->name) ?></span>
                            <span class="direct-chat-timestamp float-left"><?= h($message->created) ?></span>
                        </div>
                        <img class="direct-chat-img" src="/img/face2.png">
                        <div class="direct-chat-text msg-staff">
                            <?= $this->Link->linkify(h($message->message)) ?>
                        </div>
                    </div>
                <?php elseif ($message->sender == '3') : // 管理者 ?>
                    <div class="direct-chat-msg right">
                        <div class="direct-chat-infos clearfix">
                            <span class="direct-chat-name float-right">管理者</span>
                            <span class="direct-chat-timestamp float-left"><?= h($message->created) ?></span>
                        </div>
                        <img class="direct-chat-img" src="/img/logo.png">
                        <div class="direct-chat-text msg-admin">
                            【運営より】<br>
                            <?= $this->Link->linkify(h($message->message)) ?>
                        </div>
                    </div>
                <?php endif ?>
            <?php endforeach ?>
            <a id="msg-footer"></a>
        </div>
    </div>
    <div class="card-footer">
        <?= $this->Form->create(null, ['url' => ['action' => 'messageSend']]) ?>
            <?= $this->Form->hidden('user_id', ['value' => $user->id]) ?>
            <?= $this->Form->hidden('staff_id', ['value' => $user->staff_id]) ?>
            <div class="input-group">
                <textarea type="text" name="message" placeholder="" class="form-control" required="required"></textarea>
                <span class="input-group-append">
                    <button type="submit" class="btn btn-primary">送信</button>
                </span>
            </div>
        <?= $this->Form->end() ?>
    </div>
</div>

<div class="card-footer">
    <a class="btn btn-outline-secondary" href="<?= $this->Url->build(['action' => 'index']); ?>"><i class="fas fa-arrow-circle-left"></i> 戻る</a>
</div>

<script>
    $(function() {
        // 最下部までスクロール
        $("#msg-board").scrollTop($("#msg-footer").position().top);

        // エンターキー無効
        $("input").on("keydown", function(e) {
            if ((e.which && e.which === 13) || (e.keyCode && e.keyCode === 13)) {
                return false;
            } else {
                return true;
            }
        });
    });
</script>

