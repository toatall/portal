<?php
$this->breadcrumbs=array(
    $modelTree['name'] => array('index'),
    'Главная',
);

?>


<h1>Профсоюз</h1>
<hr />

<?php $this->renderPartial('_index', [
    'model'=>$modelNews,    
]); ?>