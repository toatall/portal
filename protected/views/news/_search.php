    <form id="form-search" class="">
		<div class="input-append span12">
    		<input name="News[title]" type="text" class="span9" placeholder="Заголовок" />
    		<button class="btn btn-primary" type="submit"><i class="fas fa-search"></i> Поиск</button>    		
    		<button class="btn btn-default" id="form-search-settings"><i class="fas fa-plus"></i>&nbsp;</button>
		</div>
		<div id="div-form-search-settings" class="form-horizontal" style="display: none;">
			<div>
				<input type="text" name="News[message1]" class="span9" placeholder="Текст новости" />
			</div>
			<div style="margin-top:10px;">
				<input type="text" name="News[date_create_1]" class="span4 " placeholder="Дата с..." />
				<input type="text" name="News[date_create_2]" class="span4 offset1" placeholder="... по" />
			</div>	
			<?php if (!(isset($hideOrganization) && $hideOrganization)) { ?>
			<div style="margin-top:10px;">
				<?= CHtml::dropDownList('News[id_organization]', '', [''=>'- выберите организацию -'] + CHtml::listData(Organization::model()->findAll(),'code','name'), ['class'=>'span9']); ?>
			</div>	
			<?php } ?>	
		</div>
	</form>
	
<script type="text/javascript">

    jQuery(function() {
    
        // поиск новостей
        $('#form-search').on('submit',function() {
            <?php 
                $urlParams = (isset($hideOrganization) && isset($organization) && $hideOrganization) ? ['organization' => $organization] : [];
            ?>
        	url = '<?= (isset($linkActionNews) && $linkActionNews != null ? $linkActionNews : Yii::app()->controller->createUrl('news/news', $urlParams)) ?>';
        	url = urlConcationation(url, $(this).serialize());
        	ajaxNews(url, {}, '#container_news', false);
        	return false;
        });

        $('#form-search-settings').on('click', function() {
            
        	$('#div-form-search-settings').toggle();

        	if ($('#div-form-search-settings').css('display') == 'none')
        	{
        		$('#form-search-settings').html('<i class="fas fa-plus"></i>&nbsp;');
        	}
        	else
        	{
        		$('#form-search-settings').html('<i class="fas fa-minus"></i>&nbsp;');
        	}
        	return false;
        });
    
    });
    
</script>