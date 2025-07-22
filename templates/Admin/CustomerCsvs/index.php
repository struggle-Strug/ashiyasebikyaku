<?php $this->assign('title', '顧客カルテCSV出力'); ?>

<div class="card">
  <div class="card-body">
    <h4>【顧客カルテCSV】</h4>

    <?= $this->Form->create(null, [
        'url' => ['controller' => 'CustomerCsvs', 'action' => 'exportCustomerCsv'],
        'type' => 'get',
        'class' => 'form-inline'
    ]) ?>

    <div class="form-group mb-3">
      <label for="year" class="form-label me-2">受講開始年月:</label>
      <?= $this->Form->control('year', [
        'label' => false,
        'type' => 'number',
        'class' => 'form-control me-2',
        'min' => 2000,
        'max' => date('Y'),
        'placeholder' => '年'
      ]) ?>
      <?= $this->Form->control('month', [
        'label' => false,
        'type' => 'number',
        'class' => 'form-control me-3',
        'min' => 1,
        'max' => 12,
        'placeholder' => '月'
      ]) ?>

      <?= $this->Form->button('出力', ['class' => 'btn btn-primary']) ?>
    </div>

    <?= $this->Form->end() ?>
  </div>
</div>

<script>
  // Optional: validation
</script>
