<?php

/**
 * Model for search
 * @author alexeeivch
 * @see News
 */
class NewsSearch extends News
{
    /**
     * Количество новостей
     * @var integer
     */
    const LIMIT_TOP_NEWS = 10;

    /**
     * Дополнительный параметр
     * @var string
     */
    public $param1;

    /**
     * Дата создания "с"
     * Используется для поиска на главной странице
     * @var string
     */
    public $date_from;

    /**
     * Дата создания "до"
     * Используется для поиска на главной странице
     * @var string
     */
    public $date_to;

    /**
     * Поисковое поле
     * @var string
     */
    public $team;


    /**
     * @inheritDoc
     * @return array
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            ['date_from, date_to, team', 'safe', 'on' => 'searchPublic'],
        ]);
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     *
     * Typical usecase:
     * - Initialize the model fields with values from filter form.
     * - Execute this method to get CActiveDataProvider instance which will filter
     * models according to data in model fields.
     * - Pass data provider to CGridView, CListView or any similar widget.
     *
     * @return CActiveDataProvider the data provider that can return the models
     * based on the search/filter conditions.
     */
    public function search($idTree = null)
    {
        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        if ($idTree === null) {
            $criteria->compare('id_tree', $this->id_tree);
        } else {
            $criteria->compare('id_tree', $idTree);
        }

        $criteria->compare('title', $this->title, true);
        $criteria->compare('message1', $this->message1, true);
        $criteria->compare('message2', $this->message2, true);
        $criteria->compare('author', $this->author, true);
        $criteria->compare('date_start_pub', $this->date_start_pub, true);
        $criteria->compare('date_end_pub', $this->date_end_pub, true);
        $criteria->compare('date_create', $this->date_create, true);
        $criteria->compare('date_delete', $this->date_delete);
        $criteria->compare('flag_enable', $this->flag_enable);
        $criteria->compare('general_page', $this->general_page);
        if (!(isset(Yii::app()->user->admin) && Yii::app()->user->admin))
            $criteria->addCondition('date_delete IS NULL');

        $criteria->compare('id_organization', Yii::app()->session['organization']);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => array(
                'defaultOrder' => 'date_sort desc, id desc',
            ),
        ));
    }

    /**
     * Search for frontend
     * @return CActiveDataProvider
     */
    public function searchPublic(/*$id = 0, $moduleNews = true*/)
    {
        /*
        $criteria = new CDbCriteria;

        $criteria->with = array('tree', 'organization');

        if ($id > 0)
        {
            //$criteria->compare("CONVERT(varchar,t.date_create,112)+right('0'+cast(DATEPART(HOUR,t.date_create) as varchar),2)+right('0'+cast(DATEPART(MINUTE,t.date_create) as varchar),2)+right('0'+cast(DATEPART(SECOND,t.date_create) as varchar),2)+CAST(t.id as varchar)", '<' . $id);
            $criteria->compare('convert(varchar,t.date_create,120) + cast(t.id as varchar)', '<' . $id);
        }

        $criteria->limit = self::LIMIT_TOP_NEWS;

        if (!empty($this->team))
        {
            $criteriaTeam = new CDbCriteria();
            $criteriaTeam->compare('t.title', $this->team, true, 'OR');
            $criteriaTeam->compare('t.message2', $this->team, true, 'OR');
            $criteria->mergeWith($criteriaTeam);
        }
        else
        {
            $criteria->compare('t.title', $this->title, true);
            $criteria->compare('t.message1', $this->message1, true);
            $criteria->compare('t.message2', $this->message2, true);
        }
        $criteria->compare('t.author', $this->author, true);
        $criteria->compare('t.tags', $this->tags, true);
        //$criteria->compare('t.date_start_pub',$this->date_start_pub,true);
        //$criteria->compare('t.date_end_pub',$this->date_end_pub,true);
        if ($this->date_from != null)
        {
            $criteria->compare('t.date_create', '>=' . $this->date_from);
        }
        if ($this->date_to != null)
        {
            $criteria->compare('t.date_create', '<=' . $this->date_to);
        }
        if ($moduleNews)
        {
            $criteria->compare('tree.module', 'news');
        }
        $criteria->addCondition('t.flag_enable=1 AND t.date_delete is null
            AND tree.date_delete is null AND t.date_start_pub < getdate()
            AND t.date_end_pub > getdate()');
        $criteria->compare('t.id_organization', $this->id_organization);
        $criteria->compare('tree.id', $this->id_tree);
        $criteria->compare('tree.param1', $this->param1);
        //$criteria->order = "CONVERT(varchar,t.date_create,112)+right('0'+cast(DATEPART(HOUR,t.date_create) as varchar),2)+right('0'+cast(DATEPART(MINUTE,t.date_create) as varchar),2)+right('0'+cast(DATEPART(SECOND,t.date_create) as varchar),2)+CAST(t.id as varchar) desc";
        $criteria->order = 'convert(varchar,t.date_create,120) + cast(t.id as varchar) desc';

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => array('defaultOrder' => 't.date_create desc, t.id desc'),
        ));*/
        $criteria = $this->baseCriteria();
        $criteria->compare('t.id_organization', $this->id_organization);
//        if ($moduleNews)
//        {
//            $criteria->compare('tree.module', 'news');
//        }

        return new CActiveDataProvider($this, [
            'criteria' => $criteria,
            'sort' => ['defaultOrder' => 't.date_sort desc, t.id desc'],
            'pagination' => [
                'pageSize' => $this->getPageSize(),
            ],
        ]);

    }

    /**
     * Новости для Управления
     * @param integer $date_sort
     * @return CActiveDataProvider
     */
    public function searchPublicUfns($date_sort = null)
    {
        $criteria = $this->baseCriteria();
        $criteria->compare('tree.module', 'news');
        $criteria->compare('t.id_organization', '8600');

        return new CActiveDataProvider($this, [
            'criteria' => $criteria,
            'sort' => ['defaultOrder' => 't.date_sort desc, t.id desc'],
            'pagination' => [
                'pageSize' => $this->getPageSize(),
            ],
        ]);
    }

    /**
     * Новости для Инспекций
     * @param integer $date_sort
     * @return CActiveDataProvider
     */
    public function searchPublicIfns($date_sort = null)
    {
        $criteria = $this->baseCriteria();
        $criteria->compare('tree.module', 'news');
        $criteria->compare('t.id_organization', '<>8600');

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => array('defaultOrder' => 't.date_sort desc, t.id desc'),
            'pagination' => [
                'pageSize' => $this->getPageSize(),
            ],
        ));
    }

    protected function baseCriteria()
    {
        $criteria = new CDbCriteria();

        $criteria->with = array('tree', 'organization');

        if (!empty($this->team))
        {
            $criteriaTeam = new CDbCriteria();
            $criteriaTeam->compare('t.title', $this->team, true, 'OR');
            $criteriaTeam->compare('t.message2', $this->team, true, 'OR');
            $criteria->mergeWith($criteriaTeam);
        }
        else
        {
            $criteria->compare('t.title', $this->title, true);
            $criteria->compare('t.message1', $this->message1, true);
            $criteria->compare('t.message2', $this->message2, true);
        }

        $criteria->compare('t.author', $this->author, true);
        $criteria->compare('t.tags', $this->tags, true);

        if ($this->date_from != null)
        {
            $criteria->compare('t.date_create', '>=' . $this->date_from);
        }
        if ($this->date_to != null)
        {
            $criteria->compare('t.date_create', '<=' . $this->date_to);
        }

        $criteria->addCondition('t.flag_enable=1 AND t.date_delete is null
            AND tree.date_delete is null AND t.date_start_pub < getdate()
            AND t.date_end_pub > getdate()');
        $criteria->compare('t.id_organization', $this->id_organization);
        $criteria->compare('tree.id', $this->id_tree);
        $criteria->compare('tree.param1', $this->param1);

        return $criteria;
    }

    private function getPageSize()
    {
        return Yii::app()->params['news']['pageSize'];
    }

    /**
     * Search for frontend
     * @return CActiveDataProvider
     */
    public function searchPublicOLD()
    {
        $criteria = new CDbCriteria;

        $criteria->with = array('tree', 'organization');

        $criteria->compare('t.id', $this->id);
        $criteria->compare('t.id_tree', $this->id_tree);
        $criteria->compare('t.title', $this->title, true);
        $criteria->compare('t.message1', $this->message1, true);
        $criteria->compare('t.message2', $this->message2, true);
        $criteria->compare('t.author', $this->author, true);
        $criteria->compare('t.date_start_pub', $this->date_start_pub, true);
        $criteria->compare('t.date_end_pub', $this->date_end_pub, true);
        $criteria->compare('t.date_create', $this->date_create, true);
        $criteria->compare('t.date_delete', $this->date_delete);

        $criteria->compare('tree.module', 'news');
        $criteria->addCondition('t.flag_enable=1 AND t.date_delete is null
            AND t.date_start_pub < getdate() AND t.date_end_pub > getdate()');


        $criteria->compare('t.id_organization', $this->id_organization);
        $criteria->compare('tree.param1', $this->param1);


        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => array('defaultOrder' => 't.date_create desc, t.id desc'),
        ));
    }

    /**
     * Поиск
     * @param string $page
     * @see CDbCriteria
     * @return CActiveDataProvider
     */
    public function searchPages($page = null) {
        $criteria = new CDbCriteria;

        $criteria->with = array('tree');

        $criteria->compare('t.id', $this->id);
        $criteria->compare('t.id_tree', $this->id_tree);
        $criteria->compare('t.title', $this->title, true);
        $criteria->compare('t.message1', $this->message1, true);
        $criteria->compare('t.message2', $this->message2, true);
        $criteria->compare('t.author', $this->author, true);
        $criteria->compare('t.date_start_pub', $this->date_start_pub, true);
        $criteria->compare('t.date_end_pub', $this->date_end_pub, true);
        $criteria->compare('t.date_create', $this->date_create, true);
        $criteria->compare('t.date_delete', $this->date_delete);
        $criteria->compare('t.id_organization', $this->id_organization);
        $criteria->compare('tree.param1', $page);
        $criteria->addCondition('t.flag_enable=1 AND t.date_delete is null
            AND t.date_start_pub < getdate() AND t.date_end_pub > getdate()');

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => array('defaultOrder' => 't.date_create desc'),
        ));
    }

    /**
     * Новости для раздела "Новость дня"
     * @param number $id
     * @return array
     * @uses NewsController::actionNewsDay()
     * @uses SiteController::actionIndex()
     */
    public static function getFeedNewsDay($id = 0, $team, $dateFrom, $dateTo) {
        $model = Yii::app()->db->createCommand()
            ->from('{{view_feed_news_day}}')
            ->limit(self::LIMIT_TOP_NEWS)
            ->order('date_create desc, id desc');
        
        if ($id > 0 && is_numeric($id))
        {
            $model->where('id<:id', [':id' => $id]);
        }
        
        // поиск текста
        if ($team != null && strlen($team)>0)
        {
            $like = '%'.$team.'%';
            $model->andWhere(['OR',
                ['like', 'title', $like],
                ['like', 'message1', $like],
                ['like', 'message2', $like],
            ]);
        }
        
        // поиск по дате от
        if ($dateFrom != null && strtotime($dateFrom))        
        {
            $model->andWhere('date_create>=:dateFrom', [':dateFrom'=>$dateFrom]);
        }
        
        // поиск по дате до
        if ($dateTo != null && strtotime($dateTo))        
        {
            $model->andWhere('date_create<=:dateTo', [':dateTo'=>$dateTo]);
        }
        
        return $model->queryAll();
    }

    /**
     * Новости
     * @param integer $id идентификатор новости
     * @return array|mixed
     * @uses SiteController::actionIndex()
     */
    public static function feedNews($id = 0) {
        $model = Yii::app()->db->createCommand()
            ->from('{{view_feed_news}}')
            ->limit(self::LIMIT_TOP_NEWS)
            ->order('date_create desc, id desc');

        if ($id > 0 && is_numeric($id)) {
            $model->where('id<:id', [':id' => $id]);
        }

        return $model->queryAll();
    }

    /**
     * Новости Управления
     * @return CDbCommand
     * @deprecated
     */
    public static function getFeedUFNS() {
        return Yii::app()->db->createCommand()
            ->from('{{view_feed_news_ufns}}')
            ->queryAll();
    }

    /**
     * Новости Инспекций
     * @return CDbCommand
     * @uses NewsController::actionNewsIfns()
     * @uses SiteController::actionIndex()
     */
    public static function getFeedIfns($id = 0, $team, $dateFrom, $dateTo) {
        $model = Yii::app()->db->createCommand()
            ->from('{{view_feed_news_ifns}}')
            ->order('date_create desc, id desc')
            ->limit(self::LIMIT_TOP_NEWS);

        if ($id > 0 && is_numeric($id)) {
            $model->where('id<:id', [':id' => $id]);
        }
        
        // поиск текста
        if ($team != null && strlen($team)>0) {
            $like = '%'.$team.'%';
            $model->andWhere(['OR', 
                ['like', 'title', $like],
                ['like', 'message1', $like],
                ['like', 'message2', $like],
            ]);
        }
        
        // поиск по дате от
        if ($dateFrom != null && strtotime($dateFrom)) {
            $model->andWhere('date_create>=:dateFrom', [':dateFrom'=>$dateFrom]);
        }
        
        // поиск по дате до
        if ($dateTo != null && strtotime($dateTo)) {
            $model->andWhere('date_create<=:dateTo', [':dateTo'=>$dateTo]);
        }
        
        return $model->queryAll();
    }

    /**
     * Дополнительные разделы
     * @return CDbCommand
     * @uses NewsController::actionHumor()
     * @uses SiteController::actionIndex()
     */
    public static function feedDopNews($module, $id = 0) {
        $model = Yii::app()->db->createCommand()
                ->from('{{view_feed_news}}')
                ->limit(self::LIMIT_TOP_NEWS)
                ->where('param1=:param1', [':param1' => $module])
                ->order('date_create desc, id desc');

        if ($id > 0 && is_numeric($id)) {
            $model->andWhere('id<:id', [':id' => $id]);
        }                

        return $model->queryAll();
    }

}
