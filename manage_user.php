<?php 
include('db_connect.php');
if(isset($_GET['id'])){
$user = $conn->query("SELECT * FROM users where id =".$_GET['id']);
foreach($user->fetch_array() as $k =>$v){
	$meta[$k] = $v;
}
}
?>

<div class="container-fluid">
	<form action="" id="manage-user">
		<input type="hidden" name="id" value="<?php echo isset($meta['id']) ? $meta['id']: '' ?>">
		<div class="row">
			<div class="form-group">
				<label for="profile_image">Profile Image</label>
				<input type="file" name="profile_image" id="profile_image" class="form-control-file">
				<div class = "preview">
					<img src="" id = "img" alt = "Preview" style = "width: 100%; height: 100%">
				</div>
			</div>
            
				<div class="col-md-6">
				<div class="form-group">
					<label for="name">Name</label>
					<input type="text" name="name" id="name" class="form-control" value="<?php echo isset($meta['name']) ? $meta['name']: '' ?>" required>
				</div>

				<div class="form-group">
					<label for="username">Username</label>
					<input type="text" name="username" id="username" class="form-control" value="<?php echo isset($meta['username']) ? $meta['username']: '' ?>" required>
				</div>
				

				<div class="form-group">
					<label for="adress">Address</label>
					<input type="text" name="adress" id="adress" class="form-control" value="<?php echo isset($meta['adress']) ? $meta['adress']: '' ?>" required>
				</div>

				<div class="form-group">
					<label for="type">User Type</label>
					<select name="type" id="type" class="custom-select">
						<option value="1" <?php echo isset($meta['type']) && $meta['type'] == 1 ? 'selected': '' ?>>Admin</option>
						<option value="2" <?php echo isset($meta['type']) && $meta['type'] == 2 ? 'selected': '' ?>>User</option>
					</select>
				</div>

			</div>
			<div class="col-md-6">

				<div class="form-group">
					<label for="job">Job</label>
					<input type="text" name="job" id="job" class="form-control" value="<?php echo isset($meta['job']) ? $meta['job']: '' ?>" required>
				</div>

				<div class="form-group">
					<label for="password">Password</label>
					<input type="password" name="password" id="password" class="form-control" value="<?php echo isset($meta['password']) ? $meta['password']: '' ?>" required>
				</div>

				<div class="form-group">
					<label for="phone">Phone</label>
					<input type="text" name="phone" id="phone" class="form-control" value="<?php echo isset($meta['phone']) ? $meta['phone']: '' ?>" required>
				</div>

				<div class="form-group">
					<label for="email">Email</label>
					<input type="text" name="email" id="email" class="form-control" value="<?php echo isset($meta['email']) ? $meta['email']: '' ?>" required>
				</div>

			</div>
		</div>
	</form>
</div>
<script>

	profile_image.onchange = evt => {
        const [file] = profile_image.files
        if (file) {
          img.src = URL.createObjectURL(file)
        }
      }

	$('#manage-user').submit(function(e){
    e.preventDefault();
    start_load();

    var formData = new FormData(this);

    $.ajax({
        url: 'ajax.php?action=save_user',
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(resp) {
            if(resp == 1) {
                alert_toast("Data successfully saved", 'success');
                setTimeout(function(){
                    location.reload();
                }, 1500);
            }
        }
    });
});
</script>