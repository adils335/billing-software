<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use dektrium\user\widgets\Connect;
?>
<div class="card">
    <div class="card-body login-card-body">
        <p class="login-box-msg">Login</p>

        <?php $form = ActiveForm::begin([
                    'id'                     => 'login-form',
                    'enableAjaxValidation'   => true,
                    'enableClientValidation' => false,
                    'validateOnBlur'         => false,
                    'validateOnType'         => false,
                    'validateOnChange'       => false,
                ]) ?>

                <?= $form->field($model, 'login', ['inputOptions' => ['autofocus' => 'autofocus', 'class' => 'form-control', 'tabindex' => '1']])->label("Username") ?>

                <?= $form->field($model, 'password', ['inputOptions' => ['class' => 'form-control', 'tabindex' => '2']])->passwordInput()->label(Yii::t('user', 'Password') . ($module->enablePasswordRecovery ? ' (' . Html::a(Yii::t('user', 'Forgot password?'), ['/user/recovery/request'], ['tabindex' => '5']) . ')' : '')) ?>
                
                <div class="row">
                    <div class="col-8">
                        <?= $form->field($model, 'rememberMe')->checkbox([
                            'template' => '<div class="icheck-primary">{input}{label}</div>',
                            'labelOptions' => [
                                 'class' => ''
                            ],
                            'uncheck' => null
                        ]) ?>
                    </div>
                     <div class="col-4">
                         <?= Html::submitButton('Sign In', ['class' => 'btn btn-primary btn-block']) ?>
                    </div>
                </div>
                <?php ActiveForm::end(); ?>

        <p class="mb-1">
            <a href="forgot-password.html">I forgot my password</a>
        </p>
        <p class="mb-0">
            <a href="register.html" class="text-center">Register a new membership</a>
        </p>
    </div>
    <!-- /.login-card-body -->
</div>