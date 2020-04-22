<?php
/* @var $this QuestionController */
/* @var $model TestQuestion */
/* @var $modelTest Test */

$this->breadcrumbs=array(
	'Тесты'=>array('/test/test/admin'),
	$modelTest->name => array('/test/test/view', 'id'=>$modelTest->id),
	'Управление вопросами',
);


Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#test-question-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<?php echo BsHtml::pageHeader('Тесты','Управление вопросами') ?>
<?php if ($this->isManager()): ?>
    <?= BsHtml::link('Добавить вопрос', ['/test/question/create', 'idTest'=>$model->id_test], ['class' => 'btn btn-primary']) ?>
    <br /><br />
<?php endif; ?>


<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('bootstrap.widgets.BsGridView', array(
	'id'=>'test-question-grid',
	'dataProvider'=>$model->search(),
	'filter'=>null,
	'columns'=>array(
		'id',
		'id_test',
		'name',
		'type_question',
		'attach_file',
		'weight',
		'date_create',
        [
            'name' => '',
            'value' => function($model) {
		        /* @var $model TestQuestion */
		        return BsHtml::link('<i class="fas fa-chevron-right"></i> Ответы', ['/test/answer/admin', 'idQuestion'=>$model->id], ['class'=>'btn btn-default']);
            },
            'type' => 'raw',
        ],
		array(
			'class'=>'bootstrap.widgets.BsButtonColumn',
		),
	),
    'pager'=>array(
        'class'=>'bootstrap.widgets.BsPager',
        'size' => BsHtml::BUTTON_SIZE_DEFAULT,
    ),
)); ?>
