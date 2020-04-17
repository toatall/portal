<?php
/**
 * @var $this CController
 * @var message string
 */

if (!isset($message)) {
    $message = 'Данные успешно сохранены';
}

?>
<div class="alert alert-info">
    <?= $message ?>
</div>
<script type="text/javascript">
    $("#modal-dialog").attr('data-result', 'true');
</script>