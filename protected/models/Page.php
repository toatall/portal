<?php 

    class Page extends News
    {
        
        
        /**
         * Render for tree
         * @param Tree(array) $modelTree
         * @return
         * @use TreeController::actionView(102)
         */
        public function treeAction($modelTree)
        {
            $model=new NewsSearch('searchPublic');
            $model->unsetAttributes();  // clear any default values
            if(isset($_GET['News']))
            $model->attributes=$_GET['News'];
            
            $model->id_tree = $modelTree['id'];
            $model = $model->searchPublic(0, false);
       
            $lastId = isset($model[count($model)-1]['id']) ? date('YmdHis', strtotime($model[count($model)-1]['date_create'])) . $model[count($model)-1]['id'] : 0;

            Yii::app()->controller->render('/news/feed',array(
                'model'=>$model,
                'lastId'=>$lastId,
                'type'=>'news',
                'urlAjax'=>Yii::app()->controller->createUrl('news/index', ['q'=>null, 'id'=>$lastId])
            ));
        }
        
    }
?>