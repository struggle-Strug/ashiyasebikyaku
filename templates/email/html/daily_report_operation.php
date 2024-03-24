<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
</head>
<body>
    <h1><?= h($name) ?>さんから日報が提出されました</h1>
    <p>日報 提出日:<?= h($today) ?></p>
    <p><a href="<?= h($reportUrl) ?>">日報を見る</a></p>
</body>
</html>