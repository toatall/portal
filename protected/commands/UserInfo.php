<?php


class UserInfo extends CConsoleCommand
{
    
    public function actionGetADInfo($username)
    {
        echo "Начало работы...";
        
        if ($username == null)
        {
            $model = User::model()->findAll('username_windows=:username', [':username' => $username]);            
        }
        else 
        {
            $model = User::model()->findAll();
            echo "Обновление информации по всем пользователям...";            
        }
        
        foreach ($model as $m)
        {
            echo "... обновление пользователя {$m->username_windows}";
            // function update
        }
        
        echo "Обновление завершено";
        
    }
    
    
}