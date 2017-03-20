﻿@extends('layouts.base')

@section('js')
<script type="text/javascript" src="resource/js/lib/jquery-ui-1.10.3.min.js"></script>

	<script type="text/javascript">
      window.type = "{{$goods['type']}}";
      window.virtual = "{{$goods['virtual']}}";

      $(function () {

        $(':radio[name=type]').click(function () {
          window.type = $("input[name='type']:checked").val();
          window.virtual = $("#virtual").val();
          if(window.type=='1'){
            $('#dispatch_info').show();
          } else {
            $('#dispatch_info').hide();
          }
          if (window.type == '3') {
            if ($('#virtual').val() == '0') {
              $('.choosetemp').show();
            }
          }
        })

        $("input[name='back']").click(function () {
          location.href = "{{yzWebUrl('goods.goods.index')}}";
        });
      })
      window.optionchanged = false;
      require(['bootstrap'], function () {
        $('#myTab a').click(function (e) {
          e.preventDefault();
          $(this).tab('show');
        })
      });

      require(['util'], function (u) {
        $('#cp').each(function () {
          u.clip(this, $(this).text());
        });
      })

      function formcheck() {
        window.type = $("input[name='goods[type]']:checked").val();
        window.virtual = $("#virtual").val();

        if ($("#goodsname").isEmpty()) {
          $('#myTab a[href="#tab_basic"]').tab('show');
          Tip.focus("#goodsname", "请输入商品名称!");
          return false;
        }

		  @if (empty($id))
          if ($.trim($(':input[name="goods[thumb]"]').val()) == '') {
            $('#myTab a[href="#tab_basic"]').tab('show');
            Tip.focus(':input[name="goods[thumb]"]', '请上传缩略图.');
            return false;
          }
				  @endif
        var full = true;
        if (window.type == '3') {

          if (window.virtual != '0') {  //如果单规格，不能有规格

            if ($('#hasoption').get(0).checked) {

              $('#myTab a[href="#tab_option"]').tab('show');
              util.message('您的商品类型为：虚拟物品(卡密)的单规格形式，需要关闭商品规格！');
              return false;
            }
          }
          else {

            var has = false;
            $('.spec_item_virtual').each(function () {
              has = true;
              if ($(this).val() == '' || $(this).val() == '0') {
                $('#myTab a[href="#tab_option"]').tab('show');
                Tip.focus($(this).next(), '请选择虚拟物品模板!');
                full = false;
                return false;
              }
            });
            if (!has) {
              $('#myTab a[href="#tab_option"]').tab('show');
              util.message('您的商品类型为：虚拟物品(卡密)的多规格形式，请添加规格！');
              return false;
            }
          }
        }
        if (!full) {
          return false;
        }

        full = checkoption();
        if (!full) {
          return false;
        }
        if (optionchanged) {
          $('#myTab a[href="#tab_option"]').tab('show');
          alert('规格数据有变动，请重新点击 [刷新规格项目表] 按钮!');
          return false;
        }
        var discountway = $('input:radio[name=discountway]:checked').val();
        var discounttype = $('input:radio[name=discounttype]:checked').val();
        var returntype = $('input:radio[name=returntype]:checked').val();
        var marketprice = $('input:text[name=marketprice]').val();
        var isreturn = false;

        // Tip.focus("#goodsname", "请输入商品名称!");
        // 		return false;

        if(discountway == 1){
          if(discounttype == 1){
            $(".discounts").each(function(){
              if(parseFloat($(this).val()) <= 0 || parseFloat($(this).val()) >= 10){
                $(this).val('');
                isreturn = true;
                alert('请输入正确折扣！');
                return false;
              }
            });
          }else{
            $(".discounts2").each(function(){
              if(parseFloat($(this).val()) <= 0 || parseFloat($(this).val()) >= 10){
                $(this).val('');
                isreturn = true;
                alert('请输入正确折扣！');
                return false;
              }
            });
          }


        }else{
          if(discounttype == 1){
            $(".discounts").each(function(){
              if( parseFloat($(this).val()) < 0 || parseFloat($(this).val()) >= parseFloat(marketprice)){
                $(this).val('');
                isreturn = true;
                alert('请输入正确折扣金额！');
                return false;
              }
            });
          }else{
            $(".discounts2").each(function(){
              if( parseFloat($(this).val()) < 0 || parseFloat($(this).val()) >= parseFloat(marketprice)){
                $(this).val('');
                isreturn = true;
                alert('请输入正确折扣金额！');
                return false;
              }
            });
          }


        }
        if(returntype == 1){
          $(".returns").each(function(){
            if( parseFloat($(this).val()) < 0 || parseFloat($(this).val()) >= parseFloat(marketprice)){
              $(this).val('');
              isreturn = true;
              alert('请输入正确返现金额！');
              return false;
            }
          });
        }else{
          $(".returns2").each(function(){
            if( parseFloat($(this).val()) < 0 || parseFloat($(this).val()) >= parseFloat(marketprice)){
              $(this).val('');
              isreturn = true;
              alert('请输入正确返现金额！');
              return false;
            }
          });
        }


        if(isreturn){
          return false;
        }
        return true;

      }

      function checkoption() {

        var full = true;
        if ($("#hasoption").get(0).checked) {
          $(".spec_title").each(function (i) {
            if ($(this).isEmpty()) {
              $('#myTab a[href="#tab_option"]').tab('show');
              Tip.focus(".spec_title:eq(" + i + ")", "请输入规格名称!", "top");
              full = false;
              return false;
            }
          });
          $(".spec_item_title").each(function (i) {
            if ($(this).isEmpty()) {
              $('#myTab a[href="#tab_option"]').tab('show');
              Tip.focus(".spec_item_title:eq(" + i + ")", "请输入规格项名称!", "top");
              full = false;
              return false;
            }
          });
        }
        if (!full) {
          return false;
        }
        return full;
      }

	</script>

	<script type="text/javascript">
      //鼠标划过显示商品链接二维码
      $('.umphp').hover(function() {
          var url = $(this).attr('data-url');
          var goodsid = $(this).attr('data-goodsid');
          $.post("{php echo $this->createWebUrl('shop/goods')}"
            , {'op': 'goods_qrcode', id: goodsid, url: url}
            , function (qr) {
              if (qr.img) {
                var goodsqr = qr.img;
                var element = document.getElementById(goodsid);
                element.src = goodsqr;
              }
            }
            , "json"
          );
          $(this).addClass("selected");
        },
        function() {
          $(this).removeClass("selected");
        })
      function fastChange(id, type, value) {
        $.ajax({
          url: "{php echo $this->createWebUrl('shop/goods')}",
          type: "post",
          data: {op: 'change', id: id, type: type, value: value},
          cache: false,
          success: function () {
            location.reload();
          }
        })
      }
      $(function () {
        $("form").keypress(function(e) {
          if (e.which == 13) {
            return false;
          }
        });

        $('.tdedit input').keydown(function (event) {
          if (event.keyCode == 13) {
            var group = $(this).closest('.input-group');
            var type = group.find('button').data('type');
            var goodsid = group.find('button').data('goodsid');
            var val = $.trim($(this).val());
            if(type=='title' && val==''){
              return;
            }
            group.prev().show().find('span').html(val);
            group.hide();
            fastChange(goodsid,type,val);
          }
        })
        $('.tdedit').mouseover(function () {
          $(this).find('.fa-pencil').show();
        }).mouseout(function () {
          $(this).find('.fa-pencil').hide();
        });
        $('.fa-edit-item').click(function () {
          var group = $(this).closest('span').hide().next();

          group.show().find('button').unbind('click').click(function () {
            var type = $(this).data('type');
            var goodsid = $(this).data('goodsid');
            var val = $.trim(group.find(':input').val());
            if(type=='title' && val==''){
              Tip.show(group.find(':input'), '请输入名称!');
              return;
            }
            group.prev().show().find('span').html(val);
            group.hide();
            fastChange(goodsid,type,val);
          });
        })
      })
      function setProperty(obj, id, type) {
        $(obj).html($(obj).html() + "...");
        $.post("{{yzWebUrl('goods.goods.index')}}"
          , {'op': 'setgoodsproperty', id: id, type: type, plugin: "", data: obj.getAttribute("data")}
          , function (d) {
            $(obj).html($(obj).html().replace("...", ""));
            if (type == 'type') {
              $(obj).html(d.data == '1' ? '实体物品' : '虚拟物品');
            }
            if (type == 'status') {
              $(obj).html(d.data == '1' ? '{{$lang['putaway']}}' : '{{$lang['soldout']}}');
            }
            $(obj).attr("data", d.data);
            if (d.result == 1) {
              $(obj).toggleClass("label-info text-pinfo");
            }
          }
          , "json"
        );
      }

	</script>
