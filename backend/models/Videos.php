<?php

/**
 * Coding by JiangYu 2210705
 * 使用 Gii 生成了 Videos 的 CRUD
 */
/**
 * Designing by WangYuheng 2213040
 * 参与数据库设计
 */
namespace app\models;

use Yii;

/**
 * This is the model class for table "Videos".
 *
 * @property int $VideoID
 * @property string $Title
 * @property string $URL
 * @property int $UserID
 * @property string|null $UploadedAt
 * @property string|null $UpdatedAt
 * @property int|null $ViewCount
 * @property int|null $LikeCount
 * @property string|null $PictureURL
 *
 * @property Users $user
 * @property VideoComments[] $videoComments
 * @property VideoLikes[] $videoLikes
 */
class Videos extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'Videos';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['Title', 'URL', 'UserID'], 'required'],
            [['UserID', 'ViewCount', 'LikeCount'], 'integer'],
            [['UploadedAt', 'UpdatedAt'], 'safe'],
            [['Title', 'URL', 'PictureURL'], 'string', 'max' => 255],
            [['UserID'], 'exist', 'skipOnError' => true, 'targetClass' => Users::class, 'targetAttribute' => ['UserID' => 'UserID']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'VideoID' => 'Video ID',
            'Title' => 'Title',
            'URL' => 'Url',
            'UserID' => 'User ID',
            'UploadedAt' => 'Uploaded At',
            'UpdatedAt' => 'Updated At',
            'ViewCount' => 'View Count',
            'LikeCount' => 'Like Count',
            'PictureURL' => 'Picture Url',
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
     * Gets query for [[VideoComments]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVideoComments()
    {
        return $this->hasMany(VideoComments::class, ['VideoID' => 'VideoID']);
    }

    /**
     * Gets query for [[VideoLikes]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVideoLikes()
    {
        return $this->hasMany(VideoLikes::class, ['VideoID' => 'VideoID']);
    }
}
