<?php $this->assign('title', '過去動画'); ?>

<?php foreach ($lessonMovies as $lessonMovie): ?>
    <div class="col-md-12">
        <div class="card">
            <div class="card-content">
                <div class="row">
                    <div class="col-xs-2">
                        <?php if ($lessonMovie->image_url): ?>
                            <img src="<?= h($lessonMovie->image_url) ?>" style="width:100%">
                        <?php else: ?>
                            <img src="/img/noimage.png" style="width:100%">
                        <?php endif ?>
                    </div>
                    <div class="col-xs-10">
                        <h5 class="card-title" style="margin:0">
                            <?= h($lessonMovie->name) ?> <i class="fa-solid fa-key"></i>
                        </h5>
                        <p class="description"><small><?= $this->Link->linkify(h($lessonMovie->note)) ?></small></p>
                        <button class="btn btn-wd btn-primary open-password"
                            data-lesson-id="<?= h($lessonMovie->id) ?>"
                            data-password="<?= h($lessonMovie->password) ?>">
                            <span class="btn-label"><i class="fa-solid fa-chevron-right"></i></span>
                            動画を見る
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endforeach ?>

<!-- Password Modal -->
<div class="modal fade" id="passwordModal" tabindex="-1" role="dialog" aria-labelledby="passwordModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="passwordForm">
                <div class="modal-header">
                    <h5 class="modal-title">パスワードを入力してください</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="閉じる">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="password" id="lesson-password" class="form-control" placeholder="パスワード">
                    <div id="password-error" class="text-danger mt-2" style="display: none;">パスワードが違います</div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">確認</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">キャンセル</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="col-md-12 text-center">
    <?= $this->element('pagination'); ?>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    let selectedLessonId = null;
    let expectedPassword = null;

    document.querySelectorAll('.open-password').forEach(button => {
        button.addEventListener('click', function () {
            selectedLessonId = this.dataset.lessonId;
            expectedPassword = this.dataset.password;

            document.getElementById('lesson-password').value = '';
            document.getElementById('password-error').style.display = 'none';
            $('#passwordModal').modal('show');
        });
    });

    document.getElementById('passwordForm').addEventListener('submit', function (e) {
        e.preventDefault();
        const enteredPassword = document.getElementById('lesson-password').value;

        if (enteredPassword === expectedPassword) {
            const redirectUrl = '<?= $this->Url->build(['action' => 'view']) ?>/' + selectedLessonId;
            window.location.href = redirectUrl;
        } else {
            document.getElementById('password-error').style.display = 'block';
        }
    });
});
</script>


