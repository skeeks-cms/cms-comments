<?php
/**
 * @link https://cms.skeeks.com/
 * @copyright Copyright (c) 2010 SkeekS
 * @license https://cms.skeeks.com/license/
 * @author Semenov Alexander <semenov@skeeks.com>
 */
return [
    'components' => [
        'i18n' => [
            'translations' => [
                'skeeks/comments' => [
                    'class'    => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@skeeks/cms/comments/messages',
                    'fileMap'  => [
                        'skeeks/comments' => 'main.php',
                    ],
                ],
            ],
        ],
    ],
];