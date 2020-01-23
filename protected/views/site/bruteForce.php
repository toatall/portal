<div class="row">

<h1>Подбор букв с словах</h1>
<hr />

<?= CHtml::beginForm(); ?>
	
	<?= CHtml::label('Список', 'label') ?>
	<?= CHtml::textArea('list', '', ['class'=>'span12', 'rows'=>10]) ?>
	
	<?= CHtml::label('Разделитель в результате', 'demiter') ?>
	<?= CHtml::textField('demiter', '/') ?>
	
	<?= CHtml::ajaxSubmitButton('Отправить', ['site/bruteforce'], [
	    'type' => 'GET',
	    'beforeSend' => "js:function() { $('#btn-submit').attr('disabled', true); return true; }",
	    'success' => "js:function(data) { $('#result_container').html(data); }",
	    'complete' => "js:function() { $('#btn-submit').attr('disabled', false); return true; }",
	], ['class'=>'btn btn-primary', 'id'=>'btn-submit', 'style'=>'margin-top:-10px;']) ?>
    
<?= CHtml::endForm() ?>

	<div class="well" id="result_container" style="margin-top:10px;"></div>

</div>
<script type="text/javascript">	
</script>