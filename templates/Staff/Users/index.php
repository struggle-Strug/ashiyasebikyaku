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
            <div class="col-md-3">
                <label>担当</label>
                <?= $this->Form->select('in_charge', [
                    '' => '選択してください',
                    '1' => '担当',
                    '2' => '非担当'
                ], ['class' => 'form-control', 'default' => $param['in_charge']]) ?>
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
    <div class="table-responsive-md scrollbar scrollbar-thumb-rounded scrollbar-thumb-black" style="height: 50vh;" id="member-table-container">
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
                        <th style="width:150px">画像比較確認</th>
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
                                <?php 
                                // 今日の日付がpause_startとpause_endの間にあるかチェック
                                $today = new DateTime();
                                $isPaused = false;
                                
                                if ($user->pause_start && $user->pause_end) {
                                    $pauseStart = new DateTime($user->pause_start);
                                    $pauseEnd = new DateTime($user->pause_end);
                                    
                                    if ($today >= $pauseStart && $today <= $pauseEnd) {
                                        $isPaused = true;
                                    }
                                }
                                
                                if ($isPaused) : ?>
                                    <span class="right badge badge-info">休会中</span>
                                <?php endif; ?>
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
                                <a href="<?= $this->Url->build(['action' => 'edit', $user->id, '?' => $param]); ?>"
                                    class="btn btn-outline-primary btn-xs">編集</a>
                            </div>
                        </td>
                        <td nowrap>
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
                        </td>
                        <td nowrap>
                            <div>
                                <a href="<?= $this->Url->build(['action' => 'message', $user->id, '?' => $param]); ?>" class="btn btn-outline-primary btn-xs">メッセージ確認</a>
                            </div>
                            <div>
                            <?php
                                $hasUnread = false;
                                $staffId = $staff->id;
                                if (!empty($user->messages)) {
                                    foreach ($user->messages as $msg) {
                                        $readArray = is_array($msg->is_read) ? $msg->is_read : [];

                                        if (!in_array($staffId, $readArray)) {
                                            $hasUnread = true;
                                            break;
                                        }
                                    }
                                }
                                ?>

                                <?php if ($hasUnread): ?>
                                    <span class="right badge badge-danger">未読</span>
                                <?php endif; ?>

                            </div>
                        </td>
                        <td nowrap>
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
                        </td>
                        <td nowrap>
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
                        </td>
                        <td nowrap>
                            <a href="<?= $this->Url->build(['action' => 'comparedImages', $user->id, '?' => $param]); ?>" class="btn btn-outline-primary btn-sm save-scroll-link">画像比較確認</a>
                            <div>
                                <?php if ($user->compared_images) : ?>
                                    <span class="right badge badge-danger">未送信</span>
                                <?php endif ?>
                            </div>
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

<script>
$(document).ready(function() {
    // Scroll position management for index page only
    const SCROLL_STORAGE_KEY = 'memberIndexScrollPosition';
    const tableContainer = $('#member-table-container');
    
    // Function to save scroll position
    function saveScrollPosition() {
        const tableScrollTop = tableContainer.scrollTop();
        const tableScrollLeft = tableContainer.scrollLeft();
        const windowScrollTop = $(window).scrollTop();
        
        const scrollData = {
            tableTop: tableScrollTop,
            tableLeft: tableScrollLeft,
            windowTop: windowScrollTop,
            timestamp: Date.now()
        };
        
        sessionStorage.setItem(SCROLL_STORAGE_KEY, JSON.stringify(scrollData));
    }

    function scrollToTop() {
        // ページ全体をトップにスクロール
        $('html, body').scrollTop(0);
        
        // テーブルコンテナもトップにスクロール
        tableContainer.scrollTop(0);
        
        // 保存されたスクロール位置をクリア
        sessionStorage.removeItem(SCROLL_STORAGE_KEY);
    }


    
    // Function to restore scroll position
    function restoreScrollPosition() {
        const savedScrollData = sessionStorage.getItem(SCROLL_STORAGE_KEY);
        
        if (savedScrollData) {
            try {
                const scrollData = JSON.parse(savedScrollData);
                
                // Check if data is not too old (within 30 minutes)
                const currentTime = Date.now();
                const maxAge = 30 * 60 * 1000; // 30 minutes
                
                if (currentTime - scrollData.timestamp < maxAge) {
                    // Restore scroll positions with slight delay to ensure DOM is ready
                    setTimeout(function() {
                        if (scrollData.tableTop > 0) {
                            tableContainer.scrollTop(scrollData.tableTop);
                        }
                        if (scrollData.tableLeft > 0) {
                            tableContainer.scrollLeft(scrollData.tableLeft);
                        }
                        if (scrollData.windowTop > 0) {
                            $(window).scrollTop(scrollData.windowTop);
                        }
                    }, 100);
                } else {
                    // Remove old data
                    sessionStorage.removeItem(SCROLL_STORAGE_KEY);
                }
            } catch (e) {
                console.error('Error restoring scroll position:', e);
                sessionStorage.removeItem(SCROLL_STORAGE_KEY);
            }
        }
    }
    
    // Restore scroll position when page loads
    restoreScrollPosition();
    
    // Save scroll position when navigating to other pages
    $('.save-scroll-link').on('click', function() {
        saveScrollPosition();
    });
    
    // Save scroll position when clicking "新規会員登録" button
    $('#add-member-btn').on('click', function() {
        saveScrollPosition();
    });
    
    // Save scroll position when clicking pagination links
    $('.pagination a').on('click', function() {
        // ページネーションクリック時はスクロールをトップに移動
        scrollToTop();
    });
    
    // Save scroll position when submitting search form
    $('form').on('submit', function() {
        saveScrollPosition();
    });
    
    // Save scroll position periodically while scrolling (debounced)
    let scrollTimeout;
    tableContainer.on('scroll', function() {
        clearTimeout(scrollTimeout);
        scrollTimeout = setTimeout(function() {
            saveScrollPosition();
        }, 200);
    });
    
    // Save window scroll position
    let windowScrollTimeout;
    $(window).on('scroll', function() {
        clearTimeout(windowScrollTimeout);
        windowScrollTimeout = setTimeout(function() {
            saveScrollPosition();
        }, 200);
    });
    
    // Save scroll position before page unload
    $(window).on('beforeunload', function() {
        saveScrollPosition();
    });
});
</script>