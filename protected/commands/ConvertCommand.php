<?php


include 'ConvertTable.php';

class ConvertCommand extends CConsoleCommand
{
	
	const PATH_FROM = '\\\u8600-app003\Inetpub\wwwroot\WEB86\My Webs';
	const PATH_TO = 'c:\WWW\portal80\web\files\8600\news\old_images';
	const PATH_TO_WEB = '/files/8600/news/old_images';
	
	private $_images;
	
	
		
	// замена ковычек
	private function regReplaceQuote($str)
	{
		return  preg_replace(['/\[quote_1]/', '/\[quote_2]/'], ['"', "'"], $str);
	}
	
	// парсинг изображений
	private function regParseImg($str, $prefix)
	{
		$this->_images = array();
		
		preg_match_all('/<img[^>]+>/i', $str, $result);
		foreach ($result as $res)
		{
			foreach ($res as $r)
			{
				preg_match('/(src)=("[^"]*")/i', $r, $img);
				if (isset($img[2]))
				{
					$this->_images[$prefix][] = $img[2];
				}
			}
		}
	}
	
	// преобразования пути из web в windows
	private function replacePath($path)
	{
		$path = str_replace("http://10.186.201.12/", "", $path);
		$path = str_replace("/", "\\", $path);
		if (substr($path, 0, 1) != "\\")
			$path = "\\" . $path;	
		return $path;
	}
	
	
	// копирование файла
	private function copyFile($image, &$text, $toPath = '')
	{
		
		if (is_array($image))
		{
			if (isset($image[2]))
			{
				$image = $image[2];
			}
			else
			{
				return;
			}
		}
		
		$image = str_replace('"', '', $image);
		
		$imageNew = $this->replacePath($image);
	
		$from = self::PATH_FROM . $imageNew;
		$to = (($toPath != '') ? $toPath . basename($imageNew) : self::PATH_TO . $imageNew);
		
		echo "From: " . $from ."\n";
		echo "To :" . $to . "\n";
	
		
		if (copy($from, $to))
		{
			echo "Success copy!\n";
		}
		else
		{
			$errors = error_get_last();
			echo "Error copy image :{$errors['message']}!\n";
		}
		
		$text = str_replace($image, self::PATH_TO_WEB . $imageNew, $text);
	}
	
	
	
