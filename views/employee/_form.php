<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\jui\DatePicker;
use \app\models\Employee;

/* @var $this yii\web\View */
/* @var $model app\models\Employee */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="employee-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'phone')->textInput() ?>

    <?= $form->field($model, 'designation')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'address')->textarea(['rows' => 6]) ?>
    
    <?php if(!$model->isNewRecord): ?>
    <?= $form->field($model, 'status')->dropDownList(Employee::getStatusList()) ?>
    <?php endif; ?>
    
    <?= $form->field($model, 'joined_on')->widget(\yii\jui\DatePicker::classname(), [
                           'language' => 'en',
                           'options' => ['autocomplete'=>'off'],
                            'clientOptions'=>[
                              'changeMonth'=>true,
                              'changeYear'=> true,
                              'yearRange'=> "-5:+5",
                              //'minDate'=> "+0",
                              'dateFormat' => 'dd/mm/yy',
                            ],
                      ])->label('Joined On'); ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
