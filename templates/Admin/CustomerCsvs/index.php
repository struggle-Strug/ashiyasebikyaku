<?php $this->assign('title', '顧客カルテCSV出力'); ?>

<div class="card">
  <div class="card-body">
    <h4>【顧客カルテCSV】</h4>

    <?= $this->Form->create(null, [
        'url' => ['controller' => 'CustomerCsvs', 'action' => 'exportCustomerCsv'],
        'type' => 'get',
    ]) ?>

    <div class="row">
      <div class="col-md-4 mt-2">
        <label>測定日</label>
        <div class="input-group">
            <?= $this->Form->date('start_date', ['class' => 'form-control', 'placeholder' => '', 'default' => $param['start_date'] ?? '']) ?>
            <div class="input-group-prepend">
                <span class="input-group-text">
                    〜
                </span>
            </div>
            <?= $this->Form->date('end_date', ['class' => 'form-control', 'placeholder' => '', 'default' => $param['end_date'] ?? '']) ?>
        </div>
    </div>

      <div class="col-md-12 text-right mt-3">
        <?= $this->Form->button('CSV出力', ['class' => 'btn btn-primary']) ?>
      </div>
    </div>

    <?= $this->Form->end() ?>
  </div>
</div>
