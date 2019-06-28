<?php

/**
 * @author toatall
 */
class RegecrController extends Controller 
{

    const CHART_COLOR_COUNT_CREATE = 'rgb(255, 99, 132)';       // red
    const CHART_COLOR_COUNT_VOTE = 'rgb(255, 159, 64)';         // organge
    const CHART_COLOR_AVG_EVAL_A_1_1 = 'rgb(75, 192, 192)';     // green
    const CHART_COLOR_AVG_EVAL_A_1_2 = 'rgb(54, 162, 235)';     // blue
    const CHART_COLOR_AVG_EVAL_A_1_3 = 'rgb(201, 203, 207)';    // gray   
    
    /**
     * Default controller
     */
    public function actionDetail() 
    {        
        $model=new RegEcr('search');
        $model->unsetAttributes();  // clear any default values
        if(isset($_GET['RegEcr'])) 
        {
            $model->attributes=$_GET['RegEcr'];
        }
        
        if (!$model->date1) 
        {
            $model->date1 = date_add(new DateTime('now'), date_interval_create_from_date_string('-1 month'))->format('d.m.Y');
        }
        
        if (!$model->date2) 
        {
            $model->date2 = date('d.m.Y');
        }
        
        return $this->render('detail', ['model' => $model]);        
    }
    
    /**
     * Chart
     */
    public function actionChart($date1=null, $date2=null)
    {
        $this->loadDefaultDate($date1, $date2);
        
        $query = Organization::model()->findAll();
        
        return $this->render('chart', [
            'queryIfns'=>$query,
            'date1'=>$date1,
            'date2'=>$date2,
        ]);
    }
    
    /**
     * 
     * @param type $code_org
     * @param type $date1
     * @param type $date2
     */
    public function actionChartAjax($code_org=null, $date1=null, $date2=null)
    {       
        $this->loadDefaultDate($date1, $date2);
        $modelOrg = $this->loadOrganization($code_org);
        $result = $this->loadChartData($code_org, $date1, $date2);
        
        header('Content-type: application/json');
        echo CJSON::encode([
            'orgName' => $modelOrg->fullName,
            'data' => 
            [            
                'labels' => $result['labels'],
                'datasets' => [      
                    [
                        'label' => 'Количество вновь созданных ООО',
                        'data' => $result['count_create'],
                        'borderColor' => self::CHART_COLOR_COUNT_CREATE,
                        'backgroundColor' => self::CHART_COLOR_COUNT_CREATE,
                        'fill' => false,
                    ],
                    [
                        'label' => 'Кол-во опрошенных',
                        'data' => $result['count_vote'],
                        'borderColor' => self::CHART_COLOR_COUNT_VOTE,
                        'backgroundColor' => self::CHART_COLOR_COUNT_VOTE,
                        'fill' => false,
                    ],
                    [
                        'label' => 'Средняя оценка А 1.1',
                        'data' => $result['avg_eval_a_1_1'],
                        'borderColor' => self::CHART_COLOR_AVG_EVAL_A_1_1,
                        'backgroundColor' => self::CHART_COLOR_AVG_EVAL_A_1_1,
                        'fill' => false,
                    ],
                    [
                        'label' => 'Средняя оценка А 1.2',
                        'data' => $result['avg_eval_a_1_2'],
                        'borderColor' => self::CHART_COLOR_AVG_EVAL_A_1_2,
                        'backgroundColor' => self::CHART_COLOR_AVG_EVAL_A_1_2,
                        'fill' => false,
                    ],
                    [
                        'label' => 'Средняя оценка А 1.3',
                        'data' => $result['avg_eval_a_1_3'],
                        'borderColor' => self::CHART_COLOR_AVG_EVAL_A_1_3,
                        'backgroundColor' => self::CHART_COLOR_AVG_EVAL_A_1_3,
                        'fill' => false,
                    ],
                ],
            ]
        ]);
        Yii::app()->end();
    }
    
    /**
     * 
     * @param type $code_org
     * @return type
     * @throws CHttpException
     */
    private function loadOrganization($code_org)
    {
        $model = Organization::model()->findByPk($code_org);
        if ($model === null)
        {
            throw new CHttpException(404,'Страница не найдена.');
        }
        return $model;
    }
    
