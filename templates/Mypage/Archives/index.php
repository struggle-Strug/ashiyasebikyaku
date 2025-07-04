<?php $this->assign('title', 'アーカイブ'); ?>
<div style="margin: 15px; font-weight: bold; font-size:24px;">
  <a href="<?= $this->Url->build(['action' => 'courses']); ?>">オンライン講座（旧バージョン）</a>
</div>

<?php foreach ($categories as $category): ?>
  <div style="margin: 15px; font-weight: bold; font-size:24px;">
    <a href="<?= $this->Url->build(['action' => 'lessons', '?' => ['category' => $category->id]]) ?>"><?= $category->name ?></a>
  </div>
<?php endforeach; ?>