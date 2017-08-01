<?php
$this->breadcrumbs=array(
	'Меню'=>array('index'),
	'Управление',
);
/*
$this->menu=array(
	array('label'=>'Создать','url'=>array('create'), 'icon'=>'asterisk'),
);*/

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('menu-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Управление Меню</h1>
<br /><br />

<div class="well" style="background-color: white;">
    <h1>Верхнее меню</h1>
    <hr />
    
    <?php 
        $this->widget('bootstrap.widgets.TbButton', array(
			'url'=>array('create','typeMenu'=>1),
			'type'=>'primary',
			'label'=>'Создать',
        )); 
    ?>
    
    
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
    
    <?php 
        $this->widget('bootstrap.widgets.TbButton', array(
			'url'=>array('create','typeMenu'=>2),
			'type'=>'primary',
			'label'=>'Создать',
        )); 
    ?>
    
    <p>
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
    </p>
</div>


<?php /*$this->widget('bootstrap.widgets.TbGridView',array(
	'id'=>'menu-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'id_parent',
		'type_menu',
		'name',
		'link',
		'submenu_code',
	
		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
		),
	),
)); */?>
