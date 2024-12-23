<?php

/**
 * Coding by XuHaiying 2212180
 * 使用 Gii 生成了 WebsiteVisits 的 CRUD
 */
/**
 * Designing by WangYuheng 2213040
 * 参与数据库设计
 */
namespace app\models;

use Yii;

/**
 * This is the model class for table "WebsiteVisits".
 *
 * @property int $ID
 * @property int|null $VisitCount
 */
class WebsiteVisits extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'WebsiteVisits';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['VisitCount'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'ID' => 'ID',
            'VisitCount' => 'Visit Count',
        ];
    }
}
