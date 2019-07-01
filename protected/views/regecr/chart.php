<?php
/* @var $queryIfns array */
/* @var $date1 string */
/* @var $date2 string */


$this->breadcrumbs = array(
    'Анкетирование по ГР (график)',
);

?>

<?php    
    Yii::app()->clientScript->registerScriptFile(
        Yii::app()->baseUrl.'/extension/chart.js/Chart.min.js');
?>

<script type="text/javascript">
    
    window.chartColors = {
	red: 'rgb(255, 99, 132)',
	orange: 'rgb(255, 159, 64)',
	yellow: 'rgb(255, 205, 86)',
	green: 'rgb(75, 192, 192)',
	blue: 'rgb(54, 162, 235)',
	purple: 'rgb(153, 102, 255)',
	grey: 'rgb(201, 203, 207)'
    };
    
    function setChartData(chart_id, getData, orgName)
    {
        var config = {
            type: 'line',
            data: getData,
            options: {
                responsive: true,
                title: {
                    display: true,
                    text: orgName,
                },
                scales: {
                    xAxes: [{
                        display: true,
                        scaleLabel: {
                            display: true,
                            labelString: 'Даты'
                        }
                        }],
                    yAxes: [{                       
                        position: 'left',
                        display: true,
                        scaleLabel: {
                            display: true,
                            labelString: 'Значения'
                        },
                        ticks: {
                            //reverse: true
                            beginAtZero: true
                        }
                    }]
                }
            }
        };
        var ctx = document.getElementById(chart_id).getContext('2d');
        window.myLine = new Chart(ctx, config);
    }
        
</script>

<div class="content">
    <h1>Анкетирование по ГР (график)</h1>
    <hr />
    <?= CHtml::link('Статистика', ['regecr/index'], ['class'=>'btn btn-default']) ?>&nbsp;&nbsp;
    <?= CHtml::link('Детализация', ['regecr/detail'], ['class'=>'btn btn-default']) ?>
    <hr />
    
    <div class="alert alert-info">
        <h4>Фильтр</h4><br />
        <form method="get">
            <input type="text" name="date1" class="span2" data-type="date" value="<?= $date1 ?>" />
            <input type="text" name="date2" class="span2" data-type="date" value="<?= $date2 ?>" />        
            <button type="submit" class="btn btn-primary" style="margin-top:-10px;">Поиск</button>
        </form>    
    </div>
    
    <div class="alert alert-info">
        <h4 class="divider">Описание</h4><br />
        <ul>
            <li><b>Средняя оценка А 1.1</b> - Средняя оценка респондентами по показателю А 1.1 "Среднее время регистрации, юридических лиц", дней (среднее арифметическое от общего количества опрошенных респондентов)</li>
            <li><b>Средняя оценка А 1.2</b> - Средняя оценка респондентами по показателю А 1.2 "Среднее количество процедур, необходимых для регистрации юридических лиц", штук (среднее арифметическое от общего количества опрошенных респондентов)</li>
            <li><b>Средняя оценка А 1.3</b> - Средняя оценка респондентами по показателю А 1.3 "Оценка деятельности органов власти по регистрации, юридических лиц", баллов (среднее арифметическое от общего количества опрошенных респондентов)</li>
        </ul>
    </div>
    
    <?php foreach ($queryIfns as $ifns): ?>

        <div style="width:75%">
            <canvas id="canvas_<?= $ifns['code'] ?>"></canvas>
        </div>
        <hr />
        <script type="text/javascript">
            $.get('<?= $this->createUrl('regecr/chartAjax', ['code_org'=>$ifns['code'], 'date1'=>$date1, 'date2'=>$date2]) ?>')
                .success(function(data) {
                    setChartData('canvas_<?= $ifns['code'] ?>', data.data, data.orgName);
            });        
        </script>
    <?php endforeach; ?>

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