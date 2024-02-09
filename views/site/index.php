<?php
 
use app\widgets\HistoryList\HistoryList;


$this->title = 'Americor Test';
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="site-index">
    
    <?= HistoryList::widget([]) ?>
</div>


