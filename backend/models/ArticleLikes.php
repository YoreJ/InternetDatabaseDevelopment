<?php

/**
 * Coding by WangYuheng 2213040
 * 使用 Gii 生成了 ArticleLikes 的 CRUD
 * 参与数据库设计
 */

namespace app\models;

use Yii;

/**
 * This is the model class for table "ArticleLikes".
 *
 * @property int $LikeID
 * @property int $UserID
 * @property int $ArticleID
 * @property string|null $LikedAt
 *
 * @property Articles $article
 * @property Users $user
 */
class ArticleLikes extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ArticleLikes';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['UserID', 'ArticleID'], 'required'],
            [['UserID', 'ArticleID'], 'integer'],
            [['LikedAt'], 'safe'],
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
            'LikeID' => 'Like ID',
            'UserID' => 'User ID',
            'ArticleID' => 'Article ID',
            'LikedAt' => 'Liked At',
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
