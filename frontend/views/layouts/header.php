<?php
use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $content string */
?>

<header class="main-header">

    <?= Html::a('<span class="logo-mini">APP</span><span class="logo-lg">' . Yii::$app->name . '</span>',
        Yii::$app->homeUrl, ['class' => 'logo']) ?>

    <nav class="navbar navbar-static-top" role="navigation">

        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>

        <style>
            .logout {
                color: #b8c7ce;
            }
            .logout:hover {
                color: white;
            }
        </style>
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <?php if (Yii::$app->user->isGuest) : ?>
                    <li>
                        <?= Html::a(Yii::t('app', 'Signup'),
                            ['site/signup'],
                            ['title' => Yii::t('app', 'Signup')]); ?>
                    </li>
                    <li>
                        <?= Html::a(Yii::t('app', 'Login'),
                            ['site/login'],
                            ['title' => Yii::t('app', 'Login')]); ?>
                    </li>
                <?php else : ?>
                    <li>
                        <?= Html::a(Yii::t('app', 'Logout'),
                            ['site/logout'],
                            ['title' => Yii::t('app', 'Logout')]); ?>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>
</header>
