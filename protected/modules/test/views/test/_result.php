<?php
/**
 * @var $this TestController
 * @var $result array
 */
$persent = round(($result['rightAnswers'] / $result['questions']) * 100, 2);
?>
<h3 style="font-weight: bolder">Вы ответили правильно на <?= $result['rightAnswers'] ?> из <?= $result['questions'] ?> вопросов</h3>
<div class="progress">
    <div class="progress-bar" role="progressbar" aria-valuenow="<?= $persent ?>" aria-valuemax="100" aria-valuemin="0" style="width: <?= $persent ?>%;">
        <strong><?= $persent ?>%</strong>
    </div>
</div>