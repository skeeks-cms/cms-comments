<?php
/**
 * @author Semenov Alexander <semenov@skeeks.com>
 * @link http://skeeks.com/
 * @copyright 2010 SkeekS (СкикС)
 * @date 27.08.2015
 */
return [

    'components' =>
    [
        'i18n' => [
            'translations'  =>
            [
                'skeeks/comments' =>
                [
                    'class'             => 'yii\i18n\PhpMessageSource',
                    'basePath'          => '@skeeks/cms/comments/messages',
                    'fileMap' => [
                        'skeeks/comments' => 'main.php',
                    ],
                ]
            ],
        ],
    ],

    'modules' =>
    [
        'comments' =>
        [
            'class' => 'skeeks\cms\comments\CommentsModule'
        ]
    ]
];