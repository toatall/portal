<?php

$this->breadcrumbs=array(
	'Телефонные справочники',
);

?>
<div class="content content-color">

<h1>Телефонные справочники</h1>


<?php 

$func_url = function($data) {
	return CHtml::link($data->dop_text, array("site/telephoneDownload", "id"=>$data->id), array("target"=>"_blank"));
};
	
$this->widget('bootstrap.widgets.TbGridView',array(
	//'id'=>'news-grid',
	'dataProvider'=>$model->search(),
	'filter'=>null,
    'hideHeader'=>true, 
    'summaryText'=>'',    
	'columns'=>array(
		array(
			'header' => '',
			'value' => function($data) {
				//return CHtml::link($data->org->code, array("site/telephoneDownload", "id"=>$data->id), array("target"=>"_blank", "data-id"=>$data->id));
                return CHtml::link($data->org->code, $data->url);
			},
			'type' => 'raw',
		),
		array(
			'header' => '',
			'value' => function($data) {
				//return CHtml::link($data->org->name, array("site/telephoneDownload", "id"=>$data->id), array("target"=>"_blank"));
                return CHtml::link($data->org->name, $data->url);
			},
			'type' => 'raw',
		),
		array(
			'header' => '',
			'value' => function($data) {
				//return CHtml::link($data->dop_text, array("site/telephoneDownload", "id"=>$data->id), array("target"=>"_blank"));
                return CHtml::link($data->dop_text, $data->url);
			},
			'type' => 'raw',
		),   
		/*array(
			'header'=>'',
			'value'=>function($data) { 
				if (empty($data->count_download)) return null; 
				return '<i class="icon-download-alt" title="Загружено"></i> ' . $data->count_download; 
			},
			'type'=>'raw',				
		),*/
	),
)); ?>

</div>
