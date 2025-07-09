<?php $this->assign('title', '比較画像選択'); ?>

<!-- Default box -->
<?= $this->Form->create(null, ['type' => 'get']) ?>
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-4 mt-2">
                <label>測定日</label>
                <div class="input-group">
                    <?= $this->Form->date('start_report_date', ['class' => 'form-control', 'placeholder' => '', 'default' => $param['start_report_date']]) ?>
                    <div class="input-group-prepend">
                        <span class="input-group-text">
                            〜
                        </span>
                    </div>
                    <?= $this->Form->date('end_report_date', ['class' => 'form-control', 'placeholder' => '', 'default' => $param['end_report_date']]) ?>
                </div>
            </div>
            <div class="col-md-3 mt-2">
                <label>会員名</label>
                <?= $this->Form->select('user_id', $users, ['class' => 'form-control', 'empty' => '-', 'default' => $param['user_id']]) ?>
            </div>
            <input type="text" name="target_image" id="target_image" style="display: none;"
                value="<?= $param['target_image'] ?>" />
            <input type="text" name="subject_image" id="subject_image" style="display: none;"
                value="<?= $param['subject_image'] ?>" />
            <input type="text" name="target_image_id" id="target_image_id" style="display: none;"
                value="<?= $param['target_image_id'] ?>" />
            <input type="text" name="subject_image_id" id="subject_image_id" style="display: none;"
                value="<?= $param['subject_image_id'] ?>" />
            <div class="col-md-12 text-right mt-2">
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-magnifying-glass"></i> 検索</button>
            </div>
        </div>
    </div>
</div>
<?= $this->Form->end() ?>
<style>

.uniform-image {
  width: 100px;
  display: block;
  margin: 0;
  padding: 0;
  border: none;
}

</style>

<div style="font-size: 24px; font-weight: bold;">画像選択</div>
<div class="card">
    <div class="card-body">
        <div style="display: flex">
            <div id="target_image_display" style="width: 100px; height: 180px; margin-top: 24px; margin-bottom: 24px; margin-right: 24px;">
                <div style="font-weight: bold; ">アフター画像</div>
                <img src="" id="target_image_url" alt="target_image" style="max-width:100px; display: none; " />
            </div>
            <div id="subject_image_display" style="width: 100px; height: 180px;  margin-top: 24px; margin-bottom: 24px; margin-right: 24px;">
                <div style="font-weight: bold; ">ビフォー画像</div>
                <img src="" id="subject_image_url" alt="subject_image" style="max-width:100px; display: none; " />
            </div>
        </div>

        <?php if (!$weeklyReports || count($weeklyReports) == 0): ?>

            <?php if (empty($param['user_id'])): ?>
                会員を選択してください。
            <?php else: ?>
                該当のデータはありません。
            <?php endif ?>

        <?php else: ?>

            <div class="table-responsive-md" style="height: 50vh; ">
                <table class="table table-bordered table-striped overflow-y: scroll">
                    <thead>
                        <tr>
                            <?php foreach ($photoName as $key => $name): ?>
                                <th nowrap style="min-width:120px">
                                    <?= h($name) ?>
                                </th>
                            <?php endforeach ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($weeklyReports as $key => $weeklyReport): ?>
                            <tr>
                                <?php foreach ($weeklyReport->photos as $photo): ?>
                                    <td>
                                        <?php if ($photo->image_url): ?>
                                            <?php if (strstr($photo->image_url, '.mp4') !== false || strstr($photo->image_url, '.m4v') !== false || strstr($photo->image_url, '.3gp') !== false || strstr($photo->image_url, '.mov') !== false): ?>
                                                <video src="<?= $photo->image_url ?>" style="max-width:100%;" controls></video>
                                            <?php else: ?>
                                                <div style="position: relative;" data-image="<?= $photo->image_url ?>"
                                                    data-date="<?= $photo->created ?>">
                                                    <img src="<?= $photo->image_url ?>" class="selectElem image uniform-image" style="max-width:100%;"
                                                        data-image="<?= $photo->image_url ?>" data-date="<?= $photo->created ?>"
                                                        data-id="<?= $photo->id ?>" id="<?= $photo->id ?>">
                                                    <div id="mask_<?= $photo->id ?>" data-image="<?= $photo->image_url ?>"
                                                        data-date="<?= $photo->created ?>" data-id="<?= $photo->id ?>"
                                                        class="selectElem mask"
                                                        style="position: absolute; background-color: rgba(0,0,0,0.5); top: 0; left:0; display: flex; color: white; justify-content: center; align-items: center; display: none;">
                                                        ①</div>
                                                </div>
                                            <?php endif ?>
                                        <?php endif ?>
                                    </td>
                                <?php endforeach ?>
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>
        <?php endif ?>
        <?= $this->Form->create(null, ['type' => 'get', 'action' => 'createComparedImage']) ?>
        <input type="text" name="target_id" id="target_id" style="display: none;"
            value="<?= $param['target_image_id'] ?>" />
        <input type="text" name="subject_id" id="subject_id" style="display: none;"
            value="<?= $param['subject_image_id'] ?>" />
        <input type="text" name="user" id="user" style="display: none;" value="<?= $param['user_id'] ?>" />
        <button type="submit" class="btn btn-primary" style="">画像作成ページへ</button>
        <?= $this->Form->end() ?>
    </div>
