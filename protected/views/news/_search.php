<?php
/**
 * @var $this CController
 * @var $model NewsSearch
 * @var $form BsActiveForm
 * @var $idForm string
 * @var $actionForm string
 */

    $asset = new DatepickerAsset();
    $asset->register();

    if (!isset($idForm) || empty($idForm))
    {
        $idForm = 'form-search';
    }

    if (!isset($actionForm) || empty($actionForm))
    {
        $actions = array_filter($this->actionParams, function ($k) {
            return !in_array($k, ['NewsSearch', 'yt0']);
        }, ARRAY_FILTER_USE_KEY);

        $actionForm = $this->createUrl($this->route, $actions);
    }

?>
<div class="panel panel-default">
    <div class="panel-body">
        <?php $form=$this->beginWidget('bootstrap.widgets.BsActiveForm', [
            'action'=>$actionForm,
            'method'=>'get',
            'htmlOptions' => [
                'id' => $idForm,
                'class' => 'input-append',
                'autocomplete'=>'off',
            ],
        ]); ?>
            <div class="col-sm-4">
                <?= $form->textField($model, 'team', ['class'=>'form-control', 'placeholder'=>'Поиск по тексту...'])  ?>
            </div>

            <div class="col-sm-3">
                <?= $form->textField($model, 'date_from', [
                    'class'=>'datepicker form-control krajee-datepicker',
                    'placeholder'=>'Поиск по дате от ...',
                    'prepend'=>'<i class="glyphicon glyphicon-calendar kv-dp-icon"></i>',
                ]) ?>
            </div>

            <div class="col-sm-3">
                <?= $form->textField($model, 'date_to', [
                    'class'=>'datepicker form-control krajee-datepicker',
                    'placeholder'=>'Поиск по дате до ...',
                    'prepend'=>'<i class="glyphicon glyphicon-calendar kv-dp-icon"></i>',
                ]) ?>
            </div>

            <div class="col-sm-2">
                <?= BsHtml::submitButton('Поиск', ['id' => 'btn-search-submit', 'class' => 'btn btn-primary']) ?>
                <?= BsHtml::resetButton('Очистить', ['id' => 'btn-search-clear', 'class' => 'btn btn-default']) ?>
            </div>

        <?php $this->endWidget(); ?>
    </div>
</div>
<script type="text/javascript">
    // $('#form-search').on('submit', function () {
    //     $('#btn-search-submit').prop('disabled', true);
    //     $('#btn-search-clear').prop('disabled', true);
    // })
</script>
