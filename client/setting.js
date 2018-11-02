$(document).on('click', '#add_vm', function() {
  $.post("/lessons/a_vending_machine/server/addVm.php",
    { "userEncrypt": $.cookie('userEncrypt'),
      "vmType": $('[name = vmType]').val(),
      "vmName": $('#vmName').val() },
    function(data){
      displayAddVmView();
      $("#info").html(data);
    });
});

$(document).on('click', '#addExistingDrink', function() {
  $.post("/lessons/a_vending_machine/server/addDrink.php",
    { "userEncrypt": $.cookie('userEncrypt'),
      "choicedVmId": $.cookie('choicedVmId'),
      "addedExistingDrink": $('[name = addedExistingDrink]').val(),
      "addDrinkCount": $('#addDrinkCount').val() },
    function(data){
      displaySetDrinkView($.cookie('choicedVmId'));
      $("#info").html(data);
    });
});

$(document).on('click', '#changeDrink', function() {
  $.post("/lessons/a_vending_machine/server/changeDrink.php",
    { "userEncrypt": $.cookie('userEncrypt'),
      "choicedVmId": $.cookie('choicedVmId'),
      "changedDrink": $('[name = changedDrink]').val(),
      "changeDrinkStock": $('#changeDrinkStock').val() },
    function(data){
      displaySetDrinkView($.cookie('choicedVmId'));
      $("#info").html(data);
    });
});

$(document).on('click', '#deleteDrink', function() {
  $.post("/lessons/a_vending_machine/server/deleteDrink.php",
    { "userEncrypt": $.cookie('userEncrypt'),
      "choicedVmId": $.cookie('choicedVmId'),
      "deletedDrink": $('[name = deletedDrink]').val() },
    function(data){
      displaySetDrinkView($.cookie('choicedVmId'));
      $("#info").html(data);
    });
});

$(document).on('click', '#addDrink', function() {
  $.post("/lessons/a_vending_machine/server/makeProduct.php",
    { "userEncrypt": $.cookie('userEncrypt'),
      "choicedVmId": $.cookie('choicedVmId'),
      "drinkName": $('#drinkName').val(),
      "drinkPrice": $('#drinkPrice').val() },
    function(data){
      displaySetDrinkView($.cookie('choicedVmId'));
      $("#info").html(data);
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
      displaySetDrinkView($.cookie('choicedVmId'));
      $("#info").html(data);
    });
});

$(document).on('click', '#back_vm_top', function() {
  window.location.href = '/lessons/a_vending_machine/client/vm.html'
});

$(document).on('click', '#back_vm_view', function() {
  window.location.href = '/lessons/a_vending_machine/client/vm.html'
});

function displayAddVmView(){
  $.post("/lessons/a_vending_machine/server/setVm.php",
        { },
        function(data){
          $("#view").html(data);
        });
}

function displaySetDrinkView(vmId){
  $.post("/lessons/a_vending_machine/server/setDrink.php",
		{ "userEncrypt": $.cookie('userEncrypt'),
      "selectedVmId": vmId },
		function(data){
      $.cookie('choicedVmId', data.vmId);
      $("#view").html(data.html);
		});
}
