<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Item;

/* @var $this yii\web\View */
/* @var $model app\models\Item */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="item-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'hsn_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'rate')->textInput() ?>

    <?= $form->field($model, 'taxable_amount')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'sgst')->textInput() ?>

    <?= $form->field($model, 'cgst')->textInput() ?>

    <?= $form->field($model, 'opening_stock')->textInput() ?>

    <?= $form->field($model, 'minimum_stock')->textInput() ?>

    <?php if(!$model->isNewRecord): ?>
   		<?= $form->field($model, 'status')->dropDownList(Item::getStatusList()) ?>
    <?php endif; ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
