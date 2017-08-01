<?php
$this->breadcrumbs=array(
	'Структура сайта'=>array('index'),
	'Управление',
);

$this->menu=array(
	array('label'=>'Создать','url'=>array('create'), 'icon'=>'asterisk'),
);

?>

<h1>Структура сайта</h1>


<?php 
    
    
    if (count($tree)) 
    {
        $this->widget('CTreeView', array(
            'data'=>$tree,            
        ));
    } 
    else 
    {
?>
    <h4 class="well">Нет данных</h4>
<?php   
    }
      
?>

