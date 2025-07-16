<?php $this->assign('title', '課題の自己認識結果'); ?>

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
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <a href="<?= $this->Url->build(['action' => 'add_self_understand', '?' => $param, $user -> id]) ?>" class="btn btn-outline-primary" id="add-bodydata-btn">新規登録</a>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive-md scrollbar scrollbar-thumb-rounded scrollbar-thumb-black" style="height: 50vh;" id="bodydata-table-container">
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
                            <th></th>
                        </tr>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($mockUserData as $data): ?>
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
                        <td>
                            <a href="<?= $this->Url->build(['action' => 'add_consult_content',
                             $data['id'], 
                             '?' => $param]); ?>" class="btn btn-outline-primary btn-sm save-scroll-link">指導内容入力</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- /.card-body -->
    <!-- /* <div class="card-footer clearfix">
        <?= $this->element('pagination'); ?>
    </div> */ -->
    <!-- /.card-body -->
    <div class="card-footer">
        <a class="btn btn-outline-secondary" href="<?= $this->Url->build(['action' => 'customerInformations', $user -> id, '?' => $param]) ?>" id="back-btn">
            <i class="fas fa-arrow-circle-left"></i> 戻る
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
