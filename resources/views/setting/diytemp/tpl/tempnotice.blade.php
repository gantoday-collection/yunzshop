<style>
    .form-horizontal .form-group{margin-right: -50px;}
    .col-sm-9{padding-right: 0;}
    .tm .btn { margin-bottom:5px;}

    .panel-heading{
        border-top-left-radius: 0px;
        border-top-right-radius: 0px;
    }
    .panel-default{
        color: #8c8c8c;
        border-color: #efefef;
    }
    .panel-default .panel-heading{
        background: #fdfdfd;
        border-color:#efefef;
    }
    .panel-primary{
        border-color: #efefef;
    }
    .panel-primary .panel-heading{
        background: #44abf7;
        border-color:#efefef;
        background-color: rgba(22, 161, 199, 0.82);
    }
    .panel-success .panel-heading{
        color:#fff;
        background: #54c952;
        border-color:#efefef;
    }
    .panel-info .panel-heading {
        color:#fff;
        background:#8987d7;
        border-color:#efefef;
    }
    .panel-body ~ .panel-heading {
        border-top: 1px solid #efefef;
    }
    .panel-danger .panel-heading {
        color:#fff;
        background: #eb6060;
        border-color:#efefef;
    }
    .panel-warning .panel-heading {
        color:#fff;
        background: #ffc000;
        border-color:#efefef;
    }

</style>


<div class="row">
    <div class="col-sm-8" style="padding-right: 50px;">
        <input type="hidden" name="tp_id" value="{{$list['id']}}" />

        <div class="form-group">
            <label class="col-xs-12 col-sm-3 col-md-2 control-label" >模板名称</label>
            <div class="col-sm-9 col-xs-12">
                <input type="text"  id="title" name="temp[title]"  class="form-control" value="{{$temp['title']}}" placeholder="模版名称，例：订单完成模板（自定义）" data-rule-required='true' />
            </div>
        </div>

        <div class="form-group">
            <label class="col-xs-12 col-sm-3 col-md-2 control-label" >模板消息ID</label>
            <div class="col-sm-9 ">
                @if (1)
                    <input type="text"  id="tp_template_id" name="temp[template_id]" class="form-control" value="{{$list['template_id']}}" placeholder="模版消息ID，例：P8MxRKmW7wdejmZl14-swiGmsJVrFJiWYM7zKSPXq4I" data-rule-required='true' />
                @else
                    <div class='form-control-static'>{{$list['template_id']}}</div>
                @endif
            </div>
        </div>
        <div class="form-group">
            <label class="col-xs-12 col-sm-3 col-md-2 control-label" >头部标题</label>

            <div class="col-sm-8 title" style='padding-right:0' >

                <textarea name="temp[first]" class="form-control" value="" data-rule-required='true' placeholder="@{{first.DATA}}" rows="5">{{$list['first']}}</textarea>
                <span class='help-block'>对填充模板 @{{first.DATA}} 的值 </span>
            </div>
            <div class="col-sm-1" style='padding-left:0;' >

                <input type="color" name="temp[first_color]" value="{{$list['firstcolor']}}" style="width:32px;height:32px;" />

            </div>

        </div>

        @foreach($data as $list2)
            @include('setting.diytemp.tpl.common')
        @endforeach
        <div id="type-items"></div>
        <div class="form-group">
            <label class="col-xs-12 col-sm-3 col-md-2 control-label" ></label>
            <div class="col-sm-9 col-xs-12">
                <a class="btn btn-default btn-add-type" href="javascript:;" onclick="addType();"><i class="fa fa-plus" title=""></i> 增加一条键</a>
                <span class='help-block'>
                        </span>
            </div>
        </div>

        <div class="form-group">
            <label class="col-xs-12 col-sm-3 col-md-2 control-label" >尾部描述</label>
            <div class="col-sm-8 title" style='padding-right:0' >
                <textarea name="temp[remark]" class="form-control" placeholder="@{{remark.DATA}}" rows="5" >{{$list['remark']}}</textarea>
                <span class='help-block'>填充模板 @{{remark.DATA}} 的值</span>
            </div>
            <div class="col-sm-1" style='padding-left:0' >

                <input type="color" name="temp[remark_color]" value="{{$list['remarkcolor']}}" style="width:32px;height:32px;" />

            </div>

        </div>
    </div>
    <div class="col-sm-4" style="max-width:350px;">
        <div class=""  >
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <span style="font-size: 15px">第一步：</span>添加我的模板
                </div>
                <div class="panel-body">
                    <input type="text" id="tempcode" class="form-control" placeholder="模板编号,例:TM00015" style="margin-bottom: 5px;"  value="" />
                    <a class="btn btn-default" href="javascript:;" onclick="addtempoption();"> 添加快速模板</a>
                </div>
            </div>
        </div>

        <div class="" >
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <span style="font-size: 15px">第二步：</span>选择模板
                </div>
                <div class="panel-body">
                    <select id="selecttemplate"  class=" form-control" style="margin-bottom: 5px;">
                    </select>
                    <a class="btn btn-default" href="javascript:;"  onclick="selecttemp();"> 选择模板</a>
                </div>
            </div>
        </div>

        <div class="  shilidiv"  >
            <div class="panel panel-default">
                <div class="panel-heading">
                    模板展示:
                </div>
                <div class="panel-body">
                    <div id="shili" class="text">
                    </div>
                </div>
            </div>
        </div>

        <div class="">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <select class="form-control" onclick="$('.tm').hide();$('.tm-' + $(this).val()).show()">
                        <option value="">选择模板变量类型</option>
                        <option value="order">订单类</option>
                        <option value="upgrade">升级类</option>
                        <option value="rw">充值提现类</option>
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
</div>

