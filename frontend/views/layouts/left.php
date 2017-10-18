<?php
use yii\helpers\Html;
?>

<aside class="main-sidebar">

    <section class="sidebar">

        <?= dmstr\widgets\Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu'],
                'items' => [
                    ['label' => 'Menu', 'options' => ['class' => 'header']],
                    ['label' => 'Projects', 'url' => ['branch/index'], 'visible' => !Yii::$app->user->isGuest, 'icon' => 'git'],
                    ['label' => 'Re-scan', 'url' => ['branch/scan'], 'visible' => !Yii::$app->user->isGuest, 'icon' => 'search'],
                    [
                        'label' => 'Tools',
                        'icon' => 'gears',
                        'url' => '#',
                        'items' => [
                            [
                                'label' => 'Vagrant Box',
                                'icon' => 'dropbox',
                                'url' => '#',
                                'items' => [
                                    ['label' => 'Version', 'icon' => 'code-fork', 'url' => ['vagrant-box/version'],],
                                    ['label' => 'Update', 'icon' => 'download', 'url' => ['vagrant-box/update'],],
                                ],
                            ],
                            ['label' => 'Self update', 'icon' => 'refresh', 'url' => ['site/self-update'],],
                        ],
                    ],
                ],
            ]
        ) ?>

    </section>

</aside>
