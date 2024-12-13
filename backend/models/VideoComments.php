<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "VideoComments".
 *
 * @property int $CommentID
 * @property int $UserID
 * @property int $VideoID
 * @property string $Content
 * @property string|null $CommentedAt
 *
 * @property Users $user
 * @property Videos $video
 */
class VideoComments extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'VideoComments';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['UserID', 'VideoID', 'Content'], 'required'],
            [['UserID', 'VideoID'], 'integer'],
            [['Content'], 'string'],
            [['CommentedAt'], 'safe'],
            [['UserID'], 'exist', 'skipOnError' => true, 'targetClass' => Users::class, 'targetAttribute' => ['UserID' => 'UserID']],
            [['VideoID'], 'exist', 'skipOnError' => true, 'targetClass' => Videos::class, 'targetAttribute' => ['VideoID' => 'VideoID']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'CommentID' => 'Comment ID',
            'UserID' => 'User ID',
            'VideoID' => 'Video ID',
            'Content' => 'Content',
            'CommentedAt' => 'Commented At',
        ];
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

    /**
     * Gets query for [[Video]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVideo()
    {
        return $this->hasOne(Videos::class, ['VideoID' => 'VideoID']);
    }
}
