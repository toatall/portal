<?php 

     $this->widget('bootstrap.widgets.TbGridView',array(
        'id'=>'groupUsersGrid',
        'dataProvider'=>$model->search(array(),true),
        'enableSorting'=>false,
        'filter'=>$model,
        'beforeAjaxUpdate' => 'js: function(id, options) {            
            options.url = getUrlGridViewAjax(options.url);
        }',
        'columns'=>array(
            'id',
            'username',
            'username_windows',                        
            array(
            	'name'=>'profile.name',
            	'value'=>'isset($data->profile) ? $data->profile->name : null',
            ),
        		
        		
        	array(
        		'class'=>'bootstrap.widgets.TbButtonColumn',
                'template'=>'{insert}',
                'buttons'=>array(
                    'insert'=>array(
                        'label'=>'Выбрать',                    
                        'options'=>array(
                            'class'=>'btn btn-mini btn-success',
                            'data-dismiss'=>'modal',
                            'onclick'=>'addUser($(this).parent().parent().children(":nth-child(1)").text(),
                                $(this).parent().parent().children(":nth-child(4)").text()+\' (\'
                                +$(this).parent().parent().children(":nth-child(3)").text()+\')\');',                            
                        ),
                    ),
                ),
        	),
        ),
     ));
     
?>

