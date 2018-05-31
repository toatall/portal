<?php
$this->breadcrumbs=array(
    'Голосование'=>array('admin'),
    $modelMain->name=>array('adminQuestion','idMain'=>$modelMain->id),
	'Создать',
);

$this->menu=array(
    array('label'=>'Управление','url'=>array('adminQuestion', 'idMain'=>$modelMain->id), 'icon'=>'user'),
);
?>

<h1>Создать вопрос для "<?= $modelMain->name ?>"</h1>

<?php echo $this->renderPartial('_formQuestion', array('model'=>$model, 'modelMain'=>$modelMain)); ?>