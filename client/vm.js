$(document).on('click','.selectedVm', function() {
  $.post("/lessons/a_vending_machine/server/vmview.php",
		{ "userEncrypt": $.cookie('userEncrypt'),
      "selectedVmId": $(this).val() },
		function(data){
      $("#view").html(data);
		});
});

$(document).on('click', '#back_vm_top', function() {
            displayVmTopView()
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
