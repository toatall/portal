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
	public $date_create_1;
	
	/**
	 * Дата создания "до"
	 * Используется для поиска на главной странице
	 * @var string
	 */
	public $date_create_2;
	
	
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
	public function search($idTree=null)
	{
		$criteria=new CDbCriteria;
		
		$criteria->compare('id',$this->id);
		if ($idTree===null)
		{
			$criteria->compare('id_tree',$this->id_tree);
		}
		else
		{
			$criteria->compare('id_tree',$idTree);
		}
	
		$criteria->compare('title',$this->title,true);
		$criteria->compare('message1',$this->message1,true);
		$criteria->compare('message2',$this->message2,true);
		$criteria->compare('author',$this->author,true);
		$criteria->compare('date_start_pub',$this->date_start_pub,true);
		$criteria->compare('date_end_pub',$this->date_end_pub,true);				
		$criteria->compare('date_create',$this->date_create,true);
		$criteria->compare('date_delete',$this->date_delete);
		$criteria->compare('flag_enable',$this->flag_enable);
		$criteria->compare('general_page',$this->general_page);
		if (!(isset(Yii::app()->user->admin) && Yii::app()->user->admin))
			$criteria->addCondition('date_delete IS NULL');
		
		$criteria->compare('id_organization', Yii::app()->session['organization']);
		
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>array(
				'defaultOrder'=>'id desc',
			),
		));
	}
	
	/**
	 * Search for frontend
	 * @return CActiveDataProvider
	 */
	public function searchPublic($id=0, $moduleNews=true)
	{	    	    
		$criteria=new CDbCriteria;
	   
		$criteria->with = array('tree','organization');
		
		if ($id>0)    	
    	   $criteria->compare("CONVERT(varchar,t.date_create,112)+right('0'+cast(DATEPART(HOUR,t.date_create) as varchar),2)+right('0'+cast(DATEPART(MINUTE,t.date_create) as varchar),2)+right('0'+cast(DATEPART(SECOND,t.date_create) as varchar),2)+CAST(t.id as varchar)", '<'.$id);
		
		$criteria->limit = self::LIMIT_TOP_NEWS;
		$criteria->compare('t.title',$this->title,true);
		$criteria->compare('t.message1',$this->message1,true);
		$criteria->compare('t.message2',$this->message2,true);
		$criteria->compare('t.author',$this->author,true);
		//$criteria->compare('t.date_start_pub',$this->date_start_pub,true);
		//$criteria->compare('t.date_end_pub',$this->date_end_pub,true);
		if ($this->date_create_1 != null)
		    $criteria->compare('t.date_create', '>=' . $this->date_create_1);
	    if ($this->date_create_2 != null)
	        $criteria->compare('t.date_create', '<=' . $this->date_create_2);
	    if ($moduleNews)
            $criteria->compare('tree.module','news');
		$criteria->addCondition('t.flag_enable=1 AND t.date_delete is null
            AND tree.date_delete is null AND t.date_start_pub < getdate()
            AND t.date_end_pub > getdate()');
		$criteria->compare('t.id_organization',$this->id_organization);
		$criteria->compare('tree.id', $this->id_tree);
		$criteria->compare('tree.param1', $this->param1);
		$criteria->order = "CONVERT(varchar,t.date_create,112)+right('0'+cast(DATEPART(HOUR,t.date_create) as varchar),2)+right('0'+cast(DATEPART(MINUTE,t.date_create) as varchar),2)+right('0'+cast(DATEPART(SECOND,t.date_create) as varchar),2)+CAST(t.id as varchar) desc";		
		
		return self::model()->findAll($criteria);		
	}
	
	/**
	 * Поиск
	 * @param string $page
	 * @see CDbCriteria
	 * @return CActiveDataProvider
	 */
	public function searchPages($page=null)
	{
		$criteria=new CDbCriteria;

		$criteria->with = array('tree');
		
		$criteria->compare('t.id',$this->id);
		$criteria->compare('t.id_tree',$this->id_tree);
		$criteria->compare('t.title',$this->title,true);
		$criteria->compare('t.message1',$this->message1,true);
		$criteria->compare('t.message2',$this->message2,true);
		$criteria->compare('t.author',$this->author,true);
		$criteria->compare('t.date_start_pub',$this->date_start_pub,true);
		$criteria->compare('t.date_end_pub',$this->date_end_pub,true);
		$criteria->compare('t.date_create',$this->date_create,true);
		$criteria->compare('t.date_delete',$this->date_delete);
		$criteria->compare('t.id_organization',$this->id_organization);
		$criteria->compare('tree.param1', $page);
		$criteria->addCondition('t.flag_enable=1 AND t.date_delete is null
            AND t.date_start_pub < getdate() AND t.date_end_pub > getdate()');
		 
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>array('defaultOrder'=>'t.date_create desc'),
		));
	}
	
		
    /**
     * Новости для раздела "Новость дня"
     * @param number $id
     * @return array
     * @uses NewsController::actionNewsDay()
     * @uses SiteController::actionIndex()
     */
	public static function getFeedNewsDay($id=0)
	{	   
	    $model = Yii::app()->db->createCommand()
	       ->from('{{view_feed_news_day}}')
	       ->limit(self::LIMIT_TOP_NEWS)
	       ->order('date_create desc, id desc');
	    
	    if ($id>0 && is_numeric($id))
	       $model->where('id<:id', [':id'=>$id]);
	       
	    return $model->queryAll();
	}
	
	/**
	 * Новости
	 * @param integer $id идентификатор новости
	 * @return array|mixed
	 * @uses SiteController::actionIndex()
	 */
	public static function feedNews($id=0)
	{
	    $model = Yii::app()->db->createCommand()
	       ->from('{{view_feed_news}}')
	       ->limit(self::LIMIT_TOP_NEWS)
	       ->order('date_create desc, id desc');
	    
	    if ($id>0 && is_numeric($id))
	        $model->where('id<:id', [':id'=>$id]);
	    
	    return $model->queryAll();
	}
	
	/**
	 * Новости Управления
	 * @return CDbCommand
	 * @deprecated
	 */
	public static function getFeedUFNS()
	{		    
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
	public static function getFeedIfns($id=0)
	{		    
	    $model = Yii::app()->db->createCommand()	        
    	    ->from('{{view_feed_news_ifns}}')
    	    ->order('date_create desc, id desc')
    	    ->limit(self::LIMIT_TOP_NEWS);
	    
	    if ($id>0 && is_numeric($id))
	        $model->where('id<:id', [':id'=>$id]);
	    
        return $model->queryAll();
	}
	
	/**
	 * Дополнительные разделы
	 * @return CDbCommand
	 * @uses NewsController::actionHumor()
	 * @uses SiteController::actionIndex()
	 */
	public static function feedDopNews($module, $id=0)
	{
	    $model = Yii::app()->db->createCommand()
    	    ->from('{{view_feed_news}}')
    	    ->limit(self::LIMIT_TOP_NEWS)
    	    ->where('param1=:param1', [':param1'=>$module])
    	    ->order('date_create desc, id desc');
	    
	    if ($id>0 && is_numeric($id))
	        $model->andWhere('id<:id', [':id'=>$id]);
	        
        return $model->queryAll();
	}
	
}