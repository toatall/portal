<?php
$this->breadcrumbs=array(
	'Журнал заявок'=>array('admin','idTree'=>$idTree),
	'Управление',
);

$this->menu=array(
	array('label'=>'Создать','url'=>array('create','idTree'=>$idTree), 'icon'=>'asterisk'),
);

Yii::app()->clientScript->registerScript('search', " 
$('.search-button').click(function(){ 
    $('.search-form').toggle(); 
    return false; 
}); 
$('.search-form form').submit(function(){ 
    $.fn.yiiGridView.update('jornal-request-grid', { 
        data: $(this).serialize() 
    }); 
    return false; 
}); 
"); 

?>

<h1>Журнал заявок</h1>

<?php

    Yii::app()->clientScript->registerScriptFile(
        Yii::app()->baseUrl.'/extension/date-picker/bootstrap-datepicker.js');
    Yii::app()->clientScript->registerScriptFile(
        Yii::app()->baseUrl.'/extension/date-picker/bootstrap-datepicker.ru.js');
    Yii::app()->getClientScript()->registerCssFile(
        Yii::app()->baseUrl.'/extension/date-picker/bootstrap-datepicker.css');
            
?>

<?php echo CHtml::link('Расширенный поиск','#',array('class'=>'search-button btn')); ?> 
<div class="search-form" style="display:none"> 
<?php $this->renderPartial('_search',array( 
    'model'=>$model, 
)); ?>
</div><!-- search-form --> 

<?php $this->widget('bootstrap.widgets.TbGridView',array(
	'id'=>'jornal-request-grid',
	'dataProvider'=>$model->search(),
	//'filter'=>$model,
	'columns'=>array(
		'id',
		'code_no',      
        array(
            'header'=>'Исх. запроос от ИФНС',
            'name'=>'ifns_ufns_date',
            'value'=>'$data->ifns_ufns_number.(($data->ifns_ufns_date!="")?" от ".$data->ifns_ufns_date:"")',
        ),
        array(
            'header'=>'Исх. запрос от УФНС в ФНС',
            'name'=>'ufns_fns_date',
            'value'=>'$data->ufns_fns_number.(($data->ufns_fns_date!="")?" от ".$data->ufns_fns_date:"")',
        ),
        array(
            'header'=>'Исх. ответ из ФНС',
            'name'=>'fns_ufns_date',
            'value'=>'$data->fns_ufns_number.(($data->fns_ufns_date!="")?" от ".$data->fns_ufns_date:"")',
        ),
        array(
            'header'=>'Исх. ответ из УФНС в ФНС',
            'name'=>'ufns_ifns_date',
            'value'=>'$data->ufns_ifns_number.(($data->ufns_ifns_date!="")?" от ".$data->ufns_ifns_date:"")',
        ), 
        'date_execution',

        array(
            'name'=>'status',
            'value'=>'$data->status ? "Завершено" : "На исполнении"',
        ),
		'date_create',		
		
		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
            'buttons'=>array(
                'view'=>array(
                    'url'=>'Yii::app()->createUrl("admin/jornalRequest/view", array("id"=>$data->id,"idTree"=>'.$idTree.'))',                    
                ),
                'update'=>array(
                    'url'=>'Yii::app()->createUrl("admin/jornalRequest/update", array("id"=>$data->id,"idTree"=>'.$idTree.'))',
                ),
                'delete'=>array(
                    'url'=>'Yii::app()->createUrl("admin/jornalRequest/delete", array("id"=>$data->id,"idTree"=>'.$idTree.'))',
                ),                
            ),
		),
	),
)); ?>


<script type="text/javascript">
    $("[name='JornalRequest[ifns_ufns_date]']").datepicker({
        'format':'dd.mm.yyyy',
        'autoclose':'true',
        'todayBtn':'linked',
        'language':'ru',
        'weekStart':0            
    });
    $("[name='JornalRequest[ufns_fns_date]']").datepicker({
        'format':'dd.mm.yyyy',
        'autoclose':'true',
        'todayBtn':'linked',
        'language':'ru',
        'weekStart':0            
    });
    $("[name='JornalRequest[fns_ufns_date]']").datepicker({
        'format':'dd.mm.yyyy',
        'autoclose':'true',
        'todayBtn':'linked',
        'language':'ru',
        'weekStart':0            
    });
    $("[name='JornalRequest[ufns_ifns_date]']").datepicker({
        'format':'dd.mm.yyyy',
        'autoclose':'true',
        'todayBtn':'linked',
        'language':'ru',
        'weekStart':0            
    });
    $("[name='JornalRequest[date_execution]']").datepicker({
        'format':'dd.mm.yyyy',
        'autoclose':'true',
        'todayBtn':'linked',
        'language':'ru',
        'weekStart':0            
    });
    $("[name='JornalRequest[date_create]']").datepicker({
        'format':'dd.mm.yyyy',
        'autoclose':'true',
        'todayBtn':'linked',
        'language':'ru',
        'weekStart':0            
    });
</script>
