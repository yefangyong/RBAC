<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "app_access_log".
 *
 * @property int $id
 * @property int $uid
 * @property string $target_url
 * @property string $query_params
 * @property string $ua
 * @property string $ip
 * @property int $create_time
 */
class AppAccessLog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'app_access_log';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid'], 'required'],
            [['uid', 'create_time'], 'integer'],
            [['target_url', 'query_params', 'ua', 'ip'], 'string', 'max' => 100],
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
            'target_url' => 'Target Url',
            'query_params' => 'Query Params',
            'ua' => 'Ua',
            'ip' => 'Ip',
            'create_time' => 'Create Time',
        ];
    }
}
