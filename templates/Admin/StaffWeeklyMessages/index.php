<?php $this->assign('title', '今週のワーク履歴'); ?>
<?= $this->Form->create(null, ['type' => 'get']) ?>
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-3 mt-2">
                <label>会員名</label>
                <?= $this->Form->text('name', ['class' => 'form-control', 'placeholder' => '', 'default' => $param['name']]) ?>
            </div>
            <div class="col-md-3 mt-2">
                <label>スタッフ名</label>
                <?= $this->Form->text('staff_name', ['class' => 'form-control', 'placeholder' => '', 'default' => $param['staff_name']]) ?>
            </div>
            <div class="col-md-12 mt-2">
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-magnifying-glass"></i> 検索</button>
            </div>
        </div>
    </div>
</div>

<style>
.scrollbar::-webkit-scrollbar {
  width: 10px;
  height: 10px;
}
/* スクロールバーの形を設定する */
.scrollbar-thumb-rounded::-webkit-scrollbar-thumb {
  border-radius: 0.25rem;
}
/* スクロールバーの色を設定する */
.scrollbar-thumb-black::-webkit-scrollbar-thumb {
  --bg-opacity: 1;
  background-color: rgba(0, 0, 0, 0.5);
}
</style>

<div class="card">
    <div class="card-body">
        <div class="table-responsive-md  scrollbar scrollbar-thumb-rounded scrollbar-thumb-black">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                    <tr>
                        <th style="width:150px">氏名</th>
                        <th nowrap>スタッフ名</th>
                        <th nowrap>今週のワーク</th>
                        <th style="width:150px">更新日時</th>
                        <th style="width:100px"></th>
                    </tr>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($staffWeeklyMessages as $message): ?>
                    <tr>
        <td><?= h($message->name) ?></td>
        <td><?= h($message->staff_name) ?></td>
        <td><?= $this->Link->linkify($message->staff_week_message) ?></td>
        <td><?= h($message->created) ?></td>
        <td nowrap>
                            <?= $this->Form->postLink(__('削除'), ['action' => 'delete', $message->id], ['class' => 'btn btn-outline-secondary btn-sm', 'confirm' => __('削除してよろしいですか？', $message->id)]) ?>
                        </td>
    </tr>
    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer clearfix">
        <?= $this->element('pagination'); ?>
    </div>
</div>
<?= $this->Form->end() ?>
<!-- /.card -->
<script>
    var swiper = new Swiper('.swiper', {
        loop: true,
        spaceBetween: 10,
        centeredSlides: true,
        pagination: '.swiper-pagination',
        navigation: {
            nextEl: ".swiper-button-next",
            prevEl: ".swiper-button-prev",
        },
        // スライドの表示枚数：600px未満の場合
        slidesPerView: 1,
        breakpoints: {
            // スライドの表示枚数：600px以上の場合
            600: {
                slidesPerView: 2,
            },
        },
    });
</script>

<?= $this->Form->create(null, ['id' => 'ajax-form']) ?>
<?= $this->Form->end() ?>
<script>
    $(function () {
        let csrf = $('#ajax-form').find('input[name=_csrfToken]').val();

        // 確認済みにする
        $('.btn-check').click(function () {
            var id = $(this).data('id');
            $.ajax({
                type: 'POST',
                datatype: 'json',
                url: "<?= $this->Url->build(['action' => 'check']); ?>",
                data: {
                    "id": id
                },
                beforeSend: function (xhr) {
                    xhr.setRequestHeader('X-CSRF-Token', csrf);
                },
                success: function (data, dataType) {
                    $('#btn-check-' + id).hide();
                    $('#label-checked-' + id).show();
                }
            });
        });
    });
</script>