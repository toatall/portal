<hr class="hr-sm" />

<?php 
    
	$flagActive=true;
	
	$tabs = array();
	
	foreach ($modelYear as $year)
	{
		$tabs[] = [
			'label'=>$year,
			'content'=>$this->renderPartial('_ratingData', ['model'=>RatingData::dataRating($model->id, $year, $model->order_asc)], true),
			'active'=>(in_array(date('Y'), $modelYear) ? $year==date('Y') : $flagActive),
			'id'=>'tab_rating_main_' . $year . '_' . $model->id,
		];
		$flagActive=false;
	}
	
?>

<?php $this->widget('bootstrap.widgets.TbTabs', array(	
	'placement'=>'left',
	'htmlOptions'=>['class'=>'bold', 'id'=>'tabs_' . $model->id],
	'tabs'=>$tabs,
));
?>
</div>