<script language='javascript'>

    var kw = 1;
    var temps;
    var contents;
    restempoption();

    function selecttemp()
    {
        var tid = $("#selecttemplate").val();
        var temp;

        for(var i=0;i<temps.length;i++){
            if(temps[i].template_id==tid)
            {
                temp =temps[i];
                break;
            }
        }

        if(temp == null) {
            return;
        } else {
            contents = temp.contents;

            if(contents[0]!='first' || contents[contents.length-1] != 'remark') {
                alert("此模板不可用!");
                return;
            }
            $("#shili").html(temp.content);

            $(".shilidiv").show();
            $("#tp_title").val(temp.title);
            $("#tp_template_id").val(temp.template_id);

            $('.key_item').remove();

            setcontents(0);
        }
    }


    function setcontents(i){

        if(contents.length == i) {
            return;
        }
        if(contents[i]!='first'&&contents[i]!='remark') {
            var url = "{!! yzWebUrl('setting.diy-temp.tpl') !!}";
            $.ajax({
                "url": url,
                "data":{tpkw:contents[i]},
                success: function (html) {
                    $(".btn-add-type").button("reset");
                    $("#type-items").append(html);
                    i++
                    setcontents(i);
                }
            });

        } else {
            i++
            setcontents(i);
        }
    }



    function addtempoption() {
        var tempcode = $("#tempcode").val();
        var data = {
            templateidshort: tempcode
        };
        var url = "{!! yzWebUrl('setting.wechat-notice.addTmp') !!}";
        $.ajax({
            "url": url,
            "data": data,
            success: function (ret) {
                if (ret.result == 1) {
                    alert("加入成功");
                    location.reload();
                } else {
                    alert("加入失败,请检查模板数量是否达到上限(25个)以及模板编码是否输入正确!");
                }
            }
        });
    }

    function restempoption() {
        var url = "{!! yzWebUrl('setting.wechat-notice.returnJson') !!}";
        $.ajax({
            "url": url,
            success: function (ret) {
                if (ret.result == 1) {
                    $("#selecttemplate option").remove();
                    temps = ret.data.tmp_list;
                    for(var i=0;i<temps.length;i++){
                        $("#selecttemplate").append("<option value='"+temps[i].template_id+"'>"+temps[i].title+"</option>");
                    }
                }
            }
        });
    }

    function addType() {
        $(".btn-add-type").button("loading");
        var url = "{!! yzWebUrl('setting.diy-temp.tpl') !!}";
        $.ajax({
            "url": url,
            "data":{kw:kw},
            success: function (html) {
                $(".btn-add-type").button("reset");
                $("#type-items").append(html);
            }
        });
        kw++;
    }
</script>
