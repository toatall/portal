<?php
/* @var $this SiteController */
/* @var $model LoginForm */
/* @var $form CActiveForm  */

$this->pageTitle=Yii::app()->name . ' - Вход';
$this->breadcrumbs=array(
	'Вход',
);
?>

<div class="well" style="margin:0 30% 0 30%;">
<h1>Вход</h1>
<?php if (Yii::app()->user->isGuest): ?>

<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'login-form',
	'enableClientValidation'=>false,
	'clientOptions'=>array(
		'validateOnSubmit'=>false,
	),
)); ?>

	<p class="help-block">Поля обозначенные <span class="required">*</span> обязательны для заполнения.</p>
    
	<div class="">
		<?php echo $form->labelEx($model,'username'); ?>
		<?php echo $form->textField($model,'username'); ?>
		<?php echo $form->error($model,'username'); ?>
	</div>

	<div class="">
		<?php echo $form->labelEx($model,'password'); ?>
		<?php echo $form->passwordField($model,'password'); ?>
		<?php echo $form->error($model,'password'); ?>		
	</div>
        
	<div class="checkbox">
		<?php echo $form->checkBox($model,'rememberMe'); ?>
		<?php echo $form->label($model,'rememberMe'); ?>
		<?php echo $form->error($model,'rememberMe'); ?>
	</div>

	<div class="buttons">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>'Вход',
            'htmlOptions'=>array('style'=>'width:100px'),
		)); ?>
		
				
	</div>
	


</div><!-- form -->



<?php else: ?>
<?php $this->redirect(array('index')); ?>
<?php endif; ?>
</div>

<div style="margin:0 30% 0 30%;">
	<h2 style="text-align:center;">ИЛИ</h2>
</div>

<div class="well" style="margin:0 30% 0 30%;">
	
	<img src="/images/AD.png" />
	
	<?php $this->widget('bootstrap.widgets.TbButton', 
		array(			
			'type'=>'success',			
			'label'=>'Windows-аутентификация',
            'htmlOptions'=>array(
            	'style'=>'width:200px',
            	'id'=>'windows_auth',            	
            ),
		)); ?>
		<script type="text/javascript">
					
		
			$('#windows_auth').on('click', function() {

				$("#error-win-auth").hide();
				$.get("/winAuth/").done(function(data) {
					if (data == "OK")
					{						
						$('#login-form').submit();
					}
					else
					{
						$("#error-win-auth").show();
					}
				});	
				
			});
			
		</script>
	<div class="alert alert-error" id="error-win-auth" style="display:none; margin-top:20px;">
		<button type="button" class="close" onclick="$('#error-win-auth').hide();">&times;</button>
		<h4>Ошибка</h4>
		<div class="alert-block">
			<p>Windows-аутентификация не удалась!</p>
		</div>
	</div>
	
	<div style="margin-top:20px;">
	<?php echo $form->error($model,'userWindows'); ?>
	</div>
	
</div>

<?php $this->endWidget(); ?>
