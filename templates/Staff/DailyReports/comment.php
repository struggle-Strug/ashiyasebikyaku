<?php $this->assign('title', 'スタッフコメント'); ?>

<div class="col-lg-12">
    <div class="card">
        <div class="card-content p-4">
            <?= $this->Form->create($dailyReport, ['id' => 'form']) ?>
            <?= $this->Form->hidden('staff_id', ['value' => $staff]) ?>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>コメント</label>
                            <textarea name="staff_comment" class="form-control border-input" placeholder="任意"><?= h($dailyReport->staff_comment) ?></textarea>
                        </div>
                    </div>
                </div>
                <div class="text-center" style="margin:10px">
                    <button type="submit" class="btn btn-info btn-fill btn-wd btn-enter">送信する</button>
                </div>
            <?= $this->Form->hidden('comment_update', ['value' => date('Y-m-d H:i:s')]) ?>
            <?= $this->Form->end() ?>

            <div class="clearfix" style="margin:10px"></div>
        </div>
    </div>
</div>

<script>
    $(function() {
        $('.btn-enter').click(function() {
            if(!confirm('送信してよろしいですか？')) {
                return false;
            }
        });
    });
</script>
