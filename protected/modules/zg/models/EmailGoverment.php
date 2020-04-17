<?php

/**
 * This is the model class for table "{{email_goverment}}".
 *
 * The followings are the available columns in table '{{email_goverment}}':
 * @property integer $id
 * @property string $org_name
 * @property string $ruk_name
 * @property string $telephone
 * @property string $email
 * @property string $post_address
 * @property string $date_create
 * @property string $date_update
 * @property string $author
 */
class EmailGoverment extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{email_goverment}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('org_name, email', 'required'),
			array('org_name, ruk_name', 'length', 'max'=>1000),
			array('telephone', 'length', 'max'=>200),
			array('email', 'length', 'max'=>500),
			array('author', 'length', 'max'=>250),
			array('post_address, date_update', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, org_name, ruk_name, telephone, email, post_address, date_create, date_update, author', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
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
			'org_name' => 'Организация',
			'ruk_name' => 'Руководство',
			'telephone' => 'Телефон приемной',
			'email' => 'Электронный адрес',
			'post_address' => 'Почтовый адрес',
			'date_create' => 'Дата создания',
			'date_update' => 'Дата изменения',
			'author' => 'Автор',
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
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('org_name',$this->org_name,true);
		$criteria->compare('ruk_name',$this->ruk_name,true);
		$criteria->compare('telephone',$this->telephone,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('post_address',$this->post_address,true);
		$criteria->compare('date_create',$this->date_create,true);
		$criteria->compare('date_update',$this->date_update,true);
		$criteria->compare('author',$this->author,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
            'pagination' => [
                'pageSize' => $this->getPageSize(),
            ],
		));
	}

    /**
     * Количество строк при выводе информации по базе адресов
     * @return mixed
     */
	private function getPageSize()
    {
        return Yii::app()->params['zg']['emailGoverment']['pageSize'];
    }

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return EmailGoverment the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    /**
     * @inheritDoc
     * @return bool
     */
	protected function beforeSave()
    {
        if (!parent::beforeSave()) {
            return false;
        }

        if ($this->isNewRecord)
        {
            $this->date_create = new CDbExpression('getdate()');
        }
        $this->date_update = new CDbExpression('getdate()');
        $this->author = Yii::app()->user->name;

        return true;
    }

    /**
     * @inheritDoc
     */
    protected function afterFind()
    {
        parent::afterFind();
        /* @var $dateHelper DateHelper */
        $dateHelper = Yii::app()->dateHelper;
        $this->date_create = $dateHelper->asDateTime($this->date_create);
        $this->date_update = $dateHelper->asDateTime($this->date_update);
    }
}
