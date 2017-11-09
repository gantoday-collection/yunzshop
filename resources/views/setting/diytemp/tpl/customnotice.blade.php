<div class="row">
    <div class="col-sm-4" >
        <div class="panel panel-default">
            <div class="panel-heading">
                <select class="form-control" onclick="$('.tm').hide();$('.tm-' + $(this).val()).show()">
                    <option value="">选择模板变量类型</option>
                    <option value="order">订单类</option>
                    <option value="upgrade">升级类</option>
                    <option value="rw">充值提现类</option>
                    {if cv('commission')}
                    <option value="commission">分销类</option>
                    {/if}
                    {if cv('globonus')}
                    <option value="globonus">股东类</option>
                    {/if}
                    {if cv('merch')}
                    <option value="merch">多商户类</option>
                    {/if}
                    {if cv('pstore')}
                    <option value="pstore">门店类</option>
                    {/if}
                    {if cv('bargain')}
                    <option value="bargain">砍价类</option>
                    {/if}
                    {if cv('exchange')}
                    <option value="exchange">兑换中心</option>
                    {/if}
                    {if cv('cashier')}
                    <option value="cashier">收银台类</option>
                    {/if}
                    {if cv('lottery')}
                    <option value="lottery">游戏中心类</option>
                    {/if}
                </select>
            </div>
            <div class="panel-body tm tm-upgrade" style="display:none">
                <a href='JavaScript:' class="btn btn-default  btn-sm ">商城名称</a>
                <a href='JavaScript:' class="btn btn-default btn-sm">粉丝昵称</a>
                <a href='JavaScript:' class="btn btn-default btn-sm">旧等级</a>
                <a href='JavaScript:' class="btn btn-default btn-sm">新等级</a>
            </div>
            <div class="panel-heading tm tm-rw" style="display:none">充值</div>

            <div class="panel-body tm tm-rw" style="display:none">
                <a href='JavaScript:' class="btn btn-default  btn-sm">支付方式</a>
                <a href='JavaScript:' class="btn btn-default btn-sm">充值金额</a>
                <a href='JavaScript:' class="btn btn-default btn-sm">充值时间</a>
                <a href='JavaScript:' class="btn btn-default btn-sm">赠送金额</a>
                <a href='JavaScript:' class="btn btn-default btn-sm">实际到账</a>
            </div>

            <div class="panel-heading tm tm-rw" style="display:none">充值退款</div>
            <div class="panel-body tm tm-rw" style="display:none">
                <a href='JavaScript:' class="btn btn-default  btn-sm">支付方式</a>
                <a href='JavaScript:' class="btn btn-default btn-sm">充值金额</a>
                <a href='JavaScript:' class="btn btn-default btn-sm">充值时间</a>
                <a href='JavaScript:' class="btn btn-default btn-sm">赠送金额</a>
                <a href='JavaScript:' class="btn btn-default btn-sm">实际到账</a>
                <a href='JavaScript:' class="btn btn-default btn-sm">退款金额</a>
            </div>

            <div class="panel-heading tm tm-rw" style="display:none">提现</div>
            <div class="panel-body tm tm-rw" style="display:none">
                <a href='JavaScript:' class="btn btn-default  btn-sm">提现金额</a>
                <a href='JavaScript:' class="btn btn-default btn-sm">提现时间</a>
            </div>
            <div class="panel-heading tm tm-order" style="display:none">
                订单信息
            </div>
            <div class="panel-body tm tm-order" style="display:none">
                <a href='JavaScript:' class="btn btn-default  btn-sm">商城名称</a>
                <a href='JavaScript:' class="btn btn-default btn-sm">粉丝昵称</a>
                <a href='JavaScript:' class="btn btn-default btn-sm">订单号</a>
                <a href='JavaScript:' class="btn btn-default btn-sm">订单金额</a>
                <a href='JavaScript:' class="btn btn-default btn-sm">运费</a>
                <a href='JavaScript:' class="btn btn-default btn-sm">商品详情</a>
                <a href='JavaScript:' class="btn btn-default btn-sm">单品详情</a>(单品商家下单通知变量)
                <a href='JavaScript:' class="btn btn-default btn-sm">快递公司</a>
                <a href='JavaScript:' class="btn btn-default btn-sm">快递单号</a>
                <a href='JavaScript:' class="btn btn-default btn-sm">购买者姓名</a>
                <a href='JavaScript:' class="btn btn-default btn-sm">购买者电话</a>
                <a href='JavaScript:' class="btn btn-default btn-sm">收货地址</a>
                <a href='JavaScript:' class="btn btn-default btn-sm">下单时间</a>
                <a href='JavaScript:' class="btn btn-default btn-sm">支付时间</a>
                <a href='JavaScript:' class="btn btn-default btn-sm">发货时间</a>
                <a href='JavaScript:' class="btn btn-default btn-sm">收货时间</a>
                <a href='JavaScript:' class="btn btn-default btn-sm">门店</a>
                <a href='JavaScript:' class="btn btn-default btn-sm">门店地址</a>
                <a href='JavaScript:' class="btn btn-default btn-sm">门店联系人</a>
                <a href='JavaScript:' class="btn btn-default btn-sm">门店营业时间</a>
                <a href='JavaScript:' class="btn btn-default btn-sm">虚拟物品自动发货内容</a>
                <a href='JavaScript:' class="btn btn-default btn-sm">虚拟卡密自动发货内容</a>
                <a href='JavaScript:' class="btn btn-default btn-sm">自提码</a>
                <a href='JavaScript:' class="btn btn-default btn-sm">备注信息</a>

            </div>
            <div class="panel-heading tm tm-order" style="display:none">
                售后相关
            </div>
            <div class="panel-body tm tm-order" style="display:none">
                <a href='JavaScript:' class="btn btn-default btn-sm">售后类型</a>
                <a href='JavaScript:' class="btn btn-default btn-sm">申请金额</a>
                <a href='JavaScript:' class="btn btn-default btn-sm">退款金额</a>

                <a href='JavaScript:' class="btn btn-default btn-sm">退货地址</a>
                <a href='JavaScript:' class="btn btn-default btn-sm">换货快递公司</a>
                <a href='JavaScript:' class="btn btn-default btn-sm">换货快递单号</a>

            </div>
            <div class="panel-heading tm tm-order" style="display:none">
                订单状态更新
            </div>
            <div class="panel-body tm tm-order" style="display:none">
                <a href='JavaScript:' class="btn btn-default btn-sm">粉丝昵称</a>
                <a href='JavaScript:' class="btn btn-default btn-sm">订单编号</a>
                <a href='JavaScript:' class="btn btn-default btn-sm">原收货地址</a>
                <a href='JavaScript:' class="btn btn-default btn-sm">新收货地址</a>
                <a href='JavaScript:' class="btn btn-default btn-sm">订单原价格</a>
                <a href='JavaScript:' class="btn btn-default btn-sm">订单新价格</a>
                <a href='JavaScript:' class="btn btn-default btn-sm">修改时间</a>

            </div>
            <div class="panel-heading tm tm-commission" style="display:none">成为下级或分销商</div>
            <div class="panel-body tm tm-commission" style="display:none">
                <a href='JavaScript:' class="btn btn-default btn-sm">昵称</a>
                <a href='JavaScript:' class="btn btn-default btn-sm">时间</a>
            </div>
            <div class="panel-heading tm tm-commission" style="display:none">新增下线通知</div>
            <div class="panel-body tm tm-commission" style="display:none">
                <a href='JavaScript:' class="btn btn-default btn-sm">下线层级</a>
                <a href='JavaScript:' class="btn btn-default btn-sm">下级昵称</a>
                <a href='JavaScript:' class="btn btn-default btn-sm">时间</a>
            </div>
            <div class="panel-heading tm tm-commission" style="display:none">下级付款类</div>
            <div class="panel-body tm tm-commission" style="display:none">
                <a href='JavaScript:' class="btn btn-default btn-sm">下级昵称</a>
                <a href='JavaScript:' class="btn btn-default btn-sm">订单编号</a>
                <a href='JavaScript:' class="btn btn-default btn-sm">订单金额</a>
                <a href='JavaScript:' class="btn btn-default btn-sm">商品详情</a>
                <a href='JavaScript:' class="btn btn-default btn-sm">佣金金额</a>
                <a href='JavaScript:' class="btn btn-default btn-sm">时间</a>
                <a href='JavaScript:' class="btn btn-default btn-sm">下线层级</a>
            </div>
            <div class="panel-heading tm tm-commission" style="display:none">提现申请和佣金打款类</div>
            <div class="panel-body tm tm-commission" style="display:none">
                <a href='JavaScript:' class="btn btn-default btn-sm">昵称</a>
                <a href='JavaScript:' class="btn btn-default btn-sm">时间</a>
                <a href='JavaScript:' class="btn btn-default btn-sm">金额</a>
                <a href='JavaScript:' class="btn btn-default btn-sm">提现方式</a>
            </div>
            <div class="panel-heading tm tm-commission" style="display:none">分销商等级升级通知</div>
            <div class="panel-body tm tm-commission" style="display:none">
                <a href='JavaScript:' class="btn btn-default btn-sm">昵称</a>
                <a href='JavaScript:' class="btn btn-default btn-sm">时间</a>
                <a href='JavaScript:' class="btn btn-default btn-sm">旧等级</a>
                <a href='JavaScript:' class="btn btn-default btn-sm">旧一级分销比例</a>
                <a href='JavaScript:' class="btn btn-default btn-sm">旧二级分销比例</a>
                <a href='JavaScript:' class="btn btn-default btn-sm">旧三级分销比例</a>
                <a href='JavaScript:' class="btn btn-default btn-sm">新等级</a>
                <a href='JavaScript:' class="btn btn-default btn-sm">新一级分销比例</a>
                <a href='JavaScript:' class="btn btn-default btn-sm">新二级分销比例</a>
                <a href='JavaScript:' class="btn btn-default btn-sm">新三级分销比例</a>
            </div>



            <div class="panel-heading tm tm-globonus" style="display:none">成为股东</div>
            <div class="panel-body tm tm-globonus" style="display:none">
                <a href='JavaScript:' class="btn btn-default btn-sm">昵称</a>
                <a href='JavaScript:' class="btn btn-default btn-sm">时间</a>
            </div>

            <div class="panel-heading tm tm-globonus" style="display:none">股东等级升级通知</div>
            <div class="panel-body tm tm-globonus" style="display:none">
                <a href='JavaScript:' class="btn btn-default btn-sm">昵称</a>
                <a href='JavaScript:' class="btn btn-default btn-sm">时间</a>
                <a href='JavaScript:' class="btn btn-default btn-sm">旧等级</a>
                <a href='JavaScript:' class="btn btn-default btn-sm">旧分红例</a>
                <a href='JavaScript:' class="btn btn-default btn-sm">新等级</a>
                <a href='JavaScript:' class="btn btn-default btn-sm">新分红比例</a>
            </div>
            <div class="panel-heading tm tm-globonus" style="display:none">分红发放通知</div>
            <div class="panel-body tm tm-globonus" style="display:none">
                <a href='JavaScript:' class="btn btn-default btn-sm">昵称</a>
                <a href='JavaScript:' class="btn btn-default btn-sm">时间</a>
                <a href='JavaScript:' class="btn btn-default btn-sm">打款方式</a>
                <a href='JavaScript:' class="btn btn-default btn-sm">金额</a>
            </div>

            <div class="panel-heading tm tm-merch" style="display:none">入驻申请</div>
            <div class="panel-body tm tm-merch" style="display:none">
                <a href='JavaScript:' class="btn btn-default btn-sm">商户名称</a>
                <a href='JavaScript:' class="btn btn-default btn-sm">主营项目</a>
                <a href='JavaScript:' class="btn btn-default btn-sm">联系人</a>
                <a href='JavaScript:' class="btn btn-default btn-sm">手机号</a>
                <a href='JavaScript:' class="btn btn-default btn-sm">申请时间</a>
            </div>
            <div class="panel-heading tm tm-merch" style="display:none">入驻申请(用户)</div>
            <div class="panel-body tm tm-merch" style="display:none">
                <a href='JavaScript:' class="btn btn-default btn-sm">驳回原因</a>
            </div>

            <div class="panel-heading tm tm-bargain" style="display:none">砍价类</div>
            <div class="panel-body tm tm-bargain" style="display:none">
                <a href='JavaScript:' class="btn btn-default btn-sm">砍价金额</a>
                <a href='JavaScript:' class="btn btn-default btn-sm">当前金额</a>
                <a href='JavaScript:' class="btn btn-default btn-sm">砍价时间</a>
                <a href='JavaScript:' class="btn btn-default btn-sm">砍价人昵称</a>
                <a href='JavaScript:' class="btn btn-default btn-sm">砍掉或增加</a>
                <a href='JavaScript:' class="btn btn-default btn-sm">成功或失败</a>
            </div>
            <div class="panel-heading tm tm-exchange" style="display:none">兑换中心</div>
            <div class="panel-body tm tm-exchange" style="display:none">
                <a href='JavaScript:' class="btn btn-default btn-sm">兑换时间</a>
                <a href='JavaScript:' class="btn btn-default btn-sm">兑换面值</a>
                <a href='JavaScript:' class="btn btn-default btn-sm">粉丝昵称</a>
                <a href='JavaScript:' class="btn btn-default btn-sm">商城名称</a>
            </div>
            <div class="panel-heading tm tm-pstore" style="display:none">申请通知</div>
            <div class="panel-body tm tm-pstore" style="display:none">
                <a href='JavaScript:' class="btn btn-default btn-sm">联系人</a>
                <a href='JavaScript:' class="btn btn-default btn-sm">联系电话</a>
                <a href='JavaScript:' class="btn btn-default btn-sm">申请时间</a>
            </div>

            <div class="panel-heading tm tm-pstore" style="display:none">申请结算通知</div>
            <div class="panel-body tm tm-pstore" style="display:none">
                <a href='JavaScript:' class="btn btn-default btn-sm">联系人</a>
                <a href='JavaScript:' class="btn btn-default btn-sm">联系电话</a>
                <a href='JavaScript:' class="btn btn-default btn-sm">申请时间</a>
                <a href='JavaScript:' class="btn btn-default btn-sm">申请金额</a>
            </div>

            <div class="panel-heading tm tm-pstore" style="display:none">审核通知</div>
            <div class="panel-body tm tm-pstore" style="display:none">
                <a href='JavaScript:' class="btn btn-default btn-sm">联系人</a>
                <a href='JavaScript:' class="btn btn-default btn-sm">联系电话</a>
                <a href='JavaScript:' class="btn btn-default btn-sm">审核状态</a>
                <a href='JavaScript:' class="btn btn-default btn-sm">审核时间</a>
                <a href='JavaScript:' class="btn btn-default btn-sm">驳回原因</a>
            </div>

            <div class="panel-heading tm tm-pstore" style="display:none">打款通知</div>
            <div class="panel-body tm tm-pstore" style="display:none">
                <a href='JavaScript:' class="btn btn-default btn-sm">联系人</a>
                <a href='JavaScript:' class="btn btn-default btn-sm">联系电话</a>
                <a href='JavaScript:' class="btn btn-default btn-sm">申请金额</a>
                <a href='JavaScript:' class="btn btn-default btn-sm">打款时间</a>
                <a href='JavaScript:' class="btn btn-default btn-sm">申请金额</a>
                <a href='JavaScript:' class="btn btn-default btn-sm">打款金额</a>

            </div>

            <div class="panel-heading tm tm-pstore" style="display:none">门店支付通知</div>
            <div class="panel-body tm tm-pstore" style="display:none">
                <a href='JavaScript:' class="btn btn-default btn-sm">付款人</a>
                <a href='JavaScript:' class="btn btn-default btn-sm">付款金额</a>
                <a href='JavaScript:' class="btn btn-default btn-sm">付款时间</a>
                <a href='JavaScript:' class="btn btn-default btn-sm">门店名称</a>

            </div>

            <!--收银台模板消息 begin-->
            <div class="panel-heading tm tm-cashier" style="display:none">申请结算通知</div>
            <div class="panel-body tm tm-cashier" style="display:none">
                <a href='JavaScript:' class="btn btn-default btn-sm">联系人</a>
                <a href='JavaScript:' class="btn btn-default btn-sm">联系电话</a>
                <a href='JavaScript:' class="btn btn-default btn-sm">申请时间</a>
                <a href='JavaScript:' class="btn btn-default btn-sm">申请金额</a>
            </div>

            <div class="panel-heading tm tm-cashier" style="display:none">打款通知</div>
            <div class="panel-body tm tm-cashier" style="display:none">
                <a href='JavaScript:' class="btn btn-default btn-sm">联系人</a>
                <a href='JavaScript:' class="btn btn-default btn-sm">联系电话</a>
                <a href='JavaScript:' class="btn btn-default btn-sm">申请金额</a>
                <a href='JavaScript:' class="btn btn-default btn-sm">打款时间</a>
                <a href='JavaScript:' class="btn btn-default btn-sm">申请金额</a>
                <a href='JavaScript:' class="btn btn-default btn-sm">打款金额</a>
            </div>

            <div class="panel-heading tm tm-cashier" style="display:none">支付通知</div>
            <div class="panel-body tm tm-cashier" style="display:none">
                <a href='JavaScript:' class="btn btn-default btn-sm">付款金额</a>
                <a href='JavaScript:' class="btn btn-default btn-sm">余额抵扣</a>
                <a href='JavaScript:' class="btn btn-default btn-sm">付款时间</a>
                <a href='JavaScript:' class="btn btn-default btn-sm">收银台名称</a>

            </div>

            <div class="panel-heading tm tm-lottery" style="display:none">获得抽奖通知</div>
            <div class="panel-body tm tm-lottery" style="display:none">
                <a href='JavaScript:' class="btn btn-default btn-sm">活动名称</a>
            </div>

            <div class="panel-body">
                点击变量后会自动插入选择的文本框的焦点位置，在发送给粉丝时系统会自动替换对应变量值
                <div class="text text-danger">
                    注意：以上模板消息变量只适用于系统类通知，会员群发工具不适用
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    require(['jquery','util'], function($, util){
        $(function(){
            $('#btn').click(function(){
                util.emojiBrowser(function(emoji){
                    var text = '[U+'+emoji[0].text+']';
                    var content = $('#send_desc').val()+text;
                    $('#send_desc').val(content);

                });
            });
        });

        $(function(){
            util.emotion($('#emotion'), $('#send_desc'));
        });

        $(function () {
            $('#link').click(function () {
                var link='<a href=\\"您要插入的链接\\">链接文字</a>';
                var content = $('#send_desc').val()+link;
                $('#send_desc').val(content);
            });
        });
    });
</script>
