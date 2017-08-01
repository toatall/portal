<?php
$this->breadcrumbs=array(
	'Sez Datas'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'Список SezData','url'=>array('index'), 'icon'=>'list'),
	array('label'=>'Создать SezData','url'=>array('create'), 'icon'=>'asterisk'),
	array('label'=>'Изменить SezData','url'=>array('update','id'=>$model->id), 'icon'=>'pencil'),
	array('label'=>'Удалить SezData','url'=>'#',
        'linkOptions'=>array(
            'submit'=>array('delete','id'=>$model->id),
            'confirm'=>'Вы уверены, что хотите удалить эту запись?',
        ),
        'icon'=>'trash',
    ),
	array('label'=>'Управление SezData','url'=>array('admin'), 'icon'=>'user'),
);
?>

<h1>Просмотр SezData #<?php echo $model->id; ?></h1>

<?php $this->widget('bootstrap.widgets.TbDetailView',array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'id_sez',
		'id_author',
		'date_create',
		'date_edit',
		'log_change',
		'egrn_fid',
		'egrn_fid_stat',
		'egrn_inn_actual',
		'egrn_date_inn_actual',
		'egrn_inn_not_actual',
		'egrn_date_inn_not_actual',
		'egrn_inn_value_not_actual',
		'egrn_fio',
		'egrn_fio_update',
		'egrn_date_birth',
		'egrn_place_birth',
		'egrn_date_dead',
		'egrn_ogrnip',
		'egrn_doc_identity_actual',
		'egrn_doc_identity_not_actual',
		'egrn_variant_write_ponil',
		'egrn_address_actual_location',
		'egrn_code_no_address_actual_location',
		'egrn_date_reg_location',
		'egrn_prev_address_location',
		'egrn_code_no_prev_address_location',
		'egrn_date_departures_address_location',
		'object_type_reg',
		'object_name_reg',
		'objec_fid_reg',
		'object_code_no_reg',
		'object_address_reg',
		'object_source_info_reg',
		'object_date_register',
		'object_date_unregister',
		'object_casuse_unregister',
		'krsb_code_no',
		'krsb_kbk',
		'krsb_oktmo',
		'krsb_flag_open_close',
		'krsb_saldo_calc',
	),
)); ?>
