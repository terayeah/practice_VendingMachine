$(document).on('click', '#back_vm_top', function() {
  window.location.href = '/lessons/a_vending_machine/client/vm.html'
});

$(document).on('click', '#back_vm_view', function() {
  console.log("aa");
  window.location.href = '/lessons/a_vending_machine/client/vm.html'
});

function displayAddVmView(){
  $.post("/lessons/a_vending_machine/server/addVm.php",
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
