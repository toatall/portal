<?php
    /* @var $queryResult array */
    /* @var $sum array */

    $assetDatepicker = new DatepickerAsset();
    $assetDatepicker->register();

$this->breadcrumbs = array(
    'Анкетирование по ГР',
);

?>

<div class="content">
    <h1>Анкетирование по ГР</h1>
    <hr />
    <?= CHtml::link('Детализация', ['regecr/detail'], ['class'=>'btn btn-default']) ?>&nbsp;&nbsp;
    <?= CHtml::link('В виде графика', ['regecr/chart'], ['class'=>'btn btn-default']) ?>
    <hr />


    <div class="alert alert-info">
        <div class="panel-body">
            <form method="get">
                <div class="col-sm-3">
                    <?= BsHtml::textField('date1', $date1, [
                        'class'=>'datepicker form-control krajee-datepicker',
                        'placeholder'=>'Поиск по дате от ...',
                        'prepend'=>'<i class="glyphicon glyphicon-calendar kv-dp-icon"></i>',
                        'data-type' => 'date',
                    ]) ?>
                </div>
                <div class="col-sm-3">
                    <?= BsHtml::textField('date2', $date2, [
                        'class'=>'datepicker form-control krajee-datepicker',
                        'placeholder'=>'Поиск по дате от ...',
                        'prepend'=>'<i class="glyphicon glyphicon-calendar kv-dp-icon"></i>',
                        'data-type' => 'date',
                    ]) ?>
                </div>

                <button type="submit" class="btn btn-primary" style="">Поиск</button>
            </form>
        </div>
    </div>

    
    <table class="table table-bordered table-striped table-hover">
        <tr>
            <th>Наименование НО</th>
            <th>Кол-во вновь созданных ООО</th>
            <th>Кол-во опрошенных</th>
            <th>Средняя оценка А 1.1 (средннее значение) <span class="badge badge-info" data-toggle="popover" data-original-title='Средняя оценка респондентами по показателю А 1.1 "Среднее время регистрации, юридических лиц", дней (среднее арифметическое от общего количества опрошенных респондентов)'><i class="fa fa-info"></i></span></th>
            <th>Средняя оценка А 1.2 (средннее значение) <span class="badge badge-info" data-toggle="popover" data-original-title='Средняя оценка респондентами по показателю А 1.2 "Среднее количество процедур, необходимых для регистрации юридических лиц", штук (среднее арифметическое от общего количества опрошенных респондентов)'><i class="fa fa-info"></i></span></th>
            <th>Средняя оценка А 1.3 (средннее значение) <span class="badge badge-info" data-toggle="popover" data-original-title='Средняя оценка респондентами по показателю А 1.3 "Оценка деятельности органов власти по регистрации, юридических лиц", баллов (среднее арифметическое от общего количества опрошенных респондентов)'><i class="fa fa-info"></i></span></th>
        </tr>
        <tr>
            <th>8600</th>   
            <th><?= $sum['count_create'] ?></th>        
            <th><?= $sum['count_vote'] ?></th>
            <th><?= round($sum['avg_eval_a_1_1'], 2) ?></th>
            <th><?= round($sum['avg_eval_a_1_2'], 2) ?></th>
            <th><?= round($sum['avg_eval_a_1_3'], 2) ?></th>
        </tr>
        <?php foreach ($queryResult as $result): ?>
        <tr>
            <td><?= $result['code_org'] ?></td> 
            <td><?= $result['count_create'] ?></td>        
            <td><?= $result['count_vote'] ?></td>
            <td><?= $result['avg_eval_a_1_1'] ?></td>
            <td><?= $result['avg_eval_a_1_2'] ?></td>
            <td><?= $result['avg_eval_a_1_3'] ?></td>
        </tr>    
        <?php endforeach; ?>
    </table>
</div>    
