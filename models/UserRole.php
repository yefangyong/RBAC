<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user_role".
 *
 * @property int $id
 * @property int $uid
 * @property int $role_id
 * @property int $create_time
 * @property int $update_time
 */
class UserRole extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_role';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid', 'role_id', 'create_time', 'update_time'], 'required'],
            [['uid', 'role_id', 'create_time', 'update_time'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'uid' => 'Uid',
            'role_id' => 'Role ID',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
        ];
    }
}
