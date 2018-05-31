<?php
$this->breadcrumbs=array(
    'Голосование'=>array('admin'),
    $modelMain->name=>array('adminQuestion','idMain'=>$modelMain->id),
	'Изменение',
);

$this->menu=array(	
	array('label'=>'Создать вопрос','url'=>array('createQuestion','idMain'=>$model->id_main), 'icon'=>'asterisk'),
	array('label'=>'Просмотр вопроса','url'=>array('viewQuestion','id'=>$model->id), 'icon'=>'eye-open'),
	array('label'=>'Управление вопросами','url'=>array('adminQuestion', 'idMain'=>$model->id_main), 'icon'=>'user'),
);
?>

<h1>Изменить вопрос #<?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_formQuestion',array('model'=>$model, 'modelMain'=>$modelMain)); ?>