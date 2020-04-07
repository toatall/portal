<?php
/* @var $this CController */
/* @var $modelYear array */
/* @var $model RatingMain */
?>

<hr class="hr-sm" />

<?php 
    
	$flagActive=true;
	
	$tabs = [];
	
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

<?php if (!empty($model->note)): ?>
<div class="well">
	<?= $model->note ?>
</div>
<?php endif; ?>


<?= BsHtml::tabbableTabs($tabs, [
    'class'=>'bold',
    'id'=>'tabs_' . $model->id,
    'placement'=> BsHtml::TABS_PLACEMENT_LEFT,
]) ?>
