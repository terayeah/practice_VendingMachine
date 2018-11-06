$(document).on('click', '#add_vm', function() {
  $.post("/lessons/a_vending_machine/server/addVm.php",
    { "userEncrypt": $.cookie('userEncrypt'),
      "vmType": $('[name = vmType]').val(),
      "vmName": $('#vmName').val() },
    function(data){
      displayAddVmView(data);
    });
});

$(document).on('click', '#addExistingDrink', function() {
  $.post("/lessons/a_vending_machine/server/addDrink.php",
    { "userEncrypt": $.cookie('userEncrypt'),
      "choicedVmId": $.cookie('choicedVmId'),
      "addedExistingDrink": $('[name = addedExistingDrink]').val(),
      "addDrinkCount": $('#addDrinkCount').val() },
    function(data){
      displaySetDrinkView(data);
    });
});

$(document).on('click', '#changeDrink', function() {
  $.post("/lessons/a_vending_machine/server/changeDrink.php",
    { "userEncrypt": $.cookie('userEncrypt'),
      "choicedVmId": $.cookie('choicedVmId'),
      "changedDrink": $('[name = changedDrink]').val(),
      "changeDrinkStock": $('#changeDrinkStock').val() },
    function(data){
      displaySetDrinkView(data);
    });
});

$(document).on('click', '#deleteDrink', function() {
  if(window.confirm("削除してよろしいですか？")){
    $.post("/lessons/a_vending_machine/server/deleteDrink.php",
      { "userEncrypt": $.cookie('userEncrypt'),
        "choicedVmId": $.cookie('choicedVmId'),
        "deletedDrink": $('[name = changedDrink]').val() },
      function(data){
        displaySetDrinkView(data);
      });
  }
});

$(document).on('click', '#addDrink', function() {
  $.post("/lessons/a_vending_machine/server/makeProduct.php",
    { "userEncrypt": $.cookie('userEncrypt'),
      "choicedVmId": $.cookie('choicedVmId'),
      "drinkName": $('#drinkName').val(),
      "drinkPrice": $('#drinkPrice').val() },
    function(data){
      displaySetDrinkView(data);
    });
});

$(document).on('click', '#changeProduct', function() {
  $.post("/lessons/a_vending_machine/server/changeProduct.php",
    { "userEncrypt": $.cookie('userEncrypt'),
      "choicedVmId": $.cookie('choicedVmId'),
      "changedProduct": $('[name = changedProduct]').val(),
      "changeProductName": $('#changeProductName').val(),
      "changeProductPrice": $('#changeProductPrice').val()},
    function(data){
      displaySetDrinkView(data);
    });
});

$(document).on('click', '#back_vm_view', function() {
  $("#view3").css("display", "none");
  $("#cover").css("display", "none");
  displayVmTopView();
  displayVmView($.cookie('choicedVmId'));
});

$(document).on('click', '#cover', function() {
  $("#view3").css("display", "none");
  $("#cover").css("display", "none");
  displayVmTopView();
  displayVmView($.cookie('choicedVmId'));
});

$(document).on('click', '#gologin', function() {
  window.location.href = '/lessons/a_vending_machine/client/top.html'
});

function getDrinkOptions(drinkDic){
  if(!drinkDic)
    return null;

  let body = "";
  let drinkIds = Object.keys(drinkDic);
  for(let i = 0; i < drinkIds.length; i++){
    let drinkId = drinkIds[i];
    let drink = drinkDic[drinkId];
    body += "<option value=" + drinkId + ">" + drink.name + "¥" + drink.price + "</option>";
  }
  return body;
}

function displayErrorView(){
  let body = "ログインしてください<br/>";
  body += "<button id='gologin'>ログインする</button>";
  $("#view").html(body);
}

function displayAddVmView(info2 = ""){
  let body = "<h4>追加フォーム</h4>";
  body += "<select name='vmType'>";
  body += "<option value='cash'>現金会計のみ</option>";
  body += "<option value='suica'>Suica会計のみ</option>";
  body += "<option value='both'>現金Suica両方会計</option>";
  body += "<input type='text' id='vmName' placeholder='自販機名'>";
  body += "<button id='add_vm'>追加</button>";
  body += "<br>";

  body += "<button id='back_vm_view'>閉じる</button></br>";
  body += "<div id='info2'>" + info2 + "</div>";
  $("#view3").html(body);
}

function displaySetDrinkView(info2 = ""){
  $.post("/lessons/a_vending_machine/server/setDrink.php",
		{ "selectedVmId": $.cookie('choicedVmId') },
		function(data){
      console.log(data);

      let allDrinkOptions = getDrinkOptions(data.drinks.all);
      let vendingMachineDrinkOptions = getDrinkOptions(data.drinks.vendingMachine);

      let body = "";
      body += "<h2>自販機 : " + data.name + "のドリンクを編集する</h2>";

      // VendingMachineにドリンクを新規で追加する
      body += "<h4>追加フォーム</h4>";
      if(allDrinkOptions){
        body += "<select name='addedExistingDrink'>";
        body += allDrinkOptions;
        body += "<input type='text' id='addDrinkCount' placeholder='個数'>";
        body += "</select>";
        body += "<button id='addExistingDrink'>追加</button>";
        body += "<br>";
      }else{
        body += "ドリンクが登録されていません。";
      }

      // VendingMachineに既存のドリンクを追加または削除
      body += "<h4>変更フォーム</h4>";
      if(vendingMachineDrinkOptions){
        body += "<select name='changedDrink'>";
        body += vendingMachineDrinkOptions;
        body += "<input type='number' id='changeDrinkStock' placeholder='変更個数'>";
        body += "</select>";
        body += "<button id='changeDrink'>変更</button>";
        body += "<button id='deleteDrink'>削除</button>";
        body += "<br>";
    }else{
      body += "VendingMachineに飲み物が存在しません。";
    }

    body += "<br>";
    body += "<h2>商品開発をする</h2>";

    // 新しいドリンクを追加する
    body += "<h4>新規商品の開発</h4>";
    body += "<input type='text' id='drinkName' placeholder='商品名'>";
    body += "<input type='number' id='drinkPrice' placeholder='価格'>";
    body += "<button id='addDrink'>追加</button>";
    body += "<br>";

    // 既存ドリンクの情報を変更する
    body += "<h4>既存商品の改革</h4>";
    if(allDrinkOptions){
      body += "<select name='changedProduct'>";
      body += allDrinkOptions;
      body += "<input type='text' id='changeProductName' placeholder='変更名称'>";
      body += "<input type='number' id='changeProductPrice' placeholder='変更価格'>";
      body += "<button id='changeProduct'>変更</button>";
      body += "</select>";
      body += "<br>";
    }else{
      body += "ドリンクが登録されていません。";
    }

    body += "<button id='back_vm_view'>閉じる</button></br>";
    body += "<div id='info2'>" + info2 + "</div>";
    $("#view3").html(body);
		});
}
