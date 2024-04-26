<?php $this->assign('title', '比較画像一覧'); ?>

<div class="col-lg-12">
    <div class="card">
        <div class="card-content">
            <div class="nav-tabs-navigation">
                <div class="nav-tabs-wrapper">
                    <ul id="tabs" class="nav nav-tabs">
                        <li><a href="<?= $this->Url->build(['action' => 'index']); ?>">登録</a></li>
                        <li><a href="<?= $this->Url->build(['action' => 'history']); ?>">過去の記録</a></li>
                        <li class="active"><a href="<?= $this->Url->build(['action' => 'history']); ?>">過去の記録</a></li>
                    </ul>
                </div>
            </div>

            <table class="table">
                <tbody>
                    <?php foreach ($comparedImages as $comparedImage): ?>
                        <tr>
                            <td style="width: 200px">
                                <?= $comparedImage->created->format('Y年m月d日'); ?>
                            </td>
                            <td style="width: 300px">
                                <img src="<?= $comparedImage->image ?>" width="300px" />
                            </td>
                            <td>
                                <?= $comparedImage->comment ?>
                            </td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>

        </div>
    </div>
</div>


<div class="col-md-12 text-center">
    <?= $this->element('pagination'); ?>
</div>