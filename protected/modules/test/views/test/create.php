<?php
/* @var $this TestController */
/* @var $model Test */

$this->breadcrumbs=array(
	'Тесты'=>array('admin'),
	'Создание теста',
);

?>
<?php echo BsHtml::pageHeader('Тесты','Создание теста') ?>

<?php $this->renderPartial('_form', array('model'=>$model), false, true); ?>