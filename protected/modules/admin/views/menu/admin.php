<?php
$this->breadcrumbs=array(
	'Меню'=>array('index'),
	'Управление',
);

?>

<h1>Управление Меню</h1>
<br /><br />

<div class="well" style="background-color: white;">
    <h1>Верхнее меню</h1>
    <hr />

    <?= BsHtml::link('Создать', $this->createUrl('create', ['typeMenu' => 1]), ['class' => 'btn btn-primary']) ?>

    <?php 
    
    $tree = Menu::model()->getTree(0,0,1);
    if (count($tree)) {
        $this->widget('CTreeView', array(
            'data'=>$tree,
        ));
    } else {
    ?>
        <h4 class="well">Нет данных</h4>
    <?php   
        }            
          
    ?>   
</div>

<div class="well" style="background-color: white;">
    <h1>Левое меню</h1>
    <hr />

    <?= BsHtml::link('Создать', $this->createUrl('create', ['typeMenu' => 2]), ['class' => 'btn btn-primary']) ?>
    
    
    <?php 
    $tree = Menu::model()->getTree(0,0,2);
    if (count($tree)) {
        $this->widget('CTreeView', array(
            'data'=>$tree,            
        ));
    } else {
    ?>
        <h4 class="well">Нет данных</h4>
    <?php   
    }         
    ?>    
</div>
