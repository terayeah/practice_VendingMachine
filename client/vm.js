$(document).on('click','#selectedVm', function() {
  displayVmView($(this).val());
});

$(document).on('click', '#putCash', function() {
  $.post("/lessons/a_vending_machine/user/putCash",
    { "userEncrypt": $.cookie('userEncrypt'),
      "howMuchCash": $('#howMuchCash').val() },
    function(){
      displayVmView($.cookie('choicedVmId'));
    });
});

$(document).on('click', '#charge', function() {
  $.post("/lessons/a_vending_machine/user/charge",
    { "userEncrypt": $.cookie('userEncrypt'),
      "howMuchSuica": $('#howMuchSuica').val() },
    function(){
      displayVmView($.cookie('choicedVmId'));
    });
});

$(document).on('click', '#backChange', function() {
  $.post("/lessons/a_vending_machine/user/backChange",
    { "userEncrypt": $.cookie('userEncrypt') },
    function(){
      displayVmView($.cookie('choicedVmId'));
    });
});

$(document).on('click', '#selectedDrink', function() {
  $.post("/lessons/a_vending_machine/user/selectedDrink",
    { "userEncrypt": $.cookie('userEncrypt'),
      "selectedDrink": $(this).val()},
    function(data){
      displayVmView($.cookie('choicedVmId'));
      $("#info").html(data);
    });
});

$(document).on('click', '#buySuica', function() {
  $.post("/lessons/a_vending_machine/user/buySuica",
    { "userEncrypt": $.cookie('userEncrypt') },
    function(data){
      displayVmView($.cookie('choicedVmId'));
      $("#info").html(data);
    });
});

$(document).on('click', '#addVm', function() {
  displayAddVmView();
  $("#view3").css("display","block");
  $("#cover").css("background-color","grey")
             .css("position", "absolute")
             .css("z-index","800")
             .css("top", "0px")
             .css("left", "0px")
             .css("right", "0px")
             .css("bottom", "0px")
             .css("opacity", "0.8")
             .css("display", "block");
});

$(document).on('click', '#setDrink', function() {
  displaySetDrinkView();
  $("#view3").css("display","block");
  $("#cover").css("background-color","grey")
             .css("position", "absolute")
             .css("z-index","800")
             .css("top", "0px")
             .css("left", "0px")
             .css("right", "0px")
             .css("bottom", "0px")
             .css("opacity", "0.8")
             .css("display", "block");
});

$(document).on('click', '#logout', function() {
  $.removeCookie("userEncrypt");
  $.removeCookie("choicedVmId");
  window.location.href = '/lessons/a_vending_machine/client/top.html'
});

function displayErrorView(){
  let body = "ログインしてください<br/>";
  body += "<button id='logout'>ログインする</button>";
  $("#view").html(body);
}

function displayVmTopView(){
  $.post("/lessons/a_vending_machine/vendingmachine/drowvmtop",
        { "userEncrypt": $.cookie('userEncrypt') },
        function(data){
          console.log(data);
          if(data.vmArray){
            let body = "";
            let cashButton = "";
            let suicaButton = "";
            let bothButton = "";
            let vmIds = Object.keys(data.vmArray);
            for(let i = 0; i < vmIds.length; i++){
              let vmId = vmIds[i];
              let vm = data.vmArray[vmId];
              if (vm.type == vm_type_cash){
                cashButton += "<button id='selectedVm' value='" + vmId + "'>" + vm.name + "</button></br>";
              }
              if (vm.type == vm_type_suica){
                suicaButton += "<button id='selectedVm' value='" + vmId + "'>" + vm.name + "</button></br>";
              }
              if (vm.type == vm_type_both){
                bothButton += "<button id='selectedVm' value='" + vmId + "'>" + vm.name + "</button></br>";
              }
            }
            body += "<h3>現金会計のみの自販機</h3>";
            body += cashButton;
            body += "<h3>Suica会計のみの自販機</h3>";
            body += suicaButton;
            body += "<h3>現金とSuica両方会計の自販機</h3>";
            body += bothButton;
            body += "<br>";
            body += "<button id='addVm'>自販機を追加する</button><br/>";
            body += "<br>";
            body += "<button id='logout'>サインアウト</button>";


            $("#view").html(body);
          }else{
            let body = "";

            body += "<button id='addVm'>自販機を追加する</button><br/>";
            body += "<br>";
            body += "<button id='logout'>サインアウト</button>";

            $("#view").html(body);
            $("#info").html("自販機を追加してください");
          }
        });
}

