<?php $this->assign('title', '会員管理'); ?>

<?= $this->Form->create(null, ['type' => 'get']) ?>
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-3 mt-2">
                <label>会員番号</label>
                <?= $this->Form->text('member_number', ['class' => 'form-control', 'placeholder' => '', 'default' => $param['member_number']]) ?>
            </div>
            <div class="col-md-3 mt-2">
                <label>会員名</label>
                <?= $this->Form->text('name', ['class' => 'form-control', 'placeholder' => '', 'default' => $param['name']]) ?>
            </div>
            <div class="col-md-3 mt-2">
                <label>担当講師</label>
                <?= $this->Form->select('staff_id', $staffs, ['class' => 'form-control', 'empty' => '-', 'default' => $param['staff_id']]) ?>
            </div>
            <div class="col-md-12 text-right mt-2">
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-magnifying-glass"></i> 検索</button>
            </div>
        </div>
    </div>
</div>
<?= $this->Form->end() ?>

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
    <div class="card-header">
    <a href="<?= $this->Url->build(['action' => 'add', '?' => $param]) ?>" class="btn btn-outline-primary" id="add-member-btn">新規会員登録</a>
    </div>
    <div class="card-body">
        <div class="table-responsive-md scrollbar scrollbar-thumb-rounded scrollbar-thumb-black" style="height: 50vh;" id="member-table-container">
            <table class="table table-bordered table-striped" style="overflow-y: scroll;">
                <thead>
                    <tr>
                        <tr>
                            <th nowrap>会員番号</th>
                            <th nowrap>氏名</th>
                            <th nowrap>生年月日</th>
                            <!-- <th nowrap>住所</th> -->
                            <th nowrap>メールアドレス</th>
                            <th nowrap>電話番号</th>
                            <th nowrap>利用開始日時</th>
                            <th nowrap>利用終了日時</th>
                            <th nowrap>担当講師</th>
                            <th nowrap>コミュニケーション<br>ツール</th>
                            <th style="width:150px"></th>
                        </tr>
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
                                <?php endif; ?>
                                <?php if ($user->course_id === 3) : ?>
                                        <span class="right badge badge-info">休会中</span>
                                <?php endif; ?>
                            </div>
                            <div>
                                <?php if ($user->light_plan == "1") : ?>
                                    <span class="right badge badge-success">ライトプラン</span>
                                <?php endif ?>
                            </div>
                            <div>
                                <?php if ($user->staff_plan == "1") : ?>
                                    <span class="right badge badge-success">講師育成</span>
                                <?php endif ?>
                            </div>
                        </td>
                        <td nowrap><small><?= h($user->last_name_kana) ?> <?= h($user->first_name_kana) ?></small><br><?= h($user->last_name) ?> <?= h($user->first_name) ?></td>
                        <td>
                            <?php if ($user->birthday) : ?>
                                <?= h($user->birthday) ?><br>（<?= floor((date('Ymd') - date('Ymd', strtotime($user->birthday))) / 10000) ?>歳）
                            <?php endif ?>
                        </td>
                        <!--
                        <td>
                            〒<?= h($user->zipcode) ?>
                            <div><?= h($user->prefecture) ?><?= h($user->city) ?><?= h($user->address) ?></div>
                        </td>
                        -->
                        <td><?= h($user->email) ?></td>
                        <td><?= h($user->tel) ?></td>
                        <td><?= h($user->start_datetime) ?></td>
                        <td><?= h($user->end_datetime) ?></td>
                        <td><?= ($user->staff) ? $user->staff->name : '' ?></td>
                        <td nowrap>
                            <a href="<?= $this->Url->build(['action' => 'message', $user->id, '?' => $param]); ?>" class="btn btn-outline-primary btn-sm save-scroll-link">メッセージ確認</a>
                            <div>
                                <?php if ($user->messages) : ?>
                                    <span class="right badge badge-danger">未読</span>
                                <?php endif ?>
                            </div>
                        </td>
                        <td nowrap>
                            <a href="<?= $this->Url->build(['action' => 'edit', $user->id, '?' => $param]); ?>" class="btn btn-outline-primary btn-sm save-scroll-link">編集</a>
                            <?= $this->Form->postLink(__('削除'), ['action' => 'delete', $user->id], ['class' => 'btn btn-outline-secondary btn-sm save-scroll-link', 'confirm' => __('退会は編集から「利用終了日時」を設定してください。本当に削除してよろしいですか？', $user->id)]) ?>
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