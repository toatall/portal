<?php


class PAuthManager extends CDbAuthManager
{
	
	public $itemTable='p_roles';
	public $itemChildTable='p_roles_child';
	public $assignmentTable='p_roles_assignment';
	
	
	public function init()
	{
		parent::init();
		
		//parent::clearAll();
		
		// привязка ролей пользователю
		if (!Yii::app()->user->isGuest)
		{					
			$modelGroup = Group::model()->with('groupUsers')->findAll(
				'groupUsers.id=:userid and (t.id_organization=:org1 or t.id_organization=:org2)', 
				[':userid'=>Yii::app()->user->id, ':org1'=>'0000', ':org2'=>Yii::app()->session['organization']]);
			foreach ($modelGroup as $row)
			{
				$this->assign($row->name, Yii::app()->user->id);
			}
			
			if (Yii::app()->user->admin)
			{
				$this->assign('admin', Yii::app()->user->id);
			}
		}
	}
	
	
}

