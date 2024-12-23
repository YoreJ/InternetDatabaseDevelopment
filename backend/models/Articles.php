<?php

/**
 * Coding by WangYuheng 2213040
 * 使用 Gii 生成了 Articles 的 CRUD
 * 参与数据库设计
 */

namespace app\models;

use Yii;

/**
 * This is the model class for table "Articles".
 *
 * @property int $ArticleID
 * @property string $Title
 * @property string $Content
 * @property int $AuthorID
 * @property string|null $PublishedAt
 * @property string|null $UpdatedAt
 * @property int|null $ViewCount
 * @property int|null $LikeCount
 *
 * @property ArticleComments[] $articleComments
 * @property ArticleLikes[] $articleLikes
 * @property Users $author
 */
class Articles extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'Articles';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['Title', 'Content', 'AuthorID'], 'required'],
            [['Content'], 'string'],
            [['AuthorID', 'ViewCount', 'LikeCount'], 'integer'],
            [['PublishedAt', 'UpdatedAt'], 'safe'],
            [['Title'], 'string', 'max' => 255],
            [['AuthorID'], 'exist', 'skipOnError' => true, 'targetClass' => Users::class, 'targetAttribute' => ['AuthorID' => 'UserID']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'ArticleID' => 'Article ID',
            'Title' => 'Title',
            'Content' => 'Content',
            'AuthorID' => 'Author ID',
            'PublishedAt' => 'Published At',
            'UpdatedAt' => 'Updated At',
            'ViewCount' => 'View Count',
            'LikeCount' => 'Like Count',
        ];
    }

    /**
     * Gets query for [[ArticleComments]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getArticleComments()
    {
        return $this->hasMany(ArticleComments::class, ['ArticleID' => 'ArticleID']);
    }

    /**
     * Gets query for [[ArticleLikes]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getArticleLikes()
    {
        return $this->hasMany(ArticleLikes::class, ['ArticleID' => 'ArticleID']);
    }

    /**
     * Gets query for [[Author]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAuthor()
    {
        return $this->hasOne(Users::class, ['UserID' => 'AuthorID']);
    }
}
