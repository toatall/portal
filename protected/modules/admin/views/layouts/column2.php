<?php
/**
 * @var $this Controller
 * @var $content string
 */ ?>
<?php $this->beginContent('/layouts/main'); ?>
<div class="row">
    <?php if (count($this->menu)): ?>     
    <div class="col-sm-3 col-md-3">
        <div class="well" id="sidebar">
        <?php
            $this->beginWidget('zii.widgets.CPortlet', array(
                'title' => '<b>Операции</b>',
            ));

            echo BsHtml::navList($this->menu, ['class'=>'operations']);

            $this->endWidget();
        ?>
        </div><!-- sidebar -->        
    </div>
    <?php endif; ?>
    <div class="col-sm-9 col-md-9"<?php if (!count($this->menu)) { ?> style="width: 100%;"<?php }?>>
        <div id="content">
            <?php echo $content; ?>
        </div><!-- content -->
    </div>
</div>
<?php $this->endContent(); ?>