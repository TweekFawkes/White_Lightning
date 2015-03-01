function delete_rows(tasking_id, random_string){
	var x = confirm("Are you sure you want to remove tasking #" + tasking_id);
	if (x){
		$.ajax({
		type: "POST",
		url: 'includes/taskings_delete_rows.php',
		data:{action:random_string},
		success:function(html) {
			location.replace("tasking.php");
		}
		});
	}
}