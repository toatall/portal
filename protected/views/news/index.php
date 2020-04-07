<?php
/**
 * @var $this NewsController
 * @var $model NewsSearch
 * @var $searchModelData array
 * @var $searchModel NewsSearch
 */

    // Навигатор
    if (isset($breadcrumbs))
    {
        $this->breadcrumbs = $breadcrumbs;
    }

    // Панель поиска
    $this->renderPartial('_search', array(
        'model' => $model,
    ));

    if ($searchModelData != null)
    {
        // данные
        foreach ($searchModelData as $data)
        {
            echo $this->renderPartial('application.views.news._indexRow', ['data' => $data], true);
        }
    }
    else
    {
        $this->renderPartial('application.views.share.partials.notFound');
    }

    // навигатор с разбивкой по страницам
    $this->widget('bootstrap.widgets.BsPager', [
        'pages' => $searchModel->pagination,
        'size' => BsHtml::BUTTON_SIZE_DEFAULT,
        'htmlOptions' => [
            'class' => 'pagination',
        ],
    ]);

?>