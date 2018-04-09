<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "access".
 *
 * @property int $id
 * @property string $title
 * @property string $urls
 * @property int $status
 * @property int $create_time
 * @property int $update_time
 */
class Access extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'access';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'urls', 'create_time', 'update_time'], 'required'],
            [['create_time', 'update_time'], 'integer'],
            [['title'], 'string', 'max' => 50],
            [['urls'], 'string', 'max' => 1000],
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
            'title' => 'Title',
            'urls' => 'Urls',
            'status' => 'Status',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
        ];
    }
}
