Comments
===================================

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist skeeks/cms-comments "*"
```

or add

```
"skeeks/cms-comments": "*"
```


How to use (simple)
----------


```php
//App config
[
    
    'components'    =>
    [
        //....
        'externalLinks' =>
        [
            'class' => 'skeeks\yii2\externalLinks\ExternalLinksComponent',
        ],
        //....
    ],

    'modules'    =>
    [
        //....
        'externallinks' =>
        [
            'class' => 'skeeks\yii2\externalLinks\ExternalLinksModule',
        ],
        //....
    ]
]

```

How to use (advanced)
----------


```php
//App config
[
    'bootstrap'    => ['externalLinks'],

    'components'    =>
    [
        //....
        'externalLinks' =>
        [
            'class' => 'skeeks\yii2\externalLinks\ExternalLinksComponent',

            //Additional
            'enabled'                           => true,
            'noReplaceLocalDomain'              => true,
            'backendRoute'                      => '/externallinks/redirect/redirect',
            'backendRouteParam'                 => 'url',
            'enabledB64Encode'                  => true,
            'noReplaceLinksOnDomains'           => [
                'site1.ru',
                'www.site1.ru',
                'site2.ru',
            ],
        ],
        
        'urlManager' => 
        [
            'rules' => 
            [
                //Rewriting the standard route
                //And add robots.txt  Disallow: /~*
                '~skeeks-redirect'                        => '/externallinks/redirect/redirect',
            ]
        ]
        //....
    ],

    'modules'    =>
    [
        //....
        'externallinks' =>
        [
            'class' => 'skeeks\yii2\externalLinks\ExternalLinksModule',
        ],
        //....
    ]
]

```

##Screenshot
[![SkeekS CMS admin panel](http://marketplace.cms.skeeks.com/uploads/all/b3/c5/f6/b3c5f64a07798c80f78c0de102a4cf14.png)](http://marketplace.cms.skeeks.com/uploads/all/b3/c5/f6/b3c5f64a07798c80f78c0de102a4cf14.png)

___


> [![skeeks!](https://gravatar.com/userimage/74431132/13d04d83218593564422770b616e5622.jpg)](http://skeeks.com)  
<i>SkeekS CMS (Yii2) — fast, simple, effective!</i>  
[skeeks.com](http://skeeks.com) | [cms.skeeks.com](http://cms.skeeks.com) | [marketplace.cms.skeeks.com](http://marketplace.cms.skeeks.com)



