<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'section-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="help-block">Поля обозначенные <span class="required">*</span> обязательны для заполнения.</p>

	<?php echo $form->errorSummary($model); ?>

	<?php echo $form->textFieldRow($model,'name',array('class'=>'span5','maxlength'=>50)); ?>

	<?php echo $form->dropDownListRow($model,'module',CHtml::listData(Module::model()->findAll(),'name','name'),
        array('class'=>'span5','maxlength'=>50)); ?>

	<?php echo $form->checkBoxRow($model,'use_organization'); ?>
    
    <script type="text/javascript">
        $(document).ready(function() {
            $('#<?php echo CHtml::activeId($model, 'use_organization'); ?>').change(function() {
                if ($(this).is(':checked')) { $('#orgs').show(); } else { $('#orgs').hide(); }    
            }); 
            
            if ($('#<?php echo CHtml::activeId($model, 'use_organization'); ?>').is(':checked')) 
            { 
                $('#orgs').show(); 
            }
            else 
            { 
                $('#orgs').hide(1);
            }                        
        });                                                  
    </script>
    
    <div class="well" style="background: #fff;" id="orgs">       
        <?php $this->widget('CTreeView', array('data'=>Section::model()->getListOrganization(0, $model->id))); ?>    
    </div>
    
	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>$model->isNewRecord ? 'Создать' : 'Сохранить',
		)); ?>
	</div>

<?php $this->endWidget(); ?>
