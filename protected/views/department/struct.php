<?php
	$this->breadcrumbs=array(
		'Отделы' => array('department/index'),
		$model->concatened . ' (структура)',
	);
?>

<h1><?= $model->department_name . ' (структура)' ?></h1>
<hr />

<?php foreach ($arrayCard as $structRow): ?>
	<div class="row-fluid">
		<div class="span12 text-center">
	<?php foreach ($structRow as $struct): ?>
		<div class="" style="margin:0 auto;">
			<div class="thumbnails span3 well">
				<img src="<?= $struct['user_photo'] ?>" class="thumbnail" style="height: 300px; margin: 0 auto;" />
				<div class="caption text-centered" style="height: 300px; overflow: auto;">
					<h4><?= $struct['user_fio'] ?></h4>
					<p><?= $struct['user_position'] ?></p>
					<p><?= $struct['user_rank'] ?></p>
					<p><?= $struct['user_telephone'] ?></p>
					<p><?= $struct['user_resp'] ?></p>
				</div>
			</div>
		</div>

	<?php endforeach; ?>
		</div>		
	</div>
<?php endforeach; ?>
