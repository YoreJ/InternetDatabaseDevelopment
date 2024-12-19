<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "Students".
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $student_id
 * @property string|null $role
 * @property string|null $file_path
 */
class Students extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'Students';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'role'], 'string', 'max' => 100],
            [['student_id'], 'string', 'max' => 20],
            [['file_path'], 'string', 'max' => 255],
            [['student_id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'student_id' => 'Student ID',
            'role' => 'Role',
            'file_path' => 'File Path',
        ];
    }
}
