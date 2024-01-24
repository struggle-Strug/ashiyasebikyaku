<?php $this->assign('title', 'マイページトップ'); ?>

<?php if ($user->messages) : ?>
    <div class="col-lg-12">
        <div class="alert alert-primary alert-with-icon" data-notify="container">
            <span data-notify="icon" class="ti-email"></span>
            <span data-notify="message"><a href="<?= $this->Url->build(['controller' => 'Messages', 'action' => 'index']); ?>"><b>未読メッセージがあります。</b></a></span>
        </div>
    </div>
<?php endif ?>

<div class="col-lg-12" style="height: 250px;overflow-y: scroll;">
    <h4>事務局からのお知らせ</h4>
    <?php foreach ($informations as $information): ?>
        <div class="alert alert-primary alert-with-icon" data-notify="container">
            <span data-notify="icon" class="ti-info-alt"></span>
            <span data-notify="message">[<?= h($information->start_datetime) ?>]<br><?= $this->Link->linkify($information->content) ?></span>
        </div>
    <?php endforeach ?>
</div>

<!-- <a href="<?= $this->Url->build(['controller' => 'Courses', 'action' => 'index']); ?>">
    <div class="col-lg-6 col-sm-6">
        <div class="card">
            <div class="card-content">
                <div class="row">
                    <div class="col-xs-3">
                        <div class="icon-big icon-primary text-center">
                            <i class="fa-solid fa-user-lock"></i>
                        </div>
                    </div>
                    <div class="col-xs-7">
                        <div class="h5 text-primary">
                            パーソナルプログラム
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</a>

<a href="<?= $this->Url->build(['controller' => 'LessonMovies', 'action' => 'index']); ?>">
    <div class="col-lg-6 col-sm-6">
        <div class="card">
            <div class="card-content">
                <div class="row">
                    <div class="col-xs-3">
                        <div class="icon-big icon-primary text-center">
                            <i class="fa-solid fa-folder-open"></i>
                        </div>
                    </div>
                    <div class="col-xs-7">
                        <div class="h5 text-primary">
                            グループレッスン
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</a>

<a href="<?= $this->Url->build(['controller' => 'DailyReports', 'action' => 'index']); ?>">
    <div class="col-lg-6 col-sm-6">
        <div class="card">
            <div class="card-content">
                <div class="row">
                    <div class="col-xs-3">
                        <div class="icon-big icon-primary text-center">
                            <i class="fa-solid fa-file-signature"></i>
                        </div>
                    </div>
                    <div class="col-xs-7">
                        <div class="h5 text-primary">
                            毎日の報告
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</a>

<a href="<?= $this->Url->build(['controller' => 'WeeklyReports', 'action' => 'index']); ?>">
    <div class="col-lg-6 col-sm-6">
        <div class="card">
            <div class="card-content">
                <div class="row">
                    <div class="col-xs-3">
                        <div class="icon-big icon-primary text-center">
                            <i class="fa-solid fa-photo-film"></i>
                        </div>
                    </div>
                    <div class="col-xs-7">
                        <div class="h5 text-primary">
                            毎週の報告
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</a> -->

<p style="margin-top:10px">&nbsp;</p>

<?php if ($user->staff_week_message || $user->staff_message) : ?>
<div class="col-lg-12">
    <div class="row">
        <div class="col-xs-9">

            <?php if ($user->staff_week_message) : ?>
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">今週のワーク</h4>
                </div>
                <div class="card-content">
                    <?= $this->Link->linkify($user->staff_week_message) ?>
                </div>
            </div>
            <?php endif ?>

            <?php if ($user->staff_message) : ?>
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">講師コメント</h4>
                </div>
                <div class="card-content">
                    <?= $this->Link->linkify($user->staff_message) ?>
                </div>
            </div>
            <?php endif ?>
        </div>
        <div class="col-xs-3">
            <img src="/img/staff.png" style="width:100%; max-width:150px">
        </div>
    </div>
</div>
<?php endif ?>


