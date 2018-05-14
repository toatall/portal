<?php

$this->pageTitle = $model->department_name . ': Структура';

$breadcrumbsTemp = (isset($breadcrumbsTreePath) ? $breadcrumbsTreePath : []);
$this->breadcrumbs = array_merge(
    [   
        'Отделы' => array('department/index'),    
        $model->concatened => array('department/view','id'=>$model->id),
    ],
    $breadcrumbsTemp);
if ($breadcrumbsTemp !== null)
{
    end($this->breadcrumbs);
    $key = key($this->breadcrumbs);
    array_pop($this->breadcrumbs);    
    $this->breadcrumbs = array_merge($this->breadcrumbs, [$key]);
}

?>

<h1><?= $model->department_name; ?></h1>
<hr />

<?php 
    
    echo $treeDepartment;
