    <?= $this->Flash->render() ?>

    <?= $this->Form->create() ?>
    <div class="input-group mb-3">
        <?= $this->Form->text('loginid', ['class' => 'form-control', 'placeholder' => 'ログインID', 'required' => true, 'autofocus' => true]) ?>
        <div class="input-group-append">
        <div class="input-group-text">
            <span class="fas fa-id-badge"></span>
        </div>
        </div>
    </div>
    <div class="input-group mb-3">
        <?= $this->Form->password('password', ['class' => 'form-control', 'placeholder' => 'パスワード', 'required' => true]) ?>
        <div class="input-group-append">
        <div class="input-group-text">
            <span class="fas fa-lock"></span>
        </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
        <?= $this->Form->button(__('ログイン'), ['class' => 'btn btn-primary']) ?>
        </div>
        <!-- /.col -->
    </div>
    <?= $this->Form->end() ?>
