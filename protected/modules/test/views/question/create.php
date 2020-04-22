<?php
/* @var $this TestQuestionController */
/* @var $model TestQuestion */
/* @var $modelTest Test */

$this->breadcrumbs=array(
	'Тесты' => array('/test/test/admin'),
	$modelTest->name => array('/test/test/view', 'id'=>$modelTest->id),
	'Создание вопроса',
);

?>

<?php echo BsHtml::pageHeader($modelTest->name,'Создание вопроса') ?>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>