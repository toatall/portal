<style type="text/css">
	.uneditable-input {
		cursor: auto;
		color: #555;
	}
</style>

<?php 
	foreach (Yii::app()->user->getFlashes() as $key=>$message)
	{
?>
	<div class="alert alert-<?= $key ?>"><?= $message ?></div>
	<script type="text/javascript">
		loadData('<?= Yii::app()->controller->createUrl('comment/index',array('id'=>$id)) ?>', '#container-comments-<?= $id ?>');	
	</script>
<?php 		
	}
?>

<h4>Добавить комментарий</h4>

<?php if (UserInfo::inst()->userAuth): ?>

<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
		'id'=>'comment-form',
		'enableAjaxValidation'=>false,
		'enableClientValidation'=>true,
		'clientOptions'=>array(
			'validateOnSubmit'=>true,
		),	
)); ?>
	
	<?php echo $form->errorSummary($model); ?>
	
	<?php echo $form->textFieldRow($model,'username', array('disabled'=>'disabled')); ?>	
	
	<?php echo $form->textAreaRow($model,'comment',
		array('class'=>'span5','style'=>'height:100px;display:none;')); ?>	
	<div class="uneditable-input span5" contenteditable="true" id="commentDiv" style="height: 200px;">		
	</div>
	<?php $this->widget('application.extensions.mySmile.SmileysWidget',array(                    		   	             
	   'textareaId'=>'commentDiv', // the ID of the textarea where we will put the smileys	  
	 ));?>
   
    <script type="text/javascript">
    	$('#comment-form').on('submit', function() { 
			$('#<?= CHtml::activeId($model, 'comment') ?>').val($('#commentDiv').html());
        	
        	var dataForm = $('#comment-form').serialize();
    		$('#container-comment-form-<?= $id ?>').html('<img src="/images/loading.gif" />');  		
   	   		$.ajax({
   	   			type: 'POST',
				url: '<?= Yii::app()->controller->createUrl('comment/form',array('id'=>$id)) ?>',
				data: dataForm
   	   	   	})
	   	   	.done(function(data){
				$('#container-comment-form-<?= $id ?>').html(data);
			})
			.error(function(jqXHR){
				$('#container-comment-form-<?= $id ?>').html(jqXHR.statusText);
			});

   	   		return false;
	   	});        
    </script>
    
	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>'Отправить',			
		)); ?>
	</div>

<?php $this->endWidget(); ?>

<?php else: ?>	
	<div class="alert alert-warning">
		Вы не можете оставлять комментарии!
	</div>
<?php endif; ?>