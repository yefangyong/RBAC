<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "role".
 *
 * @property int $id
 * @property string $name
 * @property int $status 1有效 0 无效
 * @property int $update_time
 * @property int $create_time
 */
class Role extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'role';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'update_time', 'create_time'], 'required'],
            [['update_time', 'create_time'], 'integer'],
            [['name'], 'string', 'max' => 50],
            [['status'], 'string', 'max' => 1],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'status' => 'Status',
            'update_time' => 'Update Time',
            'create_time' => 'Create Time',
        ];
    }
}
