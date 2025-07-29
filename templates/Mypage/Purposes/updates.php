<?php $this->assign('title', '目標設定シート（2回目以降）'); ?>

<div class="col-lg-12">
    <div class="card">
        <div class="card-content">
            <div class="nav-tabs-navigation">
                <div class="nav-tabs-wrapper">
                    <ul id="tabs" class="nav nav-tabs">
                        <li><a href="<?= $this->Url->build(['action' => 'index']); ?>">初回</a></li>
                        <li class="active"><a href="<?= $this->Url->build(['action' => 'updates']); ?>">2回目以降</a></li>
                    </ul>
                </div>
            </div>

            <div class="clearfix" style="margin:10px"></div>

            <?= $this->Form->create($selfdata, ['id' => 'form']) ?>

            <!-- A. 前回から今回までの良い変化・成長 -->
            <div class="form-group">
                <?= $this->Form->control('good_changes', [
                    'label' => '[A. 前回から今回までの良い変化・成長] ※<br>・ボディライン面での嬉しい変化<br>・体調面での改善点<br>・メンタル面での成長<br>・行動習慣での変化',
                    'escape' => false,
                    'type' => 'textarea',
                    'class' => 'form-control',
                    'placeholder' => 'ボディラインの嬉しい変化、体調面での変化、メンタル面での成長、行動習慣での変化など'
                ]) ?>
                <span class="text-danger"><?= $this->Form->error('good_changes') ?></span>
            </div>


            <!-- B. 前回設定したワークの振り返り -->
            <div class="form-group">
                <?= $this->Form->control('work_review', [
                    'label' => '【B. 前回設定したワークの振り返り】 ※<br>・できたこと・継続できたこと<br>・難しかった・できなかったこと',
                    'escape' => false,
                    'type' => 'textarea',
                    'class' => 'form-control',
                    'placeholder' => 'できたこと・継続できたこと、難しかったこと・できなかったこと'
                ]) ?>
                <span class="text-danger"><?= $this->Form->error('work_review') ?></span>
            </div>

            <!-- C. まだ変化を感じられていない部分 -->
            <div class="form-group">
                <?= $this->Form->control('no_change_parts', [
                    'label' => '【C. まだ変化を感じられていない部分】 ※<br>・ボディライン面で気になること<br>・体調面で改善したいこと<br>・なぜ変わらないと思うか（自分なりの仮説）<br>・どうすれば変わりそうか',
                    'escape' => false,
                    'type' => 'textarea',
                    'class' => 'form-control',
                    'placeholder' => 'ボディラインで気になること、体調・メンタルの課題、感情・癖・思考の部分（自分なりの仮説）、どうすれば変わりそうか'
                ]) ?>
                <span class="text-danger"><?= $this->Form->error('no_change_parts') ?></span>
            </div>

            <!-- D. 今の自分の状態 -->
            <div class="form-group">
                <?= $this->Form->control('current_state', [
                    'label' => '【D. 今の自分の状態】<br>・体からのメッセージ・サイン<br>・心の状態・やる気レベル<br>・生活リズムの変化',
                    'escape' => false,
                    'type' => 'textarea',
                    'class' => 'form-control',
                    'placeholder' => '体がめざめるサイン、心の状態・やる気レベル、生活リズムの変化'
                ]) ?>
            </div>

            <!-- E. 次の2週間でやりたいワーク -->
            <div class="form-group">
                <?= $this->Form->control('next_two_weeks_work', [
                    'label' => '【E. 次の2週間でやりたいワーク】<br>・新しくチャレンジしたいこと<br>・継続・強化したいこと<br>・改善したい習慣',
                    'escape' => false,
                    'type' => 'textarea',
                    'class' => 'form-control',
                    'placeholder' => '新しくチャレンジしたいこと、継続・強化したいこと、改善したい習慣'
                ]) ?>
            </div>

            <!-- F. 個別コンサルで相談したいこと -->
            <div class="form-group">
                <?= $this->Form->control('consult_topics', [
                    'label' => '【F. 個別コンサルで相談したいこと】<br>・困っていること・悩み<br>・より良くしたいこと<br>・新しく知りたいこと',
                    'escape' => false,
                    'type' => 'textarea',
                    'class' => 'form-control',
                    'placeholder' => '困っていること・悩み、取り除きたいこと、新しく知りたいこと'
                ]) ?>
            </div>

            <div class="form-group" style="text-align: right;">
                <?= $this->Form->button('送信', ['class' => 'btn btn-primary']) ?>
            </div>

            <?= $this->Form->end() ?>

            <p class="text-muted">※回答のコピーが指定アドレスにメール送信されます</p>

        </div>
    </div>
</div>

<script>
</script>
