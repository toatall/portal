<?php
/* @var $this AnswerController */
/* @var $model TestAnswer */
/* @var $modelQuestion TestQuestion */

$this->breadcrumbs=array(
	'Тесты' => ['/test/test/admin'],
	$modelQuestion->test->name => ['test/test/view', 'id'=>$modelQuestion->id_test],
    'Вопросы' => ['/test/question/admin', 'idTest'=>$modelQuestion->id_test],
    $modelQuestion->name => ['/test/question/view', 'id'=>$modelQuestion->id],
    'Ответы',
);
?>

<?= BsHtml::pageHeader('Ответы', 'Управление') ?>

<?= BsHtml::link('Добавить', ['/test/answer/create', 'idQuestion'=>$modelQuestion->id], ['class'=>'btn btn-primary']) ?>

<?php $this->widget('bootstrap.widgets.BsGridView', array(
	'id'=>'test-answer-grid',
	'dataProvider'=>$model->search(),
	'filter'=>null,
	'columns'=>array(
		'id',
		'id_test_question',
		'name',
		'attach_file',
		'weight',
		'date_create',
		array(
			'class'=>'bootstrap.widgets.BsButtonColumn',
		),
	),
    'pager'=>array(
        'class'=>'bootstrap.widgets.BsPager',
        'size' => BsHtml::BUTTON_SIZE_DEFAULT,
    ),
)); ?>