<?php
    $lastSumPoint = null;
    $firstDate = null;
    $lastDate = null;
    $firstPoint = [0, 0, 0, 0, 0];
    $lastPoint = [0, 0, 0, 0, 0];
    foreach ($user->user_points as $key => $userPoint) {
        if ($key == 0) {
            $lastPoint[0] = $userPoint->point_1;
            $lastPoint[1] = $userPoint->point_2;
            $lastPoint[2] = $userPoint->point_3;
            $lastPoint[3] = $userPoint->point_4;
            $lastPoint[4] = $userPoint->point_5;
            $lastSumPoint = ($userPoint->point_1 + $userPoint->point_2 + $userPoint->point_3 + $userPoint->point_4 + $userPoint->point_5);
            $lastDate = $userPoint->created;
        } else {
            $firstPoint[0] = $userPoint->point_1;
            $firstPoint[1] = $userPoint->point_2;
            $firstPoint[2] = $userPoint->point_3;
            $firstPoint[3] = $userPoint->point_4;
            $firstPoint[4] = $userPoint->point_5;
            $firstDate = $userPoint->created;
        }
    }
?>
<?php if ($lastSumPoint) : ?>
    <div class="col-md-6 col-xs-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">美脚進捗度：<b><?= $lastSumPoint ?></b>点</h4>
                <p class="category">送信された報告を元に講師が登録しています。</p>
            </div>
            <div class="card-content">
                <?php if ($firstDate || $lastDate) : ?>
                    <canvas id="chart" style="height:300px"></canvas>
                <?php endif ?>
            </div>
        </div>
    </div>

    <?php if ($firstDate || $lastDate) : ?>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.5.1/chart.js"></script>
    <script>
    var ctx = document.getElementById("chart");
    var myChart = new Chart(ctx, {
        type: 'radar',
        data: {
        labels: ["仙骨", "距骨", "股関節", "背骨・肋骨", "横隔膜・鎖骨"],
        datasets: [
                <?php if ($firstDate) : ?>
                {
                    label: "初回(<?= date('Y/m/d', strtotime($firstDate)) ?>)",
                    data: [<?= $firstPoint[0] ?>, <?= $firstPoint[1] ?>, <?= $firstPoint[2] ?>, <?= $firstPoint[3] ?>, <?= $firstPoint[4] ?>],
                    backgroundColor: "rgba(242, 184, 0, 0.5)",  //グラフ背景色
                    borderColor: "rgba(242, 184, 0, 1)",        //グラフボーダー色
                },
                <?php endif ?>
                <?php if ($lastDate) : ?>
                {
                    label: "最新(<?= date('Y/m/d', strtotime($lastDate)) ?>)",
                    data: [<?= $lastPoint[0] ?>, <?= $lastPoint[1] ?>, <?= $lastPoint[2] ?>, <?= $lastPoint[3] ?>, <?= $lastPoint[4] ?>],
                    backgroundColor: "rgba(252, 144, 153, 0.5)",  //グラフ背景色
                    borderColor: "rgba(252, 144, 153, 1)",        //グラフボーダー色
                }
                <?php endif ?>
            ]
        },
        options: {
        scales: {
            r: {
            max: 20, //グラフの最大値
            min: 0, //グラフの最小値
            ticks: {
                stepSize: 5  //目盛間隔
            }
            }
        },
        }
    });
    </script>
    <?php endif ?>
<?php endif ?>

<div class="col-md-6 col-xs-12">
    <div class="card card-plain">
        <div class="card-header">
            <h4 class="card-title">会員情報</h4>
        </div>
        <div class="card-content">
            <table class="table">
                <tbody>
                    <tr>
                        <th nowrap style="width:35%">会員番号</th>
                        <td><?= h($user->member_number) ?></td>
                    </tr>
                    <tr>
                        <th>氏名</th>
                        <td><?= h($user->last_name) ?> <?= h($user->first_name) ?></td>
                    </tr>
                    <tr>
                        <th>ご利用開始日</th>
                        <td><?= date('Y年n月j日', strtotime($user->start_datetime)) ?></td>
                    </tr>
                    <tr>
                        <th>ご利用終了日</th>
                        <td><?= ($user->end_datetime) ? date('Y年n月j日', strtotime($user->end_datetime)) : '' ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
