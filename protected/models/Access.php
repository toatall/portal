<?php

/**
 * Класс для работы с разграничение прав доступа
 * 
 * @author oleg
 * @version 12.08.2016
 * 
 */

	class Access 
	{
		
		
		/** 
		 * Сохранение прав доступа (группы и пользователи)
		 * привязанных к текущему УН структуры
		 * 
		 * @author oleg
		 * @version 12.08.2016
		 * 
		 **/
		public static function saveRelationsPermissions($tree_id, $model, $groups, $users)
		{
						
			$command = Yii::app()->db->createCommand();
			
			$command->delete('{{access_user}}', 'id_tree=:id and id_organization=:id_organization', 
					array(':id'=>$tree_id, ':id_organization'=>Yii::app()->session['organization']));
			$command->delete('{{access_group}}', 'id_tree=:id and id_organization=:id_organization', 
					array(':id'=>$tree_id, ':id_organization'=>Yii::app()->session['organization']));
		
		
			// если установлена галочка "Добавить разрешения, наследуемые от родительских групп и пользователей"
			if ($model->useParentRight)
			{
				if ($model->id_parent==0) return; // если нет родителя, то выходим из функции
		
				// сохраняем пользователей
				$command->reset();							
				$command->text = "INSERT INTO {{access_user}} (id_user,id_tree,id_organization,date_create)
							SELECT id_user,$tree_id,id_organization,getdate() FROM {{access_user}}
								WHERE id_tree=$model->id_parent";
				$command->execute();
		
				// сохраняем группы
				$command->reset();
				$command->text = "INSERT INTO {{access_group}} (id_group,id_tree,id_organization,date_create)
							SELECT id_group,$tree_id,id_organization,getdate() FROM {{access_group}}
								WHERE id_tree=$model->id_parent";
				$command->execute();
		
			}
			else
			{
				// сохранияем группы
				if (count($groups))
				{
					foreach ($groups as $value)
					{
						if (is_numeric($tree_id) && is_numeric($value))
						{
							$command->reset();
							$command->insert('{{access_group}}', array(
								'id_group'=>$value,
								'id_tree'=>$tree_id,
								'id_organization' => Yii::app()->session['organization'],
								'date_create'=>new CDbExpression('getdate()'),
							));
						}
					}
				}
		
				// сохранияем пользователей
				if (count($users))
				{
					foreach ($users as $value)
					{
						if (is_numeric($tree_id) && is_numeric($value))
						{
							$command->reset();
							$command->insert('{{access_user}}', array(
								'id_user'=>$value,
								'id_tree'=>$tree_id,
								'id_organization' => Yii::app()->session['organization'],
								'date_create'=>new CDbExpression('getdate()'),
							));
						}
					}
				}
			}
		}
		
		
		
		/** 
		 * Сохранение прав доступа к отделам (группы и пользователи)
		 * привязанных к текущему УН отдела
		 * 
		 * @author oleg
		 * @version 16.08.2016
		 * 
		 **/
		public static function saveRelationsPermissionsDepartment($model, $groups, $users)
		{
						
			$id = $model->id;
			
			$command = Yii::app()->db->createCommand();
			
			$command->delete('{{access_department_user}}', 'id_department=:id', array(':id'=>$id));
			$command->delete('{{access_department_group}}', 'id_department=:id', array(':id'=>$id));
		
			// сохранияем группы
			if (count($groups))
			{
				foreach ($groups as $value)
				{
					if (is_numeric($id) && is_numeric($value))
					{
						$command->reset();
						$command->insert('{{access_department_group}}', array(
								'id_group'=>$value,
								'id_department'=>$id,
								'id_organization' => Yii::app()->session['organization'],
								'date_create'=>new CDbExpression('getdate()'),
						));
					}
				}
			}
		
			// сохранияем пользователей
			if (count($users))
			{
				foreach ($users as $value)
				{
					if (is_numeric($id) && is_numeric($value))
					{
						$command->reset();
						$command->insert('{{access_department_user}}', array(
								'id_user'=>$value,
								'id_department'=>$id,
								'id_organization' => Yii::app()->session['organization'],
								'date_create'=>new CDbExpression('getdate()'),
						));
					}
				}
			}
			
		}
		
		
	
		/** 
		 * Получение списка групп и пользователей в виде списка
		 * 	с разделением <br /> для view контроллера TreeController
		 *  (admin/tree/view/id/$id_tree)
		 *  
		 * @author oleg
		 * @version 12.08.2016
		 *     
		 */
		public static function getListGroupUser($id_tree)
		{
			$icon_group = '<img src="/images/group.png" /> ';
			$icon_user = '<img src="/images/user.png" /> ';
		
			$result_str = '';
		
			$group = AccessGroup::model()->with('group')->findAll(array(
					'condition'=>'t.id_tree=:id_tree and t.id_organization=:id_organization',
					'params'=>array(':id_tree'=>$id_tree, ':id_organization'=>Yii::app()->session['organization']),
					'order'=>'[group].[name]',
			));
		
			foreach ($group as $val)
			{
				$result_str .= $icon_group.$val->group->name.'<br />';
			}
			 
			$user = AccessUser::model()->with('user')->findAll(array(
				'condition'=>'t.id_tree=:id_tree and t.id_organization=:id_organization',
				'params'=>array(':id_tree'=>$id_tree, ':id_organization'=>Yii::app()->session['organization']),
				'order'=>'[user].[username_windows], [user].[username]',
			));
			foreach ($user as $val)
			{
				$result_str .= $icon_user . $val->user->concatened;
			}
		
			return $result_str;
		}
		
		
		/**
		 * Проверка прав пользователя для структуры $id_tree
		 * @param integer $id_tree
		 * 
		 * @author oleg
		 * @version 12.08.2016
		 * 
		 */
		public static function checkAccessUserForTree($id_tree)
		{		
		    if (!is_numeric($id_tree))
		        return  false;
		    
			return Yii::app()->db->createCommand()
				->from('{{view_access_tree}}')
				->where('id=:id and id_user=:id_user', array(
					':id' => $id_tree,
					':id_user' => Yii::app()->user->id,
				))
				->queryScalar();
		}
		
		
		/**
		 * Получение списка подключенных пользователей
		 * для отдела с id = $id
		 * 
		 * @param int $id
		 * @return CDbCommand
		 * 
		 * @author oleg
		 * @version 16.08.2016
		 */
		public static function accessDepartmentUserById($id)
		{
			return Yii::app()->db->createCommand()
				->select("u.id, 
					case when u.username_windows is not null then u.username_windows else u.username end + 
					case when p.name is not null then ' (' + p.name + ')' end concatened")
				->from('{{access_department_user}} t ')
				->join('{{user}} u', 't.id_user = u.id')
				->leftJoin('{{profile}} p', 'u.id = p.id')
				->where('id_department=:id_department', [':id_department'=>$id])
				->queryAll();
		}
		
		
		public static function accessDepartmentGroupById($id)
		{		
			return Yii::app()->db->createCommand()
				->select('g.id, g.name')
				->from('{{access_department_group}} t')
				->join('{{group}} g', 't.id_group=g.id')
				->where('t.id_department=:id_department', [':id_department'=>$id])
				->queryAll();
		}
		
		
		
		
		/**
		 * Сохранение прав доступа к модулям (группы и пользователи)
		 *
		 * @author oleg
		 * @version 03.03.2017
		 *
		 **/
		public static function saveRelationsPermissionModule($model, $groups, $users)
		{
			$id = $model->name;
				
			$command = Yii::app()->db->createCommand();
				
			$command->delete('{{access_module_user}}', 'module_name=:id', array(':id'=>$id));
			$command->delete('{{access_module_group}}', 'module_name=:id', array(':id'=>$id));
			
			
				
			// сохранияем группы
			if (count($groups))
			{
				foreach ($groups as $value)
				{					
					$command->reset();
					$command->insert('{{access_module_group}}', array(
						'id_group'=>$value,
						'module_name'=>$id,
						'date_create'=>new CDbExpression('getdate()'),
					));
				}
			}
			
			// сохранияем пользователей
			if (count($users))
			{
				foreach ($users as $value)
				{					
					$command->reset();
					$command->insert('{{access_module_user}}', array(
						'id_user'=>$value,
						'module_name'=>$id,								
						'date_create'=>new CDbExpression('getdate()'),
					));
				}
			}
		}
		
		
		
		
	}