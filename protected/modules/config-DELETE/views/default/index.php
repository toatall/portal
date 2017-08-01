<?php
/* @var $this DefaultController */

$this->breadcrumbs=array(   
    //$this->module->name,
);
?>

<h1>Добро пожаловать в систему управления</h1>


<?php        


    /*$this->widget('bootstrap.widgets.TbThumbnails', array(
        'dataProvider'=>new CActiveDataProvider('Section', array(
            'criteria'=>array(
            ),
        )),
        'template'=>"{items}\n{pager}",
        'itemView'=>'_thumb',
    ));*/
?>
<p>Уважаемый <b><?php echo Yii::app()->user->last_name.' '.Yii::app()->user->first_name.' '.Yii::app()->user->middle_name; ?></b> 
для продолжения работы Вам необходимо перейти в необходимый раздел. Для этого необходимо щелкнуть по имени раздела.</p>
<h3>Выберите раздел сайта</h3>

<?php
    
    /*if (!isset(Yii::app()->user->code_no))
        throw new CHttpException(401,'Вам не назначен налоговый орган. Пожалуйста, обратитесь к администратору.');
    */
    /*$this->widget('bootstrap.widgets.TbButton', array(
        'buttonType'=>'button',
        //'type'=>'primary',
        'label'=>'Скрыть навигацию',
        'htmlOptions'=>array(
            'id'=>'buttonShowHideSection',
        ),
    ));*/
    
    /*if (Yii::app()->user->admin) {
        $orgs = Organization::model()->findAll();
    } else {
        if (isset($model->organization))
            { $orgs = $model->organization; }
                else throw new CHttpException(401,'Вам не назначен налоговый орган. Пожалуйста, обратитесь к администратору.');
    }*/
    
    
    
            
    //echo CHtml::dropDownList('listOrganizations', '', CHtml::listData($orgs, 'code', 'name'),
    //    array(
    //        'class'=>'span6', 
    //        'submit'=>array('default/ajaxSections', 'id'=>'8600'), 
    //        'name'=>'listOrganizations',
            /*'ajax'=>array(
                'type'=>'POST',
                'url'=>$this->createUrl('ajaxSections'),
                'update'=>'#ajaxSections',
                'data'=>array('org'=>'js:this.value'),
            ),*/
    //));
?>
<script type="text/javascript">
    /*$.post(
        "<?php echo $this->createUrl('ajaxSections'); ?>",
        { 'org': $('#listOrganizations').val() },
        function(data)  {
            $('#ajaxSections').html(data);
        }
    );*/
</script>
<div id="ajaxSections"></div>


<div class="well" id="containerSection" style="background-color:white; margin-top:3px;">
<?php /*$form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'tree-form',
	'enableAjaxValidation'=>false,
    'method'=>'get',
));*/ ?>

<?php /*echo CHtml::dropDownList('listTree', '', CheckAcccess::getOrganizationList(),
    array('class'=>'span6', 'submit'=>'', 'name'=>'listTree')); ?>
<?php $this->endWidget();*/ ?>

<?php
    
    $tree = Tree::model()->getTreeForMain();
    
    if (count($tree)) {
        $this->widget('CTreeView', array(
            'data'=>$tree,            
        ));
    } else {
?>
    <h4 class="well">Нет данных</h4>
<?php   
    }  
    
?>

</div>
