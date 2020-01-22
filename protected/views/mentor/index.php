<?php
/* @var $this MentorController */
/* @var $model MentorWays */

$this->breadcrumbs=array(
	'Наставничество',
);

?>

<h1>Наставничество</h1>
<hr />

<?php foreach ($model as $m): ?>
	<p><?= CHtml::link($m->name . ' (' . $m->countPosts . ')', ['/mentor/way', 'id'=>$m->id]) ?></p>
<?php endforeach; ?>