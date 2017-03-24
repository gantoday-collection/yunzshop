@extends('layouts.base')

@section('content')

    <div class="w1200 m0a">
        <div class="rightlist">
            {if $operation=='display'}
            <form action="./index.php" method="get" class="form-horizontal" role="form" id="form1">
                <input type="hidden" name="c" value="site" />
                <input type="hidden" name="a" value="entry" />
                <input type="hidden" name="m" value="sz_yi" />
                <input type="hidden" name="do" value="plugin" />
                <input type="hidden" name="p" value="coupon" />
                <input type="hidden" name="method" value="coupon" />
                <input type="hidden" name="op" value="display" />

                <div class="panel panel-info">
                    <div class="panel-heading">筛选</div>
                    <div class="panel-body">

                        <div class="form-group">

                            <div class="form-group">
                                <label class="col-xs-12 col-sm-2 col-md-2 col-lg-2 control-label">优惠券名称</label>
                                <div class="col-sm-8 col-lg-9 col-xs-12">
                                    <input type="text" class="form-control"  name="keyword" value="" placeholder='可搜索优惠券名称'/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-xs-12 col-sm-2 col-md-2 col-lg-2 control-label">分类</label>
                                <div class="col-sm-9 col-xs-12">
                                    <select name='catid' class='form-control'>
                                        <option value=''></option>

                                    </select>

                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-xs-12 col-sm-2 col-md-2 col-lg-2 control-label">领取中心是否显示</label>
                                <div class="col-sm-8 col-lg-9 col-xs-12">
                                    <select name='gettype' class='form-control'>
                                        <option value=''></option>
                                        <option value='0' >不显示</option>
                                        <option value='1' >显示</option>
                                    </select>
                                </div>

                            </div>
                            <div class="form-group">
                                <label class="col-xs-12 col-sm-2 col-md-2 col-lg-2 control-label">创建时间</label>
                                <div class="col-sm-7 col-lg-9 col-xs-12">
                                    <div class="col-sm-3">
                                        <label class='radio-inline'>
                                            <input type='radio' value='0' name='searchtime'>不搜索
                                        </label>
                                        <label class='radio-inline'>
                                            <input type='radio' value='1' name='searchtime' >搜索
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-xs-12 col-sm-2 col-md-2 col-lg-2 control-label"></label>
                                <div class="col-sm-8 col-lg-9 col-xs-12">
                                    <button class="btn btn-default"><i class="fa fa-search"></i> 搜索</button>
                                    <input type="hidden" name="token" value="{{$var['token']}}" />

                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">总数:<small>排序数字越大越靠前</small></div>
                        <div class="panel-body">
                            <table class="table table-hover table-responsive">
                                <thead class="navbar-inner" >
                                <tr>
                                    <th width="4%">ID</th>
                                    <th width="6%">排序</th>
                                    <th width="16%">优惠券名称</th>
                                    <th width="16%">使用条件/优惠</th>
                                    <th width="10%">已使用/已发出/剩余数量</th>
                                    <th width="10%">领取中心</th>
                                    <th width="10%">创建时间</th>
                                    <th width="18%">操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                {loop $list $row}
                                <tr>
                                    <td>{$row['id']}</td>
                                    <td>
                                        {ifp 'coupon.coupon.edit'}
                                        <input type="text" class="form-control" name="displayorder[{$row['id']}]" value="{$row['displayorder']}">
                                        {else}
                                        {$row['displayorder']}
                                        {/if}
                                    </td>

                                    <td>{if $row['coupontype']==0}
                                        <label class='label label-success'>购物</label>
                                        {else}
                                        <label class='label label-warning'>充值</label>
                                        {/if}
                                        {if !empty($row['catid'])}
                                        <label class='label label-primary'>{$category[$row['catid']]['name']}</label>
                                        {/if}
                                        <br/>{$row['couponname']}
                                    </td>
                                    <td>{if $row['enough']>0}
                                        <label class="label label-danger">满{$row['enough']}可用</label>
                                        {else}
                                        <label class="label label-warning">不限</label>
                                        {/if}

                                        <br/>{if $row['backtype']==0}
                                        立减 {$row['deduct']} 元
                                        {else if $row['backtype']==1}
                                        打 {$row['discount']} 折
                                        {else if $row['backtype']==2}
                                        {if $row['backmoney']>0}返 {$row['backmoney']} 余额;{/if}
                                        {if $row['backcredit']>0}返 {$row['backcredit']} 积分;{/if}
                                        {if $row['backredpack']>0}返 {$row['backredpack']} 红包;{/if}
                                        {/if}
                                    </td>

                                    <td>
                                        {ifp 'coupon.log.view'}
                                        <a href="{php echo $this->createPluginWebUrl('coupon/log',array('coupon'=>$row['id']))}">
                                            {$row['usetotal']} / {$row['gettotal']} / {if $row['total']==-1}无限数量{else}{php echo $row['lasttotal']}{/if}
                                        </a>
                                        {else}
                                        {$row['usetotal']} / {$row['gettotal']} / {if $row['total']==-1}无限数量{else}{php echo $row['lasttotal']}{/if}
                                        {/if}

                                    <td>{if $row['gettype']==0}
                                        <label class="label label-default">不显示</label>
                                        {else}

                                        {if $row['credit']>0 || $row['money']>0}
                                        {if $row['credit']>0}<label class='label label-primary'>{$row['credit']} 积分</label><br/>{/if}
                                        {if $row['money']>0}<label class='label label-danger'>{$row['money']} 现金</label><br/>{/if}
                                        {else}
                                        <label class='label label-warning'>免费</label>
                                        {/if}
                                        {/if}
                                    </td>
                                    <td>{php echo date('Y-m-d',$row['createtime'])}</td>
                                    <td style="position:relative">
                                        <a href="javascript:;" data-url="{php echo $this->createPluginMobileUrl('coupon/detail', array('id' => $row['id']))}"  title="复制连接" class="btn btn-default btn-sm js-clip"><i class="fa fa-link"></i></a>

                                        {ifp 'coupon.coupon.edit'}
                                        <a class='btn btn-default btn-sm' href="{php echo $this->createPluginWebUrl('coupon/coupon/post',array('id' => $row['id'], 'type' => ($row['coupontype']==0?0:1)));}" title="编辑" ><i class='fa fa-edit'></i></a>

                                        {/if}
                                        {ifp 'coupon.coupon.delete'}
                                        <a class='btn btn-default  btn-sm' href="{php echo $this->createPluginWebUrl('coupon/coupon/delete',array('id' => $row['id']));}" title="删除" onclick="return confirm('确定要删除该优惠券吗？');"><i class='fa fa-remove'></i></a>

                                        {/if}

                                        {ifp 'coupon.coupon.send'}
                                        <a  class='btn btn-primary  btn-sm' href="{php echo $this->createPluginWebUrl('coupon/send',array('couponid' => $row['id']));}" title="发放优惠券" ><i class='fa fa-send'></i></a>

                                        {/if}
                                        </ul>
                        </div>


                        </td>
                        </tr>
                        {/loop}
                        </tbody>
                        </table>
                        {$pager}
                    </div>
                    <div class='panel-footer'>
                        {ifp 'article.page.edit'}
                        <input name="submit" type="submit" class="btn btn-default" value="提交排序">
                        {/if}
                        {ifp 'coupon.coupon.add'}
                        <a class='btn btn-primary' href="{php echo $this->createPluginWebUrl('coupon/coupon',array('op'=>'post'))}"><i class='fa fa-plus'></i> 添加购物优惠券</a>
                        <a class='btn btn-primary' href="{php echo $this->createPluginWebUrl('coupon/coupon',array('op'=>'post','type'=>1))}"><i class='fa fa-plus'></i> 添加充值优惠券</a>
                        {/if}
                    </div>
                </div>
            </form>
            {else if $operation=='post'}

            <form {ife 'coupon.coupon.edit' $coupon}action="" method='post'{/if} class='form-horizontal'>
            <input type="hidden" name="id" value="{$coupon['id']}">
            <input type="hidden" name="op" value="detail">
            <input type="hidden" name="c" value="site" />
            <input type="hidden" name="a" value="entry" />
            <input type="hidden" name="m" value="sz_yi" />
            <input type="hidden" name="p" value="coupon" />
            <input type="hidden" name="method" value="coupon" />
            <input type="hidden" name="op" value="post" />
            <div class='panel panel-default'>
                <div class='panel-heading'>
                    编辑{if empty($_GPC['type'])}购物{else}充值{/if}优惠券
                </div>

                <div class='panel-body'>
                    <div class="form-group">
                        <label class="col-xs-12 col-sm-3 col-md-2 control-label">排序</label>
                        <div class="col-sm-5">
                            {ife 'coupon.coupon' $coupon}
                            <input type="text" name="displayorder" class="form-control" value="{$coupon['displayorder']}"  />
                            <span class='help-block'>数字越大越靠前</span>
                            {else}
                            <div class='form-control-static'>{$coupon['displayorder']}</div>
                            {/if}
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-xs-12 col-sm-3 col-md-2 control-label"><span style='color:red'>*</span> 优惠券名称</label>
                        <div class="col-sm-9 col-xs-12">
                            {ife 'coupon.coupon' $coupon}
                            <input type="text" name="couponname" class="form-control" value="{$coupon['couponname']}"  />
                            {else}
                            <input type="hidden" name="couponname" class="form-control" value="{$coupon['couponname']}"  />
                            <div class='form-control-static'>{$coupon['couponname']}</div>
                            {/if}
                        </div>
                    </div>


                    <div class="form-group">
                        <label class="col-xs-12 col-sm-3 col-md-2 control-label">分类</label>
                        <div class="col-sm-9 col-xs-12">
                            {ife 'coupon.coupon' $coupon}
                            <select name='catid' class='form-control'>
                                <option value=''></option>
                                {loop $category $k $c}
                                <option value='{$k}' {if $coupon['catid']==$k}selected{/if}>{$c['name']}</option>
                                {/loop}
                            </select>
                            {else}
                            <div class='form-control-static'>{if empty($coupon['catid'])}暂时无分类{else} {$category[$coupon['catid']]['name']}{/if}</div>
                            {/if}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-xs-12 col-sm-3 col-md-2 control-label">缩略图</label>
                        <div class="col-sm-9 col-xs-12">
                            {ife 'coupon.coupon' $coupon}
                            {php echo tpl_form_field_image('thumb', $coupon['thumb'])}
                            {else}
                            <input type="hidden" name="thumb" value="{$coupon['thumb']}"/>
                            {if !empty($coupon['thumb'])}
                            <a href='{php echo tomedia($coupon['thumb'])}' target='_blank'>
                            <img src="{php echo tomedia($coupon['thumb'])}" style='width:100px;border:1px solid #ccc;padding:1px' />
                            </a>
                            {/if}
                            {/if}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-xs-12 col-sm-3 col-md-2 control-label">使用条件</label>
                        <div class="col-sm-9 col-xs-12">
                            {ife 'coupon.coupon' $coupon}
                            <input type="text" name="enough" class="form-control" value="{$coupon['enough']}"  />
                            <span class='help-block' >{if empty($_GPC['type'])}消费{else}充值{/if}满多少可用, 空或0 不限制</span>
                            {else}
                            <div class='form-control-static'>{if $coupon['enough']>0}满 {$coupon['enough']} 可用 {else}不限制{/if}</div>
                            {/if}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-xs-12 col-sm-3 col-md-2 control-label">使用时间限制</label>

                        {ife 'coupon.coupon.edit' $coupon}
                        <div class="col-sm-5">
                            <div class='input-group'>
                        <span class='input-group-addon'>
                             <label class="radio-inline" style='margin-top:-5px;' ><input type="radio" name="timelimit" value="0" {if $coupon['timelimit']==0}checked{/if}>获得后</label>
                        </span>

                                <input type='text' class='form-control' name='timedays' value="{$coupon['timedays']}" />
                                <span class='input-group-addon'>天内有效(空为不限时间使用)</span>
                            </div>
                        </div>

                        <div class="col-sm-3">
                            <div class='input-group'>
                        <span class='input-group-addon'>
                             <label class="radio-inline" style='margin-top:-5px;' ><input type="radio" name="timelimit" value="1" {if $coupon['timelimit']==1}checked{/if}>日期</label>
                        </span>
                                {php echo tpl_form_field_daterange('time', array('starttime'=>date('Y-m-d', $starttime),'endtime'=>date('Y-m-d', $endtime)));}
                                <span class='input-group-addon'>内有效</span>
                            </div>
                        </div>
                        {else}
                        <div class="col-sm-9 col-xs-12">
                            <div class='form-control-static'>
                                {if $coupon['timelimit']==0}
                                {if !empty($coupon['timedays'])}获得后 {$coupon['timedays']} 天内有效{else}不限时间{/if}
                                {else}
                                {php echo date('Y-m-d',$starttime)} - {php echo date('Y-m-d',$endtime)}  范围内有效
                                {/if}</div>
                        </div>
                        {/if}

                    </div>
                    {if empty($_GPC['type'])}
                    {template 'coupon/consume'}
                    {else}
                    {template 'coupon/recharge'}
                    {/if}
                    {if empty($_GPC['type'])}
                    <div class="form-group">
                        <label class="col-xs-12 col-sm-3 col-md-2 control-label">是否支持收银台使用</label>
                        <div class="col-sm-9 col-xs-12" >
                            {ife 'coupon.coupon' $coupon}
                            <label class="radio-inline">
                                <input type="radio" name="getcashier" value="0" {if $coupon['getcashier'] == 0}checked="true"{/if}  onclick="$('.getcashierarea').hide()"/> 否
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="getcashier" value="1" {if $coupon['getcashier'] == 1}checked="true"{/if} onclick="$('.getcashierarea').show()" /> 是
                            </label>
                            <span class='help-block' style="color:red">注:选择"是"之后,则只支持收银台使用， 如不继续选择指定商户,则默认支持所有收银台使用; 如选择"否",则不支持收银台使用!</span>
                            {else}
                            <div class='form-control-static'>
                                {if $coupon['getcashier']==1}是{else}否{/if}
                            </div>
                            {/if}
                        </div>

                    </div>

                    <div class="form-group">
                        <label class="col-xs-12 col-sm-3 col-md-2 control-label"></label>
                        <div class="col-sm-7 getcashier getcashierarea" {if $coupon['getcashier']!=1}style='display:none'{/if}>
                        <div class='input-group'>
                            <div id="cashier">
                                <table class="table">
                                    <tbody id="param-itemscashier">
                                    {loop $coupon[cashiersids] $k $v}
                                    <tr>
                                        <td>
                                            <a href="javascript:;" class="fa fa-move" title="拖动调整此显示顺序" ><i class="fa fa-arrows"></i></a>&nbsp;
                                            <a href="javascript:;" onclick="deleteParam(this)" style="margin-top:10px;"  title="删除"><i class='fa fa-times'></i></a>
                                        </td>
                                        <td colspan="2">
                                            <input type="hidden" class="form-control" name="cashiersids[]" data-id="{$v}" data-name="cashiersids"  value="{$v}" style="width:200px;float:left"  />

                                            <input class="form-control" type="text" data-id="{$v}" data-name="cashiersnames" name="cashiersnames[]"  value="{$coupon[cashiersnames][$k]}" style="width:200px;float:left">
                      <span class="input-group-btn">
                          <button class="btn btn-default nav-link-cashier" type="button" data-id="{$v}" >选择商户</button>
                      </span>
                                        </td>
                                    </tr>
                                    {/loop}
                                    </tbody>
                                    <tbody>
                                    <tr>
                                        <td colspan="3">
                                            <a href="javascript:;" id='add-param_cashier' onclick="addParam('cashier')" style="margin-top:10px;" class="btn btn-primary"  title="添加商户"><i class='fa fa-plus'></i> 添加商户</a>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>　
                </div>

                <div class="form-group">
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label">是否指定核销门店</label>
                    <div class="col-sm-9 col-xs-12" >
                        {ife 'coupon.coupon' $coupon}
                        <label class="radio-inline">
                            <input type="radio" name="getstore" value="0" {if $coupon['getstore'] == 0}checked="true"{/if}  onclick="$('.getstorearea').hide()"/> 否
                        </label>
                        <label class="radio-inline">
                            <input type="radio" name="getstore" value="1" {if $coupon['getstore'] == 1}checked="true"{/if} onclick="$('.getstorearea').show()" /> 是
                        </label>
                        <span class='help-block' style="color:red">注:选择"是"之后, 如不继续选择指定门店,则默认支持所有门店使用!</span>
                        {else}
                        <div class='form-control-static'>
                            {if $coupon['getstore']==1}是{else}否{/if}
                        </div>
                        {/if}
                    </div>

                </div>

                <div class="form-group">
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label"></label>
                    <div class="col-sm-7 getstore getstorearea" {if $coupon['getstore']!=1}style='display:none'{/if}>
                    <div class='input-group'>
                        <div id="store">
                            <table class="table">
                                <tbody id="param-itemsstore">
                                {loop $coupon[storeids] $k $v}
                                <tr>
                                    <td>
                                        <a href="javascript:;" class="fa fa-move" title="拖动调整此显示顺序" ><i class="fa fa-arrows"></i></a>&nbsp;
                                        <a href="javascript:;" onclick="deleteParam(this)" style="margin-top:10px;"  title="删除"><i class='fa fa-times'></i></a>
                                    </td>
                                    <td colspan="2">
                                        <input type="hidden" class="form-control" name="storeids[]" data-id="{$v}" data-name="storeids"  value="{$v}" style="width:200px;float:left"  />

                                        <input class="form-control" type="text" data-id="{$v}" data-name="storenames" name="storenames[]"  value="{$coupon[storenames][$k]}" style="width:200px;float:left">
                      <span class="input-group-btn">
                          <button class="btn btn-default nav-link-store" type="button" data-id="{$v}" >选择门店</button>
                      </span>
                                    </td>
                                </tr>
                                {/loop}
                                </tbody>
                                <tbody>
                                <tr>
                                    <td colspan="3">
                                        <a href="javascript:;" id='add-param_store' onclick="addParam('store')" style="margin-top:10px;" class="btn btn-primary"  title="添加门店"><i class='fa fa-plus'></i> 添加门店</a>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>　
            </div>
            <div class="form-group">
                <label class="col-xs-12 col-sm-3 col-md-2 control-label">是否指定供应商</label>
                <div class="col-sm-9 col-xs-12" >
                    {ife 'coupon.coupon' $coupon}
                    <label class="radio-inline">
                        <input type="radio" name="getsupplier" value="0" {if $coupon['getsupplier'] == 0}checked="true"{/if}  onclick="$('.getsupplierarea').hide()"/> 否
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="getsupplier" value="1" {if $coupon['getsupplier'] == 1}checked="true"{/if} onclick="$('.getsupplierarea').show()" /> 是
                    </label>
                    <span class='help-block' style="color:red">注:选择"是"之后, 如不继续选择指定供应商,则默认支持所有供应商使用!</span>
                    {else}
                    <div class='form-control-static'>
                        {if $coupon['getsupplier']==1}是{else}否{/if}
                    </div>
                    {/if}
                </div>

            </div>

            <div class="form-group">
                <label class="col-xs-12 col-sm-3 col-md-2 control-label"></label>
                <div class="col-sm-7 getsupplier getsupplierarea" {if $coupon['getsupplier']!=1}style='display:none'{/if}>
                <div class='input-group'>
                    <div id="supplier">
                        <table class="table">
                            <tbody id="param-itemssupplier">
                            {loop $coupon[supplierids] $k $v}
                            <tr>
                                <td>
                                    <a href="javascript:;" class="fa fa-move" title="拖动调整此显示顺序" ><i class="fa fa-arrows"></i></a>&nbsp;
                                    <a href="javascript:;" onclick="deleteParam(this)" style="margin-top:10px;"  title="删除"><i class='fa fa-times'></i></a>
                                </td>
                                <td colspan="2">
                                    <input type="hidden" class="form-control" name="supplierids[]" data-id="{$v}" data-name="supplierids"  value="{$v}" style="width:200px;float:left"  />

                                    <input class="form-control" type="text" data-id="{$v}" data-name="suppliernames" name="suppliernames[]"  value="{$coupon[suppliernames][$k]}" style="width:200px;float:left">
                      <span class="input-group-btn">
                          <button class="btn btn-default nav-link-supplier" type="button" data-id="{$v}" >选择供应商</button>
                      </span>
                                </td>
                            </tr>
                            {/loop}
                            </tbody>
                            <tbody>
                            <tr>
                                <td colspan="3">
                                    <a href="javascript:;" id='add-param_supplier' onclick="addParam('supplier')" style="margin-top:10px;" class="btn btn-primary"  title="添加供应商"><i class='fa fa-plus'></i> 添加供应商</a>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>　


        <div class="form-group">
            <label class="col-xs-12 col-sm-3 col-md-2 control-label">适用范围</label>
            <div class="col-sm-9 col-xs-12">
                <input type="hidden" name="coupontype" value="0"/>
                {ife 'coupon.coupon' $coupon}
                <label class="radio-inline " ><input type="radio" name="usetype" onclick='showusetype(0)' value="0" {if $coupon['usetype']==0}checked{/if}>全类适用</label>
                <label class="radio-inline"><input type="radio" name="usetype" onclick='showusetype(1)' value="1" {if $coupon['usetype']==1}checked{/if}>指定商品分类</label>
                <label class="radio-inline "><input type="radio" name="usetype" onclick='showusetype(2)' value="2" {if $coupon['usetype']==2}checked{/if}>指定商品</label>
                {else}
                <div class='form-control-static'>
                    {if $coupon['usetype']==0}
                    全类适用
                    {elseif $coupon['usetype']==1}
                    指定商品
                    {else}
                    指定商品分类
                    {/if}
                </div>
                {/if}
            </div>
        </div>

        <div class="form-group">
            <label class="col-xs-12 col-sm-3 col-md-2 control-label"></label>

            {ife 'coupon.coupon' $coupon}
            <div class="col-sm-2 usetype usetype0"  {if $coupon['usetype']!=0}style='display:none'{/if}>
            <div class='input-group'>
                <span class='help-block'>如选择此项,则支持商城所有商品使用!</span>
            </div>
        </div>
        <div class="col-sm-7 usetype usetype1"  {if $coupon['usetype']!=1}style='display:none'{/if}>
        <div class='input-group'>
            <div id="category" >
                <table class="table">
                    <thead>

                    </thead>
                    <tbody id="param-itemscategory">
                    {loop $coupon[categoryids] $k $v}
                    <tr>
                        <td>
                            <a href="javascript:;" class="fa fa-move" title="拖动调整此显示顺序" ><i class="fa fa-arrows"></i></a>&nbsp;
                            <a href="javascript:;" onclick="deleteParam(this)" style="margin-top:10px;"  title="删除"><i class='fa fa-times'></i></a>
                        </td>
                        <td  colspan="2">
                            <input type="hidden" class="form-control" name="categoryids[]" data-id="{$v}" data-name="categoryids"  value="{$v}" style="width:200px;float:left"  />

                            <input class="form-control" type="text" data-id="{$v}" data-name="categorynames" name="categorynames[]"  value="{$coupon[categorynames][$k]}" style="width:200px;float:left">
                                          <span class="input-group-btn">
                                              <button class="btn btn-default nav-link" type="button" data-id="{$v}" >选择分类</button>
                                          </span>


                        </td>

                    </tr>
                    {/loop}
                    </tbody>
                    <tbody>
                    <tr>
                        <td colspan="3">
                            <a href="javascript:;" id='add-param_category' onclick="addParam('category')" style="margin-top:10px;" class="btn btn-primary"  title="添加分类"><i class='fa fa-plus'></i> 添加分类</a>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-sm-7 usetype usetype2"  {if $coupon['usetype']!=2}style='display:none'{/if}>
    <div class='input-group'>

        <div id="goods">
            <table class="table">
                <tbody id="param-itemsgoods">
                {loop $coupon[goodsids] $k $v}
                <tr>
                    <td>
                        <a href="javascript:;" class="fa fa-move" title="拖动调整此显示顺序" ><i class="fa fa-arrows"></i></a>&nbsp;
                        <a href="javascript:;" onclick="deleteParam(this)" style="margin-top:10px;"  title="删除"><i class='fa fa-times'></i></a>
                    </td>
                    <td  colspan="2">
                        <input type="hidden" class="form-control" name="goodsids[]" data-id="{$v}" data-name="goodsids"  value="{$v}" style="width:200px;float:left"  />

                        <input class="form-control" type="text" data-id="{$v}" data-name="goodsnames" name="goodsnames[]"  value="{$coupon[goodsnames][$k]}" style="width:200px;float:left">
                                          <span class="input-group-btn">
                                              <button class="btn btn-default nav-link-goods" type="button" data-id="{$v}" >选择商品</button>
                                          </span>
                    </td>
                </tr>
                {/loop}
                </tbody>

                <tbody>
                <tr>
                    <td colspan="3">
                        <a href="javascript:;" id='add-param_goods' onclick="addParam('goods')" style="margin-top:10px;" class="btn btn-primary"  title="添加商品"><i class='fa fa-plus'></i> 添加商品</a>
                    </td>
                </tr>
                </tbody>

            </table>

        </div>
    </div>

    </div>　
    {else}
    <div class='form-control-static'>
        {if $coupon['usetype']==0}
        支持商城所有商品使用!
        {else if $coupon['usetype']==1}

        {else if $coupon['usetype']==2}

        {/if}
    </div>
    {/if}
    </div>
    {/if}

    <div id="goods" style="display: none"></div>




    <div class="form-group">
        <label class="col-xs-12 col-sm-3 col-md-2 control-label">领券中心是否可获得</label>
        <div class="col-sm-9 col-xs-12" >
            {ife 'coupon.coupon' $coupon}
            <label class="radio-inline">
                <input type="radio" name="gettype" value="0" {if $coupon['gettype'] == 0}checked="true"{/if}  onclick="$('.gettype').hide()"/> 不可以
            </label>
            <label class="radio-inline">
                <input type="radio" name="gettype" value="1" {if $coupon['gettype'] == 1}checked="true"{/if} onclick="$('.gettype').show()" /> 可以
            </label>
            <span class='help-block'>会员是否可以在领券中心直接领取或购买</span>

            {else} <div class='form-control-static'>
                {if $coupon['gettype']==1}可以{else}不可以{/if}
            </div>
            {/if}
        </div>
    </div>

    <div class="form-group gettype" {if $coupon['gettype']!=1}style="display:none"{/if}>
    <label class="col-xs-12 col-sm-3 col-md-2 control-label"></label>
    <div class="col-sm-6">
        {ife 'coupon.coupon' $coupon}
        <div class="input-group">
            <span class="input-group-addon">每个限领</span>
            <input type='text' class='form-control' value="{$coupon['getmax']}" name='getmax' style="width: 80px" />
            <span class="input-group-addon">张 消耗</span>
            <input style="width: 80px"  type='text' class='form-control' value="{$coupon['credit']}" name='credit'/>
            <span class="input-group-addon">积分 + 花费</span>
            <input style="width: 80px"  type='text' class='form-control' value="{$coupon['money']}" name='money'/>
                              <span class="input-group-addon">元&nbsp;&nbsp;
                                  <label class="checkbox-inline" style='margin-top:-8px;'>
                                      <input type="checkbox" name='usecredit2' value="1" {if $coupon['usecredit2']==1}checked{/if} /> 优先使用余额支付
                                  </label>
                              </span></div>
        <span class="help-block">每人限领，空不限制，领取方式可任意组合，可以单独积分兑换，单独现金兑换，或者积分+现金形式兑换, 如果都为空，则可以免费领取</span>
        {else}
        <div class='form-control-static'>消耗 {$coupon['credit']} 积分 花费 {$coupon['money']} 元现金</div>
        {/if}
    </div>

    </div>
    　
    <div class="form-group">
        <label class="col-xs-12 col-sm-3 col-md-2 control-label">发放总数</label>
        <div class="col-sm-9 col-xs-12">
            {ife 'coupon.coupon' $coupon}
            <input type="text" name="total" class="form-control" value="{$coupon['total']}"  />
            <span class='help-block' >优惠券总数量，没有不能领取或发放,-1 为不限制张数</span>
            {else}
            <div class='form-control-static'>{if $coupon['total']==-1}无限数量{else}剩余 {$coupon['total']} 张{/if}</div>
            {/if}
        </div>
    </div>

    　    </div>
    　
    <div class='panel-heading'>
        使用说明
    </div>
    <div class='panel-body'>

        <div class="form-group">
            <label class="col-xs-12 col-sm-3 col-md-2 control-label">是否使用统一说明 </label>
            <div class="col-sm-9 col-xs-12">
                {ife 'coupon.coupon' $coupon}
                <label class="radio-inline" >
                    <input type="radio" name="descnoset" value="0" {if $coupon['descnoset'] == 0}checked="true"{/if} /> 使用
                </label>

                <label class="radio-inline">
                    <input type="radio" name="descnoset" value="1" {if $coupon['descnoset'] == 1}checked="true"{/if} /> 不使用
                </label>
                <span class='help-block'>统一说明在<a href="{php echo $this->createPluginWebUrl('coupon/set')}" target='_blank'>【基础设置】</a>中设置，如果使用统一说明，则在优惠券说明前面显示统一说明</span>
                {else}

                <div class='form-control-static'>
                    {if $coupon['descnoset']==0}
                    使用
                    {else if $coupon['descnoset']==1}
                    不使用
                    {else}
                    {/if}
                </div>
                {/if}
            </div>
        </div>


        <div class="form-group">
            <label class="col-xs-12 col-sm-3 col-md-2 control-label">使用说明</label>
            <div class="col-sm-9 col-xs-12">
                {ife 'coupon.coupon' $coupon}
                {php echo tpl_ueditor('desc',$coupon['desc'])}
                {else}
                <textarea id='desc' style='display:none'>{$coupon['desc']}</textarea>
                <a href='javascript:preview_html("#desc")' class="btn btn-default">查看内容</a>
                {/if}
            </div>
        </div>
    </div>
    <div class='panel-heading'>
        推送消息 (发放或用户从领券中心获得后的消息推送，如果标题为空就不推送消息)
    </div>
    <div class='panel-body'>


        <div class="form-group">
            <label class="col-xs-12 col-sm-3 col-md-2 control-label">推送标题</label>
            <div class="col-sm-9 col-xs-12">
                {ife 'coupon.coupon' $coupon}
                <input type="text" name="resptitle" class="form-control" value="{$coupon['resptitle']}"  />
                <span class="help-block">变量 [nickname] 会员昵称 [total] 优惠券张数</span>
                {else}
                <div class='form-control-static'>{$coupon['resptitle']}</div>
                {/if}
            </div>
        </div>
        <div class="form-group">
            <label class="col-xs-12 col-sm-3 col-md-2 control-label">推送封面</label>
            <div class="col-sm-9 col-xs-12">
                {ife 'coupon.coupon' $coupon}
                {php echo tpl_form_field_image('respthumb', $coupon['respthumb'])}
                {else}
                <input type="hidden" name="respthumb" value="{$coupon['respthumb']}"/>
                {if !empty($coupon['thumb'])}
                <a href='{php echo tomedia($coupon['respthumb'])}' target='_blank'>
                <img src="{php echo tomedia($coupon['respthumb'])}" style='width:100px;border:1px solid #ccc;padding:1px' />
                </a>
                {/if}
                {/if}
            </div>
        </div>

        <div class="form-group">
            <label class="col-xs-12 col-sm-3 col-md-2 control-label">推送说明</label>
            <div class="col-sm-9 col-xs-12">
                {ife 'coupon.coupon' $coupon}
                <textarea name="respdesc" class='form-control'>{$coupon['respdesc']}</textarea>
                <span class="help-block">变量 [nickname] 会员昵称 [total] 优惠券张数</span>
                {else}
                <div class='form-control-static'>{$coupon['respdesc']}</div>
                {/if}
            </div>
        </div>
        <div class="form-group">
            <label class="col-xs-12 col-sm-3 col-md-2 control-label">推送连接</label>
            <div class="col-sm-9 col-xs-12">
                {ife 'coupon.coupon' $coupon}
                <input type="text" name="respurl" class="form-control" value="{$coupon['respurl']}"  />
                <span class='help-block'>消息推送点击的连接，为空默认为优惠券详情</span>
                {else}
                <div class='form-control-static'>{$coupon['respurl']}</div>
                {/if}
            </div>
        </div>
    </div>


    <div class='panel-heading'>
        口令玩法 (用户发送关键词猜取优惠券)
    </div>
    <div class='panel-body'>

        <div class="form-group">
            <label class="col-xs-12 col-sm-3 col-md-2 control-label">是否开启口令玩法</label>
            <div class="col-sm-9 col-xs-12" >
                {ife 'coupon.coupon' $coupon}
                <label class="radio-inline">
                    <input type="radio" name="pwdopen" value="0" {if $coupon['pwdopen'] == 0}checked="true"{/if} onclick="$('.couponkey').hide()"  /> 关闭
                </label>
                <label class="radio-inline">
                    <input type="radio" name="pwdopen" value="1" {if $coupon['pwdopen'] == 1}checked="true"{/if} onclick="$('.couponkey').show()"  /> 开启
                </label>
                {else}
                <div class='form-control-static'>
                    {if $coupon['pwdopen']==1}开启{else}关闭{/if}
                </div>
                {/if}
            </div>
        </div>

        <div class="form-group couponkey" {if empty($coupon['pwdopen'])}style="display:none"{/if}>
        <label class="col-xs-12 col-sm-3 col-md-2 control-label">开始活动关键词</label>
        <div class="col-sm-9 col-xs-12">
            {ife 'coupon.coupon' $coupon}
            <input type="text" name="pwdkey" class="form-control" value="{$coupon['pwdkey']}"  />
            <span class="help-block">从平台获取优惠券的回复关键词,如果设置关键词为空，则不使用口令玩法，如果更换关键词，则表示开启另一轮活动</span>
            {else}
            <div class='form-control-static'>{$coupon['pwdkey']}</div>
            {/if}
        </div>
    </div>
    <div class="form-group couponkey" {if empty($coupon['pwdopen'])}style="display:none"{/if}>
    <label class="col-xs-12 col-sm-3 col-md-2 control-label">口令集</label>
    <div class="col-sm-9 col-xs-12">
        {ife 'coupon.coupon' $coupon}
        <textarea name="pwdwords" class='form-control'>{$coupon['pwdwords']}</textarea>
        <span class="help-block">可以多个口令, 用半角逗号隔开,口令不要与其他系统关键词重复</span>
        {else}
        <div class='form-control-static'>{$coupon['pwdwords']}</div>
        {/if}
    </div>
    </div>

    <div class="form-group couponkey" {if empty($coupon['pwdopen'])}style="display:none"{/if}>
    <label class="col-xs-12 col-sm-3 col-md-2 control-label">每人猜测机会</label>
    <div class="col-sm-9 col-xs-12">
        {ife 'coupon.coupon' $coupon}
        <input name="pwdtimes" class='form-control' value='{$coupon['pwdtimes']}'>
        <span class="help-block">每人机会，空或0为不限制 </span>
        {else}
        <div class='form-control-static'>{if empty($coupon['pwdtimes'])}不限制{else}{$coupon['pwdtimes']}次{/if}</div>

        {/if}
    </div>
    </div>
    <div class="form-group couponkey" {if empty($coupon['pwdopen'])}style="display:none"{/if}>
    <label class="col-xs-12 col-sm-3 col-md-2 control-label">提示语</label>
    <div class="col-sm-9 col-xs-12">
        {ife 'coupon.coupon' $coupon}
        <textarea name="pwdask" class='form-control'>{$coupon['pwdask']}</textarea>
        <span class="help-block">默认: 请输入优惠券口令: </span>
        <span class='help-block'>变量: [nickname] 会员昵称 [couponname] 优惠券名称 [times] 已猜测次数 [lasttimes] 剩余猜测次数</span>
        {else}
        <div class='form-control-static'>{if empty($coupon['pwdask'])}请输入优惠券口令:{else}{$coupon['pwdask']}{/if}</div>

        {/if}
    </div>
    </div>

    <div class="form-group couponkey" {if empty($coupon['pwdopen'])}style="display:none"{/if}>
    <label class="col-xs-12 col-sm-3 col-md-2 control-label">猜中提示语</label>
    <div class="col-sm-9 col-xs-12">
        {ife 'coupon.coupon' $coupon}
        <textarea name="pwdsuc" class='form-control'>{$coupon['pwdsuc']}</textarea>
        <span class="help-block">默认: 恭喜你，猜中啦！优惠券已发到您账户了!</span>
        <span class='help-block'>变量: [nickname] 会员昵称 [couponname] 优惠券名称 [times] 已猜测次数 [lasttimes] 剩余猜测次数</span>
        {else}
        <div class='form-control-static'>{if empty($coupon['pwdsuc'])}恭喜你，猜中啦！优惠券已发到您账户了!{else}{$coupon['pwdsuc']}{/if}</div>

        {/if}
    </div>
    </div>

    <div class="form-group couponkey" {if empty($coupon['pwdopen'])}style="display:none"{/if}>
    <label class="col-xs-12 col-sm-3 col-md-2 control-label">猜错提示语</label>
    <div class="col-sm-9 col-xs-12">
        {ife 'coupon.coupon' $coupon}
        <textarea name="pwdfail" class='form-control'>{$coupon['pwdfail']}</textarea>
        <span class='help-block'>默认: 很抱歉，您猜错啦，继续猜~</span>
        <span class='help-block'>变量: [nickname] 会员昵称 [couponname] 优惠券名称 [times] 已猜测次数 [lasttimes] 剩余猜测次数</span>
        {else}
        <div class='form-control-static'>{if empty($coupon['pwdfail'])}很抱歉，您猜错啦，继续猜~{else}{$coupon['pwdfail']}{/if}</div>

        {/if}
    </div>
    </div>
    <div class="form-group couponkey" {if empty($coupon['pwdopen'])}style="display:none"{/if}>
    <label class="col-xs-12 col-sm-3 col-md-2 control-label">猜测次数超出限制提示</label>
    <div class="col-sm-9 col-xs-12">
        {ife 'coupon.coupon' $coupon}
        <textarea name="pwdfull" class='form-control'>{$coupon['pwdfull']}</textarea>
        <span class='help-block'>默认: 很抱歉，您已经没有机会啦~</span>
        <span class='help-block'>变量: [nickname] 会员昵称 [couponname] 优惠券名称 [times] 已猜测次数 [lasttimes] 剩余猜测次数</span>
        {else}
        <div class='form-control-static'>{if empty($coupon['pwdfull'])}很抱歉，您已经没有机会啦~{else}{$coupon['pwdfull']}{/if}</div>
        {/if}
    </div>
    </div>
    <div class="form-group couponkey" {if empty($coupon['pwdopen'])}style="display:none"{/if}>
    <label class="col-xs-12 col-sm-3 col-md-2 control-label">退出口令</label>
    <div class="col-sm-9 col-xs-12">
        {ife 'coupon.coupon' $coupon}
        <input type="text" name="pwdexit" class="form-control" value="{$coupon['pwdexit']}"  />
        <span class="help-block">如果设置有次数限制，用户继续猜了，可输入退出口令，默认为0</span>
        {else}
        <div class='form-control-static'>{$coupon['pwdexit']}</div>
        {/if}
    </div>
    </div>
    <div class="form-group couponkey" {if empty($coupon['pwdopen'])}style="display:none"{/if}>
    <label class="col-xs-12 col-sm-3 col-md-2 control-label">退出后提示</label>
    <div class="col-sm-9 col-xs-12">
        {ife 'coupon.coupon' $coupon}
        <textarea name="pwdexitstr" class='form-control'>{$coupon['pwdexitstr']}</textarea>
        <span class='help-block'>默认: 好的，等待您下次来玩!</span>
        <span class='help-block'>变量: [nickname] 会员昵称 [couponname] 优惠券名称 [times] 已猜测次数 [lasttimes] 剩余猜测次数</span>

        {else}
        <div class='form-control-static'>{if empty($coupon['pwdexitstr'])}很好的，等待您下次来玩!{else}{$coupon['pwdexitstr']}{/if}</div>
        {/if}
    </div>
    </div>

    <div class="form-group couponkey" {if empty($coupon['pwdopen'])}style="display:none"{/if}>
    <label class="col-xs-12 col-sm-3 col-md-2 control-label">已获得提示</label>
    <div class="col-sm-9 col-xs-12">
        {ife 'coupon.coupon' $coupon}
        <textarea name="pwdown" class='form-control'>{$coupon['pwdown']}</textarea>
        <span class='help-block'>默认: 您已经参加过啦,等待下次活动吧~</span>
        <span class='help-block'>变量: [nickname] 会员昵称 [couponname] 优惠券名称 [times] 已猜测次数 [lasttimes] 剩余猜测次数</span>
        {else}
        <div class='form-control-static'>{if empty($coupon['pwdown'])}您已经参加过啦,等待下次活动吧~{else}{$coupon['pwdown']}{/if}</div>
        {/if}
    </div>
    </div>
    </div>

    <div class="form-group"></div>
    <div class="form-group">
        <label class="col-xs-12 col-sm-3 col-md-2 control-label"></label>
        <div class="col-sm-9 col-xs-12">
            {ife 'coupon.coupon' $coupon}
            <input type="submit" name="submit" value="提交" class="btn btn-primary col-lg-1"  />
            <input type="hidden" name="token" value="{$_W['token']}" />
            {/if}
            <input type="button" name="back" onclick='history.back()' {ife 'coupon.coupon' $coupon}style='margin-left:10px;'{/if} value="返回列表" class="btn btn-default" />
        </div>
    </div>




    {template 'web/sysset/selectgoods'}
    {template 'web/sysset/selectcategory'}
    {template 'web/sysset/selectcashier'}
    {template 'web/sysset/selectstore'}
    {template 'web/sysset/selectsupplier'}
    <script type="text/javascript">
        $(function() {
            $("#chkoption").click(function() {
                var obj = $(this);
                if (obj.get(0).checked) {
                    $("#tboption").show();
                    $(".trp").hide();
                }
                else {
                    $("#tboption").hide();
                    $(".trp").show();
                }
            });
        })

        function addParam(type) {
            var url = "{php echo $this->createWebUrl('shop/tpl')}&tpl="+type;
            $.ajax({
                "url": url,
                success: function(data) {
                    $('#param-items'+type).append(data);
                }
            });
            return;
        }
        function deleteParam(o) {
            $(o).parent().parent().remove();
        }
        function saveadd(o) {
            $(o).parent().parent().remove();
        }
    </script>





    </div>


    </div>

    </form>
    <script language='javascript'>

        function showbacktype(type){

            $('.backtype').hide();
            $('.backtype' + type).show();
        }

        function showusetype(type){

            $('.usetype').hide();
            $('.usetype' + type).show();
        }
        $(function(){

            $('form').submit(function(){

                if($(':input[name=couponname]').isEmpty()){
                    Tip.focus($(':input[name=couponname]'),'请输入优惠券名称!');
                    return false;
                }
                var backtype = $(':radio[name=backtype]:checked').val();
                if(backtype=='0'){
                    if($(':input[name=deduct]').isEmpty()){
                        Tip.focus($(':input[name=deduct]'),'请输入立减多少!');
                        return false;
                    }
                }else if(backtype=='1'){
                    if($(':input[name=discount]').isEmpty()){
                        Tip.focus($(':input[name=discount]'),'请输入折扣多少!');
                        return false;
                    }
                }else if(backtype=='2'){
                    if($(':input[name=backcredit]').isEmpty() && $(':input[name=backmoney]').isEmpty() && $(':input[name=backredpack]').isEmpty()){
                        Tip.focus($(':input[name=backcredit]'),'至少输入一种返利!');
                        return false;
                    }
                }
                return true;
            })

        })
    </script>

    {/if}
    </div>
    </div>
    </div>

@endsection('content')