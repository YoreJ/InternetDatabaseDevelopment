<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "WebsiteVisits".
 *
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
            'VisitCount' => 'Visit Count',
        ];
    }
}
