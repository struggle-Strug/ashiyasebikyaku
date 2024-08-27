<?php $this->assign('title', '動画アップロード'); ?>
<!-- Default box -->
<div class="card">
    <div class="card-header">
        <a href="<?= $this->Url->build(['action' => 'add']); ?>" class="btn btn-outline-primary">新規動画追加</a>
    </div>
    <div class="card-body">
        <div class="table-responsive-md">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th nowrap>登録日時</th>
                        <th nowrap>動画名</th>
                        <th style="width:250px">動画</th>
                        <th style="width:100px"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($uploadMovies as $index => $uploadMovie): ?>
                        <tr style="height:160px">
                            <td><?= h($uploadMovie->created) ?></td>
                            <td><?= h($uploadMovie->name) ?></td>
                            <td>
                                <video src="<?= h($uploadMovie->movie_url) ?>" class="videoUrl" style="width:160px;height:80px" controls>
                                    お使いのブラウザは動画の再生をサポートしていません。
                                </video>
                            <td nowrap>
                                <a class="btn btn-outline-primary btn-sm copyButton" href="#">URLをコピー</a>
                                <?= $this->Form->postLink(__('削除'), ['action' => 'delete', $uploadMovie->id], ['class' => 'btn btn-outline-secondary btn-sm', 'confirm' => __('本当に削除してよろしいですか？', $uploadMovie->id)]) ?>
                            </td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="card-footer clearfix">
        <?= $this->element('pagination'); ?>
    </div>
</div>

<script>
    document.querySelectorAll('.copyButton').forEach(function(button, index) {
        button.addEventListener('click', function(event) {
            event.preventDefault();
            var videoUrl = document.getElementsByClassName('videoUrl')[index].src;
            navigator.clipboard.writeText(videoUrl).then(function() {
                alert('URLがクリップボードにコピーされました。');
            }, function(err) {
                alert('URLのコピーに失敗しました: ' + err);
            });
        });
    });
</script>
