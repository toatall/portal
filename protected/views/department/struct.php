<?php
/**
 * @var $this DepartmentController
 * @var $model Department
 * @var $arrayCard arrays
 */

$this->pageTitle = $model->department_name . ': Cтруктура';
	
$this->breadcrumbs = [
	'Отделы' => ['department/index'],
	$model->concatened => ['department/view', 'id'=>$model->id],
	'Структура',
];
?>
<div class="content content-color">
<h1><?= $model->department_name . ' (структура)' ?></h1>
<hr />

<?php foreach ($arrayCard as $structRow): ?>

    <div class="row">

    <?php foreach ($structRow as $struct): ?>

		<div class="col-sm-5 col-md-3" style="margin:0 auto;">
			<div class="well thumbnails">
				<div style="height: 300px;">
					<img src="<?= $struct['user_photo'] ?>" class="thumbnail" style="max-height:300px; margin: 0 auto;" />
				</div>
				<div class="caption text-center" style="height: 200px; margin-top:10px; overflow: auto;">
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

<?php endforeach; ?>
</div>