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
'modules'=>[
	'comments' => [
		'class' => 'yeesoft\comments\Comments',
	],
],
```

- In you model [optional]

```php
public function behaviors()
{
  return [
    'comments' => [
      'class' => 'yeesoft\comments\behaviors\CommentsBehavior'
    ]
  ];
}
```

Usage
---

- Widget namespace
```php
use yeesoft\comments\widgets\Comments;
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

  Example of module settings:
  ```php
    'comments' => [
      'class' => 'yeesoft\comments\Comments',
      'userAvatar' => function($user_id){
        return User::getUserAvatarByID($user_id);
      }
    ]
  ```

Screenshots
-------

___


> [![skeeks!](https://gravatar.com/userimage/74431132/13d04d83218593564422770b616e5622.jpg)](http://skeeks.com)  
<i>SkeekS CMS (Yii2) — fast, simple, effective!</i>  
[skeeks.com](http://skeeks.com) | [cms.skeeks.com](http://cms.skeeks.com) | [marketplace.cms.skeeks.com](http://marketplace.cms.skeeks.com)



