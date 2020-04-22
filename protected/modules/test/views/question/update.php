<?php
/* @var $this TestQuestionController */
/* @var $model TestQuestion */

$this->breadcrumbs=array(
    'Тесты' => array('/test/test/admin'),
    $model->test->name => array('/test/question/admin', 'idTest'=>$model->id_test),
	'Изменение вопроса',
);

?>

<?php echo BsHtml::pageHeader($model->name,'Изменение вопроса') ?>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>