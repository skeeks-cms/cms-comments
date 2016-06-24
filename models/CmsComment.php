<?php
/**
 * @author Semenov Alexander <semenov@skeeks.com>
 * @link http://skeeks.com/
 * @copyright 2010 SkeekS (СкикС)
 * @date 24.06.2016
 */
namespace skeeks\comments\models;
use skeeks\comments\CommentsModule;
use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\helpers\HtmlPurifier;
use yii\helpers\Html;
/**
 * This is the model class for table "comment".
 *
 * @property integer $id
 * @property string $model
 * @property integer $model_id
 * @property integer $user_id
 * @property string $username
 * @property string $email
 * @property integer $super_parent_id
 * @property integer $parent_id
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $content
 * @property string $user_ip
 * @property string $url
 */

/**
 * Class CmsComment
 * @package skeeks\comments\models
 */
class CmsComment extends \yii\db\ActiveRecord
{
    const STATUS_PENDING = 0;
    const STATUS_APPROVED = 1;
    const STATUS_SPAM = 2;
    const STATUS_TRASH = 3;
    const STATUS_PUBLISHED = self::STATUS_APPROVED;
    const SCENARIO_GUEST = 'guest';
    const SCENARIO_USER = 'user';
    private $_comments;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%comment}}';
    }
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->on(self::EVENT_BEFORE_INSERT, [$this, 'setUserData']);
    }
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
            'blameable' => [
                'class' => BlameableBehavior::className(),
                'createdByAttribute' => 'user_id',
            ]
        ];
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['content'], 'required'],
            [['username', 'email'], 'required', 'on' => self::SCENARIO_GUEST],
            [['created_at', 'status', 'parent_id', 'super_parent_id'], 'integer'],
            [['content'], 'string'],
            [['username'], 'string', 'max' => 128],
            [['url'], 'string', 'max' => 255],
            [['username', 'content'], 'string', 'min' => 4],
            ['username', 'match', 'pattern' => CommentsModule::getInstance()->usernameRegexp, 'on' => self::SCENARIO_GUEST],
            ['username', 'match', 'not' => true, 'pattern' => CommentsModule::getInstance()->usernameBlackRegexp, 'on' => self::SCENARIO_GUEST],
            [['email'], 'email'],
            ['username', 'unique',
                'targetClass' => CommentsModule::getInstance()->userModel,
                'targetAttribute' => 'username',
                'on' => self::SCENARIO_GUEST,
            ],
        ];
    }
    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_USER] = ['content', 'parent_id', 'super_parent_id'];
        $scenarios[self::SCENARIO_GUEST] = ['username', 'email', 'content', 'parent_id', 'super_parent_id'];
        return $scenarios;
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => \Yii::t('skeeks/comments', 'ID'),
            'model' => \Yii::t('skeeks/comments', 'Model'),
            'model_id' => \Yii::t('skeeks/comments', 'Model ID'),
            'user_id' => \Yii::t('skeeks/comments', 'User ID'),
            'username' => \Yii::t('skeeks/comments', 'Username'),
            'email' => \Yii::t('skeeks/comments', 'E-mail'),
            'super_parent_id' => \Yii::t('skeeks/comments', 'Super Parent Comment'),
            'parent_id' => \Yii::t('skeeks/comments', 'Parent Comment'),
            'status' => \Yii::t('skeeks/comments', 'Status'),
            'created_at' => \Yii::t('skeeks/comments', 'Created'),
            'updated_at' => \Yii::t('skeeks/comments', 'Updated'),
            'content' => \Yii::t('skeeks/comments', 'Content'),
            'user_ip' => \Yii::t('skeeks/comments', 'IP'),
            'url' => \Yii::t('skeeks/comments', 'URL'),
        ];
    }
    /**
     * @inheritdoc
     *
     * @return CommentQuery the active query used by this AR class.
     */
    public static function find($loadComments = false)
    {
        $query = new CmsCommentQuery(get_called_class());
        if ($loadComments) {
            $query->loadComments = true;
        }
        return $query;
    }

    public function save($runValidation = true, $attributeNames = null)
    {
        if (isset($this->parent_id) && $this->parent_id) {
            $parent = self::find()
                            ->where(['id' => $this->parent_id])
                            ->select('super_parent_id')->one();
            $super_parent_id = ($parent->super_parent_id) ? $parent->super_parent_id : $this->parent_id;
            $this->super_parent_id = $super_parent_id;
        }
        return parent::save($runValidation, $attributeNames);
    }
    public function getShortContent($length = 64)
    {
        return HtmlPurifier::process(mb_substr(Html::encode($this->content), 0, $length, "UTF-8")) . ((strlen($this->content) > $length) ? '...' : '');
    }
    public function getComments()
    {
        return $this->_comments;
    }
    public function setComments($comments)
    {
        $this->_comments = $comments;
    }
    /**
     * getTypeList
     * @return array
     */
    public static function getStatusList()
    {
        return [
            self::STATUS_PENDING => \Yii::t('skeeks/comments', 'Pending'),
            self::STATUS_APPROVED => \Yii::t('skeeks/comments', 'Approved'),
            self::STATUS_SPAM => \Yii::t('skeeks/comments', 'Spam'),
            self::STATUS_TRASH => \Yii::t('skeeks/comments', 'Trash'),
        ];
    }
    /**
     * getStatusOptionsList
     * @return array
     */
    public static function getStatusOptionsList()
    {
        return [
            [self::STATUS_PENDING, \Yii::t('skeeks/comments', 'Pending'), 'default'],
            [self::STATUS_APPROVED, \Yii::t('skeeks/comments', 'Approved'), 'primary'],
            [self::STATUS_SPAM, \Yii::t('skeeks/comments', 'Spam'), 'default'],
            [self::STATUS_TRASH, \Yii::t('skeeks/comments', 'Trash'), 'default']
        ];
    }
    /**
     * Get created date
     *
     * @param string $format date format
     * @return string
     */
    public function getCreatedDate($format = 'Y-m-d')
    {
        return date($format, ($this->isNewRecord) ? time() : $this->created_at);
    }
    /**
     * Get created date
     *
     * @param string $format date format
     * @return string
     */
    public function getUpdatedDate($format = 'Y-m-d')
    {
        return date($format, ($this->isNewRecord) ? time() : $this->updated_at);
    }
    /**
     * Get created time
     *
     * @param string $format time format
     * @return string
     */
    public function getCreatedTime($format = 'H:i')
    {
        return date($format, ($this->isNewRecord) ? time() : $this->created_at);
    }
    /**
     * Get created time
     *
     * @param string $format time format
     * @return string
     */
    public function getUpdatedTime($format = 'H:i')
    {
        return date($format, ($this->isNewRecord) ? time() : $this->updated_at);
    }
    /**
     * Get author of comment
     *
     * @return string
     */
    public function getAuthor()
    {
        if ($this->user_id) {
            $userModel = CommentsModule::getInstance()->userModel;
            $user = $userModel::findIdentity($this->user_id);
            return ($user && isset($user)) ? $user->username : CommentsModule::getInstance()->deletedUserName;
        } else {
            return $this->username;
        }
    }
    /**
     * Updates user's data before comment insert
     */
    public function setUserData()
    {
        $this->user_ip = Yii::$app->getRequest()->getUserIP();
        $this->url = Yii::$app->getRequest()->url;
        if (!Yii::$app->user->isGuest) {
            $this->user_id = Yii::$app->user->id;
        }
    }
    /**
     * Check whether comment has replies
     *
     * @return int nubmer of replies
     */
    public function isReplied()
    {
        return Comment::find()->where(['parent_id' => $this->id])->active()->count();
    }
    /**
     * Get count of active comments by $model and $model_id
     *
     * @param string $model
     * @param int $model_id
     * @return int
     */
    public static function activeCount($model, $model_id = NULL)
    {
        return Comment::find()->where(['model' => $model, 'model_id' => $model_id])->active()->count();
    }
}