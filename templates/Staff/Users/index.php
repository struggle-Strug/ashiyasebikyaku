<?php $this->assign('title', '会員管理(担当分を表示)'); ?>

<?= $this->Form->create(null, ['type' => 'get']) ?>
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-3">
                <label>会員番号</label>
                <?= $this->Form->text('member_number', ['class' => 'form-control', 'placeholder' => '', 'default' => $param['member_number']]) ?>
            </div>
            <div class="col-md-3">
                <label>氏名</label>
                <?= $this->Form->text('name', ['class' => 'form-control', 'placeholder' => '', 'default' => $param['name']]) ?>
            </div>
            <div class="col-md-12 text-right">
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-magnifying-glass"></i> 検索</button>
            </div>
        </div>
    </div>
</div>
<?= $this->Form->end() ?>

<div class="card">
    <div class="card-body">
        <div class="table-responsive-md">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th nowrap>会員番号</th>
                        <th nowrap>氏名</th>
                        <th nowrap>年齢</th>
                        <th nowrap>性別</th>
                        <th nowrap>表示メッセージ</th>
                        <th nowrap>美脚進捗度</th>
                        <th style="width:160px">コミュニケーション<br>ツール</th>
                        <th style="width:150px">日次報告</th>
                        <th style="width:150px">週次報告</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                    <tr>
                        <td>
                            <?= h($user->member_number) ?>
                            <div>
                                <?php if ($user->is_continue) : ?>
                                <span class="right badge badge-info">継続</span>
                                <?php else : ?>
                                <span class="right badge badge-warning">新規</span>
                                <?php endif ?>
                            </div>
                        </td>
                        <td>
                            <small><?= h($user->last_name_kana) ?> <?= h($user->first_name_kana) ?></small><br>
                            <?= h($user->last_name) ?> <?= h($user->first_name) ?>
                        </td>
                        <td>
                            <?php if ($user->birthday) : ?>
                                <?= floor((date('Ymd') - date('Ymd', strtotime($user->birthday))) / 10000) ?>歳
                            <?php endif ?>
                        </td>
                        <td>
                            <?php if ($user->sextype == '1') : ?>
                                女性
                            <?php elseif ($user->sextype == '2') : ?>
                                男性
                            <?php endif ?>
                        </td>
                        <td nowrap>
                            <div>
                                <?php if ($user->staff_id == $staff->id) : ?>
                                <a href="<?= $this->Url->build(['action' => 'edit', $user->id, '?' => $param]); ?>"
                                    class="btn btn-outline-primary btn-xs">編集</a>
                                <?php endif ?>
                            </div>
                        </td>
                        <td nowrap>
                            <?php if ($user->staff_id == $staff->id) : ?>
                            <div>
                                <a href="<?= $this->Url->build(['action' => 'point', $user->id, '?' => $param]); ?>"
                                    class="btn btn-outline-primary btn-xs">登録</a>
                                </div>
                                <div>
                                    最新：
                                    <?php
                                    foreach ($user->user_points as $userPoint) {
                                        echo h($userPoint->point_1 + $userPoint->point_2 + $userPoint->point_3 + $userPoint->point_4 + $userPoint->point_5);
                                        break;
                                    }
                                    ?>
                            </div>
                            <?php endif ?>
                        </td>
                        <td nowrap>
                            <div>
                                <a href="<?= $this->Url->build(['action' => 'message', $user->id, '?' => $param]); ?>" class="btn btn-outline-primary btn-xs">メッセージ確認</a>
                            </div>
                            <div>
                                <?php if ($user->messages) : ?>
                                    <span class="right badge badge-danger">未読</span>
                                <?php endif ?>
                            </div>
                        </td>
                        <td nowrap>
                            <?php if ($user->staff_id == $staff->id) : ?>
                            <a href="<?= $this->Url->build(['controller' => 'DailyReports', 'action' => 'index', '?' => ['user_id' => $user->id, 'member_number' => $param['member_number'], 'name' => $param['name']]]); ?>"
                                class="btn btn-outline-primary btn-xs">日次報告確認</a>
                            <div>
                                最新：
                                <?php
                                    foreach ($user->daily_reports as $dailyReport) {
                                        echo h($dailyReport->report_date);
                                        break;
                                    }
                                ?>
                            </div>
                            <?php endif ?>
                        </td>
                        <td nowrap>
                            <?php if ($user->staff_id == $staff->id) : ?>
                            <a href="<?= $this->Url->build(['controller' => 'WeeklyReports', 'action' => 'index', '?' => ['user_id' => $user->id, 'member_number' => $param['member_number'], 'name' => $param['name']]]); ?>"
                                class="btn btn-outline-primary btn-xs">週次報告確認</a>
                            <div>
                                最新：
                                <?php
                                    foreach ($user->weekly_reports as $weeklyReport) {
                                        echo h($weeklyReport->report_date);
                                        break;
                                    }
                                ?>
                            </div>
                            <?php endif ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
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
