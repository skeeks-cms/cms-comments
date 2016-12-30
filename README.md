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

- Run migrations

```php
yii migrate --migrationPath=@skeeks/cms/comments/migrations/
```

How to use (simple)
----------


Configuration
------

- In your config file

```php
'bootstrap' => ['comments'],
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
	    'class' => 'skeeks\cms\comments\CommentsModule',
	    //'maxNestedLevel'          => 5
        //'onlyRegistered'          => false
        //'orderDirection'          => SORT_DESC
        //'nestedOrderDirection'    => SORT_ASC
        //'displayAvatar'           => true
        //'commentsPerPage'         => 5,
        'on commentAdded' => function(\skeeks\cms\comments\events\CommentEvent $e)
        {
            /**
             * @var $comment \skeeks\cms\comments\models\CmsComment
             */
            $comment = $e->comment;
            $comment->user;
            $comment->model;
            $comment->model_id;
        },

        'on commentDeleted' => function(\skeeks\cms\comments\events\CommentEvent $e)
        {
            //...
        }
	]
]
```

- In you model [optional]

```php
public function behaviors()
{
  return [
    'comments' => [
      'class' => 'skeeks\cms\comments\behaviors\CommentsBehavior'
    ]
  ];
}
```

- Content element property update count comments

```php
'on commentAdded' => function(\skeeks\cms\comments\events\CommentEvent $e)
{
	/**
	 * @var $comment \skeeks\cms\comments\models\CmsComment
	 * @var $user \common\models\User
	 * @var $element \skeeks\cms\models\CmsContentElement
	 */
	$comment = $e->comment;
	$user = $comment->user;

	/*$user->appUser->total_comments = $user->appUser->total_comments + 1;
	if (!$user->appUser->save())
	{
	    \Yii::error("Not update user total comments: {$user->id}", 'project');
	}*/

	\Yii::error(\skeeks\cms\models\CmsContentElement::tableName(), 'project');
	\Yii::error(\yii\helpers\Json::encode($comment->toArray()), 'project');

	if ($comment->model == \skeeks\cms\models\CmsContentElement::tableName())
	{
	    $element = \skeeks\cms\models\CmsContentElement::findOne($comment->model_id);
	    if ($element && $element->relatedPropertiesModel->hasAttribute('comments'))
	    {
		$totalComments = \skeeks\cms\comments\models\CmsComment::find()->where([
		    'model_id' => $element->id,
		])->andWhere(['model' => \skeeks\cms\models\CmsContentElement::tableName()])->count();
		$element->relatedPropertiesModel->setAttribute('comments', $totalComments);
		//$element->relatedPropertiesModel->setAttribute('comments', ((int) $element->relatedPropertiesModel->getAttribute('comments') + 1));

		if (!$element->relatedPropertiesModel->save())
		{
		    \Yii::error("Not update element total comments: {$element->id}", 'project');
		}
	    } else
	    {
		\Yii::error("Element not found or not have property comments: {$element->id}", 'project');
	    }
	}
},

'on commentDeleted' => function(\skeeks\cms\comments\events\CommentEvent $e)
{
....
	    
```
	    
Usage
---

- Widget namespace
```php
use skeeks\cms\comments\widgets\CommentsWidget;
```

- Add comment widget in model view using (string) page key :

```php
echo Comments::widget(['model' => $pageKey]);
```

- Or display comments using model name and id:

```php
echo Comments::widget(['model' => 'post', 'model_id' => 1]);
```

- Or display comments using model behavior:

```php
echo Post::findOne(10)->displayComments();
```

Module Options
-------

Use this options to configurate comments module:

- `userModel` - User model class name.

- `maxNestedLevel` - Maximum allowed nested level for comment's replies.

- `onlyRegistered` - Indicates whether not registered users can leave a comment.

- `orderDirection` - Comments order direction.

- `nestedOrderDirection` - Replies order direction.

- `userAvatar` - The field for displaying user avatars.

  Is this field is NULL default avatar image will be displayed. Also it can specify path to image or use callable type.

  If this property is specified as a callback, it should have the following signature: `function ($user_id)`


For dev
-------
```php
php yii lang/translate-app @skeeks/cms/comments/messages/ru/main.php @skeeks/cms/comments/messages/ main.php
```
___


> [![skeeks!](https://gravatar.com/userimage/74431132/13d04d83218593564422770b616e5622.jpg)](http://skeeks.com)  
<i>SkeekS CMS (Yii2) — fast, simple, effective!</i>  
[skeeks.com](http://skeeks.com) | [cms.skeeks.com](http://cms.skeeks.com) | [marketplace.cms.skeeks.com](http://marketplace.cms.skeeks.com)



