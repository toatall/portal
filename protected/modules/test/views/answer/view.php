<?php
/* @var $this AnswerController */
/* @var $model TestAnswer */

$modelQuestion = $model->question;

$this->breadcrumbs=array(
    'Тесты' => ['/test/test/admin'],
    $modelQuestion->test->name => ['test/test/view', 'id'=>$modelQuestion->id_test],
    'Вопросы' => ['/test/question/admin', 'idTest'=>$modelQuestion->id_test],
    $modelQuestion->name => ['/test/question/view', 'id'=>$modelQuestion->id],
    'Ответы' => ['admin', 'idQuestion'=>$modelQuestion->id],
    $model->name,
);

?>

<?= BsHtml::pageHeader('Ответы', $model->name) ?>

<?= BsHtml::link('<i class="fas fa-plus-circle"></i> Добавить новый ответ', ['/test/answer/create', 'idQuestion'=>$modelQuestion->id], ['class' => 'btn btn-default']) ?>&nbsp;
<?= BsHtml::link('<i class="fas fa-edit"></i> Изменить', ['/test/answer/update', 'id'=>$model->id], ['class' => 'btn btn-primary']) ?>&nbsp;
<?= BsHtml::link('<i class="fas fa-edit"></i> Удалить', [], ['class' => 'btn btn-danger', 'confirm' => 'Вы уверены, что хотите удалить?', 'submit' => ['/test/answer/delete', 'id'=>$model->id]]) ?>

<br /><br />
<?php $this->widget('bootstrap.widgets.BsDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'id_test_question',
		'name',
		'attach_file',
		'weight',
		'date_create',
	),
)); ?>
