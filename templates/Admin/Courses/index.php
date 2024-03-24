<?php $this->assign('title', '講座管理'); ?>

<!-- Default box -->
<div class="card">
    <div class="card-header">
        <a href="<?= $this->Url->build(['action' => 'add']); ?>" class="btn btn-outline-primary">新規講座登録</a>
    </div>
    <div class="card-body">
        <div class="table-responsive-md">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th style="width:100px">並び替え</th>
                        <th nowrap>講座タイプ</th>
                        <th nowrap>講座名</th>
                        <th nowrap>概要説明</th>
                        <th nowrap>動画</th>
                        <th style="width:100px"></th>
                    </tr>
                </thead>
                <tbody id="sortable">
                    <?php foreach ($courses as $course): ?>
                        <tr class="detail-list" data-id="<?= $course->id ?>" id="<?= $course->id ?>">
                            <td>
                                <span><img src="/img/drag.png" style="width:25px"></span>
                            </td>
                            <td><?= $courseType[$course->type] ?></td>
                            <td><?= h($course->name) ?></td>
                            <td><small><?= $this->Link->linkify(h($course->note)) ?></small></td>
                            <td nowrap>
                                <a href="<?= $this->Url->build(['controller' => 'CourseMovies', 'action' => 'index', $course->id]); ?>" class="btn btn-outline-primary btn-xs">動画管理</a>
                            </td>
                            <td nowrap>
                                <a href="<?= $this->Url->build(['action' => 'edit', $course->id]); ?>" class="btn btn-outline-primary btn-sm">編集</a>
                                <?= $this->Form->postLink(__('削除'), ['action' => 'delete', $course->id], ['class' => 'btn btn-outline-secondary btn-sm', 'confirm' => __('削除してよろしいですか？', $course->id)]) ?>
                            </td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>
    </div>
    <!-- /.card-body -->
</div>
<!-- /.card -->

<blockquote class="quote-secondary">
    <small>並び替えアイコンをドラッグ・アンド・ドロップすることで、並び順を変更できます</small>
</blockquote>

<?= $this->Form->create(null, ['id' => 'ajax-form']) ?>
<?= $this->Form->end() ?>
<script>
$(function() {
    let csrf = $('#ajax-form').find('input[name=_csrfToken]').val();

    // リストを並べ替え
    $('#sortable').sortable({
        handle: 'span',
        // updateで並べ替えるたびに更新
        update: function() {
            // toArrayで現在の順番を取得
            let list = $('#sortable').sortable("toArray");

            // データ登録
            $.ajax({
                type: 'POST',
                datatype: 'json',
                url: "<?= $this->Url->build(['action' => 'changeSort']); ?>",
                data: {"list":list},
                beforeSend: function(xhr){
                    xhr.setRequestHeader('X-CSRF-Token', csrf);
                },
                success: function(data, dataType) {
                    console.log(data);
                }
            });
        }
    });
});
</script>
