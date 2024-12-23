<?php

/**
 * Coding by JiangYu 2210705
 * 使用 Gii 生成了 VideoLikes 的 CRUD
 */

namespace app\models;

use Yii;

/**
 * This is the model class for table "VideoLikes".
 *
 * @property int $LikeID
 * @property int $UserID
 * @property int $VideoID
 * @property string|null $LikedAt
 *
 * @property Users $user
 * @property Videos $video
 */
class VideoLikes extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'VideoLikes';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['UserID', 'VideoID'], 'required'],
            [['UserID', 'VideoID'], 'integer'],
            [['LikedAt'], 'safe'],
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
            'LikeID' => 'Like ID',
            'UserID' => 'User ID',
            'VideoID' => 'Video ID',
            'LikedAt' => 'Liked At',
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
