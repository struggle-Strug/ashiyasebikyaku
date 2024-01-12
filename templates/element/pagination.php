<ul class="pagination pagination-sm m-0 float-right">
    <?= $this->Paginator->prev('< ' . __('')) ?>
    <?= $this->Paginator->numbers() ?>
    <?= $this->Paginator->next(__('') . ' >') ?>
</ul>