function getdrinkOptions(drinkDic){
  if(!drinkDic)
    return null;

  let body = "";
  let drinkNames = Object.keys(drinkDic);
  for(let i = 0; i < drinkNames.length; i++){
    let drinkName = drinkNames[i];
    let drinkStock = drinkDic[drinkName];
    body += drinkName + " : " + drinkStock +  "本　";
  }
  return body;
}

function displayVmView(vmId){
  $.post("/lessons/a_vending_machine/vendingmachine/drowvmview",
		{ "userEncrypt": $.cookie('userEncrypt'),
      "selectedVmId": vmId },
		function(data){

      console.log(data);
      $.cookie('choicedVmId', data.vm.id);

      if(data.vm_drink){
        let body = "";
        let vmdrinkStock = getdrinkOptions(data.vm_stock);
        let userdrinkStock = getdrinkOptions(data.user_drink);
        // header
        body += "<h3>自販機 : " + data.vm.name + "</h3>";
        body += '<ul>';
        body += "<li>自販機売上額 : ¥" + data.vm.total + " </li>";
        body += "<li>現在の入金額 : ¥" + data.vm.charge + "</li>";
        body += "<li>在庫 ";
        body += vmdrinkStock;
        body += '</li></ul><br>';

        // 入金フォーム
        if (data.vm.type == vm_type_cash || data.vm.type == vm_type_both){
          body += "<input type='text' id='howMuchCash' placeholder='金額を記入'>";
          body += "<button id='putCash'>入金</button>";
          body += "<button id='backChange'>お釣り</button>";
          body += "<br>";
        }
        // suicaフォーム
        if (data.vm.type == vm_type_suica || data.vm.type == vm_type_both){
          body += "<input type='text' id='howMuchSuica' placeholder='Suicaへのチャージ額を記入'>";
          body += "<button id='charge'>チャージ</button>";
          body += "<br>";
        }
        // ドリンクボタン
        let drinkIds = Object.keys(data.vm_drink);
        for(let i = 0; i < drinkIds.length; i++){
          let drinkId = drinkIds[i];
          let drink = data.vm_drink[drinkId];
          if(data.vm_stock[drink.name] > 0){
            body += "<button id='selectedDrink' value='" + drinkId + "'>" + drink.name + " ¥ " + drink.price + "</button>";
          }else{
            body += "<button id='selectedLossDrink' value='" + drinkId + "'>" + drink.name + " 売り切れ </button>";
          }
        }
        body += "<br/>";

        // suica購入ボタン
        if (data.vm.type == vm_type_suica || data.vm.type == vm_type_both){
          body += "<button id='buySuica'>購入</button>";
          body += "<br>";
        }
        // fotter
        body += '<ul>';
        body += "<li>ユーザー名 : " + data.user.name + " さん</li>";
        body += "<li>現在の所持金 : ¥" + data.user.cash + "</li>";
        body += "<li>現在のチャージ額 : ¥" + data.user.suica + "</li>";
        body += "<li>持ってる飲み物 : ";
        body += userdrinkStock
        body += "</li></ul>";

        body += "<button id='setDrink'>商品を編集する</button></br>";

        $("#view2").html(body);
      }else{
        let body = "";
        body += "商品を追加してください</br>";
        body += "<button id='setDrink'>商品を編集する</button></br>";
        $("#view2").html(body);
      }

		});
}
