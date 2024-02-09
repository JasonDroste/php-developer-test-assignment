<?php

namespace app\models;
use app\models\traits\ObjectNameTrait;
use app\models\search\HistorySearch;
use app\widgets\Export\Export;
use yii\base\Widget;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

use Yii;

/**
 * This is the model class for table "history".
 *
 * @property int $id
 * @property string $ins_ts
 * @property int|null $customer_id
 * @property int|null $user_id
 * @property string $event
 * @property string|null $object
 * @property int|null $object_id
 * @property string|null $message
 * @property string|null $detail
 *
 * @property Customer $customer
 * @property User $user
 */
class History extends \yii\db\ActiveRecord
{

   use ObjectNameTrait;

    const EVENT_CREATED_TASK = 'created_task';
    const EVENT_UPDATED_TASK = 'updated_task';
    const EVENT_COMPLETED_TASK = 'completed_task';

    const EVENT_INCOMING_SMS = 'incoming_sms';
    const EVENT_OUTGOING_SMS = 'outgoing_sms';

    const EVENT_INCOMING_CALL = 'incoming_call';
    const EVENT_OUTGOING_CALL = 'outgoing_call';

    const EVENT_INCOMING_FAX = 'incoming_fax';
    const EVENT_OUTGOING_FAX = 'outgoing_fax';

    const EVENT_CUSTOMER_CHANGE_TYPE = 'customer_change_type';
    const EVENT_CUSTOMER_CHANGE_QUALITY = 'customer_change_quality';
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'history';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ins_ts'], 'safe'],
            [['customer_id', 'user_id', 'object_id'], 'integer'],
            [['event'], 'required'],
            [['message', 'detail'], 'string'],
            [['event', 'object'], 'string', 'max' => 255],
            [['customer_id'], 'exist', 'skipOnError' => true, 'targetClass' => Customer::class, 'targetAttribute' => ['customer_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'ins_ts' => 'Ins Ts',
            'customer_id' => 'Customer ID',
            'user_id' => 'User ID',
            'event' => 'Event',
            'object' => 'Object',
            'object_id' => 'Object ID',
            'message' => 'Message',
            'detail' => 'Detail',
        ];
    }

    /**
     * Gets query for [[Customer]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCustomer()
    {
        return $this->hasOne(Customer::class, ['id' => 'customer_id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

      /**
     * @return array
     */
    public static function getEventTexts()
    {
        return [
            self::EVENT_CREATED_TASK => Yii::t('app', 'Task created'),
            self::EVENT_UPDATED_TASK => Yii::t('app', 'Task updated'),
            self::EVENT_COMPLETED_TASK => Yii::t('app', 'Task completed'),

            self::EVENT_INCOMING_SMS => Yii::t('app', 'Incoming message'),
            self::EVENT_OUTGOING_SMS => Yii::t('app', 'Outgoing message'),

            self::EVENT_CUSTOMER_CHANGE_TYPE => Yii::t('app', 'Type changed'),
            self::EVENT_CUSTOMER_CHANGE_QUALITY => Yii::t('app', 'Property changed'),

            self::EVENT_OUTGOING_CALL => Yii::t('app', 'Outgoing call'),
            self::EVENT_INCOMING_CALL => Yii::t('app', 'Incoming call'),

            self::EVENT_INCOMING_FAX => Yii::t('app', 'Incoming fax'),
            self::EVENT_OUTGOING_FAX => Yii::t('app', 'Outgoing fax'),
        ];
    }

    /**
     * @param $event
     * @return mixed
     */
    public static function getEventTextByEvent($event)
    {
        return static::getEventTexts()[$event] ?? $event;
    }

    /**
     * @return mixed|string
     */
    public function getEventText()
    {
        return static::getEventTextByEvent($this->event);
    }


    /**
     * @param $attribute
     * @return null
     */
    public function getDetailChangedAttribute($attribute)
    {
        $detail = json_decode($this->detail);
        return isset($detail->changedAttributes->{$attribute}) ? $detail->changedAttributes->{$attribute} : null;
    }

    /**
     * @param $attribute
     * @return null
     */
    public function getDetailOldValue($attribute)
    {
        $detail = $this->getDetailChangedAttribute($attribute);
        return isset($detail->old) ? $detail->old : null;
    }

    /**
     * @param $attribute
     * @return null
     */
    public function getDetailNewValue($attribute)
    {
        $detail = $this->getDetailChangedAttribute($attribute);
        return isset($detail->new) ? $detail->new : null;
    }

    /**
     * @param $attribute
     * @return null
     */
    public function getDetailData($attribute)
    {
        $detail = json_decode($this->detail);
        return isset($detail->data->{$attribute}) ? $detail->data->{$attribute} : null;
    }


    public function run()
    {
        $model = new HistorySearch();

        return $this->render('main', [
            'model' => $model,
            'linkExport' => $this->getLinkExport(),
            'dataProvider' => $model->search(Yii::$app->request->queryParams)
        ]);
    }

    /**
     * @return string
     */
    private function getLinkExport()
    {
        $params = Yii::$app->getRequest()->getQueryParams();
        $params = ArrayHelper::merge([
            'exportType' => Export::FORMAT_CSV
        ], $params);
        $params[0] = 'site/export';

        return Url::to($params);
    }
}