	/**
	 * @author tvog17
	 * @desc Новости управления
	 * @version 22.06.2017 - create
	 */
	public function actionIndex($id=0)
	{		
		
		//получение последнего id
		
		if ($id == 0)
		{
			$modelLastId = new ConvertTable('news_gp');
			$id = $modelLastId->lastId;
		}
		
		
		
		// подключение к старому сайту
		$lastIdStr = ($id > 0) ? ' and id > ' . $id : '';
		$modelNewsOld = Yii::app()->db2
			->createCommand("select id, title, small_message, message, small_image, convert(varchar,date_created,112) [date_created]  from [news_gp] where [date_deleted] is null and [date_created] > cast('01.01.2017' as datetime){$lastIdStr} order by id ")
			->queryAll();
		foreach ($modelNewsOld as $mNewsOld)
		{
			
			try 
			{
				// 1 - распарсить сообщение
				$id = $mNewsOld['id'];
				$news['title'] = $mNewsOld['title'];
				$news['small_message'] = $mNewsOld['small_message'];
				$news['message'] = $mNewsOld['message'];
				$news['small_image'] = $mNewsOld['small_image'];
				$news['date_create'] = $mNewsOld['date_created'];
				
				echo "-------------------------------------- \n";
				echo "ID: {$id}\n";
				//echo "Title: {$news['title']}\n";
				
				
				// заменить ковычки
				$news['title'] = $this->regReplaceQuote($news['title']);
				$news['message'] = $this->regReplaceQuote($news['message']);
				$news['small_image'] = $this->regReplaceQuote($news['small_image']);
				
				
				// парсинг картинок, копирование и замена в тексте
				$this->regParseImg($news['message'], 'body');						
				foreach ($this->_images as $image)
				{					
					@$this->copyFile($image, $news['message']);				
				}
				
				$this->regParseImg($news['small_image'], 'body');
				foreach ($this->_images as $image)
				{
					@$this->copyFile($image, $news['small_image']);
				}
				
				@$this->copyFile('images/gp/' . $news['small_image'], $news['small_image'], 'c:\WWW\portal80\web\files\8600\news\old_images\thumbhail\\');
				$news['small_image'] = self::PATH_TO_WEB . '/thumbhail/' . basename($news['small_image']); 			
				
				// 3 - сохранение новости в новую БД
				Yii::app()->db->createCommand()
					->insert('p_news', [
						'id_tree' => 3,
						'title' => iconv('windows-1251','utf-8',$news['title']),
						'message1' => iconv('windows-1251','utf-8',$news['small_message']),
						'message2' => iconv('windows-1251','utf-8',$news['message']),
						'date_start_pub' => new CDbExpression('getdate()'),
						'date_end_pub' => '01.01.2032',
						'thumbail_title' => iconv('windows-1251','utf-8',$news['title']),
						'thumbail_image' => $news['small_image'],
						'date_create' => $news['date_create'],
						'log_change' => '$' . date('d.m.Y H:i:s') . '|create|system',
						'id_organization' => '8600',
						'on_general_page' => 1,
						'author' => 'system',
					]);
	 			
						
			}
			catch (Exception $ex)
			{
				echo $ex->getMessage();
			}
		
			$modelLastId->lastId = $id;
		}		
		
		echo "End work!\n";
		echo "==========================\n\n\n\n";		
		
	}
	
	
	/**
	 * copy news ifns
	 * @param unknown $code
	 */
	public function actionIndexIfns()
	{
		
		$modelLastId = new ConvertTable('ifns_news');
		$id = $modelLastId->lastId;
		
		// подключение к старому сайту		
		$modelNewsOld = Yii::app()->db2
			->createCommand("select id, title, small_message, message, author_name, convert(varchar,date_created,112) [date_created], [ifns_sono].[ifns_kod] from [ifns_news] left join [ifns_sono] on [ifns_sono].[ifns_tree_id]=ifns_news.id_tree where [date_deleted] is null and [date_created] > cast('01.01.2017' as datetime) and id>".$id." order by id")
			->queryAll();
		foreach ($modelNewsOld as $mNewsOld)
		{
			
			try 
			{
				// 1 - распарсить сообщение
				$id = $mNewsOld['id'];
				$news['title'] = $mNewsOld['title'];
				$news['small_message'] = $mNewsOld['small_message'];
				$news['message'] = $mNewsOld['message'];				
				$news['date_create'] = $mNewsOld['date_created'];
				$news['author_name'] = $mNewsOld['author_name'];
				$code = $mNewsOld['ifns_kod'];
				
				echo "-------------------------------------- \n";
				echo "ID: {$id}\n";
				echo "Title: {$news['title']}\n";
				
				
				// заменить ковычки
				$news['message'] = $this->regReplaceQuote($news['message']);
				$news['small_message'] = $this->regReplaceQuote($news['small_message']);
								
				// парсинг картинок, копирование и замена в тексте
				$this->regParseImg($news['message'], 'body');
				foreach ($this->_images as $image)
				{
					@$this->copyFile($image, $news['message']);
				}
				
				$this->regParseImg($news['small_message'], 'body');
				foreach ($this->_images as $image)
				{
					@$this->copyFile($image, $news['small_message']);
				}
								
				
				// 3 - сохранение новости в новую БД
				Yii::app()->db->createCommand()
					->insert('p_news', [
						'id_tree' => 3,
						'title' => iconv('windows-1251','utf-8',$news['title']),
						'message1' => iconv('windows-1251','utf-8',$news['small_message']),
						'message2' => iconv('windows-1251','utf-8',$news['message']),
						'date_start_pub' => new CDbExpression('getdate()'),
						'date_end_pub' => '01.01.2032',
						'thumbail_title' => iconv('windows-1251','utf-8',$news['title']),
						//'thumbail_image' => '',
						'date_create' => $news['date_create'],
						'log_change' => '$' . date('d.m.Y H:i:s') . '|create|system',
						'id_organization' => $code,
						'on_general_page' => 0,
						'author' => 'system',
					]);
	 			
				$modelLastId->lastId = $id;	
				
			}
			catch (Exception $ex)
			{
				echo $ex->getMessage();
			}
			
		}
		
		echo "End work!\n";
		echo "==========================\n\n\n\n";	
	}
	
	
	
