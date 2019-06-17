<?php

/**
 * This is the model class for table "{{rating_data}}".
 *
 * The followings are the available columns in table '{{rating_data}}':
 * @property integer $id
 * @property integer $id_rating_main
 * @property string $note
 * @property integer $rating_year
 * @property string $rating_period
 * @property string $date_create
 * @property string $author
 * @property string $log_change
 *
 * The followings are the available model relations:
 * @property RatingMain $ratingMain
 * @property File[] $files
 * @property string fileView
 */
class RatingData extends CActiveRecord {

    /**
     * Периоды для рейтинга
     * @var array
     */
    private $periods = array(
        // месяцы
        '01_1_mes' => 'Январь',
        '02_1_mes' => 'Февраль',
        '03_1_mes' => 'Март',
        '04_1_mes' => 'Апрель',
        '05_1_mes' => 'Май',
        '06_1_mes' => 'Июнь',
        '07_1_mes' => 'Июль',
        '08_1_mes' => 'Август',
        '09_1_mes' => 'Сентябрь',
        '10_1_mes' => 'Октябрь',
        '11_1_mes' => 'Ноябрь',
        '12_1_mes' => 'Декабрь',
        // кварталы
        '03_2_kv' => '1 квартал',
        '06_2_kv' => '2 квартал',
        '09_2_kv' => '3 квартал',
        '12_2_kv' => '4 квартал',
        // полугодия
        '06_3_pol' => '1 полугодие',
        '12_3_pol' => '2 полугодие',
        // 9 месяцев
        '09_4_9mes' => '9 месяцев',
        // год
        '12_5_god' => 'Годовой',
    );

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{rating_data}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('rating_year, rating_period', 'required'),
            array('id_rating_main, rating_year', 'numerical', 'integerOnly' => true),
            array('rating_period', 'length', 'max' => 200),
            array('author', 'length', 'max' => 250),
            array('date_create, log_change, id_rating_main', 'unsafe'),
            array('note', 'safe'),
            // The following rule is used by search().			
            array('id, id_rating_main, note, rating_year, rating_period, date_create, 
				author, log_change', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'ratingMain' => array(self::BELONGS_TO, 'RatingMain', 'id_rating_main'),
            'files' => array(self::HAS_MANY, 'File', 'id_model',
                'condition' => "[files].[model]='ratingData'"),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => '#',
            'id_rating_main' => 'RatingMain.id',
            'note' => 'Примечание',
            'rating_year' => 'Год',
            'rating_period' => 'Период',
            'rating_name' => 'Период',
            'date_create' => 'Дата создания',
            'author' => 'Автор',
            'log_change' => 'Журнал изменений',
            'fileView' => 'Файлы',
            'periodName' => 'Период',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     *
     * Typical usecase:
     * - Initialize the model fields with values from filter form.
     * - Execute this method to get CActiveDataProvider instance which will filter
     * models according to data in model fields.
     * - Pass data provider to CGridView, CListView or any similar widget.
     *
     * @return CActiveDataProvider the data provider that can return the models
     * based on the search/filter conditions.
     */
    public function search() {
        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('id_rating_main', $this->id_rating_main);
        $criteria->compare('note', $this->note, true);
        $criteria->compare('rating_year', $this->rating_year);
        $criteria->compare('rating_period', $this->rating_period, true);
        $criteria->compare('date_create', $this->date_create, true);
        $criteria->compare('author', $this->author, true);
        $criteria->compare('log_change', $this->log_change, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => array(
                'defaultOrder' => 'rating_year desc, rating_period desc',
            ),
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return RatingData the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * {@inheritDoc}
     * @see CActiveRecord::beforeSave()
     * @see UserInfo
     */
    protected function beforeSave() {
        if ($this->isNewRecord) {
            $this->date_create = new CDbExpression('getdate()');
            $this->author = UserInfo::inst()->userLogin;
        }
        return parent::beforeSave();
    }

    /**
     * {@inheritDoc}
     * @see CActiveRecord::afterFind()
     * @see DateHelper
     */
    protected function afterFind() {
        $this->date_create = DateHelper::explodeDateTime($this->date_create);
        return parent::afterFind();
    }

    /**
     * {@inheritDoc}
     * @see CActiveRecord::beforeDelete()
     */
    protected function beforeDelete() {
        // delete all files
        $this->deleteFiles([], true);
        return parent::beforeDelete();
    }

    /**
     * Доступные года для рейтига (+- 2 года от текущего)
     * @return array
     */
    public function getYears() {
        $y = date('Y');
        $resultYears = array();
        for ($i = ($y - 2); $i <= ($y + 2); $i++) {
            $resultYears[$i] = $i;
        }
        return $resultYears;
    }

    /**
     * Периоды для рейтинга
     * @return array 
     */
    public function getPeriods() {
        return $this->periods;
    }

    /**
     * Сохранение файлов рейтинга
     * @return boolean
     * @uses RatingDataController::actionCreateRating()
     * @uses RatingDataController::actionUpdateRating()
     */
    public function saveFiles() {
        // определение модуля
        $module_name = Tree::model()->findByPk($this->ratingMain->id_tree)->module;

        // получение списка файлов 
        $files = CUploadedFile::getInstancesByName('files');

        if (isset($files) && count($files) > 0) {
            $baseDir = str_replace('{code_no}', Yii::app()->session['organization'],
                    Yii::app()->params['pathDocumets']);
            $baseDir = str_replace('{module}', $module_name, $baseDir);
            $baseDir = str_replace('{id}', $this->id, $baseDir);

            // создание каталога, если его нет
            if (!file_exists($_SERVER['DOCUMENT_ROOT'] . $baseDir)) {
                if (!@mkdir($_SERVER['DOCUMENT_ROOT'] . $baseDir, 0777, true))
                    return false;
            }

            foreach ($files as $file) {
                $fileName = iconv('UTF-8', 'windows-1251', $file->name);

                if ($file->saveAs($_SERVER['DOCUMENT_ROOT'] . $baseDir . $fileName)) {
                    // сохранение информации о файлах в БД
                    Yii::app()->db->createCommand()->insert('{{file}}', [
                        'id_model' => $this->id,
                        'model' => 'ratingData',
                        'file_name' => $file->name,
                        'file_size' => $file->size,
                        'date_create' => new CDbExpression('getdate()'),
                        'author' => UserInfo::inst()->userLogin,
                        'id_organization' => Yii::app()->session['organization'],
                    ]);
                }
            }
        }
    }

    /**
     * Удаление файла(ов)
     * @param array $listFiles - список файлов [id=>name,...]
     * @param bool $all - признак удаления всех файлов // 
     * @todo move to FileHelper
     * @uses RatingDataController::actionUpdateRating()
     */
    public function deleteFiles($listFiles, $all = false) {
        if ($all) {
            $model = Yii::app()->db->createCommand()
                    ->from('{{file}}')
                    ->where('model=:model', [':model' => 'ratingData'])
                    ->andWhere('id_model=:id_model', [':id_model' => $this->id])
                    ->queryAll();

            $listFiles = CHtml::listData($model, 'id', 'file_name');
        }


        foreach ($listFiles as $pk => $file) {
            $modelFile = File::model()->findByPk($pk);
            if ($modelFile !== null) {
                $modelFile->delete();
            }
        }
    }

    /**
     * Наименование периода в $this
     * @return string|null
     */
    public function getPeriodName() {
        if (isset($this->periods[$this->rating_period]))
            return $this->periods[$this->rating_period];
        return null;
    }

    /**
     * Ссылка для загрузки файла и информация о количесве загруженных файлов
     * @return string
     */
    public function getFileDownload() {
        $result = '';
        foreach ($this->files as $file) {
            $result .= CHtml::link($file->file_name, ['file/download', 'id' => $file->id], array('target' => '_blank'))
                    . ' (<i class="icon-download" title="Загрузок"></i> ' . $file->count_download . ')<br />';
        }
        return $result;
    }

    /**
     * Список файлов
     * @return string
     */
    public function getFileView() {
        $result = '';
        foreach ($this->files as $file) {
            $result .= CHtml::link($file->file_name, $file->urlFile, array('target' => '_blank')) . '<br />';
        }
        return $result;
    }

    /**
     * Это зачем?
     * @param int $idMain
     * @param int $year
     * @param string $orderAsc
     * @return self
     */
    public static function dataRating($idMain, $year, $orderAsc = true) {
        return self::model()->findAll([
                    'condition' => 'id_rating_main=:id_main and rating_year=:year',
                    'params' => [':id_main' => $idMain, ':year' => $year],
                    'order' => 'rating_period ' . ($orderAsc ? 'asc' : 'desc'),
        ]);
    }

    /**
     * Render for tree
     * @param Tree(array) $modelTree
     * @return 
     * @use TreeController::actionView(102)
     */
    public function treeAction($modelTree) {
        $model = Yii::app()->db->createCommand()
                ->from('{{rating_main}}')
                ->where('id_tree=:id_tree', [':id_tree' => $modelTree['id']])
                ->queryAll();

        Yii::app()->controller->pageTitle = $modelTree['name'];

        Yii::app()->controller->render('application.views.department.rating', [
            'model' => $model,
            'modelTree' => $modelTree,
            'breadcrumbs' => array(
                $modelTree['name'],
            ),
        ]);
    }

}
