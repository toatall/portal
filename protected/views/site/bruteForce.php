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
	    'success' => "js:function(data) { $('#result_container').html(data); }",
	], ['class'=>'btn btn-primary']) ?>
    
<?= CHtml::endForm() ?>

	<div class="well" id="result_container" style="margin-top:10px;"></div>

</div>
