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
	
	<?php echo $form->textAreaRow($model,'comment',
		array('class'=>'span5','style'=>'display:none;')); ?>	
	<div class="uneditable-input span5" contenteditable="true" id="<?= $commentDivId ?>" style="height: 100px;"></div>
	
	<?php $this->widget('application.extensions.mySmile.SmileysWidget',array(                    		   	             
	   'textareaId'=>$commentDivId, 
	   'prefix'=>$model->isNewRecord ? 'new' : $model->id,
	 ));?>
	 
   	<br /><br />
   	<?php $this->widget('bootstrap.widgets.TbButton', array(
		'type'=>'primary',
   	    'buttonType'=>'submit',
		'label'=>'Сохранить',                    
    )); ?>
        
    <script type="text/javascript">

		// отправка комментария на сохранение 
    	$('#comment-form').on('submit', function() {
			$('#<?= CHtml::activeId($model, 'comment') ?>').val($('#<?= $commentDivId ?>').html());        	
        	var dataForm = $('#comment-form').serialize();

    		$('#container-comment-form').html('<img src="/images/loading.gif" />');
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
		   	   		ajaxGET('<?= Yii::app()->controller->createUrl('comment/form', ['id'=>$id]) ?>', {}, '#container-comment-form');
		   	   	}
		   	   	else
		   	   	{
					$('#container-comment-form').html(data);
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
