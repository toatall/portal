<?php
$this->breadcrumbs=array(
	'Реестр разрешений на приобретение СВТ'=>array('admin'),
	'Управление',
);

?>

<style type="text/css">
	thead th { background: none; }
</style>


<h1>Реестр разрешений на приобретение СВТ</h1>

<hr />

<?php if (Yii::app()->user->inRole(['admin', 'reestrSVT_IFNS', 'reestrSVT_UFNS'])): ?>
	<?= CHtml::link('Добавить', ['create'], ['class'=>'btn btn-primary']) ?>
<?php endif; ?>


<?php $this->widget('bootstrap.widgets.TbGridView',array(
	'id'=>'reestr-svt-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'rowCssClassExpression' => '$data->color',
	'columns'=>array(
			
		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
			'template'=>'{views}',
			'buttons' => array(
				'views' => array(
					'label'=>'Просмотр',
					'options'=>array('class'=>'btn btn-primary'),
					'url'=>'Yii::app()->createUrl("reestrSVT/default/view", array("id"=>$data->id))',
				),
					
			),
		),
			
		'id',
		
		array(
			'name' => 'code_no',
			'filter' => CHtml::listData(Organization::model()->findAll(), 'code', 'code'),
		),
		'date_fault',
		'device_fault',
		//'number_inventary',
		'fault_description',
		'date_appeal_fku_ufns',
		'number_appeal_fku',
			
		'date_acceptance_fku',
		'work_spares',
		'solved_fku',
		'date_appeal_fku_ufns',
				
		'date_acceptance_ufns',
		//'actions_ufns',		
				
		//'date_close',
		'text_close',
		
		[
			'name'=>'status',
			'filter'=>false,
		],
			
		/*
		'date_create',
		'date_edit',
		'log_change',
		*/

	),
)); ?>
