<?php

/**
 * Coding by JiangYu 2210705
 * 使用 Gii 生成了 Conversations 的 CRUD
 */

namespace app\models;

use Yii;

/**
 * This is the model class for table "Conversations".
 *
 * @property int $ConversationID
 * @property int $UserID
 * @property string|null $StartedAt
 * @property string|null $EndedAt
 * @property string|null $Status
 *
 * @property Messages[] $messages
 * @property Users $user
 */
class Conversations extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'Conversations';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['UserID'], 'required'],
            [['UserID'], 'integer'],
            [['StartedAt', 'EndedAt'], 'safe'],
            [['Status'], 'string', 'max' => 50],
            [['UserID'], 'exist', 'skipOnError' => true, 'targetClass' => Users::class, 'targetAttribute' => ['UserID' => 'UserID']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'ConversationID' => 'Conversation ID',
            'UserID' => 'User ID',
            'StartedAt' => 'Started At',
            'EndedAt' => 'Ended At',
            'Status' => 'Status',
        ];
    }

    /**
     * Gets query for [[Messages]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMessages()
    {
        return $this->hasMany(Messages::class, ['ConversationID' => 'ConversationID']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(Users::class, ['UserID' => 'UserID']);
    }
}
