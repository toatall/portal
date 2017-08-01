<?php
$this->breadcrumbs=array(
	'Отделы' => array('department/index'),
	$model->concatened . ' (структура)',
);

?>


<h1>title</h1>
<hr />


<?php $this->renderPartial('_index', ['model'=>$modelNews, 'dirFile'=>'', 'dirImage'=>'']); ?>