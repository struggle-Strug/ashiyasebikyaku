<?php $this->assign('title', 'コミュニケーションツール'); ?>

<div class="col-lg-12">
    <div class="card card-chat">
        <div class="card-header">
            <h4 class="card-title">担当講師：<?= h($staff->name) ?></h4>
            <p class="category">※返信にはお時間をいただくことがあります。あらかじめご了承ください。</p>
        </div>
        <div class="card-content">
            <ol class="chat" id="msg-board" style="max-height:50%;height:50vh;overflow:scroll;">
                <?php foreach ($messages as $message): ?>
                    <?php if ($message->sender == '1') : // 利用者 ?>
                        <li class="self">
                            <div class="msg" style="position: relative;">
                                <!-- 削除ボタンフォーム -->
                                <div style="position: absolute; bottom: 5px; left: 10px;">
                                    <?= $this->Form->create(null, [
                                        'url' => ['action' => 'delete', $message->id],
                                        'style' => 'display:inline-block;',
                                        'onsubmit' => 'return confirm("このメッセージを削除しますか？");'
                                    ]) ?>
                                        <?= $this->Form->button('削除', [
                                            'class' => 'btn btn-sm btn-outline-danger small delete-button',
                                        ]) ?>
                                    <?= $this->Form->end() ?>
                                </div>
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
                                    <p>
                                        <?= $this->Link->linkify(h($message->message)) ?>
                                    </p>
                                <?php endif; ?>
                                <div class="card-footer">
                                    <i class="ti-time"></i>
                                    <h6><?= h($message->created) ?></h6>
                                </div>
                            </div>
                            <div class="avatar">
                                <img src="/img/face.png">
                            </div>
                        </li>
                    <?php elseif ($message->sender == '2') : // 講師 ?>
                        <li class="other">
                            <div style="display: inline-block;">
                                <div class="avatar" style="margin: auto;">
                                    <img src="/img/face2.png">
                                </div>
                                <small><?= h($message->staff->name) ?></small>
                            </div>
                            <div class="msg msg-staff">
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
                                    <p>
                                        <?= $this->Link->linkify(h($message->message)) ?>
                                    </p>
                                <?php endif; ?>
                                <div class="card-footer">
                                    <i class="ti-time"></i>
                                    <h6><?= h($message->created) ?></h6>
                                </div>
                            </div>
                        </li>
                    <?php elseif ($message->sender == '3') : // 管理者 ?>
                        <li class="other">
                            <div class="avatar">
                                <img src="/img/logo.png">
                            </div>
                            <div class="msg msg-admin">
                                <p>
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
                                        <?= $this->Link->linkify(h($message->message)) ?>
                                    <?php endif; ?>
                                </p>
                                <div class="card-footer">
                                    <i class="ti-time"></i>
                                    <h6><?= h($message->created) ?></h6>
                                </div>
                            </div>
                        </li>
                    <?php endif ?>
                <?php endforeach ?>
                <a id="msg-footer"></a>
            </ol>
            <hr>
            <?= $this->Form->create(null, ['url' => ['action' => 'send'], 'enctype' => 'multipart/form-data']) ?>
            <div class="send-message">
                <textarea class="form-control textarea" type="text" name="message" placeholder=""></textarea>
                <div class="send-button" style="margin-top:10px">
                    <button type="button" class="btn btn-outline-secondary" onclick="document.getElementById('attachment').click();" style="margin-right:10px">
                        <i class="fas fa-paperclip"></i> ファイル添付
                    </button>
                    <button type="submit" class="btn btn-info btn-fill btn-enter">送信</button>
                </div>
                <div class="mt-2">
                    <input type="file" id="attachment" name="attachment" accept="image/*,video/*" style="display: none;" onchange="updateFileName(this)">
                    <div id="file-info" class="text-success large" style="display: none;"></div>
                </div>
            </div>
            <?= $this->Form->end() ?>
        </div>
    </div>
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

.delete-button {
    font-size: 1rem;
    padding: 4px 6px;
    line-height: 1.2;
}

.self .msg {
    min-width: 180px;
}

</style>

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

         // メイン送信ボタンの送信中テキスト
         $('form').on('submit', function () {
            const $submitBtn = $(this).find('button[type="submit"]');
            if ($submitBtn.hasClass('delete-button')) {
                $submitBtn.prop('disabled', true).text('削除中...');
            } else {
                $submitBtn.prop('disabled', true).text('送信中...');
            }
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

