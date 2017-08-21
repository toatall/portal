<?php

/**
 * Model for search
 * @author tvog17
 * @version 29.06.2017
 */
class NewsSearch extends News
{
	
	
	const LIMIT_TOP_NEWS = 5;
	
	
	public $param1;
	
	
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
	public function searchPublic()
	{
		$criteria=new CDbCriteria;		
	
		$criteria->with = array('tree','organization');
	
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
		
		$criteria->compare('tree.module','news');
		$criteria->addCondition('t.flag_enable=1 AND t.date_delete is null
            AND t.date_start_pub < getdate() AND t.date_end_pub > getdate()');
		
		
		$criteria->compare('t.id_organization',$this->id_organization);
		
		$criteria->compare('tree.param1', $this->param1);
		
		
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>array('defaultOrder'=>'t.date_create desc, t.id desc'),
		));
	}
	
	
	
	public function searchPages($page)
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
	 * Базовый запрос для выборки новостей 
	 * @return CDbCommand
	 */
	private function queryBaseTabsNews()
	{
		$criteria = new CDbCriteria();
		$criteria->with = array('tree', 'organization');
		$criteria->compare('t.flag_enable', 1);
		$criteria->addCondition('t.date_delete is null');
		$criteria->addCondition('t.date_start_pub < getdate()');
		$criteria->addCondition('t.date_end_pub > getdate()');
		$criteria->order = 't.date_create desc, t.id desc';
		
		return $criteria;	
	}
	
	
	public function getFeedNewsDay()
	{
	    $criteria = $this->queryBaseTabsNews();
	    $criteria->compare('tree.module', 'news');
	    
	    $criteria->addCondition('t.on_general_page=:general_page');	    
	    $criteria->params[':general_page'] = 1;
	    $criteria->limit = self::LIMIT_TOP_NEWS;
	    
	    return new CActiveDataProvider($this, [
	        'criteria' => $criteria,
	        'pagination'=>false,
	    ]);
	}
	
	
	/**
	 * Новости Управления
	 * @return CDbCommand
	 */
	public function getFeedUFNS()
	{	
	    $criteria = $this->queryBaseTabsNews();
	    $criteria->compare('tree.module', 'news');
	    $criteria->compare('t.id_organization', '8600');
	    $criteria->limit = self::LIMIT_TOP_NEWS;
	    
	    return new CActiveDataProvider($this, [
	        'criteria' => $criteria,
	        'pagination'=>false,
	    ]);
	}
	
	
	/**
	 * Новости Инспекций
	 * @return CDbCommand
	 */
	public function getFeedIfns()
	{		
		$criteria = $this->queryBaseTabsNews();
		$criteria->compare('tree.module', 'news');
		$criteria->compare('t.id_organization', '<>8600');		
		$criteria->limit = self::LIMIT_TOP_NEWS;
		
		return new CActiveDataProvider($this, [
			'criteria' => $criteria,
			'pagination'=>false,
		]);
	}
	
	
	
	//------------------------------------------------	
	
	/**
	 * Дополнительные разделы
	 * @return CDbCommand
	 */
	public function feedDopNews($module)
	{
		/*
		$query = $this->queryBaseTabsNews();
		$query->andWhere('tree.module=:module', [':module'=>'news']);
		$query->andWhere('tree.param1=:param1', [':param1'=>$module]);
		$query->limit = self::LIMIT_TOP_NEWS;
		return $query->queryAll();
		*/
		$criteria = $this->queryBaseTabsNews();
		$criteria->compare('tree.module', 'news');
		$criteria->compare('tree.param1', $module);		
		$criteria->limit = self::LIMIT_TOP_NEWS;
		
		return new CActiveDataProvider($this, [
			'criteria' => $criteria,
			'pagination'=>false,
		]);
	}
	
	
}