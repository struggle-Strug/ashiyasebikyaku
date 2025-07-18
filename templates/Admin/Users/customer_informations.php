<?php $this->assign('title', 'カルテ'); ?>
<style>
.scrollbar::-webkit-scrollbar {
  width: 10px;
  height: 10px;
}
/* スクロールバーの形を設定する */
.scrollbar-thumb-rounded::-webkit-scrollbar-thumb {
  border-radius: 0.25rem;
}
/* スクロールバーの色を設定する */
.scrollbar-thumb-black::-webkit-scrollbar-thumb {
  --bg-opacity: 1;
  background-color: rgba(0, 0, 0, 0.5);
}
</style>

<!-- Default box -->
<div class="card">
    <div class="card-body">
        <div class="table-responsive-md scrollbar scrollbar-thumb-rounded scrollbar-thumb-black" id="bodydata-table-container">
            <div style="font-weight: bold; font-size:24px; margin-top: 8px;">
                <p>基本情報</p>
            </div>
            <table class="table table-bordered table-striped" style="overflow-y: scroll;">
                <thead>
                    <tr>
                        <tr>
                            <th nowrap style="text-align: center;">コース名</th>
                            <th nowrap style="text-align: center;">氏名（漢字）</th>
                            <th nowrap style="text-align: center;">氏名（カナ）</th>
                            <th nowrap style="text-align: center;">生年月日</th>
                            <th nowrap style="text-align: center;">住所</th>
                            <th nowrap style="text-align: center;">プログラム開始日</th>
                            <th nowrap style="text-align: center;">担当講師</th>
                            <th></th>
                        </tr>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td nowrap style="text-align: center;">
                            <?php if ($user->course_id == 1) : ?>
                                エバーコース
                            <?php elseif ($user->course_id == 2) : ?>
                                マスターコース
                            <?php else : ?>
                                自習コース
                            <?php endif; ?>
                        </td>
                        <td nowrap style="text-align: center;"><?= h($user->last_name) ?> <?= h($user->first_name) ?></td>
                        <td nowrap style="text-align: center;"><?= h($user->last_name_kana) ?> <?= h($user->first_name_kana) ?></td>
                        <td nowrap style="text-align: center;">
                            <?php if ($user->birthday) : ?>
                                <?= h($user->birthday) ?><br>（<?= floor((date('Ymd') - date('Ymd', strtotime($user->birthday))) / 10000) ?>歳）
                            <?php endif ?>
                        </td>
                        <td nowrap style="text-align: center;">
                            <?php if($user->zipcode) : ?>
                                〒<?= h($user->zipcode) ?>
                            <?php endif; ?>
                             <?= h($user->prefecture) ?> <?= h($user->city) ?> <?= h($user->address) ?>
                        </td>
                        <td nowrap style="text-align: center;"><?= h($user->start_datetime) ?></td>
                        <td nowrap style="text-align: center;"><?= ($user->staff) ? $user->staff->name : '' ?></td>
                        <td nowrap style="text-align: center;">
                            <a href="<?= $this->Url->build(['action' => 'customerMain', $user->id, '?' => $param]); ?>" class="btn btn-outline-primary btn-sm save-scroll-link">編集</a>
                        </td>
                    </tr>
                </tbody>
            </table>
            <div style="font-weight: bold; font-size:24px; margin-top: 8px;">
                <p>生活習慣・既往歴</p>
            </div>
            <table class="table table-bordered table-striped" style="overflow-y: scroll;">
                <thead>
                    <tr>
                        <tr>
                            <th nowrap style="text-align: center;">職業</th>
                            <th nowrap style="text-align: center;">食事習慣</th>
                            <th nowrap style="text-align: center;">睡眠</th>
                            <th nowrap style="text-align: center;">ストレス要因</th>
                            <th nowrap style="text-align: center;">運動歴</th>
                            <th nowrap style="text-align: center;">既往歴</th>
                            <th nowrap style="text-align: center;">その他注意点</th>
                            <th style="width: 150px;"></th>
                        </tr>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><?= h($habit['occupation']) ?></td>
                        <td><?= h($habit['eating_habits']) ?></td>
                        <td><?= h($habit['sleep']) ?></td>
                        <td><?= h($habit['stress_factors']) ?></td>
                        <td><?= h($habit['exercise_history']) ?></td>
                        <td><?= h($habit['medical_history']) ?></td>
                        <td><?= h($habit['other_notes']) ?></td>
                        <td nowrap style="text-align: center;">
                            <a href="<?= $this->Url->build(['action' => 'habits', $user->id, '?' => $param]); ?>" class="btn btn-outline-primary btn-sm save-scroll-link">編集</a>
                        </td>
                    </tr>
                </tbody>
            </table>
            <div style="font-weight: bold; font-size:24px; margin-top: 8px;">
                <p>目標設定シート</p>
            </div>
            <table class="table table-bordered table-striped" style="overflow-y: scroll;">
                <thead>
                    <tr>
                        <tr>
                            <th nowrap style="text-align: center;">測定日</th>
                            <th nowrap style="text-align: center;">足首</th>
                            <th nowrap style="text-align: center;">足の甲</th>
                            <th nowrap style="text-align: center;">縦アーチ</th>
                            <th nowrap style="text-align: center;">横アーチ</th>
                            <th nowrap style="text-align: center;">足指</th>
                            <th nowrap>備考</th>
                        </tr>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($mockBodyData as $data): ?>
                    <tr>
                        <td nowrap style="text-align: center;"><?= h($data['date']) ?></td>
                        <td nowrap style="text-align: center;"><?= h($data['ankle']) ?></td>
                        <td nowrap style="text-align: center;"><?= h($data['instep']) ?></td>
                        <td nowrap style="text-align: center;"><?= h($data['vertical_arch']) ?></td>
                        <td nowrap style="text-align: center;"><?= h($data['horizontal_arch']) ?></td>
                        <td nowrap style="text-align: center;"><?= h($data['toes']) ?></td>
                        <td nowrap><?= h($data['note']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <div style="font-weight: bold; font-size:24px; margin-top: 8px;">
                <p>心理・モチベーション</p>
            </div>
            <table class="table table-bordered table-striped" style="overflow-y: scroll;">
                <thead>
                    <tr>
                        <tr>
                            <th nowrap style="text-align: center;">性格傾向</th>
                            <th nowrap style="text-align: center;">モチベーション源泉</th>
                            <th nowrap style="text-align: center;">離脱リスク</th>
                        </tr>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><?= h($mockMentionData['personality_tendency']) ?></td>
                        <td><?= h($mockMentionData['motivation_source']) ?></td>
                        <td><?= h($mockMentionData['dropout_risk']) ?></td>
                    </tr>
                </tbody>
            </table>
            <div style="font-weight: bold; font-size:24px; margin-top: 8px;">
                <p>レッスン・コンサル時の指導履歴</p>
            </div>
            <table class="table table-bordered table-striped" style="overflow-y: scroll;">
                <thead>
                    <tr>
                        <tr>
                            <th nowrap style="text-align: center;">開催日時</th>
                            <th nowrap style="text-align: center;">区分</th>
                            <th nowrap style="text-align: center;">テーマ</th>
                            <th style="text-align: center;">指導内容<br />（やったこと）</th>
                            <th style="text-align: center;">注意点<br />（分かったこと）</th>
                            <th style="text-align: center;">申し送り事項 <br />（次やること）</th>
                            <th nowrap style="text-align: center;">担当講師</th>
                        </tr>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($mockConsultData as $data): ?>
                    <tr>
                        <td nowrap style="text-align: center;"><?= h($data['date']) ?></td>
                        <td nowrap style="text-align: center;"><?= h($data['category']) ?></td>
                        <td nowrap style="text-align: center;"><?= h($data['theme']) ?></td>
                        <td><?= h($data['instruction']) ?></td>
                        <td><?= h($data['note']) ?></td>
                        <td><?= h($data['next_step']) ?></td>
                        <td nowrap style="text-align: center;"><?= h($data['instructor']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <div style="font-weight: bold; font-size:24px; margin-top: 8px;">
                <p>課題の自己認識結果</p>
            </div>
            <table class="table table-bordered table-striped" style="overflow-y: scroll;">
                <thead>
                    <tr>
                        <tr>
                            <th nowrap style="text-align: center;">登録日</th>
                            <th>A.前回から今回までの良い変化・成長</th>
                            <th>B.前回設定したワークの振り返り</th>
                            <th>C.まだ変化を感じられていない部分</th>
                            <th>D.今の自分の状態</th>
                            <th>E.次の2週間でやりたいワーク</th>
                            <th>F.個別コンサルで相談したいこと</th>
                            <th>指導内容</th>
                        </tr>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($mockSelfData as $data): ?>
                    <tr>
                        <td nowrap style="text-align: center;"><?= h($data['date']) ?></td>
                        <td><?= h($data['good_changes']) ?></td>
                        <td><?= h($data['work_review']) ?></td>
                        <td><?= h($data['no_change_parts']) ?></td>
                        <td><?= h($data['current_state']) ?></td>
                        <td><?= h($data['next_two_weeks_work']) ?></td>
                        <td><?= h($data['consult_topics']) ?></td>
                        <td>
                            <?php if ($data['consult_content'] != '' && $$data['consult_content'] != null) : ?>
                                <?= h($data['consult_content']) ?>
                            <?php else : ?>
                                <small><span class="text-muted">未記入</span></small>
                            <?php endif ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <div style="font-weight: bold; font-size:24px; margin-top: 8px;">
                <p>標準モデルとの差異結果</p>
            </div>
            <table class="table table-bordered table-striped" style="overflow-y: scroll;">
                <thead>
                    <tr>
                        <tr>
                            <th nowrap style="text-align: center;">マイルストーン</th>
                            <th nowrap style="text-align: center;">成果カテゴリ</th>
                            <th nowrap style="text-align: center;">標準達成目安</th>
                            <th style="text-align: center;">評価</th>
                            <th style="text-align: center;">評価コメント</th>
                            <th style="text-align: center;">Before/After写真</th>
                        </tr>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($mockDifferenceData as $index => $data): ?>
                        <tr>
                            <td nowrap style="text-align: center;"><?= h($data['milestone']) ?></td>
                            <td nowrap style="text-align: center;"><?= h($data['category']) ?></td>
                            <td>
                                <?= h(trim($data['target1'])) ?><br>
                                <?= h(trim($data['target2'])) ?>
                            </td>
                            <td>
                                <?= h(trim($data['evaluation1'])) ?><br>
                                <?= h(trim($data['evaluation2'])) ?>
                            </td>
                            <td>
                                <?= h(trim($data['comment1'])) ?><br>
                                <?= h(trim($data['comment2'])) ?>
                            </td>
                            <td>
                                <?php if($index == 0) : ?>
                                    <div style="display: flex; justify-content: center;">
                                        <a href="<?= $this->Url->build(['action' => 'comparedImages', $user->id, '?' => $param]); ?>" 
                                            class="btn btn-outline-primary btn-sm save-scroll-link" 
                                            target="_blank">
                                            週報
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <!-- /.card-body -->
    <!-- <div class="card-footer clearfix">
        <?= $this->element('pagination'); ?>
    </div> -->
    <!-- /.card-body -->
    <div class="card-footer">
        <a class="btn btn-outline-secondary" href="<?= $this->Url->build(['action' => 'customerInformations', $user -> id, '?' => $param]) ?>" id="back-btn">
            <i class="fas fa-arrow-circle-left"></i> 戻る
        </a>
        <a class="btn btn-primary float-right" href="<?= $this->Url->build(['action' => 'editCustomerInformations', $user -> id, '?' => $param]) ?>" id="back-btn">
            <i class="fas fa-arrow-circle-right"></i> 編集
        </a>
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
        saveScrollPosition();
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