<?php
/* @var $this QuestionController */
/* @var $model TestQuestion */

$this->breadcrumbs=array(
    'Тесты' => array('/test/test/admin'),
	$model->test->name => array('/test/test/view','id'=>$model->id_test),
	'Вопросы' => array('admin','idTest'=>$model->id_test),
	$model->name,
);

?>

<?php echo BsHtml::pageHeader($model->name,'Просмотр') ?>

<?= BsHtml::link('<i class="fas fa-arrow-circle-left"></i> Назад к вопросам', ['admin','idTest'=>$model->id_test], ['class' => 'btn btn-default']) ?>&nbsp&nbsp;
<?= BsHtml::link('<i class="fas fa-sitemap"></i> Управление ответами', ['/test/answer/admin', 'idQuestion'=>$model->id], ['class' => 'btn btn-default']) ?>&nbsp;
<?= BsHtml::link('<i class="fas fa-edit"></i> Изменить', ['/test/question/update', 'id'=>$model->id], ['class' => 'btn btn-primary']) ?>&nbsp;
<?= BsHtml::link('<i class="fas fa-edit"></i> Удалить', [], ['class' => 'btn btn-danger', 'confirm' => 'Вы уверены, что хотите удалить?', 'submit' => ['/test/question/delete', 'id'=>$model->id]]) ?>
<br /><br />

<?php $this->widget('bootstrap.widgets.BsDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'id_test',
		'name',
		'type_question',
		'attach_file',
		[
            'name' => 'attach_file',
            'type' => 'raw',
            'value' => function ($data) {
		        /* @var $data TestQuestion */
                if ($data->attach_file) {
                    return BsHtml::link($data->attach_file, $data->getAttachUrl());
                }
            },
        ],
		'weight',
		'date_create',
        'userModel.fio:text:Автор',
	),
)); ?>
