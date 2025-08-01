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
    <div class="card mt-3 shadow-sm">
      <div class="card-header bg-light">
          <h5 class="mb-0">
              <i class="fa fa-file-csv text-warning"></i> 生成済みCSVファイル
          </h5>
      </div>
      <div class="card-body">
          <?php if (empty($files)): ?>
              <div class="alert alert-info mb-0">
                  <i class="fa fa-info-circle"></i> 現在、ダウンロード可能なCSVはありません。
              </div>
          <?php else: ?>
              <div class="list-group">
                  <?php foreach ($files as $file): ?>
                      <div class="list-group-item d-flex justify-content-between align-items-center">
                          <div>
                              <i class="fa fa-file-alt text-success"></i>
                              <span class="ml-2"><?= h($file) ?></span>
                          </div>
                          <a href="<?= $this->Url->build(['action' => 'downloadExportFile', $file]) ?>"
                            class="btn btn-outline-primary btn-sm">
                              <i class="fa fa-download"></i> ダウンロード
                          </a>
                      </div>
                  <?php endforeach; ?>
              </div>
          <?php endif; ?>
      </div>
  </div>


    <?= $this->Form->end() ?>
  </div>
</div>
