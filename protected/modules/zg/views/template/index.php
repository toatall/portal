<?php
/* @var $this TemplateController */
/* @var $model Template */


$this->breadcrumbs=array(
	'Проект Обращения'=>array('index'),
	'Шаблоны ответов на однотипные обращения',
);


Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#template-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>
<?php echo BsHtml::pageHeader('Проект Обращения','Шаблоны ответов на однотипные обращения') ?>
<?php if ($this->isEditor()): ?>
<?= BsHtml::link('Добавить', '/zg/template/create', ['class' => 'btn btn-primary'])  //BsHtml::button('Добавить', ['class' => 'btn btn-primary', 'id' => 'btn-add']) ?><br /><br />
<?php endif; ?>
<div class="panel panel-default">
    <div class="panel-body">
        <div class="search-form" style="display:none">
            <?php $this->renderPartial('_search',array(
                'model'=>$model,
            )); ?>
        </div>
        <!-- search-form -->

        <?php $this->widget('bootstrap.widgets.BsGridView',array(
			'id'=>'template-grid',
			'htmlOptions' => [
			    'class' => '',
            ],
			'dataProvider'=>$model->search(),
			'filter'=>$model,
			'columns'=>array(
        		'id',
                'kind',
                'description',
                'date_create',
				array(
					'class'=>'bootstrap.widgets.BsButtonColumn',
                    'buttons'=>[
                        'view'=>array(
                            'options' => [
                                'class' => 'show-modal-dialog',
                            ],
                        ),
                        'update'=>array(
                            /*'options' => [
                                'class' => 'show-modal-dialog',
                            ],*/
                            'visible' => function() {
                                return $this->isEditor();
                            }
                        ),
                        /*'delete'=>array(
                            'visible' => function() {
                                return $this->isEditor();
                            }
                        ),*/
                    ],
				),
			),
            'pager'=>array(
                'class'=>'bootstrap.widgets.BsPager',
                'size' => BsHtml::BUTTON_SIZE_DEFAULT,
            ),
        )); ?>
    </div>
</div>
<script type="text/javascript">
    /*
    $(document).ready(function() {

        // Добавление адреса
        $('#btn-add').on('click', function () {
            $('#modal-dialog').attr('data-result', 'false');
            $('#modal-title').html('Добавить адрес');
            ajaxJSON('<?= $this->createUrl('/zg/template/create') ?>', {
                'title': '#modal-title',
                'content': '#modal-body'
            });
            $('#modal-dialog').modal('show');
        });


        $('#modal-dialog').on('hidden.bs.modal', function () {
            if ($(this).attr('data-result') == 'true') {
                $('#template-grid').yiiGridView('update', {
                    data: $('.search-form form').serialize()
                });
            }
            $('#modal-dialog').attr('data-result', 'false');
        });

    });
    */
</script>



