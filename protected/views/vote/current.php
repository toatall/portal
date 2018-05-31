<?php

if (count($model)>0) {
    foreach ($model as $m) {
    ?>
    <a href="<?= Yii::app()->createUrl('vote/view', ['id'=>$m['id']]); ?>" class="sw_dlg">"<?= $m['name'] ?>"</a><br />
    <?php 
    }
} else {
    ?>
    Нет доступных голосований
    <?php 
}
?>
