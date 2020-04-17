<?php

class DefaultController extends Controller
{
    /**
     * {@inheritDoc}
     * @see CController::accessRules()
     * @return array
     */
    public function accessRules()
    {
        return [
            ['allow',
                'users'=>['@'],
            ],
        ];
    }

    /**
     * Главная страница модуля
     */
	public function actionIndex()
	{
		$this->render('index');
	}



}