<?php

/**
 * This is the model class for table "{{conference}}".
 * 
 * 
 *
 * The followings are the available columns in table '{{conference}}':
 * @property integer $id
 * @property integer $type_conference
 * @property string $theme
 * @property string $responsible
 * @property string $members_people
 * @property string $members_organization
 * @property string $date_start
 * @property string $duration
 * @property boolean $is_confidential
 * @property string $date_create
 * @property string $date_edit
 * @property string $date_delete
 * @property bool $time_start_msk
 */
class Conference extends CActiveRecord
{
		
	/**
	 * ВКС с УФНС
	 * @var integer
	 */
	const TYPE_VKS_UFNS = 1;
	/**
	 * ВКС с ФНС
	 * @var integer
	 */
	const TYPE_VKS_FNS = 2;
	/**
	 * Собрания
	 * @var integer
	 */
	const TYPE_CONFERENCE = 3;
	
	private $notifyMailAddress = '8600_notifyVksUfns';
	
	/**
	 * Тип конференции
	 * @var array
	 * @uses getTypeName()
	 * @uses getTypeController()
	 */
	private $_typeConference = array(
		1 => [
			'name' => 'ВКС с УФНС',
			'controller' => 'vksUFNS',
		],
		2 => [
			'name' => 'ВКС с ФНС',
			'controller' => 'vksFNS',
		],
		3 => [
			'name' => 'Собрания',
			'controller' => 'conference',
		],
	);
	
	/**
	 * Флаг отвечающий за дополнительные настройки прав
	 * @var boolean
	 */
	public $useOptionalAccess = false;
		
	/**
	 * Дата начала конференции
	 * @var string
	 */
	public $_tempDateStart;
	
	/**
	 * Время начала конференции
	 * @var string
	 */	
	public $_tempTimeStart;
	
	/**
	 * Административная часть
	 * @var string
	 */
	public $backendSide = true;
	
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{conference}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{		
		return array(
			array('theme, _tempDateStart, _tempTimeStart', 'required'),
			array('type_conference, time_start_msk', 'numerical', 'integerOnly'=>true),
			array('theme', 'length', 'max'=>500),
			array('duration', 'length', 'max'=>20),
			array('place', 'length', 'max'=>100),
			array('type_conference', 'unsafe'),
			array('responsible, members_people, members_organization, is_confidential, note', 'safe'),			
			array('id, type_conference, theme, responsible, members_people, 
				members_organization, date_start, duration, is_confidential, 
				date_create, date_edit, date_delete, place, time_start_msk, note', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 * @deprecated
	 */
	public function relations()
	{		
		return array(
			
		);
	}
	
	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ИД',
			'type_conference' => 'Тип конференции',
			'theme' => 'Тема',
			'responsible' => 'Отвественные',
			'members_people' => 'Участники (сотрудники Управления)',
			'members_organization' => 'Участники (Инспекции)',
			'date_start' => 'Дата и время начала',
			'_tempDateStart' => 'Дата начала',
			'_tempTimeStart' => 'Время начала',
			'duration' => 'Продолжительность',
			'is_confidential' => 'Конфиценциально',
			'date_create' => 'Дата создания',
			'date_edit' => 'Дата изменения',
			'date_delete' => 'Дата удаления',
			'time_start_msk' => 'Время московское',
			'place' => 'Место проведения',
			'note' => 'Примечание',
			'dateStartFormat'=>'Дата начала',
			'timeStartFormat'=>'Время начала',
		);
	}
		
	/**
	 * Дополнительные аттрибуты: для собрания
	 * @return array
	 */
	public function attributeConference()
	{
		return array(
			'is_confidential',
			'place',
		);
	}
	
	/**
	 * Дополнительные аттрибуты: для ВКС с ФНС
	 * @return array
	 */
	public function atttributeVksFns()
	{
		return array(
			'time_start_msk',
		);
	}
	
	/**
	 * Дополнительные аттрибуты: для ВКС с УФНС
	 * @return array
	 */
	public function attributeVksUfns()
	{
		return array(
			'responsible',				
		);
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
	 * @uses getDataArray()
	 */
	public function search($typeConference=null)
	{
		$criteria=new CDbCriteria;
		
		$criteria->compare('id',$this->id);
		$criteria->compare('type_conference',($typeConference!==null) ? $typeConference : $this->type_conference);
		$criteria->compare('theme',$this->theme,true);
		$criteria->compare('responsible',$this->responsible,true);
		$criteria->compare('members_people',$this->members_people,true);
		$criteria->compare('members_organization',$this->members_organization,true);
		$criteria->compare('date_start',$this->date_start,true);
		$criteria->compare('duration',$this->duration,true);
		$criteria->compare('is_confidential',$this->is_confidential);
		$criteria->compare('date_create',$this->date_create,true);
		$criteria->compare('date_edit',$this->date_edit,true);		
		$criteria->compare('time_start_msk',$this->time_start_msk);
		$criteria->compare('place',$this->place,true);
		$criteria->compare('note',$this->place,true);
		
		if ($this->backendSide)
		{
		    $criteria->compare('date_delete',$this->date_delete,true);
		}
		else
		{
		    $criteria->addCondition('date_delete is null');
		}		
		
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>array(
				'defaultOrder' => 'date_start desc',
			),
		));
	}
	
	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Conference the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	/**
	 * {@inheritDoc}
	 * @see CActiveRecord::beforeSave()
	 * @see DateHelper
	 * @see CDbExpression
	 */
	protected function beforeSave()
	{
		$this->date_start = DateHelper::implodeDateTime($this->_tempDateStart, $this->_tempTimeStart);		
		$this->date_create = new CDbExpression('getdate()');
		return parent::beforeSave();
	}
	
