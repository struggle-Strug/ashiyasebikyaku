<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
</head>
<body>
    <p><?= h($name) ?>さんから<?= h($type) ?>の予約が入りました。</p>
    <p><strong>予約内容：</strong> <?= h($type) ?></p>
    <p><strong>予約者：</strong> <?= h($name) ?></p>
    <p><strong>日時：</strong> <?= h($datetime) ?></p>
    <p><?= h($type) ?>予約 予約日時:<?= h($today) ?></p>
</body>
</html>