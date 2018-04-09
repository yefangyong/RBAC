<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "role_access".
 *
 * @property int $id
 * @property int $role_id
 * @property int $access_id
 * @property int $create_time
 */
class RoleAccess extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'role_access';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['role_id', 'access_id', 'create_time'], 'required'],
            [['role_id', 'access_id', 'create_time'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'role_id' => 'Role ID',
            'access_id' => 'Access ID',
            'create_time' => 'Create Time',
        ];
    }
}
