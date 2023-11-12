<?php 
include('db_connect.php');

$users = $conn->query("SELECT * FROM users"); 
$selected_user = isset($_GET['user_id']) ? $_GET['user_id'] : '';

if (isset($_GET['id'])) {
    $qry = $conn->query("SELECT * FROM files where id=".$_GET['id']);
    if ($qry->num_rows > 0) {
        foreach ($qry->fetch_array() as $k => $v) {
            $meta[$k] = $v;
        }
    }
}
?>

<div class="container-fluid">
    <form action="" id="manage-files">
        <input type="hidden" name="id" value="<?php echo isset($_GET['id']) ? $_GET['id'] :'' ?>">
        <input type="hidden" name="folder_id" value="<?php echo isset($_GET['fid']) ? $_GET['fid'] :'' ?>">
        
        <?php if (!isset($_GET['id']) || empty($_GET['id'])): ?>
        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text">Upload</span>
            </div>
            <div class="custom-file">
                <input type="file" class="custom-file-input" name="upload" id="upload" onchange="displayname(this,$(this))">
                <label class="custom-file-label" for="upload">Choose file</label>
            </div>
        </div>
        <?php endif; ?>

        <div class="form-group">
            <label for="name" class="control-label">
                <?php $name = isset($meta['name']) ? $meta['name'] : ''; ?>
                <span style="font-weight: bold; text-transform: uppercase; font-size: 1.5em;"><?php echo $name; ?></span>
            </label>
        </div>
        <div class="form-group">
            <label for="" class="control-label">Description</label>
            <textarea name="description" id="" cols="30" rows="10" class="form-control"><?php echo isset($meta['description']) ? $meta['description'] :'' ?></textarea>
        </div>
        <div class="form-group" id="userDropdown">
            <label for="user_id" class a="control-label">Share with User:</label>
            <select name="user_id" id="user_id" class="form-control">
                <option value="">Select a user</option>
                <option value="0" <?php echo ($selected_user == '0') ? 'selected' : ''; ?>>All Users</option>
                <?php while ($user = $users->fetch_assoc()): ?>
                    <option value="<?php echo $user['id']; ?>" <?php echo ($selected_user == $user['id']) ? 'selected' : ''; ?>><?php echo $user['username']; ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="form-group" id="msg"></div>
    </form>
</div>
<script>
    function toggleUserDropdown() {
        var userDropdown = document.getElementById("userDropdown");
        var isPublicCheckbox = document.getElementById("is_public");

        if (isPublicCheckbox.checked) {
            userDropdown.style.display = "none";
            // Set is_public to 1 when sharing with all users
            $("#user_id").val(""); // Clear the selected user
        } else {
            userDropdown.style.display = "block";
            // Set is_public to the selected user's ID
        }
    }
    $(document).ready(function(){
        $('#manage-files').submit(function(e){
            e.preventDefault();
            start_load();
            $('#msg').html('');
            var formData = new FormData($(this)[0]);

            // Get the selected user's ID or the value for "All Users"
            var selectedUser = $('#user_id').val();

            // Set is_public based on the selected user or "All Users"
            formData.set('is_public', selectedUser);

            $.ajax({
                url: 'ajax.php?action=share_files',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                method: 'POST',
                type: 'POST',
                success: function(resp){
                    if (typeof resp != undefined){
                        resp = JSON.parse(resp);
                        if (resp.status == 1){
                            alert_toast("File succesfully shared.", 'success')
                            setTimeout(function(){
                                location.reload()
                            }, 1500)
                        } else {
                            $('#msg').html('<div class="alert alert-danger">'+resp.msg+'</div>')
                            end_load()
                        }
                    }
                }
            });
        });
    });






    function displayname(input, _this) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                _this.siblings('label').html(input.files[0]['name']);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
