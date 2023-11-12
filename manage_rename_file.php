<?php 
include('db_connect.php');
if(isset($_GET['id'])){
$qry = $conn->query("SELECT * FROM files where id=".$_GET['id']);
	if($qry->num_rows > 0){
		foreach($qry->fetch_array() as $k => $v){
			$meta[$k] = $v;
		}
	}
}
$folder_parent = isset($_GET['fid']) ? $_GET['fid'] : 0;
?>
<div class="container-fluid">
	<form action="" id="manage-file">
		<input type="hidden" name="id" value="<?php echo isset($_GET['id']) ? $_GET['id'] :'' ?>">
		<input type="hidden" name="parent_id" value="<?php echo isset($_GET['fid']) ? $_GET['fid'] :'' ?>">
		<div class="form-group">
			<label for="name" class="control-label">Filename</label>
			<input type="text" name="name" id="name" value="<?php echo isset($meta['name']) ? $meta['name'] :'' ?>" class="form-control">
		</div>
		<div class="form-group" id="msg"></div>

	</form>
</div>
<script>
	$(document).ready(function () {
    $('#manage-file').submit(function (e) {
        e.preventDefault();
        start_load();
        $('#msg').html('');
        var fileId = $('input[name="id"]').val();
        var newName = $('#name').val();
        var fileType = '<?php echo isset($meta['type']) ? $meta['type'] : '' ?>'; // Define the fileType
        $.ajax({
            url: 'ajax.php?action=file_rename',
            method: 'POST',
            data: {
                id: fileId,
                name: newName,
                type: fileType, // Send fileType
                folder_id: '<?php echo $folder_parent ?>'
            },
            success: function (resp) {
                try {
                    resp = JSON.parse(resp);
                    if (resp.status == 1) {
                        alert_toast("Rename was successful.", 'success');
                        setTimeout(function () {
                            location.reload();
                        }, 1500);
                    } else {
                        $('#msg').html('<div class="alert alert-danger">' + resp.msg + '</div>');
                    }
                } catch (e) {
                    console.error("Error parsing JSON response:", e);
                } finally {
                    end_load();
                }
            }
        });
    });
});



</script>