	public function actionIndexSection($sectionName)
	{
		$type = [
			'PressClub' => 1,
			'Dosug' => 2,
			'SmiPhoto' => 3,
			'SmiVideo' => 4,
			'SmiAudio' => 5,
			'SmiPrint' => 6,			
			'Humor'=>70,
		];
		
		if (!isset($type[$sectionName]))
		{
			throw new Exception('Missed section');
		}
				
		$modelTree = Yii::app()->db->createCommand()
			->from('{{tree}}')
			->where('param1=:param1 and module=:module',[':param1'=>$sectionName, ':module'=>'news'])
			->queryRow();
		
		
		if ($modelTree == null)
		{
			throw new Exception('Not found module in p_tree');
		}
				
		$modelLastId = new ConvertTable($sectionName);
		$id = $modelLastId->lastId;
		
		// подключение к старому сайту
		$modelNewsOld = Yii::app()->db2
			->createCommand("select id, title, small_message, message, author_name, convert(varchar,date_created,112) [date_created], [ifns_sono].[ifns_kod]  "
				."from [ifns_gps] "				
				."left join [ifns_sono] on [ifns_sono].[ifns_tree_id]=[ifns_gps].[id_tree]"
				. "where [date_deleted] is null and [date_created] > cast('01.01.2017' as datetime) and id>".$id
				." and id_type=".$type[$sectionName]." and blocked=0 and deleted=0 order by id")
			->queryAll();
		
		foreach ($modelNewsOld as $mNewsOld)
		{
			try {
				
				// 1 - распарсить сообщение
				$id = $mNewsOld['id'];
				$news['title'] = $mNewsOld['title'];
				$news['small_message'] = $mNewsOld['small_message'];
				$news['message'] = $mNewsOld['message'];
				$news['date_create'] = $mNewsOld['date_created'];
				$news['author_name'] = $mNewsOld['author_name'];				
				
				echo "-------------------------------------- \n";
				echo "ID: {$id}\n";
				echo "Title: {$news['title']}\n";
				
				
				// заменить ковычки
				$news['message'] = $this->regReplaceQuote($news['message']);
				$news['small_message'] = $this->regReplaceQuote($news['small_message']);
				
				// парсинг картинок, копирование и замена в тексте
				$this->regParseImg($news['message'], 'body');
				foreach ($this->_images as $image)
				{
					@$this->copyFile($image, $news['message']);
				}
				
				$this->regParseImg($news['small_message'], 'body');
				foreach ($this->_images as $image)
				{
					@$this->copyFile($image, $news['small_message']);
				}
								
				Yii::app()->db->createCommand()
					->insert('p_news', [
						'id_tree' => $modelTree['id'],
						'title' => iconv('windows-1251','utf-8',$news['title']),
						'message1' => iconv('windows-1251','utf-8',$news['small_message']),
						'message2' => iconv('windows-1251','utf-8',$news['message']),
						'date_start_pub' => new CDbExpression('getdate()'),
						'date_end_pub' => '01.01.2032',
						'thumbail_title' => iconv('windows-1251','utf-8',$news['title']),						
						'date_create' => $news['date_create'],
						'log_change' => '$' . date('d.m.Y H:i:s') . '|create|system',
						'id_organization' => $mNewsOld['ifns_kod'],
						'on_general_page' => 0,
						'author' => 'system',
				]);
				$modelLastId->lastId = $id;
				
			}
			catch (Exception $ex)
			{
				echo $ex->getMessage();
			}
		}
		
	}
	
	
	
