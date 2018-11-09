$(document).on('click','#login', function() {
  $.post("/lessons/a_vending_machine/user/login",
		{ "username": $("#username").val(),
      "password": $("#password").val()},
		function(data){
      if(data.error != null){
        $("#info").html(data.error);
      }else{
        $.cookie('userEncrypt', data.encrypted_password);
        displayUserView();
      }
		});
});

$(document).on('click','#signup', function() {
  $.post("/lessons/a_vending_machine/user/signup",
  { "newUsername": $("#newUsername").val(),
    "newPassword": $("#newPassword").val() },
		function(data){
      if(data.isUpdated == true){
        $("#info").html("すでに登録されているユーザー名です");
      }else{
        $("#info").html("新規登録！");
      }
		});
});


function displayLoginView(){
	let html = `<h2>ログイン画面</h2>`;
	html += `<input type="text" placeholder='ユーザー名' id="username" />`;
  html += `<br/>`;
  html += `<input type="text" placeholder='パスワード' id="password" />`;
	html += `<br/>`;
	html += `<button id="login">ログイン</button>`;
  html += `<br/>`;
  html += `<input type="text" placeholder='ユーザー名' id="newUsername" />`;
  html += `<br/>`;
  html += `<input type="text" placeholder='パスワード' id="newPassword" />`;
	html += `<br/>`;
	html += `<button id="signup">新規登録</button>`;
	$("#view").html(html);
	$("#info").html("");
}

function displayUserView(){
  window.location.href = '/lessons/a_vending_machine/client/vm.html';
}
