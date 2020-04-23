<?php
/* @var $this TestController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Тесты'=>array('index'),
	'Просмотр',
);
?>

<?php echo BsHtml::pageHeader('Тесты','Просмотр тестов') ?>
<div class="list-group">
    <?php foreach ($dataProvider->data as $data): ?>
    <a href="<?= $this->createUrl('/test/test/start', ['id'=>$data->id]) ?>" class="list-group-item show-modal-dialog<?= $data->active ? ' list-group-item-success' : '' ?>">
        <h3 style="font-weight: bold;">
            <p><?= $data->name ?></p>
            <p style="font-size: medium"><span class="label label-default">Начало <?= $data->date_start ?></span></p>
            <p style="font-size: medium"><span class="label label-default">Окончание <?= $data->date_end ?></span></p>
        </h3>
    </a>
    <?php endforeach; ?>
</div>
