<?php

class ServiceController extends Controller
{
    
    /**
     * {@inheritDoc}
     * @see CController::filters()
     */
    public function filters()
    {
        return array(
            'accessControl', // perform access control for CRUD operations
        );
    }
    
    
    /**
     * {@inheritDoc}
     * @see CController::accessRules()
     */
    public function accessRules()
    {
        return array(
            array('allow',
                'actions' => array('user'),
                'users' => array('@'),
            ),
            array(
                'actoins' => array('????????'),
                'users' => array('?'),
            ),
            array('deny'),
        );
    }
    
    
    /**
     * Информация о пользователе
     */
    public function actionUser()
    {
        $user = UserInfo::inst();
     
        echo CJSON::encode([
            'userAuth' => $user->userAuth,
            'userLogin' => $user->userLogin,
            'userName' => $user->userName,
            'orgCode' => $user->orgCode,
            'clientIP' => $user->clientIP,
            'clientHost' => $user->clientHost,
            'ADLogin' => $user->ADLogin,
            'ADDepartment' => $user->ADDepartment,
            'ADPost' => $user->ADPost,
            'ADCompany' => $user->ADCompany,
            'ADTelephone' => $user->ADTelephone,
            'ADPrincipalName' => $user->ADPrincipalName,
            'ADMemberOf' => $user->ADMemberOf,
        ]);
    }
    
    
    
    
    
    
}