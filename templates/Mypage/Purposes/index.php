<?php $this->assign('title', '目標設定シート'); ?>

<div class="col-lg-12">
    <div class="card">
        <div class="card-content">
            <div class="nav-tabs-navigation">
                <div class="nav-tabs-wrapper">
                    <ul id="tabs" class="nav nav-tabs">
                        <li class="active"><a href="<?= $this->Url->build(['action' => 'index']); ?>">初回</a></li>
                        <li><a href="<?= $this->Url->build(['action' => 'updates']); ?>">2回目以降</a></li>
                    </ul>
                </div>
            </div>

            <div class="clearfix" style="margin:10px"></div>

            <?= $this->Form->create($purpose, ['id' => 'form']) ?>

            <div class="alert alert-warning" style="color: black;">
                <h5><i class="icon fas fa-exclamation-triangle" style="display: inline;"></i> 注意事項</h5>
                <ul>
                    <li>毎月、1日迄と15日迄にご提出をお願いいたします。</li>
                    <li>回答を送信すると、回答のコピーがご入力いただいたメールアドレスに送信されます。</li>
                    <li>キャリアメールなどでは届かないことがございますので、Gmailなどのパソコンメールをお勧めいたします。</li>
                </ul>
            </div>

            <div class="form-group row">
                <label for="" class="col-sm-2 col-form-label text-md-right">メールアドレス ※</label>
                <div class="col-sm-6">
                    <?= $this->Form->text('email', ['class' => 'form-control', 'placeholder' => '例: example@gmail.com']) ?>
                    <span class="text-danger"><?= $this->Form->error('email') ?></span>
                </div>
            </div>

            <div class="form-group row">
                <label for="" class="col-sm-2 col-form-label text-md-right">氏名（漢字フルネーム） ※</label>
                <div class="col-sm-6">
                    <?= $this->Form->text('name', ['class' => 'form-control', 'placeholder' => '例: 山田 太郎']) ?>
                    <span class="text-danger"><?= $this->Form->error('name') ?></span>
                </div>
            </div>

            <div class="form-group row">
                <label for="" class="col-sm-2 col-form-label text-md-right">現在の悩み(容姿面) ※</label>
                <div class="col-sm-6">
                    <?= $this->Form->textarea('current_concerns_appearance', ['class' => 'form-control', 'placeholder' => '']) ?>
                    <span class="text-danger"><?= $this->Form->error('current_concerns_appearance') ?></span>
                </div>
            </div>

            <div class="form-group row">
                <label for="" class="col-sm-2 col-form-label text-md-right">現在の悩み(機能面) ※</label>
                <div class="col-sm-6">
                    <?= $this->Form->textarea('current_concerns_function', ['class' => 'form-control', 'placeholder' => '']) ?>
                    <span class="text-danger"><?= $this->Form->error('current_concerns_function') ?></span>
                </div>
            </div>

            <div class="form-group row">
                <label for="" class="col-sm-2 col-form-label text-md-right">期間中の目標(容姿面) ※</label>
                <div class="col-sm-6">
                    <?= $this->Form->textarea('goal_appearance', ['class' => 'form-control', 'placeholder' => '']) ?>
                    <span class="text-danger"><?= $this->Form->error('goal_appearance') ?></span>
                </div>
            </div>

            <div class="form-group row">
                <label for="" class="col-sm-2 col-form-label text-md-right">期間中の目標(機能面) ※</label>
                <div class="col-sm-6">
                    <?= $this->Form->textarea('goal_function', ['class' => 'form-control', 'placeholder' => '']) ?>
                    <span class="text-danger"><?= $this->Form->error('goal_function') ?></span>
                </div>
            </div>

            <div class="form-group row">
                <label for="" class="col-sm-2 col-form-label text-md-right">未来のセルフイメージ ※</label>
                <div class="col-sm-6">
                    <?= $this->Form->textarea('future_self_image', ['class' => 'form-control', 'placeholder' => '']) ?>
                    <small class="form-text text-muted">少し長い視点で「なれるかどうかわからないけどこうなれたら最高！」という自分を思い描きましょう！できるだけ具体的にです。 例えば「カフェのオーナーになって、雑誌から特集されました！シンプルな服でも素敵！と言われます。2号店は海外に出店の予定です♪」 「大好きなバレエの衣装のアンバサダーに選ばれました！毎日撮影で大忙し！です」 など、あなたご自身がワクワクするものを書いてみましょう。</small>
                    <span class="text-danger"><?= $this->Form->error('future_self_image') ?></span>
                </div>
            </div>

            <div class="form-group row">
                <label for="" class="col-sm-2 col-form-label text-md-right">次の２週間でやるワーク ※</label>
                <div class="col-sm-6">
                    <?= $this->Form->textarea('next_two_weeks_work', ['class' => 'form-control', 'placeholder' => '']) ?>
                    <small class="form-text text-muted">「会員サイト」のトップページに記載の、今週のワークを読んで、挑戦するワークを記入してください。</small>
                    <span class="text-danger"><?= $this->Form->error('next_two_weeks_work') ?></span>
                </div>
            </div>

            <div class="form-group row">
                <label for="" class="col-sm-2 col-form-label text-md-right">個別コンサルで相談したいこと ※</label>
                <div class="col-sm-6">
                    <?= $this->Form->textarea('consulting_topics', ['class' => 'form-control', 'placeholder' => '']) ?>
                    <span class="text-danger"><?= $this->Form->error('consulting_topics') ?></span>
                </div>
            </div>

            <div class="form-group" style="text-align: right;">
                <?= $this->Form->button('登録', ['class' => 'btn btn-primary']) ?>
            </div>

            <?= $this->Form->end() ?>

        </div>
    </div>
</div>

<script>
    // Optional: Add form validation or interactions here
</script>
