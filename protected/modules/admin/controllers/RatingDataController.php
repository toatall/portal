<?php

/**
 * Manage rating data
 * @author alexeevich
 * @see AdminController
 */
class RatingDataController extends AdminController {

    /**
     * Default action
     * @var string
     */
    public $defaultAction = 'admin';

    /**
     * @return array action filters
     */
    public function filters() {
        return array(
            'accessControl', // perform access control for CRUD operations
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules() {
        return array(
            array('allow',
                'users' => array('@'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id) {
        $this->render('view', array(
            'model' => $this->loadModel($id),
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @param integer $idTree идентификатор структуры
     * @see FileHelper
     * @see RatingData
     */
    public function actionCreate($idTree) {
        $model = new RatingMain;
        $modelTree = $this->loadModelTree($idTree);

        if (isset($_POST['RatingMain'])) {
            $model->attributes = $_POST['RatingMain'];
            $model->id_tree = $modelTree->id;

            if ($model->save()) {
                FileHelper::filesUpload('files', null, ['name' => 'ratingMain', $model->id]);
                $this->redirect(array('view', 'id' => $model->id));
            }
        }

        $this->render('create', array(
            'model' => $model,
            'modelTree' => $modelTree,
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     * @see FileHelper
     */
    public function actionUpdate($id) {
        $model = $this->loadModel($id);
        $this->loadModelTree($model->id_tree); // check access user
        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['RatingMain'])) {
            $model->attributes = $_POST['RatingMain'];
            if ($model->save()) {
                FileHelper::postDeleteFiles(['modelName' => 'ratingMain', 'modelId' => $model->id]);
                FileHelper::filesUpload('files', null, ['name' => 'ratingMain', 'id' => $model->id]);
                $this->redirect(array('view', 'id' => $model->id));
            }
        }

        $this->render('update', array(
            'model' => $model,
        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     * @see FileHelper
     * @throws CHttpException
     */
    public function actionDelete($id) {
        if (Yii::app()->request->isPostRequest) {
            $model = $this->loadModel($id);
            $this->loadModelTree($model->id_tree); // check access user
            // we only allow deletion via POST request
            $model->delete();

            FileHelper::postDeleteFiles(['all' => [
                    'allow' => true,
                    'modelName' => 'ratingMain',
                    'modelId' => $id,
            ]]);

            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if (!isset($_GET['ajax']))
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
        } else
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    }

    /**
     * Manages all models.
     * @param integer $idTree идентификатор структуры
     * @see RatingMain
     */
    public function actionAdmin($idTree) {
        $model = new RatingMain('search');
        $model->unsetAttributes();  // clear any default values
        $model->id_tree = $idTree;
        if (isset($_GET['RatingMain']))
            $model->attributes = $_GET['RatingMain'];

        $this->render('admin', array(
            'model' => $model,
            'modelTree' => $this->loadModelTree($idTree),
        ));
    }

    /**
     * Управление данными рейтинга
     * @param integer $id идентификатор рейтинга
     * @see RatingData
     */
    public function actionAdminRating($id) {
        $modelRatingMain = $this->loadModelRatingMain($id);
        $this->loadModelTree($modelRatingMain->id_tree); // check access user
        $model = new RatingData('search');
        $model->unsetAttributes();  // clear any default values
        $model->id_rating_main = $id;

        if (isset($_GET['RatingData'])) {
            $model->attributes = $_GET['RatingData'];
        }

        $this->render('adminRating', array(
            'model' => $model,
            'modelRatingMain' => $modelRatingMain,
        ));
    }

    /**
     * Создание документа рейтинга
     * @param integer $id идентификатор рейтинга
     * @see RatingData
     */
    public function actionCreateRating($id) {
        $model = new RatingData;
        $modelRatingMain = $this->loadModelRatingMain($id);
        $this->loadModelTree($modelRatingMain->id_tree); // check access user
        $model->rating_year = date('Y');
        $model->rating_period = date('m') . '_1_mes';

        if (isset($_POST['RatingData'])) {
            $model->attributes = $_POST['RatingData'];
            $model->id_rating_main = $modelRatingMain->id;

            if ($model->save()) {
                $model->saveFiles();
                $this->redirect(array('viewRating', 'id' => $model->id));
            }
        }

        if (Yii::app()->request->isAjaxRequest) {
            $this->renderPartial('createRating', array(
                'model' => $model,
                'modelRatingMain' => $modelRatingMain,
                    ), false, true);
        } else {
            $this->render('createRating', array(
                'model' => $model,
                'modelRatingMain' => $modelRatingMain,
            ));
        }
    }

    /**
     * Изменение документа рейтинга
     * @param integer $id идентификатор документа рейтинга
     */
    public function actionUpdateRating($id) {
        $model = $this->loadModelRatingData($id);
        $this->loadModelTree($model->ratingMain->id_tree);  // check access user

        if (isset($_POST['RatingData'])) {
            $model->attributes = $_POST['RatingData'];
            if ($model->save()) {
                $model->saveFiles();

                // файлы для удаления
                $delFile = array();
                if (isset($_POST['RatingFile']['deleteFile'])) {
                    foreach ($_POST['RatingFile']['deleteFile'] as $f) {
                        $delFile[$f] = $f;
                    }
                }
                $model->deleteFiles($delFile);

                $this->redirect(array('viewRating', 'id' => $model->id));
            }
        }

        $this->render('updateRating', array(
            'model' => $model,
        ));
    }

    /**
     * Просмотр документа рейтинга
     * @param integer $id идентификатор рейтинга
     */
    public function actionViewRating($id) {
        $model = $this->loadModelRatingData($id);
        $this->loadModelTree($model->ratingMain->id_tree); // check access user
        $this->render('viewRating', array(
            'model' => $model,
        ));
    }

    /**
     * Удаление документа рейтинга
     * @param integer $id идентификатор документа рейтинга
     * @throws CHttpException
     */
    public function actionDeleteRating($id) {
        if (Yii::app()->request->isPostRequest) {
            $model = $this->loadModelRatingData($id);
            $this->loadModelTree($model->ratingMain->id_tree); // check access user
            $id = $model->id_rating_main;
            // we only allow deletion via POST request
            $model->delete();

            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if (!isset($_GET['ajax']))
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('adminRating', 'id' => $id));
        } else
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     * @return RatingMain
     * @uses self::actionView()
     * @uses self::actionUpdate()
     */
    public function loadModel($id) {
        $model = RatingMain::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Поиск структуры по идентификатору
     * @param int $id идентификатор структруы
     * @throws CHttpException
     * @return NULL|Tree
     * @uses self::actionCreate()
     * @uses self::actionUpdate()
     * @uses self::actionAdmin()
     * @uses self::actionAdminRating()
     * @uses self::actionUpdateRating()
     * @uses self::actionViewRating()
     * @uses self::actionDeleteRating()
     */
    public function loadModelTree($id) {
        $model = Tree::model()->findByPk($id, 'id_organization=:org', [':org' => Yii::app()->session['organization']]);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        if (!$model->allowAccess)
            throw new CHttpException(403, 'У вас недостаточно прав для выполнения указанного действия.');
        return $model;
    }

    /**
     * Поиск рейтинга по идентификатору
     * @param int $id идентификатор рейтинга
     * @return RatingData
     * @uses self::actionAdminRating()
     * @uses self::actionCreateRating()
     */
    public function loadModelRatingMain($id) {
        $model = RatingMain::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Поиск документа рейтинга по идентификатору
     * @param integer $id идентификатор документа рейтинга
     * @throws CHttpException
     * @see RatingData
     * @see Tree
     * @return RatingData
     * @uses self::actionUpdateRating()
     * @uses self::actionViewRating()
     * @uses self::actionDeleteRating()
     */
    public function loadModelRatingData($id) {
        $model = RatingData::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        if (!Tree::checkTreeNode($model->ratingMain->id_tree))
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     * @deprecated
     */
    protected function performAjaxValidation($model) {
        throw new CHttpException(410);
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'rating-main-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
