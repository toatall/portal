<?php
$this->breadcrumbs=array(
	'Пользователи'=>array('user/admin'),
	$model->name=>array('user/view','id'=>$model->id),
	'Изменить',
);

$this->menu=array(
	array('label'=>'Список','url'=>array('user/admin'), 'icon'=>'list'),
	array('label'=>'Создать','url'=>array('user/create'), 'icon'=>'asterisk'),
	array('label'=>'Просмотр','url'=>array('user/view','id'=>$model->id), 'icon'=>'eye-open'),	
);
?>

<h1>Изменить профиль #<?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form',array('model'=>$model,'ldapError'=>$ldapError)); ?>