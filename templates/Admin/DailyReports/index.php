<?php $this->assign('title', '日次報告'); ?>

<?= $this->Form->create(null, ['type' => 'get']) ?>
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-3 mt-2">
                <label>報告日</label>
                <?= $this->Form->date('report_date', ['class' => 'form-control', 'placeholder' => '', 'default' => $param['report_date']]) ?>
            </div>
            <div class="col-md-3 mt-2">
                <label>会員名</label>
                <?= $this->Form->input('user_name', [
                    'class' => 'form-control mr-4',
                    'empty' => '-',
                    'placeholder' => '会員名',
                    'default' => $param['user_name'],
                    'style' => 'border-top-right-radius: 0.2rem; border-bottom-right-radius: 0.2rem;'
                    ]) ?>
            </div>
            <div class="col-md-3 mt-2">
                <?= $this->Form->select('user_id', $users, 
                    ['class' => 'form-control', 
                    'empty' => '-', 
                    'default' => $param['user_id'],
                    'style' => 'border-top-left-radius: 0.2rem; border-bottom-left-radius: 0.2rem; margin-top: 1.75rem;'
                ]) ?>
            </div>
            <div class="col-md-12 text-right mt-2">
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-magnifying-glass"></i> 検索</button>
            </div>
        </div>
    </div>
</div>
<?= $this->Form->end() ?>

<div class="card">
    <div class="card-body">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th nowrap>報告日</th>
                    <th nowrap>会員番号</th>
                    <th nowrap>氏名</th>
                    <th nowrap>年齢</th>
                    <th nowrap>性別</th>
                    <th nowrap>状態</th>
                    <th nowrap>セルフイメージ</th>
                    <th nowrap>コメント</th>
                    <!-- <th width="150px">&nbsp;</th> -->
                </tr>
            </thead>
            <tbody>
                <?php foreach ($dailyReports as $dailyReport): ?>
                <tr>
                    <td>
                        <?= date('Y年n月j日', strtotime($dailyReport->report_date)) ?>
                    </td>
                    <td><?= h($dailyReport->user->member_number) ?></td>
                    <td>
                        <small><?= h($dailyReport->user->last_name_kana) ?> <?= h($dailyReport->user->first_name_kana) ?></small><br>
                        <?= h($dailyReport->user->last_name) ?> <?= h($dailyReport->user->first_name) ?>
                    </td>
                    <td>
                        <?php if ($dailyReport->user->birthday) : ?>
                            <?= floor((date('Ymd') - date('Ymd', strtotime($dailyReport->user->birthday))) / 10000) ?>歳
                        <?php endif ?>
                    </td>
                    <td>
                        <?php if ($dailyReport->user->sextype == '1') : ?>
                            女性
                        <?php elseif ($dailyReport->user->sextype == '2') : ?>
                            男性
                        <?php endif ?>
                    </td>
                    <td>
                        <?php if ($dailyReport->status == '1') : ?>
                            <h3><i class="fa-regular fa-face-smile text-danger"></i></h3>
                        <?php elseif ($dailyReport->status == '2') : ?>
                            <h3><i class="fa-regular fa-face-meh"></i></h3>
                        <?php elseif ($dailyReport->status == '3') : ?>
                            <h3><i class="fa-regular fa-face-frown text-info"></i></h3>
                        <?php endif ?>
                    </td>
                    <td>
                        <?php if ($dailyReport->intestine_status == '1') : ?>
                            <h3><i class="fa-regular fa-face-smile text-danger"></i></h3>
                        <?php elseif ($dailyReport->intestine_status == '2') : ?>
                            <h3><i class="fa-regular fa-face-meh"></i></h3>
                        <?php elseif ($dailyReport->intestine_status == '3') : ?>
                            <h3><i class="fa-regular fa-face-frown text-info"></i></h3>
                        <?php endif ?>
                    </td>
                    <td>
                        <small><?= $this->Link->linkify(h($dailyReport->comment)) ?></small>
                    </td>
                    <!-- <td>
                        <?php if (!$dailyReport->is_checked) : ?>
                            <button type="button" class="btn btn-sm btn-primary btn-check" id="btn-check-<?= h($dailyReport->id) ?>" data-id="<?= h($dailyReport->id) ?>">確認済みにする</button>
                            <button disabled="disabled" class="btn btn-sm btn-secondary" id="label-checked-<?= h($dailyReport->id) ?>" style="display:none">確認済み</span>
                        <?php else : ?>
                            <button disabled="disabled" class="btn btn-sm btn-secondary">確認済み</button>
                        <?php endif ?>
                    </td> -->
                </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>
    <!-- /.card-body -->
    <div class="card-footer clearfix">
        <?= $this->element('pagination'); ?>
    </div>
</div>
<!-- /.card -->

<?= $this->Form->create(null, ['id' => 'ajax-form']) ?>
<?= $this->Form->end() ?>
<script>
$(function() {
    let csrf = $('#ajax-form').find('input[name=_csrfToken]').val();

    // 確認済みにする
    $('.btn-check').click(function() {
        var id = $(this).data('id');
        $.ajax({
            type: 'POST',
            datatype: 'json',
            url: "<?= $this->Url->build(['action' => 'check']); ?>",
            data: {"id":id},
            beforeSend: function(xhr){
                xhr.setRequestHeader('X-CSRF-Token', csrf);
            },
            success: function(data, dataType) {
                console.log(data);
                $('#btn-check-' + id).hide();
                $('#label-checked-' + id).show();
            }
        });
    });
});
</script>
