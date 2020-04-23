<?php
/* @var $this TestController */
/* @var $model Test */

$this->breadcrumbs=array(
	'Тесты'=>array('admin'),
	$model->name,
);

?>

<?= BsHtml::pageHeader('Тесты', $model->name) ?>

<div class="btn-group">
	<?= BsHtml::link('<i class="fas fa-edit"></i> Изменить', ['/test/test/update', 'id'=>$model->id], ['class'=>'btn btn-primary']) ?>
	<?= BsHtml::link('<i class="fas fa-sitemap"></i> Управление вопросами', ['/test/question/admin', 'idTest'=>$model->id], ['class' => 'btn btn-default']) ?>&nbsp;
	<?= BsHtml::link('<i class="fas fa-edit"></i> Удалить', [], ['class' => 'btn btn-danger', 'confirm' => 'Вы уверены, что хотите удалить?', 'submit' => ['/test/test/delete', 'id'=>$model->id]]) ?>
</div>
<br /><br />
<?php $this->widget('bootstrap.widgets.BsDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'name',
		'date_start',
		'date_end',
		'count_attempt',
		'count_questions',
		'description',
		'date_create',
		'author',
	),
)); ?>
