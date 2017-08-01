<?php

class ConvertTable extends CComponent
{
	
	private $tableName;
	
	
	public function __construct($tableName)
	{
		$this->tableName = $tableName;
		$this->checkTable();		
	}
		
	
	
	private function checkTable()
	{
		if (Yii::app()->db->createCommand('select count(last_id) from {{convert_old_site}} where tableName=:table')
				->bindValue(':table', $this->tableName)->queryScalar() == 0)
		{
			Yii::app()->db->createCommand()->insert('{{convert_old_site}}', [
				'tableName'=>$this->tableName,
				'last_id'=>0,
			]);
		}
	}
	
	
	public function getLastId()
	{
		$model = Yii::app()->db->createCommand()
			->from('{{convert_old_site}}')
			->where('tableName=:tableName', [':tableName'=>$this->tableName])
			->queryRow();
		if ($model !== null)
		{
			return $model['last_id'];
		}	
		return 0;
	}
	
	
	public function setLastId($value)
	{
		$this->checkTable();
		Yii::app()->db->createCommand()->update('{{convert_old_site}}', [
			'last_id' => $value,
		], 'tableName=:table', [':table'=>$this->tableName]);
	}
	
}