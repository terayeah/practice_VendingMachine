$(document).on('click','.selectedVm', function() {
  displayVmView($(this).val());
});

$(document).on('click', '#back_vm_top', function() {
  $.removeCookie('choicedVmId');
  displayVmTopView();
  $("#info").html("");
});

$(document).on('click', '#putCash', function() {
  $.post("/lessons/a_vending_machine/server/putCash.php",
    { "userEncrypt": $.cookie('userEncrypt'),
      "choicedVmId": $.cookie('choicedVmId'),
      "howMuchCash": $('#howMuchCash').val() },
    function(){
      displayVmView($.cookie('choicedVmId'));
    });
});

$(document).on('click', '#charge', function() {
  $.post("/lessons/a_vending_machine/server/charge.php",
    { "userEncrypt": $.cookie('userEncrypt'),
      "choicedVmId": $.cookie('choicedVmId'),
      "howMuchSuica": $('#howMuchSuica').val() },
    function(){
      displayVmView($.cookie('choicedVmId'));
    });
});

$(document).on('click', '#backChange', function() {
  $.post("/lessons/a_vending_machine/server/backChange.php",
    { "userEncrypt": $.cookie('userEncrypt'),
      "choicedVmId": $.cookie('choicedVmId') },
    function(){
      displayVmView($.cookie('choicedVmId'));
    });
});

$(document).on('click', '.selectedDrink', function() {
  $.post("/lessons/a_vending_machine/server/selectedDrink.php",
    { "userEncrypt": $.cookie('userEncrypt'),
      "choicedVmId": $.cookie('choicedVmId'),
      "selectedDrink": $(this).val()},
    function(data){
      displayVmView($.cookie('choicedVmId'));
      $("#info").html(data);
    });
});

$(document).on('click', '#buySuica', function() {
  $.post("/lessons/a_vending_machine/server/buySuica.php",
    { "userEncrypt": $.cookie('userEncrypt'),
      "choicedVmId": $.cookie('choicedVmId') },
    function(data){
      displayVmView($.cookie('choicedVmId'));
      $("#info").html(data);
    });
});

$(document).on('click', '#addVm', function() {
  window.location.href = '/lessons/a_vending_machine/client/setting.html'
});

$(document).on('click', '#setDrink', function() {
  window.location.href = '/lessons/a_vending_machine/client/setting.html'
});

$(document).on('click', '#logout', function() {
  $.removeCookie("userEncrypt");
  $.removeCookie("choicedVmId");
  window.location.href = '/lessons/a_vending_machine/client/top.html'
});

function displayVmTopView(){
  $.post("/lessons/a_vending_machine/server/vmtop.php",
        { "userEncrypt": $.cookie('userEncrypt') },
        function(data){
          $("#view").html(data);
        });
}

function displayVmView(vmId){
  $.post("/lessons/a_vending_machine/server/vmview.php",
		{ "userEncrypt": $.cookie('userEncrypt'),
      "selectedVmId": vmId },
		function(data){
      $.cookie('choicedVmId', data.vmId);
      $("#view").html(data.html);
		});
}
