<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'user-form',
	'enableAjaxValidation'=>false,
    'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
)); ?>
	        
    <p class="help-block">Поля обозначенные <span class="required">*</span> обязательны для заполнения.</p>
    
    <div class="well">
            
	    <?php if (!$model->isNewRecord): ?>
	    <div class="alert in alert-block fade alert-info">
	        <a class="close" data-dismiss="alert">x</a>
	        <strong>Информация</strong><br />
	        Для изменения данных за исключением пароля, поля 
	        &laquo;  <?php echo $model->getAttributeLabel('password'); ?>&raquo; 
	        и &laquo; <?php echo $model->getAttributeLabel('password_repeat'); ?> &raquo; 
	        следует оставить пустыми.
	    </div>
	    <?php endif; ?>
	    
	    <?php echo $form->errorSummary($model); ?>
	    
		<?php /*echo $form->textFieldRow($model,'username',array('class'=>'span5','maxlength'=>250)); ?>
	
	    <?php echo $form->passwordFieldRow($model,'password',array('class'=>'span5','maxlength'=>250, 'value'=>'')); ?>
	    
	    <?php echo $form->passwordFieldRow($model,'password_repeat',array('class'=>'span5','maxlength'=>250));*/ ?>
	    
	    <?php echo $form->textFieldRow($model,'username_windows',array('class'=>'span5','maxlength'=>250)); ?>    
	    
	    <?php echo $form->dropDownListRow($model,'default_organization',
	        CHtml::listData(Organization::model()->findAll(array('order'=>'code')),'code','fullName'),
	        array('class'=>'span6')); ?>
	        
		<?php echo $form->checkboxRow($model,'blocked'); ?>
	    
	    <?php echo $form->checkboxRow($model,'role_admin'); ?>
	    
	    
	    
	    <div class="well" id="container_ifns" style="background-color: white;">
	    	<?php echo $form->label($model, 'organizations'); ?><hr />    	
		    <?php                        
		        echo $form->checkboxList($model,'organizations',
		            CHtml::listData(Organization::model()->findAll(),'code','name'));        
		    ?>
	    </div>
	    
	    <div class="well" id="container_ifns_admin" style="background-color: white; display: none;">
	        Пользователь с ролью администратора имеет доступ ко всем налоговым органам
	    </div>
	    
	    
	    <script type="text/javascript">
	
	    	if ($('#<?php echo CHtml::activeId($model, 'role_admin'); ?>').attr("checked") == 'checked')
	        { 
	            $('#container_ifns').hide();
	            $('#container_ifns_admin').show();
	        }
	
	        $('#<?php echo CHtml::activeId($model, 'role_admin'); ?>').on('change', function() {
	            if ($(this).attr("checked") != 'checked') {
	                $('#container_ifns').show();
	                $('#container_ifns_admin').hide();
	            } else {
	                $('#container_ifns').hide();
	                $('#container_ifns_admin').show();
	            }                 
	        });
	    </script>
    	
    	<?php //echo $form->textFieldRow($model,'folder_path',array('class'=>'span5','maxlength'=>50)); ?>
    	 
    </div>            
    
    
    
    <div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>$model->isNewRecord ? 'Далее' : 'Сохранить',
		)); ?>
	</div>

<?php $this->endWidget(); ?>
