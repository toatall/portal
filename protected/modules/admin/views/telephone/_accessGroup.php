<?php
    
    /**
     * Список групп для предоставления доступа к организациям
     */
    echo CHtml::dropDownList('Tree[AccessGroup]', '', 
        CHtml::listData(AccessGroup::model()->with('group')->findAll(array(
            'condition'=>'t.id_tree=:id_tree and t.id_organization=:id_organization',
            'params'=>array(
                ':id_tree'=>$model->id,
                ':id_organization'=>Yii::app()->session['organization'],
            ),
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
