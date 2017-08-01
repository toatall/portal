<?php
$this->breadcrumbs=array(
	'Пользователи'=>array('user/admin'),
	'Создать',
);

$this->menu=array(
	array('label'=>'Список','url'=>array('user/admin'), 'icon'=>'list'),
);
?>

<h1>Создать профиль</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model,'ldapError'=>$ldapError)); ?>