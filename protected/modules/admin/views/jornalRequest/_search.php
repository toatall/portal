<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<?php //echo $form->textFieldRow($model,'id',array('class'=>'span5')); ?>

    <?php echo $form->textFieldRow($model,'code_no',array('class'=>'span5','maxlength'=>4)); ?>

    <?php echo $form->textFieldRow($model,'ifns_ufns_date',array('class'=>'span5')); ?>

    <?php echo $form->textFieldRow($model,'ifns_ufns_number',array('class'=>'span5','maxlength'=>50)); ?>

    <?php echo $form->textFieldRow($model,'ufns_fns_date',array('class'=>'span5')); ?>

    <?php echo $form->textFieldRow($model,'ufns_fns_number',array('class'=>'span5','maxlength'=>50)); ?>

    <?php echo $form->textFieldRow($model,'fns_ufns_date',array('class'=>'span5')); ?>

    <?php echo $form->textFieldRow($model,'fns_ufns_number',array('class'=>'span5','maxlength'=>50)); ?>

    <?php echo $form->textFieldRow($model,'ufns_ifns_date',array('class'=>'span5')); ?>

    <?php echo $form->textFieldRow($model,'ufns_ifns_number',array('class'=>'span5','maxlength'=>50)); ?>

    <?php echo $form->textFieldRow($model,'date_execution',array('class'=>'span5')); ?>

    <?php //echo $form->textAreaRow($model,'description',array('rows'=>6, 'cols'=>50, 'class'=>'span8')); ?>

    <?php echo $form->textFieldRow($model,'status',array('class'=>'span5')); ?>

    <?php echo $form->textFieldRow($model,'date_create',array('class'=>'span5')); ?>

    <?php //echo $form->textFieldRow($model,'log_access',array('class'=>'span5','maxlength'=>5000)); ?>

	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>'Поиск',
		)); ?>
	</div>

<?php $this->endWidget(); ?>
