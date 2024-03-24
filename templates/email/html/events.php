<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
</head>
<body>
    <h1><?= h($type) ?>の予約を行いました</h1>
    <p><strong>予約内容：</strong> <?= h($type) ?></p>
    <p><strong>日時：</strong> <?= h($datetime) ?></p>
    <p>キャンセルは前日までに行うようにお願いします。</p>
</body>
</html>
