<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\HistorySearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="history-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'ins_ts') ?>

    <?= $form->field($model, 'customer_id') ?>

    <?= $form->field($model, 'user_id') ?>

    <?= $form->field($model, 'event') ?>

    <?php // echo $form->field($model, 'object') ?>

    <?php // echo $form->field($model, 'object_id') ?>

    <?php // echo $form->field($model, 'message') ?>

    <?php // echo $form->field($model, 'detail') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
