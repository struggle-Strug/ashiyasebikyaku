<?php $this->assign('title', 'グループレッスン動画'); ?>

<!-- Default box -->
<div class="card">
    <div class="card-header">
        <a href="<?= $this->Url->build(['action' => 'add']); ?>" class="btn btn-outline-primary">新規グループレッスン動画登録</a>
    </div>
    <div class="card-body">
        <div class="table-responsive-md">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                    <tr>
                        <th nowrap>登録日時</th>
                        <th nowrap>動画名</th>
                        <th nowrap>サムネイル画像</th>
                        <th style="width:250px">動画</th>
                        <th nowrap>概要説明</th>
                        <th nowrap>新旧</th>
                        <th style="width:100px"></th>
                    </tr>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($lessonMovies as $lessonMovie): ?>
                        <tr style="height:160px">
                            <td><?= h($lessonMovie->created) ?></td>
                            <td><?= h($lessonMovie->name) ?></td>
                            <td>
                                <?php if ($lessonMovie->image_url): ?>
                                    <img src="<?= h($lessonMovie->image_url) ?>" style="width:100px">
                                <?php else: ?>
                                    <img src="/img/noimage.png" style="width:100px">
                                <?php endif ?>
                            </td>
                            <td>
                                <?php
                                $videoUrl = h($lessonMovie->movie_url);

                                if (strpos($videoUrl, 'vimeo') !== false): ?>
                                    <div style="padding:56.25% 0 0 0;position:relative;">
                                        <iframe src="<?= $videoUrl ?>"
                                            style="position:absolute;top:0;left:0;width:100%;height:100%;" frameborder="0"
                                            allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>
                                    </div>
                                    <script src="https://player.vimeo.com/api/player.js"></script>
                                <?php else: ?>
                                    <video controls controlsList="nodownload" style="width:100%; height:auto;">
                                        <source src="<?= $videoUrl ?>" type="video/mp4">
                                    </video>
                                <?php endif; ?>
                            </td>
                            <td><small><?= $this->Link->linkify(h($lessonMovie->note)) ?></small></td>
                            <td>
                                <?php if ($lessonMovie->type == "0"): ?>
                                    <span class="right badge badge-primary">旧動画</span>
                                <?php else: ?>
                                    <span class="right badge badge-success">新動画</span>
                                <?php endif ?>
                            </td>
                            <td nowrap>
                                <a href="<?= $this->Url->build(['action' => 'edit', $lessonMovie->id]); ?>"
                                    class="btn btn-outline-primary btn-sm">編集</a>
                                <?= $this->Form->postLink(__('削除'), ['action' => 'delete', $lessonMovie->id], ['class' => 'btn btn-outline-secondary btn-sm', 'confirm' => __('退会は編集から「利用終了日時」を設定してください。本当に削除してよろしいですか？', $lessonMovie->id)]) ?>
                            </td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>
    </div>
    <!-- /.card-body -->
    <div class="card-footer clearfix">
        <?= $this->element('pagination'); ?>
    </div>
</div>
<!-- /.card -->

<script src="https://player.vimeo.com/api/player.js"></script>