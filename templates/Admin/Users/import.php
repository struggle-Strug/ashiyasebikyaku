<?php $this->assign('title', 'CSVインポート'); ?>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">CSVインポート</h3>
    </div>
    <div class="card-body">
        <div class="alert alert-info">
            <h5><i class="icon fas fa-info"></i> CSVインポートについて</h5>
            <ul>
                <li>CSVファイルの文字コードはUTF-8（BOM付き）で保存してください。</li>
                <li>1行目はヘッダー行として扱われます。</li>
                <li>必須項目：会員番号、メールアドレス、姓、名、利用開始日時</li>
                <li>日付形式：YYYY-MM-DD または YYYY/MM/DD</li>
                <li>日時形式：YYYY-MM-DD HH:MM:SS または YYYY-MM-DD HH:MM</li>
            </ul>
        </div>
        
        <div class="alert alert-warning">
            <h5><i class="icon fas fa-exclamation-triangle"></i> 注意事項</h5>
            <ul>
                <li>メールアドレスと会員番号は重複できません。</li>
                <li>パスワードは「password123」で設定されます。</li>
                <li>担当講師は講師名で指定してください。</li>
            </ul>
        </div>
        
        <?= $this->Form->create(null, ['type' => 'file']) ?>
        <div class="form-group">
            <label for="csv-file">CSVファイルを選択</label>
            <?= $this->Form->file('csv_file', ['class' => 'form-control-file', 'accept' => '.csv', 'required' => true]) ?>
        </div>
        
        <div class="mt-3">
            <a href="<?= $this->Url->build(['action' => 'index', '?' => $param]) ?>" class="btn btn-secondary">戻る</a>
            <button type="submit" class="btn btn-primary">インポート実行</button>
        </div>
        <?= $this->Form->end() ?>
        
        <div class="mt-4">
            <h5>CSVファイルのサンプル</h5>
            <div class="table-responsive">
                <table class="table table-bordered table-sm">
                    <thead>
                        <tr>
                            <th>メールアドレス</th>
                            <th>Q1.姓</th>
                            <th>Q2.名</th>
                            <th>Q3.電話番号</th>
                            <th>Q4.生年月日</th>
                            <th>Q5.お住まい</th>
                            <th>Q6.備考</th>
                            <th>利用開始日時</th>
                            <th>パスワード</th>
                            <th>担当講師</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                          <td>test1@example.com</td>
                          <td>田中</td>
                          <td>太郎</td>
                          <td>090-1234-5678</td>
                          <td>1990-01-01</td>
                          <td>東京都</td>
                          <td>サンプルデータ</td>
                          <td>2024-01-01 10:00:00</td>
                          <td>password123</td>
                          <td>山田先生</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>