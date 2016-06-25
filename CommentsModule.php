<?php
/**
 * @author Semenov Alexander <semenov@skeeks.com>
 * @link http://skeeks.com/
 * @copyright 2010 SkeekS (СкикС)
 * @date 24.06.2016
 */

namespace skeeks\cms\comments;
use skeeks\cms\models\CmsUser;
use yii\base\Module;

/**
 * Class CommentsModule
 *
 * @package skeeks\yii2\comments
 */
class CommentsModule extends Module
{
    public $controllerNamespace = 'skeeks\cms\comments\controllers';

    /**
     * Path to default avatar image
     */
    const DEFAULT_AVATAR = '/images/user.png';

    /**
     *  User model class name
     *
     * @var string
     */
    public $userModel = 'common\models\User';
    /**
     * Name to display if user is deleted
     *
     * @var string
     */
    public $deletedUserName = 'DELETED';
    /**
     * Maximum allowed nested level for comment's replies
     *
     * @var int
     */
    public $maxNestedLevel = 5;
    /**
     * Count of first level comments per page
     *
     * @var int
     */
    public $commentsPerPage = 5;

    /**
     * Bootstrap grid columns count.
     *
     * @var int
     */
    public $gridColumns = 12;
    /**
     *  Indicates whether not registered users can leave a comment
     *
     * @var boolean
     */
    public $onlyRegistered = FALSE;
    /**
     * Comments order direction
     *
     * @var int const
     */
    public $orderDirection = SORT_DESC;
    /**
     * Replies order direction
     *
     * @var int const
     */
    public $nestedOrderDirection = SORT_ASC;
    /**
     * The field for displaying user avatars.
     *
     * Is this field is NULL default avatar image will be displayed. Also it
     * can specify path to image or use callable type.
     *
     * If this property is specified as a callback, it should have the following signature:
     *
     * ~~~
     * function ($user_id)
     * ~~~
     *
     * Example of module settings :
     * ~~~
     * 'comments' => [
     *       'class' => 'yeesoft\comments\Comments',
     *       'userAvatar' => function($user_id){
     *           return User::getUserAvatarByID($user_id);
     *       }
     *   ]
     * ~~~
     * @var string|callable
     */
    public $userAvatar;
    /**
     *
     *
     * @var boolean
     */
    public $displayAvatar = true;
    /**
     * Comments asset url
     *
     * @var string
     */
    public $commentsAssetUrl;
    /**
     * Pattern that will be applied for user names on comment form.
     *
     * @var string
     */
    public $usernameRegexp = '/^(\w|\p{L}|\d|_|\-| )+$/ui';
    /**
     * Pattern that will be applied for user names on comment form.
     * It contain regexp that should NOT be in username
     * Default pattern doesn't allow anything having "admin"
     *
     * @var string
     */
    public $usernameBlackRegexp = '/^(.)*admin(.)*$/i';
    /**
     * Comments module ID.
     *
     * @var string
     */
    public $commentsModuleID = 'comments';
    /**
     * Options for captcha
     *
     * @var array
     */
    public $captchaOptions = [
        'class' => 'yii\captcha\CaptchaAction',
        'minLength' => 4,
        'maxLength' => 6,
        'offset' => 5
    ];
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
    }

    /**
     * Render user avatar by UserID according to $userAvatar setting
     *
     * @param int $user_id
     * @return string
     */
    public function renderUserAvatar($user = null)
    {
        $defaultAvatar = $this->commentsAssetUrl . self::DEFAULT_AVATAR;

        if ($user && $user->avatarSrc)
        {
            return $user->avatarSrc;
        } else
        {
            return $defaultAvatar;
        }
    }

    public static function getMultilingUrl($url)
    {
        $languages = Yii::$app->yee->languages;
        $languageRedirects = Yii::$app->yee->languageRedirects;
        $language = Yii::$app->language;
        $language = (isset($languageRedirects[$language])) ? $languageRedirects[$language] : $language;
        $language = '/' . $language . '/';
        $keys = array_unique(array_merge(array_keys($languages), array_values($languageRedirects)));
        array_walk($keys, function(&$item) {
            $item = '/' . $item . '/';
        });
        foreach ($keys as $key) {
            if (strpos($url, $key) === 0) {
                $url = substr($url, strlen($key));
            }
        }
        return $language . $url;
    }
}