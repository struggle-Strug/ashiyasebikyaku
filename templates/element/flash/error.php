<?php
/**
 * @var \App\View\AppView $this
 * @var array $params
 * @var string $message
 */
if (!isset($params['escape']) || $params['escape'] !== false) {
    $message = h($message);
}
?>
<div class="alert alert-danger alert-dismissible">
    <!-- <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> -->
    <?= $message ?>
</div>
