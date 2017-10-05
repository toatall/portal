<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'group-form',
	'enableAjaxValidation'=>false,
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
    'htmlOptions'=>array(
        'onsubmit'=>'selectUsers();',
    ),
)); ?>

	<p class="help-block">Поля обозначенные <span class="required">*</span> обязательны для заполнения.</p>
    
	<?php echo $form->errorSummary($model); ?>

	<?php echo $form->textFieldRow($model,'name',array('class'=>'span5','maxlength'=>250)); ?>

	<?php echo $form->textAreaRow($model,'description',
			array('class'=>'span5','maxlength'=>500,'style'=>'height:100px;')); ?>	

    
    <div class="well"> 
        
        <?php echo $form->dropDownListRow($model, 'groupUsers', 
        	$model->getListGroupUsers(),        	
        	array(
	            'multiple'=>true,
	            'style'=>'width: 600px; height: 250px;',
        )); ?>        
                
        <br />
        
        <?php $this->widget('bootstrap.widgets.TbButton', array(
            'label'=>'Добавить',
            'url'=>'#',
            'htmlOptions'=>array(
                'data-toggle'=>'modal', 
                'data-target'=>'#groupUsersModal',
                'onclick'=>'getListUsers();',
            ),
        )); ?>
        
        <?php $this->widget('bootstrap.widgets.TbButton', array(
            'label'=>'Удалить',           
            'htmlOptions'=>array(
                'onclick'=>'$(\'#'.CHtml::activeId($model, 'groupUsers').' option:selected\').remove();',
            ),
        )); ?>
        
    </div>
        
    <?php $this->beginWidget('bootstrap.widgets.TbModal', array(
        'id'=>'groupUsersModal',
        'htmlOptions'=>array('style'=>'width:800px; margin-left:-400px;'),
    )); ?>
    
    <div class="modal-header">
        <a class="close" data-dismiss="modal">&times;</a>
        <h4>Пользователи</h4>        
    </div>
            
    <div class="modal-body" id="users_body"></div>
            
    <div class="modal-footer">
        <?php $this->widget('bootstrap.widgets.TbButton', array(
            'label'=>'Отмена',            
            'htmlOptions'=>array('data-dismiss'=>'modal'),
        )); ?>
    </div>
    
    <?php $this->endWidget(); ?>
    
    <script type="text/javascript">
        
        function addUser(id, value)
        {
            $('#<?php echo CHtml::activeId($model, 'groupUsers'); ?>').append('<option value="'+id+'">'+value+'</option>');
        }
        
        function getUsers()
        {
            $('#users :checkbox:checked').each(function() {
                $val1 = $('#hiddenLogin_'+$(this).attr('name')).val();
                $val2 = $('#hiddenId_'+$(this).attr('name')).val();
                $('#<?php echo CHtml::activeId($model, 'groupUsers'); ?>').append('<option value="'+$val2+'">'+$val1+'</option>');
            });
        }


        function listUsers()
		{
        	var ls = [];
            $('#<?php echo CHtml::activeId($model, 'groupUsers'); ?>').children('option').each(function() {
                ls.push($(this).val());                                      
            });
            return ls;
		}
		
        function getListUsers()
        {
            $('#users_body').html('<img src="/images/loading.gif" /> Загрузка...');  
            
            $.ajax({
                url: '<?php echo $this->createUrl('/admin/tree/getListUser/'); ?>',
                type: 'POST',
                data: { users: listUsers().toString() }
            })  
            .done(function(data) {
                $('#users_body').html(data);
            });
        }
        
        function getUrlGridViewAjax(url)
        {            
            $('#<?php echo CHtml::activeId($model, 'groupUsers'); ?>').children('option').each(function() {
                if (url.indexOf('?'))
                {
                    url += '&users[]='+$(this).val();
                }
                else
                {
                    url += '?users[]='+$(this).val();
                }                                              
            });
            return url;
        }
        
        function selectUsers()
        {
            $('#<?php echo CHtml::activeId($model, 'groupUsers'); ?> option').prop('selected', true);
        }
        
    </script>
    
	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>$model->isNewRecord ? 'Создать' : 'Сохранить',
		)); ?>
	</div>

<?php $this->endWidget(); ?>
