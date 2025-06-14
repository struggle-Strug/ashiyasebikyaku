<?php $this->assign('title', '毎日の報告（過去の記録）'); ?>
<?php
    $week = [
        '日', //0
        '月', //1
        '火', //2
        '水', //3
        '木', //4
        '金', //5
        '土', //6
    ];
?>

<div class="col-lg-12">
    <div class="card">
        <div class="card-content">
            <div class="nav-tabs-navigation">
                <div class="nav-tabs-wrapper">
                    <ul id="tabs" class="nav nav-tabs">
                        <li><a href="<?= $this->Url->build(['action' => 'index']); ?>">登録</a></li>
                        <li class="active"><a href="<?= $this->Url->build(['action' => 'history']); ?>">過去の記録</a></li>
                    </ul>
                </div>
            </div>

            <table class="table">
                <tbody>
                    <?php foreach ($dailyReports as $dailyReport): ?>

                    <tr>
                        <td>
                            <?= date('Y年n月j日', strtotime($dailyReport->report_date)) ?>(<?= $week[$dailyReport->report_date->format('w')] ?>)
                            <?php if ($dailyReport->status == '1') : ?>
                                <h5 class="text-danger" style="margin-top:5px;margin-bottom:5px">状態：<i class="fa-regular fa-face-smile"></i></h5>
                            <?php elseif ($dailyReport->status == '2') : ?>
                                <h5 style="margin-top:5px;margin-bottom:5px">状態：<i class="fa-regular fa-face-meh"></i></h5>
                            <?php elseif ($dailyReport->status == '3') : ?>
                                <h5 class="text-info" style="margin-top:5px;margin-bottom:5px">状態：<i class="fa-regular fa-face-frown"></i></h5>
                            <?php endif ?>
                            <?php if ($dailyReport->intestine_status == '1') : ?>
                                <h5 class="text-danger" style="margin-top:0px;margin-bottom:5px">セルフイメージを唱えましたか？：<i class="fa-regular fa-face-smile"></i></h5>
                            <?php elseif ($dailyReport->intestine_status == '2') : ?>
                                <h5 style="margin-top:0px;margin-bottom:5px">セルフイメージを唱えましたか？：<i class="fa-regular fa-face-meh"></i></h5>
                            <?php elseif ($dailyReport->intestine_status == '3') : ?>
                                <h5 class="text-info" style="margin-top:0px;margin-bottom:5px">セルフイメージを唱えましたか？：<i class="fa-regular fa-face-frown"></i></h5>
                            <?php endif ?>
                            <?php if ($dailyReport->comment) : ?>
                                <div>
                                    <h5 style="margin-top:0px;margin-bottom:5px">
                                        コメント：<?= $this->Link->linkify(h($dailyReport->comment)) ?>
                                    </h5>
                                </div>
                            <?php endif ?>
                            <?php if ($dailyReport->staff_comment) : ?>
                                <div>
                                    <h5 style="margin-top:0px;margin-bottom:5px">
                                        <?php if ($dailyReport->staff_comment != '' && $dailyReport->staff_comment != null) : ?>
                                            スタッフコメント：<?= $this->Link->linkify(h($dailyReport->staff_comment)) ?>
                                        <?php else : ?>
                                            スタッフコメント：<span class="text-muted">未記入</span>
                                        <?php endif ?>
                                    </h5>
                                </div>
                            <?php endif ?>
                            <?php if ($dailyReport->comment_update) : ?>
                                <div>
                                    <h5 style="margin-top:0px;margin-bottom:5px">
                                        コメント更新日時：<?= date('Y年n月j日 H:i:s', strtotime($dailyReport->comment_update)) ?>
                                    </h5>
                                </div>
                            <?php endif ?>
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

