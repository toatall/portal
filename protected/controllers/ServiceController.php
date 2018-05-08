<?php

/**
 * API-сервисы
 * @author alexeevich
 */
class ServiceController extends Controller
{
    
    /**
     * {@inheritDoc}
     * @see CController::filters()
     * @return array
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
     * @return array
     */
    public function accessRules()
    {
        return array(
            array('allow',
                'actions' => array('user', 'test'),
                'users' => array('@'),
            ),            
            array('deny'),
        );
    }
    
    /**
     * Информация о пользователе
     * @see UserInfo
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