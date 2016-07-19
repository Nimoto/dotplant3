<?php

$config = [
    'adminEmail' => 'admin@example.com',
    'yii.migrations' => [
        '@yii/rbac/migrations/',
        '@DevGroup/DataStructure/migrations/',
        '@DevGroup/DeferredTasks/migrations/',
        '@DevGroup/Users/migrations/',
        '@DotPlant/Monster/migrations/',
        '@DevGroup/MediaStorage/src/migrations/',

    ],
    'icon-framework' => 'fa',
    'PolyglotTranslationPath' => '@app/messages/polyglot.js',
    'deferred.env' => [
        'HOME' => '/Users/bethrezen',
    ],
    'uploadPath' => '@webroot/files/',
];

$generatedConfig = __DIR__ . '/generated/params-generated.php';
if (file_exists($generatedConfig)) {
    $config = \yii\helpers\ArrayHelper::merge($config, require($generatedConfig));
}

$localConfig = __DIR__ . DIRECTORY_SEPARATOR . 'params-local.php';
if (file_exists($localConfig)) {
    $config = \yii\helpers\ArrayHelper::merge($config, require($localConfig));
}

return $config;
