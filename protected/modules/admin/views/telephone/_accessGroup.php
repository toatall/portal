<?php
    
    echo CHtml::dropDownList('Tree[AccessGroup]', '', 
        CHtml::listData(Access::model()->with('group')->findAll(array(            
            'condition'=>'t.id_tree=:id_tree AND t.is_group=1',
            'params'=>array(':id_tree'=>$model->id),
            'order'=>'[group].[name]',
        )),'group.id','group.name'),
        array(
            'ajax'=>array(
                'type'=>'GET',
                'url'=>$this->createUrl('/admin/telephone/ajaxTreeAccess', array('id'=>$model->id)),
                'update'=>'#ajaxTreeGroup',
                'data'=>array('identity'=>'js:this.value', 'is_group'=>1),
            ),
        )
    );
    
    Yii::app()->clientScript->registerScript(
        'update-module-on-tree-access-group',
        '$(document).ready(function() {
            $("#'.CHtml::getIdByName('Tree[AccessGroup]').'").change();
        });'
    );
            
?>

<div id="ajaxTreeGroup"></div>
