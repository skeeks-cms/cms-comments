<?php
class m150706_175101_create_comment_table extends yii\db\Migration
{
    const TABLE_NAME = '{{%cms_comment}}';

    public function up()
    {
        $tableOptions = null;

        $tableExist = $this->db->getTableSchema(self::TABLE_NAME, true);
        if ($tableExist)
        {
            return true;
        }

        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable(self::TABLE_NAME, [
            'id'                    => $this->primaryKey(),

            'created_by'            => $this->integer(),
            'updated_by'            => $this->integer(),
            'created_at'            => $this->integer(),
            'updated_at'            => $this->integer(),

            'user_id'               => $this->integer(),

            'model'                 => $this->string(64)->notNull()->defaultValue(''),
            'model_id'              => $this->integer(),
            
            'username'              => $this->string(128),
            'email'                 => $this->string(128),
            'parent_id'             => $this->integer()->comment('null-is not a reply, int-replied comment id'),
            'super_parent_id'       => $this->integer()->comment('null-has no parent, int-1st level parent id'),

            'content'               => $this->text(),
            'status'                => $this->integer(1)->unsigned()->notNull()->defaultValue(1)->comment('0-pending,1-published,2-spam,3-deleted'),
            'user_ip'               => $this->string(15),

            'url'                   => $this->string(255),

        ], $tableOptions);

        $this->createIndex('comment_model', self::TABLE_NAME, 'model');
        $this->createIndex('comment_model_id', self::TABLE_NAME, ['model', 'model_id']);
        $this->createIndex('comment_status', self::TABLE_NAME, 'status');
        $this->createIndex('comment_reply', self::TABLE_NAME, 'parent_id');
        $this->createIndex('comment_super_parent_id', self::TABLE_NAME, 'super_parent_id');

        $this->addForeignKey(
            'cms_comment__created_by', self::TABLE_NAME,
            'created_by', '{{%cms_user}}', 'id', 'SET NULL', 'SET NULL'
        );

        $this->addForeignKey(
            'cms_comment__updated_by', self::TABLE_NAME,
            'updated_by', '{{%cms_user}}', 'id', 'SET NULL', 'SET NULL'
        );

        $this->addForeignKey(
            'cms_comment__user_id', self::TABLE_NAME,
            'user_id', '{{%cms_user}}', 'id', 'SET NULL', 'SET NULL'
        );

    }
    public function down()
    {
        $this->dropIndex('cms_comment__created_by', self::TABLE_NAME);
        $this->dropIndex('cms_comment__updated_by', self::TABLE_NAME);
        $this->dropIndex('cms_comment__user_id', self::TABLE_NAME);
        $this->dropIndex('comment_model', self::TABLE_NAME);
        $this->dropIndex('comment_model_id', self::TABLE_NAME);
        $this->dropIndex('comment_status', self::TABLE_NAME);
        $this->dropIndex('comment_reply', self::TABLE_NAME);
        $this->dropIndex('comment_super_parent_id', self::TABLE_NAME);
        $this->dropTable(self::TABLE_NAME);
    }
}