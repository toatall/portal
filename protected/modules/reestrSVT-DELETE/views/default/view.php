<?php
$this->breadcrumbs=array(
	'Реестр разрешений на приобретение СВТ'=>array('admin'),
	'#'.$model->id,
);

?>

<h1>Просмотр заявки #<?php echo $model->id; ?></h1>

<div class="well">

	<?php 
	
		// Изменить
		if (Yii::app()->user->inRole(['admin', 'reestrSVT_IFNS', 'reestrSVT_UFNS']) 
			&& (in_array($model->status_code, [1,2,3]))):
			echo TbHtml::link('Изменить', ['update','id'=>$model->id], ['class'=>'btn btn-primary']);
		endif;
	?>
	
	<?php 
		// Отметка о получении ФКУ
		if (($model->date_acceptance_fku === null) && Yii::app()->user->inRole(['reestrSVT_FKU','admin'])):
			echo TbHtml::link('Принять заявку ФКУ', ['appearFKU','id'=>$model->id], ['class'=>'btn btn-success']);
		endif;
	?>
	
	<?php 
		// Отправить в УФНС
		if (Yii::app()->user->inRole(['reestrSVT_FKU','admin']) && ($model->date_acceptance_fku !== null) 
			&& ($model->date_appeal_fku_ufns === null) && ($model->date_close === null)):
			echo TbHtml::link('Заявка в УФНС', ['appearUFNS','id'=>$model->id], [
				'class'=>'btn btn-default',
				'data-toggle'=>'modal',
				'data-target'=>'#modalSVT',
				'onclick'=>'
					$("#modalSVTTitle").html("Заявка в УФНС");
					$("#containerModalSVT").html(\'<img src="/images/loading.gif" /> Загрузка...\');
					$.get("'.Yii::app()->createUrl("reestrSVT/default/requestUFNS", ["id"=>$model->id]) .'")
						.done(function(data) { $("#containerModalSVT").html(data); })
						.error(function(e) { $("#containerModalSVT").html(e.status + " " + e.statusText); });',
			]);
					
		endif;
		
	?>
	
	<?php 
		// Отметка о получении УФНС
		if (Yii::app()->user->inRole(['reestrSVT_UFNS','admin']) /*&& ($model->date_appeal_fku_ufns !== null) */
			&& ($model->date_acceptance_ufns === null)):
			echo TbHtml::link('Принять заявку', ['appearUFNS','id'=>$model->id], ['class'=>'btn btn-success']);
		endif;
	?>
	
	
	<?php 
		// Закрыть заявку
		// Администратор может закрыть на любом этапе
		// Пользователь ИФНС тоже на любом этапе
		// Пользователь ФКУ, если он ее принял и не отправил в УФНС
		// Пользователь УФНС может закрыть, если принял ее
		if  (((Yii::app()->user->inRole(['admin', 'reestrSVT_FKU']) && $model->date_acceptance_fku !== null && $model->date_appeal_fku_ufns === null) ||
			(Yii::app()->user->inRole(['admin', 'reestrSVT_UFNS']) && $model->date_acceptance_ufns !== null)) && ($model->date_close === null)):		
			echo TbHtml::link('Закрыть заявку', ['close','id'=>$model->id], [
				'class'=>'btn btn-danger',
				'data-toggle'=>'modal',
				'data-target'=>'#modalSVT',
				'onclick'=>'
					$("#modalSVTTitle").html("Закрыть заявку");
					$("#containerModalSVT").html(\'<img src="/images/loading.gif" /> Загрузка...\');
					$.get("'.Yii::app()->createUrl("reestrSVT/default/close", ["id"=>$model->id]) .'")
						.done(function(data) { $("#containerModalSVT").html(data); })
						.error(function(e) { $("#containerModalSVT").html(e.status + " " + e.statusText); });',
			]);
		endif;
	?>
	
	<?php 
		if ($model->date_close !== null):
	?>
		<h2 class="error">Закрыта</h2>
	<?php 
		endif;
	?>
	
</div>

<?php $this->widget('bootstrap.widgets.TbDetailView',array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		
		'status',
			
		'code_no',
		'date_fault',
		'device_fault',
		'number_inventary',
		'fault_description',
		'date_appeal_fku',
		'number_appeal_fku',

		// Для ФКУ	
		'date_acceptance_fku',
		'work_spares',
		'solved_fku',
		'date_appeal_fku_ufns',

		// Для УФНС	
		'date_acceptance_ufns',
		//'actions_ufns',
			
		'date_close',
		'text_close',
					
		'date_create',
		'date_edit',
		[
			'name' => 'log_change',
			'value' => LogChange::getLog($model->log_change),
			'type' => 'raw',
		],
			
	),
)); ?>

<div class="well">
	<?= TbHtml::link('Назад', ['admin'], ['class'=>'btn btn-primary']) ?>
</div>


<?php $this->beginWidget('bootstrap.widgets.TbModal', array('id'=>'modalSVT')); ?>
	<div class="modal-header">
		<a class="close" data-dismiss="modal">&times;</a>
		<h4 id="modalSVTTitle">Modal title</h4>
	</div>
	
	<div class="modal-body" id="containerModalSVT"></div>
	
	
 <?php $this->endWidget(); ?>
 

 
