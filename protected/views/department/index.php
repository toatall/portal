<?php 
    /**
     * @param $this CController
     * @param $m Department
     */
?>
<div class="content content-color">
<?php   
    $this->pageTitle .= 'Отделы';
    $this->breadcrumbs = ['Отделы'];
?>

<h1 class="page-header">Отделы</h1>

<ul class="nav nav-tabs nav-stacked">
	<?php 
	   foreach ($model as $m)
	   {
    ?>
    <li>
    	<?= CHtml::link($m->concatened, ['department/view', 'id'=>$m->id]) ?>
    </li>    
    <?php    
	   }
	?>
</ul>