	/**
	 * {@inheritDoc}
	 * @see CActiveRecord::afterFind()
	 * @see DateHelper
	 */
	protected function afterFind()
	{
		$this->date_create = DateHelper::explodeDateTime($this->date_create);
		$this->date_start = DateHelper::explodeDateTime($this->date_start);
		$this->_tempDateStart = DateHelper::explodeDate($this->date_start);
		$this->_tempTimeStart = DateHelper::explodeTime($this->date_start);
		return parent::afterFind();
	}
	
	/**
	 * Наименование вида собрания 
	 * @return string|null
	 */
	public function getTypeName()
	{
		return (isset($this->_typeConference[$this->type_conference]['name']) ? 
			$this->_typeConference[$this->type_conference]['name'] : null);
	}
	
	/**
	 * Наименование контроллера для текущего собрания
	 * @return string|null
	 */
	public function getTypeController()
	{
		return (isset($this->_typeConference[$this->type_conference]['controller']) ? 
			$this->_typeConference[$this->type_conference]['controller'] : null);
	}
	
	/**
	 * Аттрибуты для предпросмотра модели
	 * @return array
	 */
	public function getAttrForView()
	{
		$arrayAttr = array(
			'id',
			'theme',
			'members_people',			
			'date_start',	
			'duration',
			'note',
			'date_create',
		);
		
		switch ($this->type_conference)
		{
			case self::TYPE_CONFERENCE:
				{
					$arrayAttr = array_merge($arrayAttr, [
						'place',
						array(
							'name'=>'is_confidential',
							'value'=>($this->is_confidential ? 'Да' : 'Нет'),
						),
					]);
					break;
				}
			case self::TYPE_VKS_UFNS:
				{
					$arrayAttr = array_merge($arrayAttr, [
						'responsible',
						'members_organization',
					]);
					break;
				}
			case self::TYPE_VKS_FNS:
				{
					$arrayAttr = array_merge($arrayAttr, [
						
					]);
					break;
				}
		}
		
		return $arrayAttr;
	}
	
	/**
	 * Идентификатор структуры
	 * @see Tree
	 * @return int|null
	 */
	public function getTreeId()
	{
		$modelTree = Tree::model()->find('module=:module',[':module'=>$this->_typeConference[$this->type_conference]['controller']]);
		if ($modelTree !== null && count($modelTree)>0)
		{
			return $modelTree->id;
		}
		return null;
	}
	
	/**
	 * Получение данных в виде массива из CActiveDataProvider
	 * @return array
	 */
	public function getDataArray()
	{
		$resultArray = array();
		$search = $this->search();
		foreach ($search->data as $data)
		{
			$resultArray[date('d.m.Y',strtotime($data->date_start))][] = $data;
		}
		return array('search' => $resultArray, 'provider'=>$search);
	}
	
	/**
	 * Для представления
	 * @see DateHelper
	 * @return string
	 * @uses
	 */
	public function getDateStartFormat()
	{
		return "<time class=\"icon\">".
			   "<em>".DateHelper::monthByNumber(date('m',strtotime($this->date_start))).' '.date('Y',strtotime($this->date_start))."</em>".
			   "<strong>".DateHelper::weekByNumber(date('N',strtotime($this->date_start)))."</strong>".
			   "<span>".intval(date('d',strtotime($this->date_start)))."</span>";		
	}
	
	/**
	 * Форматирование даты для представления
	 * @return string
	 */
	public function getTimeStartFormat()
	{
		return "<h3>".date('H:i',strtotime($this->date_start))."</h3>";
	}
	
	/**
	 * Отправка уведомления по почте
	 * о создании/изменении мероприятия	
	 * @param string $to адрес получателя
	 */
	public function notifyEmail($to=null)
	{
	    if (empty($to))
	        $to = $this->notifyMailAddress;
        
        $conferenceName = $this->_typeConference[$this->type_conference]['name'];
        
	    $subject = 'Уведомление о ' . $conferenceName . ', назначенное на ' . $this->date_start;
	    $message = '<h1>' . date('d.m.Y', strtotime($this->date_start)) . ' в ' .  date('H:i:s', strtotime($this->date_start)) . ' будет проводиться ' . $conferenceName . '</h1>';
	    $message .= '<br />Тема: ' . $this->theme;	    
	    $message .= '<br /><br /><a href="' . Yii::app()->controller->createAbsoluteUrl('/conference/view', ['id'=>$this->id]) . '" target="_blank">Подробнее...</a>';
	        
	    $headers = 'From: =?utf-8?b?' . base64_encode($conferenceName . ' <portal86@regions.tax.nalog.ru>') . '?=' . "\r\n";	   
	    $headers .= 'Content-Type: text/html; charset=utf-8' . "\r\n";
	    
	    mb_language('ru');
	    mb_send_mail($to, $subject, $message, $headers);	    
	}
		
	
	
}
