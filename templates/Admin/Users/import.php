<?php $this->assign('title', 'CSVインポート'); ?>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">CSVインポート</h3>
    </div>
    <div class="card-body">
        <div class="alert alert-info">
            <h5><i class="icon fas fa-info"></i> CSVインポートについて</h5>
            <ul>
                <li>1行目はヘッダー行として扱われます。</li>
                <li>必須項目：メールアドレス、姓、姓（よみがな）、名、名（よみがな）、携帯番号、生年月日、お住まい（郵便番号）、お住まい（住所）、申し込みプログラム</li>
                <li>プログラム開始日、担当講師、男女、パスワードの列は以下のサンプルを参考に追加してください。</li>
                <li>生年月日形式：YYYY/MM/DD</li>
                <li>プログラム開始日形式：YYYY/MM/DD</li>
            </ul>
        </div>
        
        <div class="alert alert-warning">
            <h5><i class="icon fas fa-exclamation-triangle"></i> 注意事項</h5>
            <ul>
                <li>メールアドレスは重複できません。</li>
                <li>パスワードは「来月の年月の文字列」で設定されます。（例：202508）</li>
                <li>郵便番号は7桁の数字で入力してください。</li>
                <li>性別は「男性」か「女性」で入力してください。</li>
                <li>プログラム開始日が空白の場合は来月の1日になります。</li>
                <li>担当講師が空白の場合、または存在しない講師の場合はSHOCOさんになります。</li>
                <li>性別が空白の場合は女性になります。</li>
                <li>パスワードが空白の場合は来月の年月の文字列になります。</li>
            </ul>
        </div>
        
        <?= $this->Form->create(null, ['type' => 'file']) ?>
        <div class="form-group">
            <label for="csv-file">CSVファイルを選択</label>
            <?= $this->Form->file('csv_file', ['class' => 'form-control-file', 'accept' => '.csv', 'required' => true]) ?>
        </div>
        
        <div class="mt-3">
            <a href="<?= $this->Url->build(['action' => 'index', '?' => $param]) ?>" class="btn btn-secondary">戻る</a>
            <a href="<?= $this->Url->build(['action' => 'downloadTemplate']) ?>" class="btn btn-success">
                <i class="fas fa-download"></i> テンプレートダウンロード
            </a>
            <button type="submit" class="btn btn-primary">インポート実行</button>
        </div>
        <?= $this->Form->end() ?>
        
        <div class="mt-4">
            <h5>CSVファイルのサンプル</h5>
            <div class="table-responsive">
                <table class="table table-bordered table-sm">
                    <thead>
                        <tr>
                            <th>タイムスタンプ</th>
                            <th>メールアドレス<span class="text-danger">（必須）</span></th>
                            <th>姓<span class="text-danger">（必須）</span></th>
                            <th>姓（よみがな）<span class="text-danger">（必須）</span></th>
                            <th>名<span class="text-danger">（必須）</span></th>
                            <th>名（よみがな）<span class="text-danger">（必須）</span></th>
                            <th>携帯番号<span class="text-danger">（必須）</span></th>
                            <th>生年月日<span class="text-danger">（必須）</span></th>
                            <th>お住まい（郵便番号）<span class="text-danger">（必須）</span></th>
                            <th>お住まい（住所）<span class="text-danger">（必須）</span></th>
                            <th>申し込みプログラム<span class="text-danger">（必須）</span></th>
                            <th>プログラム開始日</th>
                            <th>担当講師</th>
                            <th>男女（性別）</th>
                            <th>パスワード</th>
                            <th>プログラム終了日</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                          <td>2025/06/20 11:27:40</td>
                          <td>test1@example.com</td>
                          <td>田中</td>
                          <td>たなか</td>
                          <td>太郎</td>
                          <td>たろう</td>
                          <td>09012345678</td>
                          <td>1990/01/01</td>
                          <td>1000000</td>
                          <td>東京都千代田区1-1-1</td>
                          <td>エバーコース</td>
                          <td>2025/08/01</td>
                          <td>SHOCO</td>
                          <td>男性</td>
                          <td>202508</td>
                          <td>2025/12/01</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>