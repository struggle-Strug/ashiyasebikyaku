<?php $this->assign('title', '比較画像編集'); ?>

<?= $this->Form->create($comparedImage) ?>
<div class="card">
    <div class="card-body">
        
        <div class="row">
            <div class="col-md-12">
                <input type="hidden" name="staff_id" value="<?= h($staff) ?>">
                <div class="form-group">
                    <?= $this->Form->control('staff_comment', [
                        'type' => 'textarea',
                        'class' => 'form-control',
                        'placeholder' => 'コメント',
                        'label' => false,
                        'id' => 'staff_comment'
                    ]) ?>
                </div>
            </div>  
        </div>

        <div class="row">
            <div class="col-md-12 text-right mt-2">
                <a class="btn btn-outline-secondary" href="<?= $this->Url->build(['action' => 'index', '?' => $param]) ?>">
                    <i class="fas fa-arrow-circle-left"></i> 戻る
                </a>
                <button type="submit" class="btn btn-primary ml-2">
                    <i class="fa fa-save"></i> 保存
                </button>
            </div>
        </div>

        <a href="<?= $comparedImage->image ?>"
            title="<?= h($comparedImage->comment) ?> - <?= date('Y年n月j日', strtotime($comparedImage->created)) ?>"
            data-lightbox="<?= $comparedImage->id ?>">
            <img src="<?= h($comparedImage->image) ?>" width="100%" />
        </a>
    </div>
    <a
</div>
<?= $this->Form->end() ?>
