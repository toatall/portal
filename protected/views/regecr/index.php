<?php
    /* @var $queryResult array */
    /* @var $sum array */

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
        <h4>Фильтр</h4><br />
        <form method="get">
            <input type="text" name="date1" class="span2" data-type="date" value="<?= $date1 ?>" />
            <input type="text" name="date2" class="span2" data-type="date" value="<?= $date2 ?>" />        
            <button type="submit" class="btn btn-primary" style="margin-top:-10px;">Поиск</button>
        </form>    
    </div>
    
    <table class="table table-bordered table-striped table-hover">
        <tr>
            <th>Наименование НО</th>
            <th>Кол-во вновь созданных ООО</th>
            <th>Кол-во опрошенных</th>
            <th>Средняя оценка А 1.1 (средннее значение)</th>
            <th>Средняя оценка А 1.2 (средннее значение)</th>
            <th>Средняя оценка А 1.3 (средннее значение)</th>
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


<?php
Yii::app()->clientScript->registerScript('search2', "       
    jQuery('input[data-type=\"date\"]').datepicker({
        'format':'dd.mm.yyyy',
        'autoclose':'true',
        'todayBtn':'linked',
        'language':'ru',
        'weekStart':0            
    });   
");
?>

<?php    
    Yii::app()->clientScript->registerScriptFile(
        Yii::app()->baseUrl.'/extension/date-picker/bootstrap-datepicker.js');
    Yii::app()->clientScript->registerScriptFile(
        Yii::app()->baseUrl.'/extension/date-picker/bootstrap-datepicker.ru.js');
    Yii::app()->getClientScript()->registerCssFile(
        Yii::app()->baseUrl.'/extension/date-picker/bootstrap-datepicker.css');
?>