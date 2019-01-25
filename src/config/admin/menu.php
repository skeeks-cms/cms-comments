<?php
/**
 * @author Semenov Alexander <semenov@skeeks.com>
 * @link http://skeeks.com/
 * @copyright 2010 SkeekS (СкикС)
 * @date 26.06.2016
 */
return [
    'other' => [
        'items' => [
            'comments' => [
                'label'    => ['skeeks/comments', 'Comments'],
                "img"      => ['skeeks\cms\comments\assets\CommentsAsset', 'images/comments.jpg'],
                'priority' => 250,

                'items' => [
                    [
                        'priority' => 0,
                        'label'    => ['skeeks/comments', 'Comments'],
                        "url"      => ["comments/admin-comment"],
                        "img"      => ['skeeks\cms\comments\assets\CommentsAsset', 'images/comments.jpg'],
                    ],
                ],
            ],
        ],
    ],
];
