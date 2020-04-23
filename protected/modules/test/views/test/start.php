<?php
/**
 * @var $this CController
 * @var $testData array
 * @var $attempts array
 * @var $model Test
 *
 * Логика в JS:
 * Сделать Wizard
 * Переключение с помощью вкладок или кнопками вперед и назад
 * В конце кнопка Готово
 *
 *
 * Изначальные данные - массив вопросов и ответов
 * [
 *    [
 *       'id' - идентификатор
 *       'name' - наименование вопроса
 *       'type' - тип вопроса (0 - radio, 1 - checkbox)
 *       'file' - файл
 *       'answers' - ответы
 *          [
 *              [
 *                  'id' - идентификатор
 *                  'name' - наименование
 *                  'file' - файл
 *              ]
 *          ]
 *    ]
 * ]
 *
 * В результат нужно отправить post:
 *
 * [
 *      'questions' => [], // какие вопросы были выбраны
 *      'answers' => [
 *          'id' => [ // идентификатор вопроса
 *              [] => [] | "" // ответ или ответы
 *          ]
 *      ]
 * ]
 *
 */

?>
<div id="test-div"></div>
<form id="test_form" action="<?= $this->createUrl('/test/test/start', ['id'=>$model->id]) ?>">
    <input type="hidden" name="Test[id]" value="<?= $model->id ?>" />
    <ul class="pagination" id="tabs-test" role="tablist">
    <?php for ($i=0; $i<count($testData); $i++): ?>
        <li role="presentation"<?= $i==0 ? ' class="active"' : '' ?>>
            <a href="#question_<?= $testData[$i]['id'] ?>" aria-controls="question_<?= $testData[$i]['id'] ?>" role="tab" data-toggle="tab">
                <?= '#' . ($i+1) ?>
            </a>
        </li>
    <?php endfor; ?>
    </ul>

    <div class="tab-content">
        <?php for ($i=0; $i<count($testData); $i++): ?>
        <input type="hidden" name="Test[questions][]" value="<?= $testData[$i]['id'] ?>" />
        <div role="tabpanel" class="tab-pane <?= $i==0 ? ' in active' : '' ?>" id="question_<?= $testData[$i]['id'] ?>">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <?php
                        // Название вопроса
                        echo $testData[$i]['name']
                        // Изображение
                    ?>
                </div>
                <div class="panel-body">
                    <?php
                        // вывести ответы
                        foreach ($testData[$i]['answers'] as $data) {
                            ?>
                            <div class="form-group">
                            <?php

                            if ($testData[$i]['type'] == TestQuestion::TYPE_QUESTION_RADIO) {
                                ?>
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="Test[answers][<?= $testData[$i]['id'] ?>]" value="<?= $data['id'] ?>" />
                                        <?= $data['name'] ?>
                                    </label>
                                </div>
                                <?php
                            }
                            elseif ($testData[$i]['type'] == TestQuestion::TYPE_QUESTION_CHECK)
                            {
                                ?>
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="Test[answers][<?= $testData[$i]['id'] ?>][]" value="<?= $data['id'] ?>" />
                                        <?= $data['name'] ?>
                                    </label>
                                </div>
                                <?php
                            }
                            else
                            {
                                echo 'Тип вопроса не опознан!';
                            }

                            ?>
                            </div>
                            <?php
                        };
                    ?>
                </div>
            </div>

            <div class="btn-group">
            <?php
                // кнопка назад
                if ($i > 0) {
                    echo BsHtml::button('<i class="fas fa-arrow-circle-left"></i> Назад', ['class'=>'btn btn-primary btn-previous']);
                }
                // кнопка вперед
                if ($i < count($testData)-1) {
                    echo BsHtml::button('<i class="fas fa-arrow-circle-right"></i> Далее', ['class'=>'btn btn-primary btn-next']);
                }
                // кнопка назад
                if ($i == count($testData)-1) {
                    echo BsHtml::submitButton('<i class="fas fa-share-square"></i> Завершить', ['class'=>'btn btn-success']);
                }
            ?>
            </div>
        </div>
        <?php endfor; ?>
    </div>

</form>
<script type="text/javascript">

    $('.btn-previous').on('click', function () {
        $('#tabs-test > .active').prev('li').find('a').trigger('click');
    });

    $('.btn-next').on('click', function () {
        $('#tabs-test > .active').next('li').find('a').trigger('click');
    });

    $('#test_form').on('submit', function (e) {
        if (!confirm('Вы уверены, что хотите завершить?')) {
            return false;
        }

        $('#modal-body').html('<img src="/images/loader_fb.gif" width="48" />');
        e.preventDefault();
        $.ajax({
            type: 'post',
            url: $(this).attr('action'),
            data: $(this).serialize()
        })
        .done(function (data) {
            $('#modal-body').html(data);
        })
        .fail(function (jqXHR) {
            $('#modal-body').html('<div class="alert alert-danger">' + jqXHR.status + ' ' + jqXHR.statusText + '</div>');
        });

        return false;
    });
    ajaxGET()
</script>