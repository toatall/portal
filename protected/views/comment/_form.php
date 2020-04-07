<?php 
    /* @var $this CController */
    /* @var $model Comment */
    /* @var $id int */

    /* @var $urlForm string */
    $urlForm = isset($urlForm) ? $urlForm : Yii::app()->controller->createUrl('comment/form',array('id'=>$id));
    
    /* @var $urlUpdate string */
    $urlUpdate = isset($urlUpdate) ? $urlUpdate : Yii::app()->controller->createUrl('comment/update',array('id'=>$model->id));
    
?>
<div id="container-comment-form">
<?php $form=$this->beginWidget('bootstrap.widgets.BsActiveForm',array(
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
	
	<?php echo $form->textArea($model,'comment',
		array('class'=>'form-control','style'=>'display:none;')); ?>
	<div class="uneditable-input form-control" contenteditable="true" id="<?= $commentDivId ?>" style="height: 100px; margin-bottom: 5px;"></div>

	<?php $this->widget('application.extensions.mySmile.SmileysWidget',array(                    		   	             
	    'textareaId'=>$commentDivId,
	    'prefix'=>$model->isNewRecord ? 'new' : $model->id,
	 ));?>

   	<br /><br />
    <?= BsHtml::submitButton('Сохранить', ['class'=>'btn btn-primary']) ?>

    <script type="text/javascript">

		// отправка комментария на сохранение
    	$('#comment-form').on('submit', function() {
			$('#<?= CHtml::activeId($model, 'comment') ?>').val($('#<?= $commentDivId ?>').html());        	
        	var dataForm = $('#comment-form').serialize();

    		$('#container-comment-form').html('<img src="/images/loading.gif" />');
			var urlPost = '<?= ($model->isNewRecord ?  $urlForm : $urlUpdate) ?>';

   	   		$.ajax({
   	   			type: 'POST',
				url: urlPost,
				data: dataForm
   	   	   	})
	   	   	.done(function(data){
		   	   	if (data == 'OK')
		   	   	{
		   	   		loadDataComments();
                    $('.modal').modal('hide');
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
</div>