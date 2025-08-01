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
        <?= $this->Form->button('CSV作成', ['class' => 'btn btn-primary']) ?>
      </div>
    </div>
    <?php if (!empty($generatedFiles)): ?>
      <div class="card mt-3 shadow-sm">
          <div class="card-header bg-light">
              <h5 class="mb-0">
                  <i class="fa fa-file-csv text-warning"></i> 生成済みCSVファイル
              </h5>
          </div>
          <div class="card-body">
              <div class="list-group">
                  <?php foreach ($generatedFiles as $file): ?>
                      <div class="list-group-item d-flex justify-content-between align-items-center">
                          <span><i class="fa fa-file-alt text-success"></i> <?= h($file) ?></span>
                          <a href="<?= $this->Url->build(['action' => 'downloadExportFile', $file]) ?>"
                            class="btn btn-outline-primary btn-sm">
                            <i class="fa fa-download"></i> ダウンロード
                          </a>
                      </div>
                  <?php endforeach; ?>
              </div>
          </div>
      </div>
    <?php endif; ?>
    <?= $this->Form->end() ?>
  </div>
</div>
