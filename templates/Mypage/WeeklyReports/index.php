<?php $this->assign('title', (($firstWeeklyReport) ? '毎週の報告' : '初期診断登録') ); ?>

<?php if (!$firstWeeklyReport) : ?>
<div class="col-lg-12">
    <div class="alert alert-primary alert-with-icon" data-notify="container">
        <span data-notify="icon" class="ti-alert"></span>
        <span data-notify="message">初回は初期診断のための情報登録となります。</span>
    </div>
</div>
<?php endif ?>

<?php if ($weeklyReport->id) : ?>
<div class="col-lg-12">
    <div class="alert alert-primary alert-with-icon" data-notify="container">
        <span data-notify="icon" class="ti-alert"></span>
        <span data-notify="message">まだ情報は送信されていません。</span>
    </div>
</div>
<?php endif ?>

<div class="col-lg-12">
    <div class="card">
        <div class="card-content">
            <div class="nav-tabs-navigation">
                <div class="nav-tabs-wrapper">
                    <ul id="tabs" class="nav nav-tabs">
                        <li class="active"><a href="<?= $this->Url->build(['action' => 'index']); ?>">登録</a></li>
                        <li><a href="<?= $this->Url->build(['action' => 'history']); ?>">過去の記録</a></li>
                        <li><a href="<?= $this->Url->build(['action' => 'comparedImages']); ?>">比較画像の記録</a></li>
                    </ul>
                </div>
            </div>

            <?= $this->Form->create($weeklyReport, ['id' => 'form']) ?>
            <?= $this->Form->hidden('id') ?>
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label>測定日</label>
                        <?= $this->Form->date('report_date', ['class' => 'form-control', 'required' => True]) ?>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>体重</label>
                        <div class="input-group">
                            <?= $this->Form->number('weight', ['class' => 'form-control', 'min' => 0, 'step' => '0.1', 'required' => True]) ?>
                            <span class="input-group-addon">kg</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>体脂肪率</label>
                        <div class="input-group">
                            <?= $this->Form->number('fat_percent', ['class' => 'form-control', 'min' => 0, 'step' => '0.1', 'required' => True]) ?>
                            <span class="input-group-addon">%</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>アンダーバスト</label>
                        <div class="input-group">
                            <?= $this->Form->number('size1', ['class' => 'form-control', 'min' => 0, 'step' => '0.1', 'required' => True]) ?>
                            <span class="input-group-addon">cm</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label>ウエスト</label>
                        <div class="input-group">
                            <?= $this->Form->number('size2', ['class' => 'form-control', 'min' => 0, 'step' => '0.1', 'required' => True]) ?>
                            <span class="input-group-addon">cm</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>おへそ回り</label>
                        <div class="input-group">
                            <?= $this->Form->number('size3', ['class' => 'form-control', 'min' => 0, 'step' => '0.1', 'required' => True]) ?>
                            <span class="input-group-addon">cm</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>お尻</label>
                        <div class="input-group">
                            <?= $this->Form->number('size4', ['class' => 'form-control', 'min' => 0, 'step' => '0.1', 'required' => True]) ?>
                            <span class="input-group-addon">cm</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>太もも一番太いところ</label>
                        <div class="input-group">
                            <?= $this->Form->number('size5', ['class' => 'form-control', 'min' => 0, 'step' => '0.1', 'required' => True]) ?>
                            <span class="input-group-addon">cm</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label>太もも中間</label>
                        <div class="input-group">
                            <?= $this->Form->number('size6', ['class' => 'form-control', 'min' => 0, 'step' => '0.1', 'required' => True]) ?>
                            <span class="input-group-addon">cm</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>膝上</label>
                        <div class="input-group">
                            <?= $this->Form->number('size7', ['class' => 'form-control', 'min' => 0, 'step' => '0.1', 'required' => True]) ?>
                            <span class="input-group-addon">cm</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>ふくらはぎ</label>
                        <div class="input-group">
                            <?= $this->Form->number('size8', ['class' => 'form-control', 'min' => 0, 'step' => '0.1', 'required' => True]) ?>
                            <span class="input-group-addon">cm</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>足首</label>
                        <div class="input-group">
                            <?= $this->Form->number('size9', ['class' => 'form-control', 'min' => 0, 'step' => '0.1', 'required' => True]) ?>
                            <span class="input-group-addon">cm</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="text-center" style="max-width:90%">
                <img src="/img/model/image000.png" style="max-width:100%">
            </div>
            <div class="text-center" style="margin:15px">
                <button type="submit" class="btn btn-info btn-fill btn-wd">写真撮影へ進む</button>
            </div>
            <?= $this->Form->end() ?>

            <div class="clearfix" style="margin:10px"></div>
        </div>
    </div>
</div>
