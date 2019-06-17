<?php

/**
 * Manager news
 * @author alexeevich
 * @see AdminController
 * @see News
 */
class NewsController extends AdminController {

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
     * @param integer $idTree идентификатор структуры
     */
    public function actionView($id, $idTree) {
        $this->render('view', array(
            'model' => $this->loadModel($id, $idTree),
            'idTree' => $idTree,
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @param integer $idTree идентификатор структуры
     * @throws CHttpException
     * @see News
     */
    public function actionCreate($idTree) {
        $modelTree = $this->loadModelTree($idTree);

        if (!(Yii::app()->user->admin || Access::checkAccessUserForTree($idTree)))
            throw new CHttpException(403, 'Доступ запрещен.');

        $model = new News;
        $model->id_tree = $idTree;
        $model->flag_enable = true;
        $model->date_start_pub = date('d.m.Y');
        $endDate = new DateTime();
        $endDate->setDate(2032, 12, 31);
        $model->date_end_pub = $endDate->format('d.m.Y');
        
        $model->author = Yii::app()->user->name;
        $model->general_page = 0;
        if (Yii::app()->user->isUFNS && $modelTree->module == 'news') {
            $model->on_general_page = 1;
        } else {
            $model->on_general_page = 0;
        }

        if (isset($_POST['News'])) {
            $model->attributes = $_POST['News'];
            $model->log_change = Log::setLog($model->log_change, 'создание');

            if ($model->save()) {
                // сохраняем файлы                
                $model->saveFiles($model->id, $idTree);
                // сохраняем изображения
                $model->saveImages($model->id, $idTree);
                // сохраняем миниатюра изображения
                $model->saveThumbailForNews($model);

                $this->redirect(array('view', 'id' => $model->id, 'idTree' => $idTree));
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
     * @param integer $idTree идентификатор структуры
     * @see Log
     */
    public function actionUpdate($id, $idTree) {
        $model = $this->loadModel($id, $idTree);
        $modelTree = $this->loadModelTree($idTree);

        $model->id_tree = $idTree;
        $model->author = Yii::app()->user->name;
        if ($modelTree->use_tape)
            $model->general_page = 0;
        $oldImageName = $model->thumbail_image;

        if (isset($_POST['News'])) {
            $model->attributes = $_POST['News'];
            $model->log_change = Log::setLog($model->log_change, 'изменение');

            if ($model->save()) {
                // файлы для удаления
                if (isset($_POST['News']['deleteFile'])) {
                    $delFile = $_POST['News']['deleteFile'];
                } else {
                    $delFile = array();
                }

                // изображения для удаления
                if (isset($_POST['News']['deleteImage'])) {
                    $delImage = $_POST['News']['deleteImage'];
                } else {
                    $delImage = array();
                }

                // сначала удаляем файлы и изображения помеченные для удаления
                $model->deleteFilesImages($model->id, $delFile, $delImage, $idTree);

                // сохраняем файлы    
                $model->saveFiles($model->id, $idTree);
                // сохраняем изображения                
                $model->saveImages($model->id, $idTree);
                // сохраняем миниатюра изображения
                $model->saveThumbailForNews($model, $oldImageName);

                $this->redirect(array('view', 'id' => $model->id, 'idTree' => $idTree));
            }
        }
        $this->render('update', array(
            'model' => $model,
            'modelTree' => $modelTree,
        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     * @param integer $idTree идентификатор структуры
     * @see Log
     * @see News
     * @throws CHttpException
     */
    public function actionDelete($id, $idTree) {
        if (Yii::app()->request->isPostRequest) {
            if (!Yii::app()->user->admin) {
                $model = $this->loadModel($id, $idTree);
                $model->date_delete = new CDbExpression('getdate()');
                $model->log_change = Log::setLog($model->log_change, 'удаление');
                $model->save();
            } else {
                // we only allow deletion via POST request
                $this->loadModel($id, $idTree)->delete();

                // удаляем все файлы и изображения
                $model = News::model();
                $model->deleteFilesImages($id,
                        CHtml::listData(Yii::app()->db->createCommand(array(
                                    'select' => 'id',
                                    'from' => '{{file}}',
                                    'where' => 'id_model=:id_model AND model=:model',
                                    'params' => array(':id_model' => $id, ':model' => 'news'),
                                ))->queryAll(),
                                'id', 'id'),
                        CHtml::listData(Yii::app()->db->createCommand(array(
                                    'select' => 'id',
                                    'from' => '{{image}}',
                                    'where' => 'id_model=:id_model AND model=:model',
                                    'params' => array(':id_model' => $id, ':model' => 'news'),
                                ))->queryAll(),
                                'id', 'id'),
                        $idTree
                );
            }

            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if (!isset($_GET['ajax']))
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin', 'idTree' => $idTree));
        } else
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    }

    /**
     * Manages all models.
     * @param integer $idTree идентификатор структуры
     * @see Tree
     * @see NewsSearch
     * @see Access
     * @throws CHttpException
     */
    public function actionAdmin($idTree) {
        // проверка существования узла структуры
        if (!Tree::model()->exists('id=:id AND module=:module and '
                        . '(id_organization=:organization1 or id_organization=:organization2)',
                        array(
                            ':id' => $idTree,
                            ':module' => 'news',
                            ':organization1' => Yii::app()->session['organization'],
                            ':organization2' => '0000',
                )))
            throw new CHttpException(404, 'Страница не найдена.');

        // проверка прав доступа к узлу
        if (!(Yii::app()->user->admin || Access::checkAccessUserForTree($idTree)))
            throw new CHttpException(403, 'Доступ запрещен.');

        $model = new NewsSearch('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['NewsSearch']))
            $model->attributes = $_GET['NewsSearch'];

        $this->render('admin', array(
            'model' => $model,
            'idTree' => $idTree,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     * @param integer $idTree идентификатор структуры
     * @see Access
     * @see News
     * @see Tree
     * @throws CHttpException
     * @uses self::actionView()
     * @uses self::actionUpdate()
     * @uses self::actionDelete()
     */
    public function loadModel($id, $idTree) {
        if (!(Yii::app()->user->admin || Access::checkAccessUserForTree($idTree)) || !Tree::model()->checkParentRight($idTree))
            throw new CHttpException(403, 'Доступ запрещен.');

        $delDate = (Yii::app()->user->admin) ? '' : ' AND date_delete is null';

        $model = News::model()->findByPk($id, 'id_tree=:id_tree ' . $delDate,
                array(':id_tree' => $idTree));

        if ($model === null)
            throw new CHttpException(404, 'Страница не найдена.');
        return $model;
    }

    /**
     * Поиск данных Tree модели
     * Если данные не найдены возникает HTTP исключение 404
     * @param int $idTree идентификатор структуры
     * @throws CHttpException
     * @return Tree
     * @uses self::actionCreate()
     * @uses self::actionUpdate()
     */
    public function loadModelTree($idTree) {
        $modelTree = Tree::model()->find('id=:id AND '
                . '(id_organization=:organization1 or id_organization=:organization2)',
                array(
                    ':id' => $idTree
                    , ':organization1' => Yii::app()->session['organization']
                    , ':organization2' => '0000'
        ));
        if ($modelTree === null)
            throw new CHttpException(404, 'Страница не найдена.');
        return $modelTree;
    }

    /**
     * Восстановление удаленной записи
     * @param integer $id идентификатор новости
     * @param integer $idTree идентификатор структуры
     * @see Log
     */
    public function actionRestore($id, $idTree) {
        if (!Yii::app()->user->admin)
            return;
        $model = $this->loadModel($id, $idTree);
        $model->date_delete = new CDbExpression('null');
        $model->log_change = Log::setLog($model->log_change, 'восстановление');
        $model->save();
        $this->redirect(array('view', 'id' => $model->id, 'idTree' => $idTree));
    }

    public function actionTags($term = null) {
        $model = Yii::app()->db->createCommand()
                ->selectDistinct('t.tags')
                ->from('{{news}} t')
                ->join('{{tree}} tree', 't.id_tree=tree.id')
                ->where('tree.module = :module and t.tags is not null', [':module' => 'news']);
        if ($term != null && strlen(trim($term)) > 0) {
            $model->andWhere(array('like', 't.tags', '%' . $term . '%'));
        }
        //print_r(array_values(CHtml::listData($model->queryAll(),'tags','tags')));
        echo CJSON::encode(array_values(CHtml::listData($model->queryAll(), 'tags', 'tags')));
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     * @deprecated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'news-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
