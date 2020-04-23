<?php

/**
 * Поиск по тестам
 * Class TestSearch
 */
class TestSearch extends Test
{
    /**
     * @return CActiveDataProvider
     */
    public function searchTest()
    {
        $criteria = new CDbCriteria;

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => array(
                'defaultOrder' => 'date_create desc, id desc',
            ),
        ));
    }

    /**
     * Получение данных для тестирования
     * @param $id
     * @return array
     * @throws CHttpException
     */
    public function searchTestData($id)
    {
        /* @var $model $this */
        $model = $this->findByPk($id);
        if ($model === null) {
            throw new CHttpException('404', 'Page not found');
        }

        $criteriaTest = new CDbCriteria();
        $criteriaTest->compare('id_test', $id);
        if ($model->count_questions > 0) {
            $criteriaTest->limit = $model->count_questions;
        }
        $criteriaTest->order = 'newid()';

        $resultData = [];

        /* @var $modelQuestion TestQuestion[] */
        $modelQuestion = TestQuestion::model()->findAll($criteriaTest);
        foreach ($modelQuestion as $itemQuestion)
        {
            /* @var $modelAnswer TestAnswer[] */
            $modelAnswer = TestAnswer::model()->findAll('id_test_question=:id', [':id'=>$itemQuestion->id]);
            $dataAnswers = [];
            foreach ($modelAnswer as $itemAnswer)
            {
                $dataAnswers[] = [
                    'id' => $itemAnswer->id,
                    'name' => $itemAnswer->name,
                    'file' => $itemAnswer->attach_file,
                ];
            }
            $resultData[] = [
                'id' => $itemQuestion->id,
                'name' => $itemQuestion->name,
                'type' => $itemQuestion->type_question,
                'file' => $itemQuestion->attach_file,
                'answers' => $dataAnswers,
            ];
        }
        return $resultData;

    }




}