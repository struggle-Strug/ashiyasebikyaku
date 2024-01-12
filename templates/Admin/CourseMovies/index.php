<?php $this->assign('title', h($course->name) . ' 動画管理'); ?>

<!-- Default box -->
<div class="card">
    <div class="card-header">
        <a href="<?= $this->Url->build(['action' => 'add', $course->id]); ?>" class="btn btn-outline-primary">新規<?= h($course->name) ?>動画登録</a>
    </div>
    <div class="card-body">
        <div class="table-responsive-md">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <tr>
                            <th style="width:100px">並び替え</th>
                            <th nowrap>動画名</th>
                            <th nowrap>サムネイル画像</th>
                            <th style="width:250px">動画</th>
                            <th style="width:250px">解説動画</th>
                            <th nowrap>概要説明</th>
                            <th style="width:100px"></th>
                        </tr>
                    </tr>
                </thead>
                <tbody id="sortable">
                    <?php foreach ($courseMovies as $courseMovie): ?>
                        <tr class="detail-list" data-id="<?= $courseMovie->id ?>" id="<?= $courseMovie->id ?>" style="height:160px">
                            <td>
                                <span><img src="/img/drag.png" style="width:25px"></span>
                            </td>
                            <td><?= h($courseMovie->name) ?></td>
                            <td>
                                <?php if ($courseMovie->image_url) : ?>
                                    <img src="<?= h($courseMovie->image_url) ?>" style="width:100px">
                                <?php else : ?>
                                    <img src="/img/noimage.png" style="width:100px">
                                <?php endif ?>
                            </td>
                            <td>
                                <div style="padding:0;position:relative;">
                                    <iframe src="<?= h($courseMovie->movie_url) ?>"
                                        style="position:absolute;top:0;left:0;width:160px;height:80px" frameborder="0"
                                        allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>
                                </div>
                            </td>
                            <td>
                                <div style="padding:0 0 0 0;position:relative;">
                                    <iframe src="<?= h($courseMovie->info_movie_url) ?>"
                                        style="position:absolute;top:0;left:0;width:160px;height:80px" frameborder="0"
                                        allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>
                                </div>
                            </td>
                            <td><small><?= nl2br(h($courseMovie->note)) ?></small></td>
                            <td nowrap>
                                <a href="<?= $this->Url->build(['action' => 'edit', $course->id, $courseMovie->id]); ?>" class="btn btn-outline-primary btn-sm">編集</a>
                                <?= $this->Form->postLink(__('削除'), ['action' => 'delete', $course->id, $courseMovie->id], ['class' => 'btn btn-outline-secondary btn-sm', 'confirm' => __('退会は編集から「利用終了日時」を設定してください。本当に削除してよろしいですか？', $courseMovie->id)]) ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <!-- /.card-body -->
    <div class="card-footer">
        <a class="btn btn-outline-secondary" href="<?= $this->Url->build(['controller' => 'Courses', 'action' => 'index']); ?>"><i class="fas fa-arrow-circle-left"></i> 戻る</a>
    </div>
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

<script src="https://player.vimeo.com/api/player.js"></script>