	public function actionIndexHumor()
	{
		
		$modelTree = Yii::app()->db->createCommand()
			->from('{{tree}}')
			->where('param1=:param1 and module=:module',[':param1'=>'Humor', ':module'=>'news'])
			->queryRow();
	
	
		if ($modelTree == null)
		{
			throw new Exception('Not found module in p_tree');
		}
	
		$modelLastId = new ConvertTable('Humor');
		$id = $modelLastId->lastId;
	
		// подключение к старому сайту
		$modelNewsOld = Yii::app()->db2
			->createCommand("select id, title, small_message, message, author_name, convert(varchar,date_created,112) [date_created], [id_ifns] as [ifns_kod]  "
				."from [humor_gp] "
				. "where [date_deleted] is null and [date_created] > cast('01.01.2017' as datetime) and id>".$id
				." and disabled=0 and deleted=0 order by id")
			->queryAll();
	
				foreach ($modelNewsOld as $mNewsOld)
				{
					try {
	
						// 1 - распарсить сообщение
						$id = $mNewsOld['id'];
						$news['title'] = $mNewsOld['title'];
						$news['small_message'] = $mNewsOld['small_message'];
						$news['message'] = $mNewsOld['message'];
						$news['date_create'] = $mNewsOld['date_created'];
						$news['author_name'] = $mNewsOld['author_name'];
	
						echo "-------------------------------------- \n";
						echo "ID: {$id}\n";
						echo "Title: {$news['title']}\n";
	
	
						// заменить ковычки
						$news['message'] = $this->regReplaceQuote($news['message']);
						$news['small_message'] = $this->regReplaceQuote($news['small_message']);
	
						// парсинг картинок, копирование и замена в тексте
						$this->regParseImg($news['message'], 'body');
						foreach ($this->_images as $image)
						{
							@$this->copyFile($image, $news['message']);
						}
	
						$this->regParseImg($news['small_message'], 'body');
						foreach ($this->_images as $image)
						{
							@$this->copyFile($image, $news['small_message']);
						}
	
						Yii::app()->db->createCommand()
						->insert('p_news', [
								'id_tree' => $modelTree['id'],
								'title' => iconv('windows-1251','utf-8',$news['title']),
								'message1' => iconv('windows-1251','utf-8',$news['small_message']),
								'message2' => iconv('windows-1251','utf-8',$news['message']),
								'date_start_pub' => new CDbExpression('getdate()'),
								'date_end_pub' => '01.01.2032',
								'thumbail_title' => iconv('windows-1251','utf-8',$news['title']),
								'date_create' => $news['date_create'],
								'log_change' => '$' . date('d.m.Y H:i:s') . '|create|system',
								'id_organization' => $mNewsOld['ifns_kod'],
								'on_general_page' => 0,
								'author' => 'system',
						]);
						$modelLastId->lastId = $id;
	
					}
					catch (Exception $ex)
					{
						echo $ex->getMessage();
					}
				}
	
	}
	
	
	
	/**
	 * Собрания
	 */
	public function actionConference()
	{
		$modelLastId = new ConvertTable('Conference');
		$id = $modelLastId->lastId;
		
		// подключение к старому сайту
		$modelNewsOld = Yii::app()->db2
			->createCommand("select [id],[kabinet],[tema],[members],[description],convert(varchar,[date_start],104) + ' ' + convert(varchar,[date_start],108) [date_start],convert(varchar,[date_create],104) + ' ' + convert(varchar,[date_create],108) date_create,[duration],case when [type_meeting]='кофиденциальное' then 1 else 0 end type_meeting  "
				."from [tbl_meeting] "
				. "where [date_create] > cast('01.01.2017' as datetime) and date_start is not null and id>".$id
				." order by id")
				->queryAll();
		
		foreach ($modelNewsOld as $modelOld)
		{			
			$id = $modelOld['id'];			
			echo "----------------------------------\n";
			echo "id: " . $id . "\n";
		
			Yii::app()->db->createCommand()
				->insert('p_conference', [
					'type_conference' => 3,
					'theme' => iconv('windows-1251','utf-8',$this->regReplaceQuote($modelOld['tema'])),
					'members_people' => iconv('windows-1251','utf-8',$this->regReplaceQuote($modelOld['members'])),
					'note' => iconv('windows-1251','utf-8',$this->regReplaceQuote($modelOld['description'])),
					'date_start' => $modelOld['date_start'],
					'date_create' => $modelOld['date_create'],
					'duration' => $modelOld['duration'],
					'is_confidential' => $modelOld['type_meeting'],	
					'place' => iconv('windows-1251','utf-8',$modelOld['kabinet']),
			]);
				
			$modelLastId->lastId = $id;
		}
		
	}
	
	
	
