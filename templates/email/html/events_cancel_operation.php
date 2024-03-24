<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
</head>
<body>
    <h1><?= h($name) ?>さんから<?= h($type) ?>の予約がキャンセルされました</h1>
    <p><strong>キャンセル内容：</strong> <?= h($type) ?></p>
    <p><strong>予約者：</strong> <?= h($name) ?></p>
    <p><strong>日時：</strong> <?= h($datetime) ?></p>
</body>
</html>
