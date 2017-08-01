<li class="thumbnail">      
    <a class="thumbnail" style="text-align: left;" href="<?php echo $this->createUrl('news',array('view'=>$data->id)); ?>">
        <img src="/images/Desert.jpg" />
    </a>    
    <div class="caption">
        <h4><?php echo $data->login; ?></h4>
        <span style="font-size: 12px;">
            <?php echo $data->last_name.' '.$data->first_name.' '.$data->middle_name; ?>
        </span>
    </div>    
</li>
 