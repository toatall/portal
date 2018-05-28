<?php 
    /**
     * @param Organization $organizationModel
     */
?>

<?php

if (isset($breadcrumbs))
{
	$this->breadcrumbs = $breadcrumbs;
}

?>

<?php if ($organizationModel !== null) { ?>
	<div class="alert alert-info" style="width: 600px;">
		<h3>Новости: <?= $organizationModel['code'] . ' (' . $organizationModel['name'] . ')' ?></h3>
	</div>	
<?php } ?>

<div class="content content-color" style="width: 600px; padding-bottom:30px;">
<?php 
    $this->renderPartial('/news/_search', ['hideOrganization'=>!$allOrganization, 'organization'=>$organization, 'linkActionNews'=>(isset($linkActionNews) ? $linkActionNews : null)]);
?>
</div>    

<div id="container_news" style="margin-top: 20px;"></div>

<script type="text/javascript">

	jQuery(function() {
		ajaxNews('<?= (isset($linkActionNews) && $linkActionNews != null ? $linkActionNews : Yii::app()->controller->createUrl('news/news', ['organization'=>$organization])) ?>', {}, '#container_news', false);
	});
		
</script>
