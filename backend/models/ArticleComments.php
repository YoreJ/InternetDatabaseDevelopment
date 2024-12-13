<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ArticleComments".
 *
 * @property int $CommentID
 * @property int $UserID
 * @property int $ArticleID
 * @property string $Content
 * @property string|null $CommentedAt
 *
 * @property Articles $article
 * @property Users $user
 */
class ArticleComments extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ArticleComments';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['UserID', 'ArticleID', 'Content'], 'required'],
            [['UserID', 'ArticleID'], 'integer'],
            [['Content'], 'string'],
            [['CommentedAt'], 'safe'],
            [['UserID'], 'exist', 'skipOnError' => true, 'targetClass' => Users::class, 'targetAttribute' => ['UserID' => 'UserID']],
            [['ArticleID'], 'exist', 'skipOnError' => true, 'targetClass' => Articles::class, 'targetAttribute' => ['ArticleID' => 'ArticleID']],
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
            'ArticleID' => 'Article ID',
            'Content' => 'Content',
            'CommentedAt' => 'Commented At',
        ];
    }

    /**
     * Gets query for [[Article]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getArticle()
    {
        return $this->hasOne(Articles::class, ['ArticleID' => 'ArticleID']);
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