	/**
	 * ВКС УФНС
	 */
	public function actionVksUfns()
	{
		$modelLastId = new ConvertTable('VksUfns');
		$id = $modelLastId->lastId;
	
		// подключение к старому сайту
		$modelNewsOld = Yii::app()->db2
		->createCommand("
				SELECT [id]
				      ,convert(varchar,[date_created],104) date_created				      
				      ,convert(varchar,[date_konf],104) [date_konf]
				      ,replace(replace([time_start],'.',':'),'-',':') + ':00' [time_start]
				      ,[time_duration]
				      ,[theme_konf]
				      ,[dept]
				      ,[members]
				      ,[ifns]				      
				  FROM [TBL_VideoConference]
				  where [date_created] > cast('01.01.2017' as datetime) and [date_konf] is not null 
					and [date_deleted] is null and [time_start] <> '-' and id>$id
				  order by id")
				->queryAll();
	
				foreach ($modelNewsOld as $modelOld)
				{
					$id = $modelOld['id'];
					echo "----------------------------------\n";
					echo "id: " . $id . "\n";
					echo $modelOld['date_konf'] . ' ' . $modelOld['time_start'] . "\n";
	
					Yii::app()->db->createCommand()
						->insert('p_conference', [
							'type_conference' => 1,
							'date_start' => ($modelOld['date_konf'] . ' ' . $modelOld['time_start']),
							'duration' => iconv('windows-1251','utf-8',$modelOld['time_duration']),							
							'theme' => iconv('windows-1251','utf-8',$this->regReplaceQuote($modelOld['theme_konf'])),
							'responsible' => iconv('windows-1251','utf-8',$this->regReplaceQuote($modelOld['dept'])),									
							'members_people' => iconv('windows-1251','utf-8',$this->regReplaceQuote($modelOld['members'])),
							'members_organization' => iconv('windows-1251','utf-8',$this->regReplaceQuote($modelOld['ifns'])),																		
							'date_create' => $modelOld['date_created'],							
					]);
	
					$modelLastId->lastId = $id;
				}	
	}
	
	
	
	/**
	 * ВКС ФНС
	 */
	public function actionVksFns()
	{
		$modelLastId = new ConvertTable('VksFns');
		$id = $modelLastId->lastId;
	
		// подключение к старому сайту
		$modelNewsOld = Yii::app()->db2
			->createCommand("				
				SELECT *      
				  FROM ViewVideoConference_FNS
				  where id>$id
				  order by id")
			->queryAll();
	
				foreach ($modelNewsOld as $modelOld)
				{
					$id = $modelOld['id'];
					echo "----------------------------------\n";
					echo "id: " . $id . "\n";
					echo $modelOld['date_konf'] . ' ' . $modelOld['time_start'] . "\n";
	
					Yii::app()->db->createCommand()
					->insert('p_conference', [
							'type_conference' => 2,
							'date_start' => iconv('windows-1251','utf-8',($modelOld['date_konf'] . ' ' . $modelOld['time_start'])),							
							'theme' => iconv('windows-1251','utf-8',$this->regReplaceQuote($modelOld['theme_konf'])),							
							'members_people' => iconv('windows-1251','utf-8',$this->regReplaceQuote($modelOld['members'])),							
							'date_create' => $modelOld['date_created'],
					]);
	
					$modelLastId->lastId = $id;
				}
	}
		
		

	
}