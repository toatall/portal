<?php
/* @var $this AnswerController */
/* @var $model TestAnswer */

$modelQuestion = $model->question;

$this->breadcrumbs=array(
    'Тесты' => ['/test/test/admin'],
    $modelQuestion->test->name => ['test/test/view', 'id'=>$modelQuestion->id_test],
    'Вопросы' => ['/test/question/admin', 'idTest'=>$modelQuestion->id_test],
    $modelQuestion->name => ['/test/question/view', 'id'=>$modelQuestion->id],
    'Ответы' => ['admin', 'idQuestion'=>$modelQuestion->id],
    'Изменение ответа #' . $model->id,
);
?>

<?= BsHtml::pageHeader('Ответы', 'Изменение ответа #' . $model->id) ?>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>