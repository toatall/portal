<?php
/* @var $this AnswerController */
/* @var $model TestAnswer */
/* @var $modelQuestion TestQuestion */

$this->breadcrumbs=array(
    'Тесты' => ['/test/test/admin'],
    $modelQuestion->test->name => ['test/test/view', 'id'=>$modelQuestion->id_test],
    'Вопросы' => ['/test/question/admin', 'idTest'=>$modelQuestion->id_test],
    $modelQuestion->name => ['/test/question/view', 'id'=>$modelQuestion->id],
    'Ответы' => ['admin', 'idQuestion'=>$modelQuestion->id],
    'Создание ответа',
);

?>
<?= BsHtml::pageHeader('Ответы', 'Создание ответа') ?>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>