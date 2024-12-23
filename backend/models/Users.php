<?php

/**
 * Coding by JiangYu 2210705
 * 使用 Gii 生成了 Users 的 CRUD
 */

namespace app\models;

use Yii;

/**
 * This is the model class for table "Users".
 *
 * @property int $UserID
 * @property string $Username
 * @property string $Password
 * @property string|null $Role
 * @property string $Avatar
 * @property string|null $CreatedAt
 *
 * @property ArticleComments[] $articleComments
 * @property ArticleLikes[] $articleLikes
 * @property Articles[] $articles
 * @property Conversations[] $conversations
 * @property VideoComments[] $videoComments
 * @property VideoLikes[] $videoLikes
 * @property Videos[] $videos
 */
class Users extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'Users';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['Username', 'Password'], 'required'],
            [['Role'], 'string'],
            [['CreatedAt'], 'safe'],
            [['Username', 'Password', 'Avatar'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'UserID' => 'User ID',
            'Username' => 'Username',
            'Password' => 'Password',
            'Role' => 'Role',
            'Avatar' => 'Avatar',
            'CreatedAt' => 'Created At',
        ];
    }

    /**
     * Gets query for [[ArticleComments]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getArticleComments()
    {
        return $this->hasMany(ArticleComments::class, ['UserID' => 'UserID']);
    }

    /**
     * Gets query for [[ArticleLikes]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getArticleLikes()
    {
        return $this->hasMany(ArticleLikes::class, ['UserID' => 'UserID']);
    }

    /**
     * Gets query for [[Articles]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getArticles()
    {
        return $this->hasMany(Articles::class, ['AuthorID' => 'UserID']);
    }

    /**
     * Gets query for [[Conversations]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getConversations()
    {
        return $this->hasMany(Conversations::class, ['UserID' => 'UserID']);
    }

    /**
     * Gets query for [[VideoComments]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVideoComments()
    {
        return $this->hasMany(VideoComments::class, ['UserID' => 'UserID']);
    }

    /**
     * Gets query for [[VideoLikes]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVideoLikes()
    {
        return $this->hasMany(VideoLikes::class, ['UserID' => 'UserID']);
    }

    /**
     * Gets query for [[Videos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVideos()
    {
        return $this->hasMany(Videos::class, ['UserID' => 'UserID']);
    }
}
