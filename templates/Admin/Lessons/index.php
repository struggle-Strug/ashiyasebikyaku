<?php $this->assign('title', 'レッスン管理'); ?>

<?= $this->Form->create(null, ['type' => 'get']) ?>
<div class="card mb-3">
  <div class="card-body">
    <div class="row">

      <div class="col-md-4 mt-2">
        <label>開催日時</label>
        <div class="input-group">
          <?= $this->Form->date('event_date', [
            'class' => 'form-control',
            'default' => $param['event_date'] ?? ''
          ]) ?>
        </div>
      </div>

      <div class="col-md-3 mt-2">
        <label>区分</label>
        <div class="input-group">
          <?= $this->Form->select('category', $listOptions, [
            'class' => 'form-control',
            'empty' => '-',
            'default' => $param['category'] ?? ''
          ]) ?>
        </div>
      </div>

      <div class="col-md-3 mt-2">
        <label>講師</label>
        <div class="input-group">
          <?= $this->Form->select('staff_id', $staffs, [
            'class' => 'form-control',
            'empty' => '-',
            'default' => $param['staff_id'] ?? ''
          ]) ?>
        </div>
      </div>

      <div class="col-md-2 d-flex align-items-end justify-content-end mt-2">
        <button type="submit" class="btn btn-primary">
          <i class="fa-solid fa-magnifying-glass"></i> 検索
        </button>
      </div>

    </div>
  </div>
</div>
<?= $this->Form->end() ?>

<style>
.scrollbar::-webkit-scrollbar {
  width: 10px;
  height: 10px;
}
/* スクロールバーの形を設定する */
.scrollbar-thumb-rounded::-webkit-scrollbar-thumb {
  border-radius: 0.25rem;
}
/* スクロールバーの色を設定する */
.scrollbar-thumb-black::-webkit-scrollbar-thumb {
  --bg-opacity: 1;
  background-color: rgba(0, 0, 0, 0.5);
}

.uniform-image {
  width: 100px;
  display: block;
  margin: 0;
  padding: 0;
  border: none;
}
</style>

<?= $this->Form->create(null, ['type' => 'file']) ?>
<div class="card mb-3">
    <div class="card-body">
        <div class="row">
        <div class="form-group">
            <label for="csv-file">CSVファイルを選択</label>
            <?= $this->Form->file('csv_file', ['class' => 'form-control-file', 'accept' => '.csv', 'required' => true]) ?>
        </div>
            <div class="col-md-6 mt-4">
                <button type="submit" class="btn btn-success">CSV読み込み</button>
            </div>
        </div>
    </div>
</div>
<?= $this->Form->end() ?>

<?php if (!empty($csvData)): ?>
<div class="card">
    <div class="card-body">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th nowrap>開催日時</th>
                    <th nowrap>区分</th>
                    <th nowrap>担当講師</th>
                    <th nowrap>会員参加者</th>
                    <th style="width:200px">指導履歴登録</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($csvData as $row): ?>
                    <tr>
                        <td><?= h(date('Y-m-d', strtotime($row['event_date']))) ?></td>
                        <td><?= h($row['category']) ?></td>
                        <td><?= h($row['staff']) ?></td>
                        <td><?= h($row['user']) ?></td>
                        <td>
                            <a href="<?= $this->Url->build([
                                'action' => 'addConsult',
                                '?' => [
                                    'name' => $row['user'],
                                    'staff' => $row['staff'],
                                    'event_date' => date('Y-m-d', strtotime($row['event_date'])),
                                ]
                            ]); ?>" class="btn btn-outline-primary btn-sm save-scroll-link">登録</a>
                        </td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>
</div>
<?php endif; ?>
<!-- /.card -->
<?= $this->Form->end() ?>
<script>
</script>
