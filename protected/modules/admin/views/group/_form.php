<?php $form=$this->beginWidget('bootstrap.widgets.BsActiveForm',array(
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

	<?php echo $form->textFieldControlGroup($model,'name',array('class'=>'span5','maxlength'=>250)); ?>

	<?php echo $form->textAreaControlGroup($model,'description',
			array('maxlength'=>500,'style'=>'height:100px;')); ?>

    <div class="well"> 
        
        <?php echo $form->dropDownListControlGroup($model, 'groupUsers',
        	$model->getListGroupUsers(),        	
        	array(
	            'multiple'=>true,
	            'style'=>'width: 600px; height: 250px;',
        )); ?>        
                
        <br />

        <?= BsHtml::button('Добавить', [
            'class' => 'btn btn-primary',
            'data-toggle' => 'modal',
            'data-target' => '#groupUsersModal',
            'onclick' => 'getListUsers();',
        ]) ?>

        <?= BsHtml::button('Удалить', [
            'onclick'=>'$(\'#'.CHtml::activeId($model, 'groupUsers').' option:selected\').remove();',
        ]) ?>
        
    </div>

    <div class="modal fade" id="groupUsersModal" role="dialog" data-backdrop="static" data-result="false" data-dialog="">
        <div class="modal-dialog modal-dialog-large modal-dialog-super-large" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="close"><span aria-label="Close">&times;</span></button>
                    <h4>Пользователи</h4>
                </div>
                <div class="modal-body" id="users_body"></div>
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn btn-primary">Закрыть</button>
                </div>
            </div>
        </div>
    </div>
    
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

    <?= BsHtml::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить', ['class' => 'btn btn-primary']) ?>

<?php $this->endWidget(); ?>
