@extends('layouts.base')

@section('title','菜单编辑')

@section('pageHeader','菜单编辑header')

@section('pageDesc','菜单编辑desc')

@section('content')
    {!! BootForm::open(['model'=>$model,'url'=>yzWebFullUrl(request()->get('route')),'method'=>'POST']) !!}
    {!! $model->id ? BootForm::hidden('id',$model->id) : '' !!}
    <div class="panel panel-default">
        <div class="panel-body">

            {!! BootForm::select('menu[parent_id]','上级',$parentMenu,$model->parent_id) !!}
            {!! BootForm::text('menu[item]','* 标识',$model->item,['help_text'=>'标识唯一也是做为权限判断标识']) !!}
            {!! BootForm::text('menu[name]','* 菜单名称',$model->name) !!}
            {!! BootForm::text('menu[url]','URL路由或链接地址',$model->url,['help_text'=>'填写路由 menu.add 或 http(s)://xxxx']) !!}
            {!! BootForm::text('menu[url_params]','URL参数',$model->url_params) !!}

            {{--图标修改start--}}
            <link rel="stylesheet" href="/addons/sz_yi/static/yunshop/plugins/bootstrap-iconpicker/icon-fonts/font-awesome-4.2.0/css/font-awesome.min.css"/>
            <link rel="stylesheet" href="/addons/sz_yi/static/yunshop/plugins/bootstrap-iconpicker/bootstrap-iconpicker/css/bootstrap-iconpicker.min.css"/>
            <div class="form-group ">
                <label for="menu[icon]" class="control-label col-xs-12 col-md-2">图标</label>
                <div class="col-sm-9 col-xs-12">
                    <button class="btn btn-default" name="menu[icon]" data-iconset="fontawesome" data-icon="{{ $model->icon?$model->icon:'fa-sliders' }}" role="iconpicker"></button>
                </div>
            </div>
            @section('js')
            <script type="text/javascript" src="/addons/sz_yi/static/yunshop/plugins/bootstrap-iconpicker/bootstrap-iconpicker/js/iconset/iconset-fontawesome-4.3.0.min.js"></script>
            <script type="text/javascript" src="/addons/sz_yi/static/yunshop/plugins/bootstrap-iconpicker/bootstrap-iconpicker/js/bootstrap-iconpicker.js"></script>
            @stop
            {{--图标修改end--}}

            {!! BootForm::text('menu[sort]','排序',$model->sort) !!}
            {!! BootForm::radios('menu[permit]','权限控制',[1=>'是',0=>'否'],(int)$model->permit,true) !!}
            {!! BootForm::radios('menu[menu]','菜单显示',[1=>'是',0=>'否'],(int)$model->menu,true) !!}
            {!! BootForm::radios('menu[status]','显示状态',[1=>'启用',0=>'禁止'],(int)$model->status,true) !!}

            {!! BootForm::submit('提交') !!}

        </div>
    </div>
   {!! BootForm::close() !!}

@endsection