<?php $this->assign('title', 'アーカイブ'); ?>

<?php foreach ($lessonMovies as $lessonMovie): ?>
    <div class="col-md-12">
        <div class="card">
            <div class="card-content">
                <div class="row">
                    <div class="col-xs-2">
                        <?php if ($lessonMovie->image_url) : ?>
                            <img src="<?= h($lessonMovie->image_url) ?>" style="width:100%">
                        <?php else : ?>
                            <img src="/img/noimage.png" style="width:100%">
                        <?php endif ?>
                    </div>
                    <div class="col-xs-10">
                        <h5 class="card-title" style="margin:0"><?= h($lessonMovie->name) ?></h5>
                        <p class="description"><small><?= $this->Link->linkify(h($lessonMovie->note)) ?></small></p>
                        <a href="<?= $this->Url->build(['action' => 'view', $lessonMovie->id]); ?>"
                            class="btn btn-wd btn-primary">
                            <span class="btn-label">
                                <i class="fa-solid fa-chevron-right"></i>
                            </span>
                            動画を見る
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endforeach ?>

<div class="col-md-12 text-center">
    <?= $this->element('pagination'); ?>
</div>
