<?php

/**
 * Coding by JiangYu 2210705
 * 使用 Gii 生成了 Messages 的 CRUD
 */
/**
 * Designing by WangYuheng 2213040
 * 参与数据库设计
 */
namespace app\models;

use Yii;

/**
 * This is the model class for table "Messages".
 *
 * @property int $MessageID
 * @property int $ConversationID
 * @property string $Sender
 * @property string $Content
 * @property string|null $Timestamp
 *
 * @property Conversations $conversation
 */
class Messages extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'Messages';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ConversationID', 'Sender', 'Content'], 'required'],
            [['ConversationID'], 'integer'],
            [['Sender', 'Content'], 'string'],
            [['Timestamp'], 'safe'],
            [['ConversationID'], 'exist', 'skipOnError' => true, 'targetClass' => Conversations::class, 'targetAttribute' => ['ConversationID' => 'ConversationID']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'MessageID' => 'Message ID',
            'ConversationID' => 'Conversation ID',
            'Sender' => 'Sender',
            'Content' => 'Content',
            'Timestamp' => 'Timestamp',
        ];
    }

    /**
     * Gets query for [[Conversation]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getConversation()
    {
        return $this->hasOne(Conversations::class, ['ConversationID' => 'ConversationID']);
    }
}
