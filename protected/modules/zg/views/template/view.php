<?php
/* @var $this ZgTemplateController */
/* @var $model Template */
?>


<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="modal-title">Шаблоны</h3>
    </div>
    <div class="panel-body">
        <div class="list-group">
        <?php foreach ($model->getListFiles() as $file): ?>
            <a href="<?= $model->getUrlFile($file) ?>" class="list-group-item list-group-item-dark">
                <i class="fas fa-file-word" style="font-size: xx-large"></i>&nbsp;&nbsp;
                <?= $file ?>
            </a>
        <?php endforeach; ?>
        </div>
    </div>
</div>

<div class="panel panel-info">
    <div class="panel-heading">
        <button class="btn btn-default" id="btn-info">Информация</button>
    </div>
    <script type="text/javascript">
        $('#btn-info').on('click', function () {
            $('#div-info').slideToggle();
        })
    </script>
    <div class="panel-body" id="div-info" style="display: none;">
        <?php $this->widget('zii.widgets.CDetailView',array(
            'htmlOptions' => array(
                'class' => 'table table-striped table-condensed table-hover',
            ),
            'data'=>$model,
            'attributes'=>array(
                'id',
                'kind',
                'description',
                'date_create',
                'date_update',
                'author',
            ),
        )); ?>
    </div>

</div>
