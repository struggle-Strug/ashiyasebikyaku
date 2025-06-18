<?php $this->assign('title', '比較画像一覧'); ?>

<div class="col-lg-12">
    <div class="card">
        <div class="card-content">
            <div class="nav-tabs-navigation">
                <div class="nav-tabs-wrapper">
                    <ul id="tabs" class="nav nav-tabs">
                        <li><a href="<?= $this->Url->build(['action' => 'index']); ?>">登録</a></li>
                        <li><a href="<?= $this->Url->build(['action' => 'history']); ?>">過去の記録</a></li>
                        <li class="active"><a
                                href="<?= $this->Url->build(['action' => 'comparedImages']); ?>">比較画像の記録</a></li>
                    </ul>
                </div>
            </div>

            <?php foreach ($comparedImages as $comparedImage): ?>
                    <div class="card">
                        <div class="card-header">
                            <h5><?= $comparedImage->created->format('Y年m月d日'); ?>
                            </h5>
                        </div>
                        <div class="card-content">
                            <div style="padding: 12px 20px 0px;font-size: 20px; margin-left: -10px">【変化ポイント解説】</div>
                            <div style="padding: 0px 20px;font-size: 20px;"><?= $comparedImage->comment ?></div>
                            <a href="<?= $comparedImage->image ?>"
                                title="<?= h($comparedImage->comment) ?> - <?= date('Y年n月j日', strtotime($comparedImage->created)) ?>"
                                data-lightbox="<?= $comparedImage->id ?>"><img src="<?= h($comparedImage->image) ?>"
                                    width="100%" /></a>
                        </div>
                    </div>
            <?php endforeach ?>

        </div>
    </div>
</div>


<div class="col-md-12 text-center">
    <?= $this->element('pagination'); ?>
</div>