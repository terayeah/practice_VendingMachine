$(document).on('click','.selectedVm', function() {
  displayVmView($(this).val());
});

$(document).on('click', '#back_vm_top', function() {
  $.removeCookie('choicedVmId');
  displayVmTopView();
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

$(document).on('click', '#logout', function() {
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