@stop

@section('content')


<link rel="stylesheet" type="text/css" href="../addons/sz_yi/static/css/font-awesome.min.css">
<style type='text/css'>
    .tab-pane {padding:20px 0 20px 0;}

</style>
{{--<div class="main rightlist">--}}


	<form   action="" method="post" class="form-horizontal form" enctype="multipart/form-data" >
        <div class="panel-default panel-center">
<!--             <div class="panel-heading">
                {if empty($goods['id'])}添加商品{else}编辑商品{/if}
            </div> -->

			<div  >
				<ul class="add-shopnav" id="myTab">
					<li class="active" ><a href="#tab_basic">基本信息</a></li>
					<li><a href="#tab_des">{{$lang['shopdesc']}}</a></li>
					<li><a href="#tab_param">属性</a></li>
					<li><a href="#tab_option">{{$lang['shopoption']}}</a></li>
					@foreach(Config::get('widget.goods') as $key=>$value)
					<li><a href="#{{$key}}">{{$value['title']}}</a></li>
					@endforeach

				</ul>
			</div>
			<div style="padding-top:50px">
				<div class="panel-body">
					<div class="tab-content">
						<div class="tab-pane  active" id="tab_basic">@include('goods.basic')</div>
						<div class="tab-pane" id="tab_des">@include('goods.des')</div>
						<div class="tab-pane" id="tab_param">@include('goods.tpl.param')</div>
						<div class="tab-pane" id="tab_option">@include('goods.tpl.option')</div>
						@foreach(Config::get('widget.goods') as $key=>$value)
						<div class="tab-pane" id="{{$key}}">{!! widget($value['class'], ['goods_id'=> $goods->id])!!}</div>
						@endforeach

					</div>
					<div class="form-group col-sm-12 mrleft40 border-t" style="text-align: right;">
						<input type="submit" name="submit" value="{{$lang['shopsubmit']}}" class="btn btn-primary col-lg-1" onclick="return formcheck()" style="float: right;margin-left: 8px;" />
						<input type="hidden" name="token" value="{{$var['token']}}" />
						<input type="button" name="back"  style='margin-left:10px;' value="返回列表" class="btn btn-default" />

					</div>
				</div>
			</div>
		</div>
	</form>
{{--</div>--}}

	@endsection('content')