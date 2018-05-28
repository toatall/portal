<?php

$this->pageTitle = $model->department_name . ': Структура';

$this->breadcrumbs=array(
    'Отделы' => array('department/index'),
    $model->concatened,
);

?>

<div class="alert">	
	<h3>Нет данных</h3>
</div>