    /**
     * 
     * @param type $code_org
     * @param type $date1
     * @param type $date2
     * @return type
     */
    private function loadChartData($code_org, $date1, $date2)
    {
        if ($code_org == '8600')
        {
            $query = Yii::app()->db->createCommand()
                ->from('{{reg_ecr}}')
                ->select('date_reg, sum(count_create) count_create, sum(count_vote) count_vote, '
                        . 'avg(avg_eval_a_1_1) avg_eval_a_1_1, avg(avg_eval_a_1_2) avg_eval_a_1_2, avg(avg_eval_a_1_3) avg_eval_a_1_3')
                ->where('date_delete is null and date_reg >= cast(:date1 as datetime) and date_reg <= cast(:date2 as datetime)', [
                    ':date1' => $date1,
                    ':date2' => $date2,                    
                ])
                ->group('date_reg')
                ->queryAll();
        }
        else
        {
            $query = Yii::app()->db->createCommand()
                ->from('{{reg_ecr}}')
                ->where('date_delete is null and date_reg >= cast(:date1 as datetime) and date_reg <= cast(:date2 as datetime) and code_org=:code_org', [
                    ':date1' => $date1,
                    ':date2' => $date2,
                    ':code_org' => $code_org,
                ])
                ->queryAll();
        }
        
        $result = [];
        $result['labels'] = [];
        $result['count_create'] = [];
        $result['count_vote'] = [];
        $result['avg_eval_a_1_1'] = [];
        $result['avg_eval_a_1_2'] = [];
        $result['avg_eval_a_1_3'] = [];

        foreach ($query as $q)
        {
            $result['labels'][] = date('d.m.Y', strtotime($q['date_reg']));
            $result['count_create'][] = $q['count_create'];
            $result['count_vote'][] = $q['count_vote'];
            $result['avg_eval_a_1_1'][] = $q['avg_eval_a_1_1'];
            $result['avg_eval_a_1_2'][] = $q['avg_eval_a_1_2'];
            $result['avg_eval_a_1_3'][] = $q['avg_eval_a_1_3'];
        }
        return $result;        
    }
        
    /**
     * Index
     * @param type $date1
     * @param type $date2
     * @return type
     */
    public function actionIndex($date1=null, $date2=null)
    {
        if (!$date1) 
        {
            $date1 = date_add(new DateTime('now'), date_interval_create_from_date_string('-1 month'))->format('d.m.Y');
        }
        
        if (!$date2) 
        {
            $date2 = date('d.m.Y');
        }
               
        
        $query = Yii::app()->db->createCommand()
            ->from('{{reg_ecr}}')
            ->select('code_org, sum(count_create) count_create, sum(count_vote) count_vote, avg(avg_eval_a_1_1) avg_eval_a_1_1, '
                    . 'avg(avg_eval_a_1_2) avg_eval_a_1_2, avg(avg_eval_a_1_3) avg_eval_a_1_3')
            ->where('date_reg >= :date1 and date_reg <= :date2 and date_delete is null', [':date1'=>$date1, ':date2'=>$date2])
            ->group('code_org')
            ->queryAll();                
        
        $sum = [
            'count_create' => 0,
            'count_vote' => 0,
            'avg_eval_a_1_1' => 0,
            'avg_eval_a_1_2' => 0,
            'avg_eval_a_1_3' => 0,
        ];
        
        foreach ($query as $q)
        {
            $sum['count_create'] += $q['count_create'];
            $sum['count_vote'] += $q['count_vote'];
            $sum['avg_eval_a_1_1'] += $q['avg_eval_a_1_1'];
            $sum['avg_eval_a_1_2'] += $q['avg_eval_a_1_2'];
            $sum['avg_eval_a_1_3'] += $q['avg_eval_a_1_3'];
        }
        $sum['avg_eval_a_1_1'] = ($sum['avg_eval_a_1_1']) ? ($sum['avg_eval_a_1_1'] / count($query)) : $sum['avg_eval_a_1_1'];
        $sum['avg_eval_a_1_2'] = ($sum['avg_eval_a_1_2']) ? ($sum['avg_eval_a_1_2'] / count($query)) : $sum['avg_eval_a_1_2'];
        $sum['avg_eval_a_1_3'] = ($sum['avg_eval_a_1_3']) ? ($sum['avg_eval_a_1_3'] / count($query)) : $sum['avg_eval_a_1_3'];
        
        
        return $this->render('index', [
            'queryResult'=>$query, 
            'sum'=>$sum,
            'date1'=>$date1,
            'date2'=>$date2,
        ]);
    }
    
    
    private function loadDefaultDate(&$date1, &$date2)
    {
        if (!$date1) 
        {
            $date1 = date_add(new DateTime('now'), date_interval_create_from_date_string('-1 month'))->format('d.m.Y');
        }
        
        if (!$date2) 
        {
            $date2 = date('d.m.Y');
        }
    }
    
    
    

}
