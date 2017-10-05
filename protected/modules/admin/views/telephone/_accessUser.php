<?php
    
    echo CHtml::dropDownList('Tree[AccessUser]', '', 
        CHtml::listData(AccessUser::model()->with('user')->findAll(array(            
            'condition'=>'t.id_tree=:id_tree and t.id_organization=:id_organization',
            'params'=>array(
                ':id_tree'=>$model->id,
                ':id_organization'=>Yii::app()->session['organization'],                
            ),
            'order'=>'[user].username_windows',
        )),'user.id','user.concatened'),
        array(
            'ajax'=>array(
                'type'=>'GET',
                'url'=>$this->createUrl('/admin/telephone/ajaxTreeAccess', array('id'=>$model->id)),
                'update'=>'#ajaxTreeUser',
                'data'=>array('identity'=>'js:this.value', 'is_group'=>0),
            ),
        )
    );
    
    Yii::app()->clientScript->registerScript(
        'update-module-on-tree-access-user',
        '$(document).ready(function() {
            $("#'.CHtml::getIdByName('Tree[AccessUser]').'").change();
        });'
    );
            
?>

<div id="ajaxTreeUser"></div>
