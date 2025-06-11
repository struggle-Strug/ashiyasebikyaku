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
                            <div class="msg">
                                <p>
                                    <?= $this->Link->linkify(h($message->message)) ?>
                                </p>
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
                            <div class="avatar">
                                <img src="/img/face2.png">
                            </div>
                            <div class="msg msg-staff">
                                <p>
                                    <?= $this->Link->linkify(h($message->message)) ?>
                                </p>
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
                                    <?= $this->Link->linkify(h($message->message)) ?>
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
            <?= $this->Form->create(null, ['url' => ['action' => 'send']]) ?>
            <div class="send-message">
                <textarea class="form-control textarea" type="text" name="message" placeholder="" required="required"></textarea>
                <div class="send-button" style="margin-top:10px">
                    <button type="submit" class="btn btn-info btn-fill btn-enter">送信</button>
                </div>
            </div>
            <?= $this->Form->end() ?>
        </div>
    </div>
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

        // Disable the send button after first click
        $('form').on('submit', function () {
            $('button[type="submit"]').prop('disabled', true).text('送信中...');
        });
    });
</script>

