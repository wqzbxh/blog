/****************************************************************
 *																*		
 * 						      为卿执笔博客						*
 * 																*
****************************************************************/
$(function(){
		var flag = false;
	    var message = "";
		var myreg = /^(((13[0-9]{1})|(14[0-9]{1})|(17[0]{1})|(15[0-3]{1})|(15[5-9]{1})|(18[0-9]{1}))+\d{8})$/;    
		var reg=/^\d{5,10}$/; 
		var passreg = /^(?![0-9]+$)(?![a-zA-Z]+$)[0-9A-Za-z]{6,21}$/;
		var nicknamereg =  /^[a-zA-Z0-9_-]{4,16}$/;
			$('.register_btn').on('click',function(){
			var telephone = $(" input[ name='telephone' ] ").val();
			var password = $(" input[ name='password' ] ").val();
			var tencentqq = $(" input[ name='tencentqq' ] ").val();
			var nickname = $(" input[ name='nickname' ] ").val();
			if(nickname ==''){
				 message = "昵称号码不能为空！";
			 }else if(!nicknamereg.test(nickname)){
				 message = "请输入有效的用户名号";
			 }else if(tencentqq ==''){
				 message = "QQ号码不能为空！";
			 }else if(!reg.test(tencentqq)){
				 message = "请输入有效的QQ号";			
			 }else if(telephone == ''){
				 message = "手机号码不能为空！";
			 }else if(telephone.length !=11){
				 message = "手机号码长度有误！";
			 }else if(!myreg.test(telephone)){
				 message = "请输入有效的手机号码！";
			 }else if(checkPhoneIsExist()){
				 message = "该手机号码已经被绑定！";
			 }else if(password == ''){
				 message = '密码不能为空';
			 }else if(!passreg.test(password)){
				 message = '密码不合法';
			 }
			 else{
				 flag = true;
			 }
			 
			 if(!flag){
				 alert(message);
				 }else{
					 console.log(111);
					 $.post("registerAction",{'nickname':nickname,'tencentqq':tencentqq,'telephone':telephone,'password':password,},function(res){
						console.log(res);
						if(res['code'] == 1){
								alert(res['info']);
								$(location).attr('href', 'login');
							}
						});
				 }
				 return flag;

	})
});
function checkPhoneIsExist(){
		 var telephone = $(" input[ name='telephone' ] ").val();
		 var flag = true;
		 jQuery.ajax(
			{ url: "checkPhone",
				data:{telephone:telephone},
				dataType:"json",
					 type:"POST",
					 async:false,
					 success:function(data){
					 console.log(data);
					 var code = data['code'];
					 if(code == "0"){
						 flag = false;
					 }
				 }
		});
		return flag;
}