<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <!-- <title>週報提出通知</title> -->
</head>
<body>
    <h1><?= h($name) ?>さんから週報が提出されました</h1>
    <p>週報 提出日:<?= h($today) ?></p>
    <p><a href="<?= h($reportUrl) ?>">週報を見る</a></p>
</body>
</html>
