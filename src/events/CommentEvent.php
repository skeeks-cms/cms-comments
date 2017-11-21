<?php
/**
 * @author Semenov Alexander <semenov@skeeks.com>
 * @link http://skeeks.com/
 * @copyright 2010 SkeekS (СкикС)
 * @date 03.07.2016
 */
namespace skeeks\cms\comments\events;

use skeeks\cms\comments\models\CmsComment;
use yii\base\Event;

/**
 * Class CommentEvent
 * @package skeeks\cms\comments\events
 */
class CommentEvent extends Event
{
    /**
     * @var CmsComment|null
     */
    public $comment = null;
}