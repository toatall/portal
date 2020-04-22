<?php
/* @var $this TestController */
/* @var $model Test */

$this->breadcrumbs=array(
	'Тесты'=>array('admin'),
	'Просмотр',
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#test-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<?php echo BsHtml::pageHeader('Тесты','Просмотр тестов') ?>

<?php if ($this->isManager()): ?>
    <?= BsHtml::link('Добавить тест', ['/test/test/create'], ['class' => 'btn btn-primary']) ?><br /><br />
<?php endif; ?>

<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('bootstrap.widgets.BsGridView', array(
	'id'=>'test-grid',
	'dataProvider'=>$model->search(),
	'filter'=>null,//$model,
	'columns'=>array(
		'id',
		'name',
		//'date_start',
		//'date_end',
		//'count_attempt',
		//'count_questions',
		//'description',
		'date_create',
		'author',
		array(
			'class'=>'CButtonColumn',
		),
	),
    'pager'=>array(
        'class'=>'bootstrap.widgets.BsPager',
        'size' => BsHtml::BUTTON_SIZE_DEFAULT,
    ),
)); ?>
