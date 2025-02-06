<?php $this->assign('title', '毎日の報告'); ?>

<div class="col-lg-12">
    <div class="card">
        <div class="card-content">
            <div class="nav-tabs-navigation">
                <div class="nav-tabs-wrapper">
                    <ul id="tabs" class="nav nav-tabs">
                        <li class="active"><a href="<?= $this->Url->build(['action' => 'index']); ?>">登録</a></li>
                        <li><a href="<?= $this->Url->build(['action' => 'history']); ?>">過去の記録</a></li>
                    </ul>
                </div>
            </div>

            <?= $this->Form->create($dailyReport, ['id' => 'form']) ?>
                <?= $this->Form->hidden('status', ['id' => 'status']) ?>
                <?= $this->Form->hidden('intestine_status', ['id' => 'intestine_status']) ?>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>報告日</label>
                            <?= $this->Form->date('report_date', ['class' => 'form-control', 'required' => True, 'max' => date('Y-m-d')]) ?>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>動画ワークは上手く進んでますか？</label>
                            <div class="text-center" style="margin-top:10px">
                                <button type="button" class="btn btn-lg btn-icon btn-danger btn-status" style="font-size:24px" data-status="1">
                                    <i class="fa-regular fa-face-smile"></i>
                                </button>
                                <button type="button" class="btn btn-lg btn-icon btn-status" style="margin-left:15px;margin-right:15px;font-size:24px" data-status="2">
                                    <i class="fa-regular fa-face-meh"></i>
                                </button>
                                <button type="button" class="btn btn-lg btn-icon btn-success btn-status" style="font-size:24px" data-status="3">
                                    <i class="fa-regular fa-face-frown"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>セルフイメージを唱えましたか？</label>
                            <div class="text-center" style="margin-top:10px">
                                <button type="button" class="btn btn-lg btn-icon btn-danger btn-intestine_status" style="font-size:24px" data-status="1">
                                    <i class="fa-regular fa-face-smile"></i>
                                </button>
                                <button type="button" class="btn btn-lg btn-icon btn-intestine_status" style="margin-left:15px;margin-right:15px;font-size:24px" data-status="2">
                                    <i class="fa-regular fa-face-meh"></i>
                                </button>
                                <button type="button" class="btn btn-lg btn-icon btn-success btn-intestine_status" style="font-size:24px" data-status="3">
                                    <i class="fa-regular fa-face-frown"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>コメント</label>
                            <textarea name="comment" class="form-control border-input" placeholder="任意"></textarea>
                        </div>
                    </div>
                </div>
                <div class="text-center" style="margin:10px">
                    <button type="submit" class="btn btn-info btn-fill btn-wd btn-enter">送信する</button>
                </div>
            <?= $this->Form->end() ?>

            <div class="clearfix" style="margin:10px"></div>
        </div>
    </div>
</div>

<script>
    $(function() {
        $('.btn-status').click(function() {
            $('.btn-status').removeClass('btn-fill');
            $(this).addClass('btn-fill');
            $('#status').val($(this).data('status'));
        });

        $('.btn-intestine_status').click(function() {
            $('.btn-intestine_status').removeClass('btn-fill');
            $(this).addClass('btn-fill');
            $('#intestine_status').val($(this).data('status'));
        });

        $('.btn-enter').click(function() {
            if (!$('#status').val() && !$('#intestine_status').val()) {
                alert('動画ワークチェックとセルフイメージを唱えましたか？はどちらか必ず選択してください。');
                return false;
            }
            if(!confirm('送信してよろしいですか？')) {
                return false;
            }
        });
    });
</script>
