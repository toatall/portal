<?php
/* @var $this EmailGovermentController */
/* @var $model EmailGoverment */


$this->breadcrumbs=array(
	'База электронных адресов органов государственной власти'=>array('index'),
	'Просмотр',
);


Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#email-goverment-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>
<?php echo BsHtml::pageHeader('Просмотр','База электронных адресов органов государственной власти') ?>
<?php if ($model->isRight()): ?>
<?= BsHtml::button('Добавить', ['class' => 'btn btn-primary', 'id' => 'btn-add']) ?><br /><br />
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
			'id'=>'email-goverment-grid',
			'htmlOptions' => [
			    'class' => '',
            ],
			'dataProvider'=>$model->search(),
			'filter'=>$model,
			'columns'=>array(
        		//'id',
                'org_name',
                'ruk_name',
                'telephone',
                'email',
                'post_address',
                /*
                'date_create',
                'date_update',
                'author',
                */
				array(
					'class'=>'bootstrap.widgets.BsButtonColumn',
                    'buttons'=>[
                        'view'=>array(
                            'options' => [
                                'class' => 'show-modal-dialog',
                            ],
                        ),
                        'update'=>array(
                            'options' => [
                                'class' => 'show-modal-dialog',
                            ],
                            'visible' => '$data->isRight()',
                        ),
                        'delete'=>array(
                            'visible' => '$data->isRight()',
                        ),
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

    $(document).ready(function() {

        // Добавление адреса
        $('#btn-add').on('click', function () {
            $('#modal-dialog').attr('data-result', 'false');
            $('#modal-title').html('Добавить адрес');
            ajaxJSON('<?= $this->createUrl('/zg/emailGoverment/create') ?>', {
                'title': '#modal-title',
                'content': '#modal-body'
            });
            $('#modal-dialog').modal('show');
        });


        $('#modal-dialog').on('hidden.bs.modal', function () {
            if ($(this).attr('data-result') == 'true') {
                $('#email-goverment-grid').yiiGridView('update', {
                    data: $('.search-form form').serialize()
                });
            }
            $('#modal-dialog').attr('data-result', 'false');
        });

    });

</script>



