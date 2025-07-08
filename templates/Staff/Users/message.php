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
                            <?php if ($message->file_path): ?>
                                <?php if (strpos($message->file_type, 'image/') === 0): ?>
                                    <div class="message-attachment">
                                        <img src="/<?= h($message->file_path) ?>" alt="<?= h($message->file_name) ?>" style="max-width: 300px; max-height: 300px; border-radius: 8px;">
                                        <div class="attachment-info">
                                            <small><?= h($message->file_name) ?></small>
                                        </div>
                                    </div>
                                <?php elseif (strpos($message->file_type, 'video/') === 0): ?>
                                    <div class="message-attachment">
                                        <video controls style="max-width: 300px; max-height: 300px; border-radius: 8px;">
                                            <source src="/<?= h($message->file_path) ?>">
                                            お使いのブラウザは動画再生に対応していません。
                                        </video>
                                        <div class="attachment-info">
                                            <small><?= h($message->file_name) ?></small>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            <?php endif; ?>
                            <?php if ($message->message): ?>
                                <div class="message-text">
                                    <?= $this->Link->linkify(h($message->message)) ?>
                                </div>
                            <?php endif; ?>
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
                            <?php if ($message->file_path): ?>
                                <?php if (strpos($message->file_type, 'image/') === 0): ?>
                                    <div class="message-attachment">
                                        <img src="/<?= h($message->file_path) ?>" alt="<?= h($message->file_name) ?>" style="max-width: 300px; max-height: 300px; border-radius: 8px;">
                                        <div class="attachment-info">
                                            <small><?= h($message->file_name) ?></small>
                                        </div>
                                    </div>
                                <?php elseif (strpos($message->file_type, 'video/') === 0): ?>
                                    <div class="message-attachment">
                                        <video controls style="max-width: 300px; max-height: 300px; border-radius: 8px;">
                                            <source src="/<?= h($message->file_path) ?>">
                                            お使いのブラウザは動画再生に対応していません。
                                        </video>
                                        <div class="attachment-info">
                                            <small><?= h($message->file_name) ?></small>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            <?php endif; ?>
                            <?php if ($message->message): ?>
                                <div class="message-text">
                                    <?= $this->Link->linkify(h($message->message)) ?>
                                </div>
                            <?php endif; ?>
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
                            <?php if ($message->file_path): ?>
                                <?php if (strpos($message->file_type, 'image/') === 0): ?>
                                    <div class="message-attachment">
                                        <img src="/<?= h($message->file_path) ?>" alt="<?= h($message->file_name) ?>" style="max-width: 300px; max-height: 300px; border-radius: 8px;">
                                        <div class="attachment-info">
                                            <small><?= h($message->file_name) ?></small>
                                        </div>
                                    </div>
                                <?php elseif (strpos($message->file_type, 'video/') === 0): ?>
                                    <div class="message-attachment">
                                        <video controls style="max-width: 300px; max-height: 300px; border-radius: 8px;">
                                            <source src="/<?= h($message->file_path) ?>">
                                            お使いのブラウザは動画再生に対応していません。
                                        </video>
                                        <div class="attachment-info">
                                            <small><?= h($message->file_name) ?></small>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            <?php endif; ?>
                            <?php if ($message->message): ?>
                                <div class="message-text">
                                    <?= $this->Link->linkify(h($message->message)) ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif ?>
            <?php endforeach ?>
            <a id="msg-footer"></a>
        </div>
    </div>
    <div class="card-footer">
        <?= $this->Form->create(null, ['url' => ['action' => 'messageSend'], 'enctype' => 'multipart/form-data']) ?>
            <?= $this->Form->hidden('user_id', ['value' => $user->id]) ?>
            <div class="input-group">
                <textarea type="text" name="message" placeholder="メッセージを入力してください..." class="form-control"></textarea>
                <span class="input-group-append">
                    <button type="button" class="btn btn-outline-secondary" onclick="document.getElementById('attachment').click();">
                        <i class="fas fa-paperclip"></i>
                    </button>
                    <button type="submit" class="btn btn-primary">送信</button>
                </span>
            </div>
            <div class="mt-2">
                <input type="file" id="attachment" name="attachment" accept="image/*,video/*" style="display: none;" onchange="updateFileName(this)">
                <div id="file-info" class="text-success large" style="display: none;"></div>
            </div>
        <?= $this->Form->end() ?>
    </div>
</div>

<div class="card-footer">
    <a class="btn btn-outline-secondary" href="<?= $this->Url->build(['action' => 'index', '?' => $param]); ?>"><i class="fas fa-arrow-circle-left"></i> 戻る</a>
</div>

<style>
.message-attachment {
    margin-bottom: 10px;
}

.attachment-info {
    margin-top: 5px;
    color: #666;
}

.message-text {
    margin-top: 10px;
}
</style>

<script>
    $(function() {
        // Scroll to the bottom of the message board
        $("#msg-board").scrollTop($("#msg-footer").position().top);

        // Disable Enter key on inputs
        $("input").on("keydown", function(e) {
            if ((e.which && e.which === 13) || (e.keyCode && e.keyCode === 13)) {
                return false;
            } else {
                return true;
            }
        });

        // Disable the send button after first click
        $('form').on('submit', function () {
            $('button[type="submit"]').prop('disabled', true).text('送信中...');
        });
    });

    function updateFileName(input) {
        const fileInfo = document.getElementById('file-info');
        if (input.files && input.files[0]) {
            const file = input.files[0];
            const fileSize = (file.size / (1024 * 1024)).toFixed(2);
            fileInfo.innerHTML = `選択されたファイル: ${file.name} (${fileSize} MB)`;
            fileInfo.style.display = 'block';
        } else {
            fileInfo.style.display = 'none';
        }
    }
</script>


