<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <!-- <title>コミュニケーションツール書き込み通知</title -->
</head>
<body>
    <p><?= $name ?>さんからコミュニケーションツールに書き込みがありました。</p>
    <p><a href="<?= $url ?>">コミュニケーションツールを見る</a></p>
    <p>コミュニケーションツール　書き込み日時: <?= h($today) ?></p>
</body>
</html>
