<?php

class BigImageCommand extends CConsoleCommand
{
    
    const MAX_WIDTH = 1920;
    const MAX_HEIGHT = 1080;
	
    private $path = __DIR__ . '/../files';
    
    
    public function actionIndex()
    {
        $images = $this->findFiles();
        foreach ($images as $image)
        {
            echo $image;
        }
    }
	
    private function findFiles($path = null)
    {
        if ($path == null)
        {
            $path = $this->path;
        }
        return CFileHelper::findFiles($path, ['fileTypes' => ['jpg', 'jpeg', 'bmp', 'png']]);        
    }
	
	

	
}