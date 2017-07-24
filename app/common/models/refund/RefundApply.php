<?php
/**
 * Created by PhpStorm.
 * Author: 芸众商城 www.yunzshop.com
 * Date: 2017/4/12
 * Time: 下午1:38
 */

namespace app\common\models\refund;

use app\common\models\BaseModel;
use app\common\models\Order;
use Illuminate\Database\Eloquent\Builder;

class RefundApply extends BaseModel
{
    protected $table = 'yz_order_refund';
    protected $hidden = ['updated_at', 'created_at', 'uniacid', 'uid', 'order_id'];
    protected $fillable = [];
    protected $guarded = ['id'];

    protected $appends = ['refund_type_name', 'status_name', 'button_models', 'is_refunded', 'is_refunding', 'is_refund_fail'];
    protected $attributes = [
        'images' => '[]',
        'refund_proof_imgs' => '[]',
        'content' => '',
        'reply' => '',
        'remark' => '',
        'refund_address' => '',
        'reject_reason' => '',
    ];
    protected $casts = [
        'images' => 'json',
        'refund_proof_imgs' => 'json'
    ];
    const REFUND_TYPE_REFUND_MONEY = 0;
    const REFUND_TYPE_RETURN_GOODS = 1;
    const REFUND_TYPE_EXCHANGE_GOODS = 2;
    const CLOSE = '-3';//关闭
    const CANCEL = '-2';//用户取消
    const REJECT = '-1';//驳回
    const WAIT_CHECK = '0';//待审核
    const WAIT_RETURN_GOODS = '1';//待退货
    const WAIT_RECEIVE_RETURN_GOODS = '2';//待收货
    const WAIT_RESEND_GOODS = '3';//重新发货
    const WAIT_RECEIVE_RESEND_GOODS = '4';//重新收货
    const WAIT_REFUND = '5';//待打款
    const COMPLETE = '6';//已完成
    const CONSENSUS = '7';//手动退款

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        // todo 转移到前端
        if (!isset($this->uniacid)) {
            $this->uniacid = \YunShop::app()->uniacid;
        }
        if (!isset($this->uid)) {
            $this->uid = \YunShop::app()->getMemberId();
        }
    }

    public function returnExpress()
    {
        return $this->hasOne(ReturnExpress::class, 'refund_id', 'id');
    }

    public function resendExpress()
    {
        return $this->hasOne(ResendExpress::class, 'refund_id', 'id');
    }

    /**
     * 前端获取退款按钮 todo 转移到前端的model
     * @return array
     */
    public function getButtonModelsAttribute()
    {
        $result = [];
        if ($this->status == self::WAIT_CHECK) {
            $result[] = [
                'name' => '修改申请',
                'api' => 'refund.edit',
                'value' => 1
            ];
            $result[] = [
                'name' => '取消申请',
                'api' => 'refund.cancel',
                'value' => 4
            ];
        }
        if ($this->status == self::WAIT_RETURN_GOODS) {
            $result[] = [
                'name' => '填写快递',
                'api' => 'refund.send',
                'value' => 2
            ];
        }
        if ($this->status == self::WAIT_RECEIVE_RESEND_GOODS) {
            $result[] = [
                'name' => '确认收货',
                'api' => 'refund.receive_resend_goods',
                'value' => 3
            ];
        }
        return $result;
    }

    public function getDates()
    {
        return ['create_time', 'refund_time', 'operate_time', 'send_time', 'return_time', 'end_time', 'cancel_pay_time', 'cancel_send_time'] + parent::getDates();
    }


    public function getRefundTypeNameAttribute()
    {
        $mapping = [
            self::REFUND_TYPE_REFUND_MONEY => '退款',
            self::REFUND_TYPE_RETURN_GOODS => '退货',
            self::REFUND_TYPE_EXCHANGE_GOODS => '换货',

        ];
        return $mapping[$this->refund_type];
    }

    protected function getStatusNameMapping()
    {
        return [
            self::CANCEL => '用户取消',
            self::REJECT => '已驳回',
            self::WAIT_CHECK => '待审核',
            self::WAIT_RETURN_GOODS => '待退货',
            self::WAIT_RECEIVE_RETURN_GOODS => '商家待收货',
            self::WAIT_RESEND_GOODS => '待商家重新发货',
            self::WAIT_RECEIVE_RESEND_GOODS => '待买家收货',
            self::WAIT_REFUND => '待退款',
            self::COMPLETE => '已退款',
            self::CONSENSUS => '已手动退款',
        ];

    }

    public function scopeRefunding($query)
    {
        return $query->where('status', '>=', self::WAIT_CHECK)->where('status', '<', self::COMPLETE);
    }

    public function scopeRefunded($query)
    {
        return $query->where('status', '>=', self::COMPLETE);
    }

    public function scopeRefundMoney($query)
    {
        return $query->where('refund_type', self::REFUND_TYPE_REFUND_MONEY);
    }

    public function scopeReturnGoods($query)
    {
        return $query->where('refund_type', self::REFUND_TYPE_RETURN_GOODS);
    }

    public function scopeExchangeGoods($query)
    {
        return $query->where('refund_type', self::REFUND_TYPE_EXCHANGE_GOODS);
    }

    public function getStatusNameAttribute()
    {

        return $this->getStatusNameMapping()[$this->status];
    }


    public function getIsRefundedAttribute()
    {
        return $this->isRefunded();
    }

    public function getIsRefundingAttribute()
    {
        return $this->isRefunding();
    }

    public function getIsRefundFailAttribute()
    {
        return $this->isRefundFail();
    }

    /**
     * 退款失败
     * @return bool
     */
    public function isRefundFail()
    {
        if ($this->status < self::WAIT_CHECK) {
            return true;
        }
        return false;
    }

    /**
     * 已退款
     * @return bool
     */
    public function isRefunded()
    {
        if ($this->status >= self::COMPLETE) {
            return true;
        }
        return false;
    }

    public function order()
    {
        return $this->belongsTo(\app\backend\modules\order\models\Order::class, 'order_id', 'id');
    }
    /**
     * 退款中
     * @return bool
     */
    public function isRefunding()
    {
        if ($this->status < self::WAIT_CHECK) {
            return false;
        }
        if ($this->status >= self::COMPLETE) {
            return false;
        }
        return true;
    }

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(function (Builder $builder) {
            $builder->uniacid();
        });
    }

}