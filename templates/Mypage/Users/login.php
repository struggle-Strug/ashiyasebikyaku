<?= $this->Flash->render() ?>

<div class="row">
    <div class="col-md-6 col-md-offset-3" style="text-align:center;">
        <img src="/img/logo.png" style="width:80%">
    </div>
    <div class="col-md-6 col-sm-6 col-md-offset-3 col-sm-offset-3">
        <?= $this->Form->create(null, ['class' => 'form-horizontal']) ?>
            <div class="card" data-background-color="light-pink">
                <div class="card-header">
                </div>
                <div class="card-content">
                    <div class="form-group">
                        <label class="col-md-4 control-label">メールアドレス</label>
                        <div class="col-md-7">
                            <?= $this->Form->email('email', ['class' => 'form-control', 'required' => true, 'autofocus' => true]) ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-4 control-label">パスワード</label>
                        <div class="col-md-7">
                            <?= $this->Form->password('password', ['class' => 'form-control', 'required' => true]) ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-7 col-md-offset-4">
                            <div class="form-check" style="margin-top: 6px; display: flex; align-items: center; gap: 8px;">
                                <input class="form-check-input" type="checkbox" name="remember_me" value="1" id="rememberMe"
                                    style="width: 17px; height: 17px;">
                                <label class="form-check-label" for="rememberMe" style="font-weight: normal; margin-top: 6px;">
                                    ログイン状態を保存
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="text-center">
                        <?= $this->Form->button(__('ログイン'), ['class' => 'btn btn-info btn-fill']) ?>
                    </div>
                </div>
            </div>
            <!-- end card -->
        <?= $this->Form->end() ?>
    </div>
</div>

