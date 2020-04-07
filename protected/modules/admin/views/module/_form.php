<?php $form=$this->beginWidget('bootstrap.widgets.BsActiveForm',array(
	'id'=>'module-form',
	'enableAjaxValidation'=>false,
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
    'htmlOptions'=>array(
        'onsubmit'=>'selectGroupUser();',
    ),
)); ?>

	<p class="help-block">Поля обозначенные <span class="required">*</span> обязательны для заполнения.</p>

	<?php echo $form->errorSummary($model); ?>

	<?php echo $form->textFieldControlGroup($model,'name',array('class'=>'span5','maxlength'=>50)); ?>

	<?php echo $form->textFieldControlGroup($model,'description',array('class'=>'span5','maxlength'=>250)); ?>
    
    <?php echo $form->checkBoxControlGroup($model,'only_one'); ?>
    
    <?php echo $form->checkBoxControlGroup($model,'children_node'); ?>
    
    <?php echo $form->textFieldControlGroup($model,'dop_action',array('class'=>'span5','maxlength'=>50)); ?>
    
    <?php echo $form->checkBoxControlGroup($model,'dop_action_right_admin'); ?>
    
    
    <script type="text/javascript">

        // ГРУППЫ
        function getListGroups()
        {
            $('#user_group_body').html('<img src="/images/loading.gif" /> Загрузка...');  
            var ls = [];
            $('#<?php echo CHtml::activeId($model, 'permissionGroup'); ?>').children('option').each(function() {
                ls.push($(this).val());                                      
            });
            $.ajax({
                url: '<?php echo $this->createUrl('/admin/tree/getListGroup/'); ?>',
                type: 'POST',
                data: { groups: ls }
            })  
            .done(function(data) {
                $('#user_group_body').html(data);
            });
        }
        
        function addGroup(id, value)
        {
            $('#<?php echo CHtml::activeId($model, 'permissionGroup'); ?>').append('<option value="'+id+'">'+value+'</option>');
        }
                            
        // ПОЛЬЗОВАТЕЛИ
        function getListUsers()
        {
            $('#user_group_body').html('<img src="/images/loading.gif" /> Загрузка...');  
            var ls = [];
            $('#<?php echo CHtml::activeId($model, 'permissionUser'); ?>').children('option').each(function() {
                ls.push($(this).val());                                      
            });
            $.ajax({
                url: '<?php echo $this->createUrl('/admin/tree/getListUser/'); ?>',
                type: 'POST',
                data: { users: ls }
            })  
            .done(function(data) {
                $('#user_group_body').html(data);
            });
        }
        
        function addUser(id, value)
        {
            $('#<?php echo CHtml::activeId($model, 'permissionUser'); ?>').append('<option value="'+id+'">'+value+'</option>');
        }        
        
        function selectGroupUser()
        {
            $('#<?php echo CHtml::activeId($model, 'permissionGroup'); ?> option').prop('selected', true);
            $('#<?php echo CHtml::activeId($model, 'permissionUser'); ?> option').prop('selected', true);
        }
    </script>           
    
    <div id="content_permission" class="well">  
        <h5 style="background-color: white;" class="well">Доступ</h5>        
        <table style="border: 0;" id="table_group_user">
        <tr><td>
            <?php   
            
            // ГРУППЫ //              
            echo $form->dropDownListControlGroup($model, 'permissionGroup', $model->rightGroup,
	            array(
	            	'multiple'=>true,
	            	'style'=>'width: 300px; height: 200px;',
	            ));                       
            ?>
            <br />
            <?= BsHtml::button('Добавить', [
                'class' => 'btn btn-success',
                'data-toggle'=>'modal',
                'data-target'=>'#userGroupModal',
                'onclick'=>'getListGroups();',
            ]) ?>
            <?= BsHtml::button('Удалить', [
                'class' => 'btn btn-danger',
                'onclick'=>'$(\'#'.CHtml::activeId($model, 'permissionGroup').' option:selected\').remove();',
            ]) ?>

        </td>
        <td>
           <?php 
            
           // ПОЛЬЗОВАТЕЛИ //
           echo $form->dropDownListControlGroup($model, 'permissionUser', $model->rightUser,
           		array(
           			'multiple'=>true,
           			'style'=>'width: 300px; height: 200px;',
           		));
         	?>
            <br />
           <?= BsHtml::button('Добавить', [
                'class' => 'btn btn-success',
                'data-toggle'=>'modal',
                'data-target'=>'#userGroupModal',
                'onclick'=>'getListUsers();',
           ]) ?>
            <?= BsHtml::button('Удалить', [
                'class' => 'btn btn-danger',
                'onclick'=>'$(\'#'.CHtml::activeId($model, 'permissionUser').' option:selected\').remove();',
            ]) ?>
        </td></tr>
        <tr><td colspan="2" style="padding-top: 20px;" id="ajaxTree"></td></tr>
        </table>


        <div class="modal fade" id="userGroupModal" role="dialog" data-backdrop="static" data-result="false" data-dialog="">
            <div class="modal-dialog modal-dialog-large modal-dialog-super-large" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="close"><span aria-label="Close">&times;</span></button>
                        <h4>Пользователи и группы</h4>
                    </div>
                    <div class="modal-body" id="user_group_body">
                    </div>
                    <div class="modal-footer">
                        <button type="button" data-dismiss="modal" class="btn btn-primary">Закрыть</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
    
	<div class="form-actions">
        <?= BsHtml::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить', ['class' => 'btn btn-primary']); ?>
	</div>

<?php $this->endWidget(); ?>
