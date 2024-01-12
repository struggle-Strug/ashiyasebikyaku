<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="utf-8">
    <title><?= $pageTitle ?></title>

    <!-- Reset CSS -->
    <link rel="stylesheet" href="<?= $this->Url->build("/") ?>css/normalize.min.css">
    <!-- Load paper.css -->
    <link rel="stylesheet" href="<?= $this->Url->build("/") ?>css/paper.css">

    <style>
      @page {
        size: <?= $pageSize ?>;
        margin: 0;
      }
      @media print{
        body{
          padding: 0mm;
          margin: 0mm;
        }
        div{
          padding: 0mm;
          margin: 0mm;
          width:100%;
        }
      }

      body {
        font-family:'メイリオ', 'Meiryo','ＭＳ ゴシック','Hiragino Kaku Gothic ProN','ヒラギノ角ゴ ProN W3',sans-serif;
        font-size: 12px;
        line-height: 1.2em;
        margin: 5px;
        padding: 5px;
      }
      p {
        line-height: 0.5em;
      }
      div {
        line-height: 1.4em;
      }

      td {
        font-size: 12px;
      }
      th {
        font-size: 12px;
      }
      .small {
        font-size: 8px;
      }
      .large {
        font-size: 14px;
      }
      .x-large {
        font-size: 18px;
      }
      h1 {
        font-size: 16px;
        margin: 0;
        line-height: 1.4em;
      }
      h2 {
        font-size: 16px;
        margin: 0;
        line-height: 1.4em;
      }
      h3 {
        font-size: 14px;
        margin: 0;
        line-height: 1.4em;
      }

      table {
        width: 100%;
        /* border: 1px solid #000; */
        border: 0;
        background-color: #fff;
        border-collapse:collapse;
      }

      .table-border {
        border-collapse: collapse;
        border: 1px solid #000;
        line-height: 1em;
      }
      .table-border-td {
        border-collapse: collapse;
        border: 1px solid #000;
        line-height: 1em;
        padding: 5px;
      }

      .table-border .active{
        background-color: #000;
        color: #fff;
      }
      .table-border .number{
        font-family: "Hiragino Sans W3", "Hiragino Kaku Gothic ProN", "ヒラギノ角ゴ ProN W3", "メイリオ", Meiryo, "ＭＳ Ｐゴシック", "MS PGothic", sans-serif;
      }

      .td-small {
        line-height: 0.9em;
        padding: 1px;
      }

      .underline {
        border-bottom: 1px solid #000;
      }

      .select-text {
        border: 1px solid #000;
        border-radius: 10px;
        background-color:#fff;
      }

      .td-bold {
        border: 2px solid #000;
      }
    </style>
    <!-- jQuery -->
    <script src="<?= $this->Url->build("/") ?>js/jquery.min.js"></script>
  </head>
  <body class="<?= $pageSize ?>">
    <?= $this->fetch('content') ?>
  </body>
</html>
