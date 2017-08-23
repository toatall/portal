<?php
/* @var $this DefaultController */

$this->breadcrumbs=array(   
    null
);
?>

<h1>Добро пожаловать в систему управления</h1>


<h3>Выберите, пожалуйуста, раздел сайта</h3>

<div class="alert alert-info">
	Если вы впервые, посетите раздел <b><?= CHtml::link('Справка', ['/admin/default/help']) ?></b>
</div>


<div class="well" id="containerSection" style="background-color:white; margin-top:3px;">
<?php
    
    $tree = Tree::model()->getTreeForMain();    
    if (count($tree)) {
        $this->widget('CTreeView', array(
            'data'=>$tree, 
            'animated'=>300,
            'collapsed'=>true,
        ));
    } else {
?>
    <h4 class="well">Нет данных</h4>
<?php   
    }  
    
?>

</div>

