<?php

require 'components\DateHelper.php';
require 'components\LDAPInfo.php';
require 'models\User.php';
require 'models\Organization.php';
require 'components\ConvertDate.php';
require 'models\Log.php';

class UserInfoCommand extends CConsoleCommand
{
    
    
    
    public function actionGetADInfo($username=null)
    {
        echo "Begin work...\n";
               
        $criteria = new CDbCriteria();
        if ($username!=null)
        {
            $criteria->addCondition('username_windows=:username', [':username'=>$username]);
        }
        
        $modelUsers = User::model()->findAll($criteria);
        
        $loginAD = new LDAPInfo();
        
        foreach ($modelUsers as $model)
        {
            echo "Check user " . $model->username_windows . "...\n";
            if ($info = $loginAD->getInfoAD($model->username_windows))
            {
                $hash = md5($loginAD->sAMAccountName . $loginAD->displayname . $loginAD->telephoneNumber . $loginAD->title . $loginAD->department . $loginAD->company);
                if ($hash !== $model->hash)
                {
                    $this->updateUser($model, $loginAD, $hash);
                    echo "User " . $model->username_windows . " updated!\n";
                }
            }
            else
            {
                echo "Error get AD info: " . $loginAD->getError() . "\n";
            }
        }
            
        echo "Finish.\n";        
    }
    
    /**
     * Обновление информации пользователя
     * @param array $userDb
     * @param array $userAd
     */
    private function updateUser($model, $userAd, $newHash)
    {
        $model->fio = $userAd->displayname;
        $model->telephone = $userAd->telephoneNumber;
        $model->post = $userAd->title;
        $model->department = $userAd->department;
        $model->organization_name = $userAd->company;
        $model->hash = $newHash;
        $model->save();
    }
    
    
}