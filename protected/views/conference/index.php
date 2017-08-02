<?php

$this->pageTitle = $model->typeName;

$this->breadcrumbs = array(
	$model->typeName,
);

?>

<h1><?= $model->typeName ?></h1>


<style type="text/css">
	time.icon
	{
		font-size: 1em; /* change icon size */
		display: block;
		position: relative;
		width: 7em;
		height: 7em;
		background-color: #fff;
		margin: 1em auto;
		border-radius: 0.6em;
		box-shadow: 0 1px 0 #bdbdbd, 0 2px 0 #fff, 0 3px 0 #bdbdbd, 0 4px 0 #fff, 0 5px 0 #bdbdbd, 0 0 0 1px #bdbdbd;
		overflow: hidden;
		-webkit-backface-visibility: hidden;
		-webkit-transform: rotate(0deg) skewY(0deg);
		-webkit-transform-origin: 50% 10%;
		transform-origin: 50% 10%;
	}
	time.icon *
	{
		display: block;
		width: 100%;
		font-size: 0.95em;
		font-weight: bold;
		font-style: normal;
		text-align: center;
	}
	time.icon strong
	{
		position: absolute;
		top: 0;
		padding: 0.4em 0;
		color: #fff;
		background-color: #006d95;/* #fd9f1b;*/
		border-bottom: 1px dashed #006084;/* #f37302;*/
		box-shadow: 0 2px 0 #00425b/*#fd9f1b*/;
	}
	time.icon em
	{
		position: absolute;
		bottom: 0.3em;
		color: #00425b/*#fd9f1b*/;
	}
	time.icon span
	{
		width: 100%;
		font-size: 2.8em;
		letter-spacing: -0.05em;
		padding-top: 1.2em;
		color: #2f2f2f;
	}
	
	.conference-finish
	{
		background-color: #E7FDE1;
	}
	
	.conference-not-begin
	{
		
	}
	
	table th 
	{
		background: white;
	}
	
</style>

<?php $this->widget('bootstrap.widgets.TbGridView',array(
	'id'=>'conference-grid',	
	'dataProvider'=>$model->search(),
	'filter'=>$model,     
	'rowCssClassExpression'=>'date("YmdHi") > date("YmdHi",strtotime($data->date_start)) ? "conference-finish" : "conference-not-begin"',
	'columns'=>array(	
        array(
        	'name'=>'date_start',
        	'type'=>'raw',
        	'value'=>'$data->dateStartFormat',
		),
		array(
			'name'=>'timeStartFormat',
			'type'=>'raw',
			'value'=>'$data->timeStartFormat . ($data->time_start_msk ? " (МСК)" : "")',
			'filter'=>false,
		),				
		'theme',
		//'responsible',
		array(
			'name'=>'members_people',
			'type'=>'raw',
		),
		'place',
		'duration',
		//'members_organization',
		//'date_start',
		'date_create',
			
		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
			'template'=>'{v}',
			'buttons'=>array(
				'v'=>array(
					'label' => 'Подробно',
					'options' => array('class'=>'btn btn-default'),
					'context' => '<i class="icon-eye"></i> Просмотр',
					'url' => function($data) { return Yii::app()->controller->createUrl('conference/view', array('id'=>$data->id)); },
				),				
			),
		),
	),
	'pager'=>array(	
		'class'=>'bootstrap.widgets.TbPager',
		'displayFirstAndLast'=>true,
	),
)); ?>