</div>
</div>
<script>
    $(function () {
        let csrf = $('#ajax-form').find('input[name=_csrfToken]').val();

        const selectedImageInfo = {
            target: {
                image_url: "",
                id: ""
            },
            subject: {
                image_url: "",
                id: ""
            }
        }


        function initLoad() {
            const target_image_url = "<?= $param["target_image"] ?>"
            const subject_image_url = "<?= $param["subject_image"] ?>"
            const target_image_id = "<?= $param["target_image_id"] ?>"
            const subject_image_id = "<?= $param["subject_image_id"] ?>"
            selectedImageInfo.target.image_url = target_image_url
            selectedImageInfo.target.id = target_image_id
            selectedImageInfo.subject.image_url = subject_image_url
            selectedImageInfo.subject.id = subject_image_id

            if (target_image_url !== "") {
                document.getElementById("target_image_url").style.display = "block"
                document.getElementById("target_image_url").src = target_image_url
            }

            if (subject_image_url !== "") {
                document.getElementById("subject_image_url").style.display = "block";
                document.getElementById("subject_image_url").src = subject_image_url

            }
            Array.from(document.getElementsByClassName("image")).forEach(element => {
                if (element.dataset.image === target_image_url) {
                    const mask = document.getElementById("mask_" + element.dataset.id);
                    mask.style.width = document.getElementById(element.dataset.id).width + "px"
                    mask.style.height = document.getElementById(element.dataset.id).height + "px";
                    mask.style.display = "flex"
                    mask.textContent = "アフター"
                }

                if (element.dataset.image === subject_image_url) {
                    const mask = document.getElementById("mask_" + element.dataset.id);
                    mask.style.width = document.getElementById(element.dataset.id).width + "px";
                    mask.style.height = document.getElementById(element.dataset.id).height + "px";
                    mask.style.display = "flex"
                    mask.textContent = "ビフォー"
                }
            })
        }

        initLoad();

        function targetImageSelect(e) {
            document.getElementById("target_image").value = e.target.dataset.image;
            document.getElementById("target_image_id").value = e.target.dataset.id;
            document.getElementById("target_image_url").src = e.target.dataset.image;
        }

        function subjectImageSelect(e) {
            document.getElementById("subject_image").value = e.target.dataset.image;
            document.getElementById("subject_image_id").value = e.target.dataset.id;
            document.getElementById("subject_image_url").src = e.target.dataset.image;
        }

        function selectImage() {
            Array.from(document.getElementsByClassName("selectElem")).forEach(element => {
                element.addEventListener("click", function (e) {
                    const mask = document.getElementById("mask_" + e.target.dataset.id);
                    mask.style.width = e.target.width + "px"
                    mask.style.height = e.target.height + "px";
                    if (mask.style.display === "none") {
                        mask.style.display = "flex"
                    } else {
                        mask.style.display = "none"
                    }
                    if (document.getElementById("target_image").value === "") {
                        if (document.getElementById("subject_image").value === e.target.dataset.image) {
                            selectedImageInfo.subject.image_url = ""
                            selectedImageInfo.subject.id = ""
                        } else {
                            selectedImageInfo.target.image_url = e.target.dataset.image;
                            selectedImageInfo.target.id = e.target.dataset.id;
                        }
                    } else {
                        if (document.getElementById("target_image").value === e.target.dataset.image) {
                            selectedImageInfo.target.image_url = ""
                            selectedImageInfo.target.id = ""
                        } else {
                            if (document.getElementById("subject_image").value === "") {
                                selectedImageInfo.subject.image_url = e.target.dataset.image;
                                selectedImageInfo.subject.id = e.target.dataset.id;
                            } else if (document.getElementById("subject_image").value === e.target.dataset.image) {
                                selectedImageInfo.subject.image_url = ""
                                selectedImageInfo.subject.id = ""
                            } else {
                                selectedImageInfo.subject.image_url = e.target.dataset.image;
                                selectedImageInfo.subject.id = e.target.dataset.id;
                            }

                        }
                    }

                    document.getElementById("target_image").value = selectedImageInfo.target.image_url;
                    document.getElementById("target_image_id").value = selectedImageInfo.target.id;
                    document.getElementById("target_id").value = selectedImageInfo.target.id;
                    document.getElementById("target_image_url").src = selectedImageInfo.target.image_url;
                    document.getElementById("subject_image").value = selectedImageInfo.subject.image_url;
                    document.getElementById("subject_image_id").value = selectedImageInfo.subject.id;
                    document.getElementById("subject_id").value = selectedImageInfo.subject.id;
                    document.getElementById("subject_image_url").src = selectedImageInfo.subject.image_url;

                    Array.from(document.getElementsByClassName("mask")).forEach(element => {
                        element.style.display = "none";
                    })

                    if (selectedImageInfo.subject.id !== "") {
                        const mask = document.getElementById("mask_" + selectedImageInfo.subject.id);
                        document.getElementById("subject_image_url").style.display = "block"
                        mask.style.width = document.getElementById(selectedImageInfo.subject.id).width + "px"
                        mask.style.height = document.getElementById(selectedImageInfo.subject.id).height + "px";
                        mask.style.display = "flex"
                        mask.textContent = "ビフォー";
                    } else {
                        document.getElementById("subject_image_url").style.display = "none"
                    }

                    if (selectedImageInfo.target.id !== "") {
                        const mask = document.getElementById("mask_" + selectedImageInfo.target.id);
                        document.getElementById("target_image_url").style.display = "block"
                        mask.style.width = document.getElementById(selectedImageInfo.target.id).width + "px"
                        mask.style.height = document.getElementById(selectedImageInfo.target.id).height + "px";
                        mask.style.display = "flex"
                        mask.textContent = "アフター"
                    } else {
                        document.getElementById("target_image_url").style.display = "none"
                    }

                })
            });
        }

        selectImage();

    });
</script>