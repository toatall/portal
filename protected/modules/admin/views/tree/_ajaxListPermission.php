<?php
/**
 * @var $this CController
 * @var $is_group bool
 *
 */

    $columns = (!$is_group) ? 
    	array(
    		'id', 
    		'username_windows', 
    	    'fio:ФИО',
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
                
    $this->widget('bootstrap.widgets.BsGridView',[
        'id'=>'listPermissionGrid',
        'dataProvider' => $model->searchForTree([]),
        'enableSorting' => false,
        'filter' => $model,
    	'beforeAjaxUpdate' => $beforeJsUpdate,
        'columns' => array_merge(
            $columns,
    		[
    		    [
                    'class'=>'bootstrap.widgets.BsButtonColumn',
                    'template'=>'{insert}',
                    'buttons' => [
                        'insert' => [
                            'label' => 'Выбрать',
                            'options' => [
                                'class' => 'btn btn-mini btn-success',
                                'data-dismiss'=>'modal',
                                'onclick'=>$onclick,
                            ],
                        ],
                    ],
                ],
    		]
        ),
        'pager'=>array(
            'class'=>'bootstrap.widgets.BsPager',
            'size' => BsHtml::BUTTON_SIZE_DEFAULT,
        ),
    ]);
?>