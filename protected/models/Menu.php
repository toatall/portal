<?php

/**
 * This is the model class for table "{{menu}}".
 *
 * The followings are the available columns in table '{{menu}}':
 * @property integer $id
 * @property integer $id_parent
 * @property integer $type_menu
 * @property string $name
 * @property string $link
 * @property string $submenu_code
 * @property string $date_create
 * @property string $date_edit
 * @property string $author
 */
class Menu extends CActiveRecord
{
	/**
	 * Левое меню
	 * @var array
	 * @uses DepartmnetController::loadMenu()
	 */
	public static $leftMenuAdd = array();
	
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{menu}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{		
		return array(
			array('id_parent, type_menu, name, blocked', 'required'),
			array('id_parent, type_menu, sort_index', 'numerical', 'integerOnly'=>true),
            array('target', 'length', 'max'=>10),
			array('name, author', 'length', 'max'=>45),
			array('link', 'length', 'max'=>500),
			array('submenu_code', 'length', 'max'=>1000),
			array('date_create, date_edit', 'safe'),	
		    // search
			array('id, id_parent, type_menu, name, link, submenu_code, 
				date_create, date_edit, author', 'safe', 'on'=>'search'),
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
			'id_parent' => 'ИД родителя',
			'type_menu' => 'Тип меню',
			'name' => 'Наименование',
			'link' => 'Ссылка',
			'submenu_code' => 'Выражение для подменю',
            'target' => 'Аттрибут target',
			'date_create' => 'Дата создания',
			'date_edit' => 'Дата изменения',
			'author' => 'Автор',
            'blocked' => 'Блокировка',
            'sort_index' => 'Сортировка',
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
	 * @see CDbCriteria
	 */
	public function search()
	{
		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('id_parent',$this->id_parent);
		$criteria->compare('type_menu',$this->type_menu);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('link',$this->link,true);
		$criteria->compare('submenu_code',$this->submenu_code,true);
        $criteria->compare('target',$this->target,true);
		$criteria->compare('date_create',$this->date_create,true);
		$criteria->compare('date_edit',$this->date_edit,true);
		$criteria->compare('author',$this->author,true);
        $criteria->compare('blocked',$this->blocked,true);
        $criteria->compare('sort_index',$this->blocked,true);
        
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Menu the static model class
	 * @uses getTree()
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
    
    /**
     * {@inheritDoc}
     * @see CActiveRecord::beforeSave()    
     */
    protected function beforeSave()
    {
        if ($this->isNewRecord)
        {
            $this->date_create = new CDbExpression('getdate()');
            $this->author = Yii::app()->user->name;
        }
        else
        {
            $this->date_edit = new CDbExpression('getdate()');
        }
        return parent::beforeSave();
    }
    
    /**
     * {@inheritDoc}
     * @see CActiveRecord::afterFind()
     * @see DateHelper
     */
    protected function afterFind()
    {
    	parent::afterFind();
    	$this->date_create = DateHelper::explodeDateTime($this->date_create);
    	$this->date_edit = DateHelper::explodeDateTime($this->date_edit);
    }

    /**
     * Построение дерева меню сайта
     * относительно текущего НО (Yii::app()->session['code_no'])
     * @param int $id
     * @param int $parent_id
     * @param int $type_menu   
     * @see Access
     * @return array
     */
    public function getTree($id=0, $parent_id=0, $type_menu)
    {
        $criteria=new CDbCriteria;
        $criteria->addCondition('id_parent='.$parent_id);
        $criteria->addCondition('id<>'.$id); 
        $criteria->addCondition('type_menu='.$type_menu);                                  
                
        $orgData = self::model()->findAll($criteria);
        
        $data = array();
        
        foreach ($orgData as $value)
        {   
            if (Yii::app()->user->admin || Access::checkAccessUserForTree($value->id))
            {
                $data[] = array(
                    'id'=>$value->id,
                    'text'=>'<i class="icon-folder-open"></i>&nbsp;'                                                
                        .$value->name.'&nbsp'.
                        
                         CHtml::link('<i class="icon-eye-open"></i>', 
                            array('view', 'id'=>$value->id),
                            array('class'=>'view', 'data-original-title'=>'Просмотреть', 
                                'rel'=>'tooltip')).'&nbsp'
                        .CHtml::link('<i class="icon-pencil"></i>', 
                            array('update', 'id'=>$value->id),
                            array('class'=>'update', 'data-original-title'=>'Редактировать', 
                                'rel'=>'tooltip')).'&nbsp'
                        .CHtml::link('<i class="icon-trash"></i>', 
                            '#', 
                            array(
                                'submit'=>array('delete', 'id'=>$value->id),
                                'confirm'=>'Вы уверены что хотите удалить "'.$value->name.'"? Все дочерние подразделы будут удалены!',
                                'class'=>'delete',
                                'data-original-title'=>'Удалить',
                                'rel'=>'tooltip',
                            )
                        ),
                    'children'=>$this->getTree($id, $value->id, $type_menu),
                );
            }
            else
            {
                $data = array_merge($data, $this->getTree($id, $value->id));
            }
        }
        return $data;
    } 
        
    /**
     * Дерево меню для DropDownList
     * @param int $type_menu тип меню (сверху, слева)
     * @param int $id идентификатор меню, которое не должно отображаться
     * @param int $parent_id идентификатор родительского меню
     * @param int $level уровень вложенности
     * @see CDbCriteria
     * @see Access
     * @return array
     */
    public function getMenuDropDownList($type_menu, $id=0, $parent_id=0, $level=1)
    {        
        $criteria=new CDbCriteria;
        $criteria->addCondition('id_parent='.$parent_id);
        $criteria->addCondition('id<>'.$id);
        $criteria->addCondition('type_menu='.$type_menu);  
        
        $data = array();
        $orgData = Menu::model()->findAll($criteria);
        foreach ($orgData as $value)
        {     
            if (Yii::app()->user->admin || Access::checkAccessUserForTree($value->id))
            {                
                $item = array($value->id => str_repeat('--', $level).' '.$value->name);
                $flagLevel = 1;
            }
            else
            {
                $item = array();
                $flagLevel = 0;
            }
            $data = $data + $item + $this->getMenuDropDownList($type_menu, $id, $value->id, $level+$flagLevel);
        }
        return $data;
    }
    
    /**
     * Верхнее меню в шаблоне сайта
     * @param int $id_parent идентификатор родителя
     * @return array
     */
    public static function getTopMenuArray($id_parent=0)
    {            
        $resultArray = array();
        $model = Yii::app()->db->createCommand()
            ->from('{{menu}}')
            ->where('blocked=0 and type_menu=1 and id_parent=:id_parent', [':id_parent'=>$id_parent])
            ->order('sort_index desc')
            ->queryAll();
        foreach ($model as $value)
        {            
            if ($value['name'] == '---') continue;
            
            $resultArray[] = array(
                'label'=>$value['name'],
                'url'=>(strpos($value['link'],'array')!==false ?
                    eval('return '.$value['link']) : $value['link']),
                'linkOptions'=>array('target'=>$value['target']),
                'items'=>($value['submenu_code'] != '') ? eval('return '.$value['submenu_code'])
                : self::getTopMenuArray($value['id']),
            );            
        }
        return $resultArray;
    }

    /**
     * Преобразование меню из массива в html-текст
     * @param array $arrayMenu массив меню
     * @return string
     * @uses getLeftMenuArray()
     */
    private function convertSubMenuFromArray($arrayMenu)
    {
        $res = '';
        foreach ($arrayMenu as $value)
        {
            if ($value['label']=='---') {
                $res .= '<li class="divider"></li>';    
            } 
            else
            {                
                $res .= '<li>';
                $res .= CHtml::link($value['label'], ((strpos($value['url'],'array')!==false) 
                    ? eval('return '.$value['url']) : $value['url']), 
                    array('target'=>(isset($value['target']) && $value['target']!='') 
                        ? $value['target'] : null));
                $res .= '</li>';                                
            }
        }
        return $res;
    }
    
    /**
     * Левое меню
     * @param int $id_parent идентификатор родителя
     * @return string
     * @uses in file views/layout/main.php
     */
    public function getLeftMenuArray($id_parent=0)
    {        
        $resultMenu = '';
        $model = Yii::app()->db->createCommand()
            ->from('{{menu}}')
            ->where('blocked=0 AND type_menu=2 AND id_parent=:id_parent', [':id_parent'=>$id_parent])
            ->order('sort_index asc')
            ->queryAll();

        foreach ($model as $value)
        {
            if ($value['name'] == '---') 
            {
                $resultMenu .= '<li class="divider"></li>'; 
                continue;
            }

            $subMenu = ($value['submenu_code'] != '') ? eval('return ' . $value['submenu_code']) : null;

            $existsStaticSubMenu = Yii::app()->db->createCommand()
                ->from('{{menu}}')
                ->select('count(id)')
                ->where('id_parent=:id_parent', [':id_parent'=>$value['id']])
                ->queryScalar();
            
            $resultMenu .= '<li'.(($subMenu != null || $existsStaticSubMenu > 0)
                ? ' class="dropdown-submenu"' : '').'>';
                $resultMenu .= CHtml::link($value['name'], (
                    (strpos($value['link'],'array') !== false) ? eval('return ' . $value['link']) : $value['link']
                    ), array('target' => ($value['target'] != '') ? $value['target'] : null));
                
                // сначала статичное меню, если есть
                if ($existsStaticSubMenu > 0)
                {
                    $resultMenu .= '<ul class="dropdown-menu">' . $this->getLeftMenuArray($value['id']);
                    if ($subMenu==null) { $resultMenu .= '</ul>'; }
                }
                
                if ($subMenu!=null)
                {
                    $resultMenu .= ($existsStaticSubMenu > 0)
                        ? '<li class="divider"></li>' : '<ul class="dropdown-menu">';
                    $resultMenu .= $this->convertSubMenuFromArray($subMenu).'</ul>';
                }
                $resultMenu .= '</li>'; 
        }        
        return $resultMenu;
    }
    
    /**
     * 
     * @todo где-то испольуется?
     * @param array $arr
     * @param boolean $main
     * @return string
     * @uses in file views/layouts/main.php
     */
    public function getLeftMenuAdd($arr, $main=true)
    {    	    
    	$resultMenu = '';    	
    	if (count($arr)>0) $resultMenu .= '<ul class="dropdown-menu' . ($main ? ' dropdown-menu-main dropdown-menu-wrap' : '') . '">';    	
    	foreach ($arr as $a)
    	{
    	    if ($a['name']==='---')
    	    {
    	        $resultMenu .= '<li class="divider"></li>';
    	    }
    	    else
    	    {
        		$flagItems = isset($a['items']) && count($a['items'])>0;
        		$resultMenu .= '<li' . ($flagItems ? ' class="dropdown-submenu"' : '') . '>'
                    . CHtml::link($a['name'], $a['link']) . ($flagItems ? $this->getLeftMenuAdd($a['items'], false) : '') . '</li>';
    	    }
    	}
    	
    	if (count($arr)>0) $resultMenu .= '</ul>';    	
    	return $resultMenu;
    	
    }
    
    
}
