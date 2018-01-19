<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'tree-form',
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
    
    <?php $array_parent = Yii::app()->user->admin ? array(0=>'Родитель') : array(); ?>        
    
    
	<?php if ($model->isNewRecord): ?>    
    <?php echo $form->dropDownListRow($model,'id_parent',$array_parent
        +Tree::model()->getTreeDropDownList()); ?>
    <?php else: ?>    
    <?php echo $form->dropDownListRow($model,'id_parent',$array_parent
        +Tree::model()->getTreeDropDownList($model->id)); ?>        
    <?php endif; ?>


	<?php echo $form->textFieldRow($model,'name',array('class'=>'span5','maxlength'=>250)); ?>       
    
    <?php echo $form->textFieldRow($model,'sort',array('class'=>'span5')); ?>
 	
 	<?php if (Yii::app()->user->admin): ?>
 		<div class="alert alert-info">
    	<?php echo $form->checkBoxRow($model,'allOrganization'); ?>
    	</div>
    <?php endif; ?>
    
    <?php echo $form->checkBoxRow($model,'use_material'); ?>
     
    <script type="text/javascript">
        $(document).ready(function() {
                $('#<?php echo CHtml::activeId($model, 'use_material'); ?>').change(function() {
                    if ($(this).is(':checked')) { $('#content_material').show(); } else { $('#content_material').hide(); }    
                }); 
                
                if ($('#<?php echo CHtml::activeId($model, 'use_material'); ?>').is(':checked')) 
                { 
                    $('#content_material').show(); 
                }
                else 
                { 
                    $('#content_material').hide(1);    
                }
                
                $('#link_show_hide_permission').on('click', function() {
                    if ($('#content_permission').css('display')=='none') {
                        $('#content_permission').show();
                        getListUsers();
                    } else {
                        $('#content_permission').hide();
                    }
                });
                
                $('#<?php echo CHtml::activeId($model, 'useParentRight'); ?>').change(function() {
                    if ($(this).is(':checked')) { $('#table_group_user').hide(); } else { $('#table_group_user').show(); }    
                }); 
                
                if ($('#<?php echo CHtml::activeId($model, 'useParentRight'); ?>').is(':checked')) 
                { 
                    $('#table_group_user').hide(); 
                }
                else 
                { 
                    $('#table_group_user').show(1);
                }
                                        
            });    
    </script>
    
    <div id="content_material" class="well">   
    	<?php        	
    		if (Yii::app()->user->admin)
    		{
    		    echo '<div class="alert alert-info">';
	    		echo $form->dropDownListRow($model, 'module',
	            	CHtml::listData(Module::listCurrentUser(),'name','description'),
	                	array(
	                    	'class'=>'span5',
	                        'maxlength'=>50,
                ));
	    		echo '</div>';
    		}
        ?>        	

    	<?php echo $form->checkBoxRow($model,'use_tape'); ?>
    
    </div>
    
    
    <script type="text/javascript">


		function listGroups()
		{
			var ls = [];
            $('#<?php echo CHtml::activeId($model, 'permissionGroup'); ?>').children('option').each(function() {
                ls.push($(this).val());
            });
            return ls;
		}
		
        // ГРУППЫ
        function getListGroups()
        {
            $('#user_group_body').html('<img src="/images/loading.gif" /> Загрузка...');
            $.ajax({
                url: '<?php echo $this->createUrl('/admin/tree/getListGroup/'); ?>',
                type: 'POST',
                data: { groups: listGroups().toString() }
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
        function listUsers()
		{
        	var ls = [];
            $('#<?php echo CHtml::activeId($model, 'permissionUser'); ?>').children('option').each(function() {
                ls.push($(this).val());                                      
            });
            return ls;
		}
		
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
                data: { users: listUsers().toString() }
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
    
    
    
    <?php if (Yii::app()->user->admin): ?>    
    
    <div id="content_permission" class="well alert alert-info">  
        <h5 style="background-color: white;" class="well">Доступ</h5>
        <p><?php echo $form->checkBoxRow($model, 'useParentRight', 
            $model->isNewRecord ? array('checked'=>'checked') : array()
        ); ?>
        </p>
        <table style="border: 0;" id="table_group_user">
        <tr><td>
            <?php   
            
            // ГРУППЫ //  
            
            echo $form->dropDownListRow($model, 'permissionGroup',               
                CHtml::listData(AccessGroup::model()->with('group')->findAll(array(
                    'order'=>'[t].[id_organization], [group].[name]',
                    'condition'=>'t.id_tree=:id_tree and t.id_organization=:organization',
                    'params'=>array(
                        ':id_tree'=>$model->id,
                        ':organization'=>Yii::app()->session['organization'],
                    ),
                )), 'group.id', 'group.name'),
                array(
	                'multiple'=>true,
	                'style'=>'width: 300px; height: 200px;',
            )); ?>
            <br />
            <?php $this->widget('bootstrap.widgets.TbButton', array(
                'label'=>'Добавить',   
            	'type'=>'success',
                'url'=>'javascript:;',
                'htmlOptions'=>array(
                    'data-toggle'=>'modal', 
                    'data-target'=>'#userGroupModal',
                    'onclick'=>'getListGroups();',
                ),
            )); ?>
            <?php $this->widget('bootstrap.widgets.TbButton', array(
                'label'=>'Удалить',
            	'type'=>'danger',
                'url'=>'javascript:;',
                'htmlOptions'=>array(
                    'onclick'=>'$(\'#'.CHtml::activeId($model, 'permissionGroup').' option:selected\').remove();',
                ),
            )); ?>                        
        </td>
        <td>
           <?php 
            
            // ПОЛЬЗОВАТЕЛИ //
            
            echo $form->dropDownListRow($model, 'permissionUser',                 
            	CHtml::listData(AccessUser::model()->with('user')->findAll(array(
            		'order'=>'[t].[id_organization], [user].[username_windows]',
            		'condition'=>'t.id_tree=:id_tree and t.id_organization=:organization',
            		'params'=>array(
            			':id_tree'=>$model->id,
            			':organization'=>Yii::app()->session['organization'],
            		),
            	)), 'user.id', 'user.concatened'),
                array(
                    'multiple'=>true,
                    'style'=>'width: 300px; height: 200px;',
            )); ?>
            <br />
            <?php $this->widget('bootstrap.widgets.TbButton', array(
                'label'=>'Добавить',
            	'type'=>'success',
                'url'=>'javascript:;',
                'htmlOptions'=>array(
                    'data-toggle'=>'modal', 
                    'data-target'=>'#userGroupModal',
                    'onclick'=>'getListUsers();',
                ),
            )); ?>
            <?php $this->widget('bootstrap.widgets.TbButton', array(
                'label'=>'Удалить',
            	'type'=>'danger',
                'url'=>'javascript:;',
                'htmlOptions'=>array(
                    'onclick'=>'$(\'#'.CHtml::activeId($model, 'permissionUser').' option:selected\').remove();',
                ),
            )); ?>                                            
        </td></tr>
        <tr><td colspan="2" style="padding-top: 20px;" id="ajaxTree"></td></tr>
        </table>
        
    
        <?php $this->beginWidget('bootstrap.widgets.TbModal', array(
            'id'=>'userGroupModal',
            'htmlOptions'=>array('style'=>'width:800px; margin-left:-400px;'),
            
        ));?>
        <div class="modal-header">
            <a class="close" data-dismiss="modal">&times;</a>
            <h4>Пользователи и группы</h4>        
        </div>
                
        <div class="modal-body" id="user_group_body"></div>
                
        <div class="modal-footer">
            <?php $this->widget('bootstrap.widgets.TbButton', array(
                'label'=>'Отмена',
                'url'=>'#',
                'htmlOptions'=>array('data-dismiss'=>'modal'),
            )); ?>
        </div>
        
        <?php $this->endWidget(); ?>
                                                 
    </div>
    
    
   
    <a href="" id="lnk-params" class="btn btn-primary">Дополнительные параметры</a><br /><br />
    <script type="text/javascript">
		$('#lnk-params').on('click', function() {
			$('#params').toggle();
			return false;
		});
    </script>
    <div id="params" class="well alert alert-info" style="display: none; padding: 10px;">
    	<?php echo $form->textFieldRow($model,'param1',array('class'=>'span5','maxlength'=>250)); ?>
    	<?php echo $form->textFieldRow($model,'alias',array('class'=>'span5','maxlength'=>50)); ?>
    </div>
        
    <?php endif; ?>
    
    <br/><br/>
    <?= $form->checkBoxRow($model, 'disable_child') ?>
    
	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>$model->isNewRecord ? 'Создать' : 'Сохранить',
		)); ?>
	</div>
            

<?php $this->endWidget(); ?>
