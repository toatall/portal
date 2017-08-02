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
		loadDataComments();
	</script>
<?php 		
	}
?>

<?php if (UserInfo::inst()->userAuth): ?>

<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
		'id'=>'comment-form',
		'enableAjaxValidation'=>false,
		'enableClientValidation'=>true,
		'clientOptions'=>array(
			'validateOnSubmit'=>true,
		),	
)); ?>
	
	<?php 
		$commentDivId = 'commentDiv' . ($model->isNewRecord ? '_new' : '_' . $model->id);
	?>
	
	<?php echo $form->errorSummary($model); ?>
	
	<?php ///echo $form->textFieldRow($model,'username', array('disabled'=>'disabled')); ?>	
	
	<?php echo $form->textAreaRow($model,'comment',
		array('class'=>'span5','style'=>'height:100px;display:none;')); ?>	
	<div class="uneditable-input span5" contenteditable="true" id="<?= $commentDivId ?>" style="height: 200px;"></div>
	
	<?php $this->widget('application.extensions.mySmile.SmileysWidget',array(                    		   	             
	   'textareaId'=>$commentDivId, // the ID of the textarea where we will put the smileys	 
	   'prefix'=>$model->isNewRecord ? 'new' : $model->id,
	 ));?>
   
    <script type="text/javascript">
    	$('#comment-form').on('submit', function() { 
        	
			$('#<?= CHtml::activeId($model, 'comment') ?>').val($('#<?= $commentDivId ?>').html());
        	
        	var dataForm = $('#comment-form').serialize();
    		$('#container-comment-form-<?= $id ?>').html('<img src="/images/loading.gif" />');

			var urlPost = '<?= ($model->isNewRecord ?  Yii::app()->controller->createUrl('comment/form',array('id'=>$id)) : Yii::app()->controller->createUrl('comment/update',array('id'=>$model->id))) ?>';
    		  		
   	   		$.ajax({
   	   			type: 'POST',
				url: urlPost,
				data: dataForm
   	   	   	})
	   	   	.done(function(data){
		   	   	if (data == 'OK')
		   	   	{
		   	   		loadDataComments();
		   	   		$('#modal-comment').modal('hide');			   	   	
		   	   	}
		   	   	else
		   	   	{
					$('#modal-comment-div').html(data);
		   	   	}
			})
			.error(function(jqXHR){
				$('#modal-comment-div').html(jqXHR.statusText);
			});

   	   		return false;
	   	});    

		$(document).ready(function() {			

			// Заголовок
			$('#modal-comment-title').html('<?php if ($model->isNewRecord): ?>Добавить комментарий<?php else: ?>Изменить комментарий<?php endif; ?>');			
			$('#<?= $commentDivId ?>').html($('#<?= CHtml::activeId($model, 'comment') ?>').val());
		});
	   	    
    </script>

<?php $this->endWidget(); ?>

<?php else: ?>	
	<div class="alert alert-warning">
		Вы не можете оставлять комментарии!
	</div>
<?php endif; ?>