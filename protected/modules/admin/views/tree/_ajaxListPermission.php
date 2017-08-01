<?php        
    
    $columns = (!$is_group) ? 
    	array(
    		'id', 
    		'username_windows', 
    		array(
				'name'=>'profile.name',
				'filter'=>CHtml::activeTextField($model, 'profile_name'),
				'type'=>'raw',    				
    		),
    		'default_organization',
    		
    	) : 
    	array('id', 'name', 'id_organization');
        
    $onclick = ($is_group) 
        ? 'addGroup(
            $(this).parent().parent().children(":nth-child(1)").text(),
            $(this).parent().parent().children(":nth-child(2)").text());' 
        : 'addUser($(this).parent().parent().children(":nth-child(1)").text(),
                                    $(this).parent().parent().children(":nth-child(2)").text()+\' (\'
                                    + $(this).parent().parent().children(":nth-child(3)").text()+\')\');';  
    $beforeJsUpdate = ($is_group)
    	? 'js: function(id, options) { options.url = options.url + \'&Group[groups]=\' + listGroups(); }'
    	: 'js: function(id, options) { options.url = options.url + \'&User[users]=\' + listUsers(); }';
                
    $this->widget('bootstrap.widgets.TbGridView',array(
        'id'=>'listPermissionGrid',
        'dataProvider'=>$model->searchForTree(array()),
        'enableSorting'=>false,
        'filter'=>$model,
    	'beforeAjaxUpdate'=>$beforeJsUpdate,
        /*'beforeAjaxUpdate' => 'js: function(id, options) {            
            options.url = getGroupUrlGridViewAjax(options.url);
        }',*/
        'columns'=>array_merge(
            $columns,                          
    		array(array(
    			'class'=>'bootstrap.widgets.TbButtonColumn',
                'template'=>'{insert}',
                    'buttons'=>array(
                        'insert'=>array(
                            'label'=>'Выбрать',
                            'options'=>array(
                                'class'=>'btn btn-mini btn-success',
                                'data-dismiss'=>'modal',
                                'onclick'=>$onclick,                            
                            ),
                        ),
                    ),
    		))
     )));       
     
?>