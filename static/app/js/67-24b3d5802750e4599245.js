webpackJsonp([67,81],{3:function(t,e,r){var o,i;r(6),o=r(4);var s=r(7);i=o=o||{},"object"!=typeof o.default&&"function"!=typeof o.default||(i=o=o.default),"function"==typeof i&&(i=i.options),i.render=s.render,i.staticRenderFns=s.staticRenderFns,i._scopeId="data-v-0130450a",t.exports=o},4:function(t,e){"use strict";Object.defineProperty(e,"__esModule",{value:!0}),e.default={props:["text","hide","totext","tolink"],mounted:function(){},methods:{goto:function(){this.$router.go(-1)}}},t.exports=e.default},5:function(t,e,r){e=t.exports=r(1)(),e.push([t.id,".mint-header[data-v-0130450a]{background:none;color:#666}.is-fixed .mint-header-title[data-v-0130450a]{font-weight:700}.mint-header.is-fixed[data-v-0130450a]{border-bottom:1px solid #e8e8e8;background:#fff;z-index:99}.is-right a[data-v-0130450a]{font-size:9.6px;font-size:.6rem}","",{version:3,sources:["/./src/components/title.vue"],names:[],mappings:"AACA,8BAA8B,gBAAgB,UAAU,CACvD,AACD,8CAA8C,eAAgB,CAC7D,AACD,uCAAuC,gCAAgC,gBAAgB,UAAU,CAChG,AACD,6BAA6B,gBAAgB,eAAe,CAC3D",file:"title.vue",sourcesContent:["\n.mint-header[data-v-0130450a]{background:none;color:#666\n}\n.is-fixed .mint-header-title[data-v-0130450a]{font-weight:bold\n}\n.mint-header.is-fixed[data-v-0130450a]{border-bottom:1px solid #e8e8e8;background:#FFF;z-index:99\n}\n.is-right a[data-v-0130450a]{font-size:9.6px;font-size:.6rem\n}\n"],sourceRoot:"webpack://"}])},6:function(t,e,r){var o=r(5);"string"==typeof o&&(o=[[t.id,o,""]]);r(2)(o,{});o.locals&&(t.exports=o.locals)},7:function(t,e){t.exports={render:function(){var t=this,e=t.$createElement,r=t._self._c||e;return r("div",[t.hide?t._e():r("mt-header",{attrs:{fixed:"",title:t.text}},[r("mt-button",{attrs:{icon:"back"},on:{click:t.goto},slot:"left"}),t._v(" "),t.tolink?[r("router-link",{attrs:{to:{path:t.tolink}},slot:"right"},[t._v(t._s(t.totext))])]:t._e()],2)],1)},staticRenderFns:[]}},446:function(t,e,r){"use strict";function o(t){return t&&t.__esModule?t:{default:t}}Object.defineProperty(e,"__esModule",{value:!0});var i=r(9),s=r(3),a=o(s);e.default={data:function(){return{CodeStr:"发送验证码",lock:!1,timer:30,Interval:null,form:{mobile:"",mobileErr:"",password:"",passwordErr:"",confirm_password:"",confirm_passwordErr:""},mydata:""}},components:{cTitle:a.default},mounted:function(){},methods:{register:function(){var t=this;return this.form.mobile&&this.form.password&&this.form.confirm_password?(this.form.usernameErr="",this.form.passwordErr="",this.form.confirm_passwordErr="",void $http.post("member.register.index",this.form).then(function(e){if(1==e.result){e.data.member_id;t.$store.commit("savemodel",e.data),window.localStorage.myuserinfo=e.data,i.MessageBox.alert("注册成功").then(function(e){t.$router.push({name:"home"})})}else t.$store.commit("alerts",e.msg),t.form={}},function(t){})):(this.form.mobile?this.form.usernameErr="":this.form.usernameErr="error",this.form.password?this.form.passwordErr="":this.form.passwordErr="error",this.form.confirm_password?this.form.confirm_passwordErr="":this.form.confirm_passwordErr="error",void console.log("pppp",this.form.confirm_passwordErr))},login:function(){this.$router.push("/login")},VerificationCode:function(){var t=this;return this.form.mobile?void(0==this.lock&&(this.Interval=setInterval(this.update,1e3),this.lock=!0,$http.get("member.register.sendCode",{mobile:this.form.mobile}).then(function(e){1!=e.result&&t.$store.commit("alerts",e.msg)},function(t){console.log(t.data.msg)}))):void this.$store.commit("alerts","手机号必须填写！")},update:function(){this.timer--,0==this.timer?(clearInterval(this.Interval),this.timer=30,this.CodeStr="获取验证码",this.lock=!1):this.CodeStr=String(this.timer)}},activated:function(){console.log(this.$route.params.object_id)}},t.exports=e.default},616:function(t,e,r){e=t.exports=r(1)(),e.push([t.id,"#register[data-v-7522e3fc]{margin-top:50px;width:100%}#register #code[data-v-7522e3fc]{background:#ccc;padding:3px 5px;border-radius:3px}#register h1[data-v-7522e3fc]{color:#42b983}#register .mint-button--large[data-v-7522e3fc]{margin-top:10px}#register #bts[data-v-7522e3fc]{margin:auto 5px}#register #bts .mint-button--default[data-v-7522e3fc]{background-color:#13ce66;color:#fff}#register .forget[data-v-7522e3fc]{color:#999;float:right}","",{version:3,sources:["/./src/views/register/register.vue"],names:[],mappings:"AACA,2BAA2B,gBAAgB,UAAU,CACpD,AACD,iCAAiC,gBAAgB,gBAAgB,iBAAiB,CACjF,AACD,8BAA8B,aAAa,CAC1C,AACD,+CAA+C,eAAe,CAC7D,AACD,gCAAgC,eAAe,CAC9C,AACD,sDAAsD,yBAAyB,UAAU,CACxF,AACD,mCAAmC,WAAW,WAAW,CACxD",file:"register.vue",sourcesContent:["\n#register[data-v-7522e3fc]{margin-top:50px;width:100%\n}\n#register #code[data-v-7522e3fc]{background:#ccc;padding:3px 5px;border-radius:3px\n}\n#register h1[data-v-7522e3fc]{color:#42b983\n}\n#register .mint-button--large[data-v-7522e3fc]{margin-top:10px\n}\n#register #bts[data-v-7522e3fc]{margin:auto 5px\n}\n#register #bts .mint-button--default[data-v-7522e3fc]{background-color:#13ce66;color:#FFF\n}\n#register .forget[data-v-7522e3fc]{color:#999;float:right\n}\n"],sourceRoot:"webpack://"}])},720:function(t,e,r){var o=r(616);"string"==typeof o&&(o=[[t.id,o,""]]);r(2)(o,{});o.locals&&(t.exports=o.locals)},1052:function(t,e,r){var o,i;r(720),o=r(446);var s=r(1109);i=o=o||{},"object"!=typeof o.default&&"function"!=typeof o.default||(i=o=o.default),"function"==typeof i&&(i=i.options),i.render=s.render,i.staticRenderFns=s.staticRenderFns,i._scopeId="data-v-7522e3fc",t.exports=o},1109:function(t,e){t.exports={render:function(){var t=this,e=t.$createElement,r=t._self._c||e;return r("div",{attrs:{id:"register"}},[r("c-title",{attrs:{hide:!1,text:"用户登录"}}),t._v(" "),r("div",{staticClass:"page"},[r("mt-field",{attrs:{label:"手机号",placeholder:"请输入手机号",type:"tel",attr:{maxlength:11},state:t.form.mobileErr},model:{value:t.form.mobile,callback:function(e){t.form.mobile=e},expression:"form.mobile"}}),t._v(" "),r("mt-field",{attrs:{label:"验证码",placeholder:"请输入验证码"},model:{value:t.form.yzm,callback:function(e){t.form.yzm=e},expression:"form.yzm"}},[r("button",{attrs:{id:"code"},on:{click:t.VerificationCode}},[t._v(t._s(t.CodeStr))])]),t._v(" "),r("mt-field",{attrs:{label:"设置密码",placeholder:"请输入密码",type:"password",state:t.form.passwordErr},model:{value:t.form.password,callback:function(e){t.form.password=e},expression:"form.password"}}),t._v(" "),r("mt-field",{attrs:{label:"确认密码",placeholder:"请输入密码",type:"password",state:t.form.confirm_passwordErr},model:{value:t.form.confirm_password,callback:function(e){t.form.confirm_password=e},expression:"form.confirm_password"}})],1),t._v(" "),r("div",{attrs:{id:"bts"}},[r("mt-button",{attrs:{type:"default",size:"large"},on:{click:t.register}},[t._v("注册")]),t._v(" "),r("mt-button",{attrs:{type:"danger",size:"large"},on:{click:t.login}},[t._v("登录")])],1)],1)},staticRenderFns:[]}}});
//# sourceMappingURL=67-24b3d5802750e4599245.js.map