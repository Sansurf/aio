<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\jui\DatePicker;
use app\models\Author;

/* @var $this yii\web\View */
/* @var $model app\models\Post */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="post-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'text')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'like')->textInput() ?>

    <?= $form->field($model, 'author_id')->dropDownList(
        Author::find()->select(['name', 'id'])->indexBy('id')->column()
    ) ?>

    <?= $form->field($model, 'date')->widget(DatePicker::class, [
        'language' => 'ru',
        'dateFormat' => 'php:Y-m-d',
//        'options' => ['value' => date('Y-m-d')]
    ]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
