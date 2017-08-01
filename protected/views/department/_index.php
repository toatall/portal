
<style type="text/css">
    #news-grid, #news-grid .summary{
        top: 0;
        padding-top: 0;
        margin-top: 0;
        margin-bottom: 0;
    }
    .filters td {
        padding: 0;
    }
    #news-grid table td {
        padding: 0;
        border: 0;
    }
</style>

<?php $this->widget('bootstrap.widgets.TbGridView',array(
	'id'=>'news-grid',
	'dataProvider'=>$model->searchPages(null),
	'filter'=>$model, 
    'hideHeader'=>true, 
    'summaryText'=>'',    
	'columns'=>array(		
        array(
            'header'=>'',            
            'filter'=>'',           
            'value'=>'Yii::app()->getController()->renderPartial("_indexRow",array("data"=>$data), true)',
            'type'=>'html',           
        ),		            
        
	),
)); ?>
