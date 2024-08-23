<?php
use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $content string */
$notification = Yii::$app->history;
?>

<header class="main-header">

    <?= Html::a('<span class="logo-mini">APP</span><span class="logo-lg">' . Yii::$app->name . '</span>', Yii::$app->homeUrl, ['class' => 'logo']) ?>

    <nav class="navbar navbar-static-top" role="navigation">

        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>

        <div class="navbar-custom-menu">

            <ul class="nav navbar-nav">
                <?php $totalNotification = $notification->totalNotification();?>
               <!--Notification-->
               <li class="dropdown notifications-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-bell-o"></i>
                        <span class="label label-warning"><?= $totalNotification?></span>
                    </a>
                    <ul class="dropdown-menu">
                    <li class="header">You have <?= $totalNotification?> notifications</li><li>
                        <ul class="menu">
                            <!--<?php //foreach( $notification->allNotification as $notifi ):?>
                            <li>
                                <a href="<?php //\yii\helpers\Url::to(['notifications'])?>">
                                <i class="fa fa-users text-aqua"></i> <?php //echo $notifi->id?>
                                </a>
                            </li>
                            <?php //endforeach;?>-->
                        </ul>
                    </li>
                    <li class="footer"><a href="#">View all</a></li>
                    </ul>
               </li>
               
                <!-- User Account: style can be found in dropdown.less -->

                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <img src="<?= $directoryAsset ?>/img/user2-160x160.jpg" class="user-image" alt="User Image"/>
                        <span class="hidden-xs"><?= Yii::$app->user->identity->username?></span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- User image -->
                        <li class="user-header">
                            <img src="<?= $directoryAsset ?>/img/user2-160x160.jpg" class="img-circle"
                                 alt="User Image"/>

                            <p>
                                <?= Yii::$app->user->identity->username?>
                            </p>
                        </li>
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div class="pull-right">
                                <?= Html::a(
                                    'Sign out',
                                    ['/site/logout'],
                                    ['data-method' => 'post', 'class' => 'btn btn-default btn-flat']
                                ) ?>
                            </div>
                        </li>
                    </ul>
                </li>

            </ul>
        </div>
    </nav>
</header>
