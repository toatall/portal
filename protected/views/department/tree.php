<?php

$this->pageTitle = $model->department_name . ': Структура';

$this->breadcrumbs=array(
    'Отделы' => array('department/index'),
    $model->concatened,
);

?>

<h1><?= $model->department_name; ?></h1>
<hr />

<?php 
    
    echo $treeDepartment;
