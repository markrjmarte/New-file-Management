
<head>
	<link rel="stylesheet" href="uicss/style1.css">
	<link rel="stylesheet" href="uicss/style2.css">
	<link rel="stylesheet" href="uicss/style5.css">
</head>
<style>
	.avatar-image {
    width: 200px; 
    height: 200px;
}
.slsulogo {
    width: 70%;
    margin: 20px 45px 0px;
	filter: drop-shadow(0px 0px 2px var(--blue));
}
.notification-image {
    width: 50px; 
    height: 50px;
}
.contact-avatar-image {
    width: 200px; 
    height: 200px;
}

.card-default .card-header-bg {
    height: 10px;
    border-top-left-radius: 24px;
    border-top-right-radius: 24px;
}

.card-profile .card-profile-body {
    flex-direction: row-reverse;
    justify-content: space-evenly;;
    padding: 2rem;
}
#content main .table-data > div {
    background: none;
}
#content main .table-data .order{
	box-shadow: none;
}
#content main .table-data .order{
	box-shadow: none;
}

</style>
<?php 
include('db_connect.php');
if(isset($_GET['id'])){
	$user = $conn->query("SELECT * FROM users where id =".$_GET['id']);
	foreach($user->fetch_array() as $k =>$v){
		$meta[$k] = $v;
	}
}
?>
	<!-- SIDEBAR -->
	<section id="sidebar">
		<img src="assets/img/avatar.png" class="slsulogo">
		<ul class="side-menu top">
			<li>
				<a href="index.php?page=dashboard">
					<i class='bx bxs-dashboard'></i>
					<span class="text">Dashboard</span>
				</a>
			</li>

			<?php if($_SESSION['login_type'] == 2): ?>
				<li>
					<a href="index.php?page=usersTab/Announcement">
						<i class='bx bxs-message-dots' ></i>
						<span class="text">Announcement</span>
					</a>
				</li>
			<?php endif; ?>
			</li>

			<li class="inactive">
				<a>
					<span class="text" style = "padding-left: 10px; color: var(--dark-grey);">File Manager</span>
				</a>
            </li>
			<li>
				<a href="index.php?page=myfiles">
					<i class='bx bxs-shopping-bag-alt' ></i>
					<span class="text">My Files</span>
				</a>
			</li>
			<li>
				<a href="index.php?page=sharedfiles">
					<i class='bx bxs-share-alt'></i>
					<span class="text">Shared Files</span>
				</a>
			</li>
			<?php if($_SESSION['login_type'] == 1): ?>
			
			<li class="inactive">
				<a>
					<span class="text" style = "padding-left: 10px; color: var(--dark-grey);">Faculty</span>
				</a>
            </li>
			
			<li>
				<a href="index.php?page=usersTab/Announcement">
					<i class='bx bxs-message-dots' ></i>
					<span class="text">Announcement</span>
				</a>
			</li>

			<li class = "active">
				<a href="index.php?page=faculties">
					<i class='bx bxs-group' ></i>
					<span class="text">Manage List</span>
				</a>
			</li>

			<li>
				<a href="index.php?page=logs">
					<i class='bx bxs-directions' ></i>
					<span class="text">Logs</span>
				</a>
			</li>

			<?php endif; ?>
			
		</ul>
		<ul class="side-menu">
			<li>
				<a href="index.php?page=pages/settings/user-settings">
					<i class='bx bxs-cog' ></i>
					<span class="text">Profile</span>
				</a>
			</li>
			<li>
				<a href="ajax.php?action=logout" class="logout">
					<i class='bx bxs-log-out-circle' ></i>
					<span class="text">Logout</span>
				</a>
			</li>
		</ul>
	</section>
	<!-- SIDEBAR -->



	<!-- CONTENT -->
	<section id="content">
		<!-- NAVBAR -->
		<nav>
		<i class='bx bx-menu icon' ></i>
			<!-- Notification -->
			<li class="custom-dropdown">
                    <button class="notify-toggler custom-dropdown-toggler">
                      <i class="bx bxs-bell icon"></i>
					  <?php
						// Fetch and display the notification count
						$notificationCountQuery = $conn->query("SELECT COUNT(*) AS notification_count FROM notification  WHERE by_who != '".$_SESSION['login_id']."' 
						AND status = 0 AND (is_public >= 0 OR is_public = '".$_SESSION['login_id']."') ");
						$notificationCountResult = $notificationCountQuery->fetch_assoc();
						$notificationCount = $notificationCountResult['notification_count'];
						?>
						<span class="badge badge-xs rounded-circle"><?php echo $notificationCount; ?></span>

                    </button>
                    <div class="dropdown-notify">
                      	<div class="" data-simplebar style="max-height: 325px;">
							<div class="tab-content" id="myTabContent">

								<div class="tab-pane fade show active" id="all" role="tabpanel" aria-labelledby="all-tabs">

									<?php
									// Fetch and display notifications
									$notificationsQuery = $conn->query("SELECT * FROM notification WHERE by_who != '".$_SESSION['login_id']."' AND 
									status = 0 AND (is_public >= 0 OR is_public = '".$_SESSION['login_id']."') ORDER BY date_updated DESC");
									
									while ($notification = $notificationsQuery->fetch_assoc()) {
										// name of announcer (by_who)
										$truncated_message = strlen($notification['description']) > 15 ? substr($notification['description'], 0, 15) . "..." : $notification['description'];
										$announcerId = $notification['by_who'];
										$announcerQuery = $conn->query("SELECT name, profile_image FROM users WHERE id = '$announcerId'");
										$announcer = $announcerQuery->fetch_assoc();

										echo '<div class="media media-sm p-4 mb-0">';
										echo '<div class="media-sm-wrapper">';
										echo '<img src="assets/img/profiles/' . $announcer['profile_image'] . '"
										class="img-fluid rounded-circle d-inline-block notification-image">';
										echo '</div>';
										echo '<div class="media-body">';
										// Determine the link based on the "kind" column
										$notificationLink = ($notification['kind'] == 1) ? 
										'index.php?page=sharedfiles&notification_id=' . $notification['id'] : 
										'index.php?page=usersTab/Announcement&notification_id=' . $notification['id'];

										echo '<a href="' . $notificationLink . '">';

										
										echo '<span class="title mb-0">' . $announcer['name'] . '</span>';
										echo '<span class="discribe">' . $truncated_message. '</span>';
										echo '<span class="time"><time>' . $notification['date_updated'] . '</time></span>';

										echo '</a>';
										echo '</div>';
										echo '</div>';
									}
									?>
								</div>
							</div>
                     	</div>
                    </div>
            	</li>
				<!-- Notification -->
			<a  href="index.php?page=pages/settings/user-settings" class="profile">
				<img src="assets/img/profiles/<?php echo $_SESSION['login_profile_image'] ?>">
			</a>
		</nav>
		<!-- NAVBAR -->

		<!-- MAIN -->
		<main>
			<div class="head-title" style = "justify-content: space-between;">
				<div class="pagetitle">
					<h1 style = "font-weight: 600;">Add faculty</h1>
					<ol class="breadcrumb"><li class="breadcrumb-item"><a href="index.php?page=faculties"> Home</a></li> 
					<li class="breadcrumb-item"><a style= "color: var(--dark-grey);"> Add faculty </a></li></ol>
				</div><!-- End Page Title -->
				<div class="toast" id="alert_toast" role="alert" aria-live="assertive" aria-atomic="true">
					<div class="toast-body text-white">
					</div>
				</div>
			</div>

			<form action="" id="manage-user">
				<input type="hidden" name="id" value="<?php echo isset($meta['id']) ? $meta['id'] : ''; ?>">
				<div class="row mb-2" style = "margin-top: 24px;">

					<div class="col-lg-4 col-md-5 col-sm-4">
						<div class="main-box clearfix">
							<div class="form-group">
								<div class="preview" style ="    width: 93%; height: 440px; margin-left: 15px; margin-top: 40px;">
									<center><img src="assets/img/profiles/<?php echo isset($meta['profile_image']) ? $meta['profile_image']: '' ?>" id="img" 
									class="profile-img img-responsive center-block " style ="width: 93%; border-radius: 20px; height: 440px;"></center>
								</div>
									<center><input type="file" name="profile_image" id="profile_image" class="form-control-file" style="display: none;">
            						<a style="color: var(--blue);" href="javascript:void(0);" id="toggleFileInput" class="btn btn-link">Change Profile Image</a></center>
								</div>
							</div>
						</div>
						<div class="col-lg-8 col-md-8 col-sm-8">
							<div class="card card-default">
								<div class="card-body">
									<div class="row mb-2" style = "padding-top: 70px;">
										<div class="col-lg-6">
											<div class="form-group">
												<label for="firstName">Name:</label>
												<input type="text" name="name" id="name" class="form-control" style="color: var(--blue); padding-left: 10%;" value="<?php echo isset($meta['name']) ? $meta['name']: '' ?>">
											</div>
										</div>

										<div class="col-lg-6">
											<div class="form-group">
												<label for="UserType">User type:</label>
												<select style="color: var(--blue); padding-left: 10%;" name="type" id="type" class="form-control">
													<option value="1" <?php echo isset($meta['type']) && $meta['type'] == 2 ? 'selected' : ''; ?>>User</option>
													<option value="2" <?php echo isset($meta['type']) && $meta['type'] == 1 ? 'selected' : ''; ?>>Administrator</option>
												</select>
											</div>
										</div>

										<div class="col-lg-6">
											<div class="form-group mb-4">
												<label for="userName">Username:</label>
																					<input type="text" name="username" id="username" class="form-control" style="color: var(--blue); padding-left: 10%;" value="<?php echo isset($meta['username']) ? $meta['username']: '' ?>">
																			</div>
																		</div>

																		<div class="col-lg-6">
																			<div class="form-group mb-4">
																				<label for="email">Email:</label>
																				<input type="text" name="email" id="email" class="form-control" style="color: var(--blue); padding-left: 10%;" value="<?php echo isset($meta['email']) ? $meta['email']: '' ?>">
																			</div>
																		</div>

																		<div class="col-lg-6">
																			<div class="form-group mb-4">
																				<label for="password">Password:</label>
																				<input type="text" name="password" id="password" class="form-control" style="color: var(--blue); padding-left: 10%;" value="<?php echo isset($meta['password']) ? $meta['password']: '' ?>">
																			</div>
																		</div>

																		<div class="col-lg-6">
																			<div class="form-group mb-4">
																				<label for="phone">Phone No.:</label>
																				<input type="text" name="phone" id="phone"class="form-control" style="color: var(--blue); padding-left: 10%;" value="<?php echo isset($meta['phone']) ? $meta['phone']: '' ?>">
																			</div>
																		</div>

																		<div class="col-lg-6">
																			<div class="form-group mb-4">
																				<label for="position">Position:</label>
																				<input type="text" name="job" id="job" class="form-control" style="color: var(--blue); padding-left: 10%;" value="<?php echo isset($meta['job']) ? $meta['job']: '' ?>">
																			</div>
																		</div>

																		<div class="col-lg-6">
																			<div class="form-group mb-4">
																				<label for="adress">Address:</label>
																				<input type="text" name="adress" id="adress" class="form-control" style="color: var(--blue); padding-left: 10%;" value="<?php echo isset($meta['adress']) ? $meta['adress']: '' ?>">
																			</div>
																		</div>
																	</div>
																	<div class="modal-footer px-4">
																		<button type="submit" class="btn btn-primary btn-pill">Save Contact</button>
																	</div>
																</div>
															</div>
														</div>
													</form>
				

			<!-- ======= Footer ======= -->
			<footer id="footer" class="mt-auto footer">
			<div class="copyright">
			&copy; Copyright <strong><span>File Repository</span></strong>.
			</div>
			<div class="credits">
				Designed by <a href="https://www.facebook.com/">Bright Group</a>
			</div>
			</footer><!-- End Footer -->
			
		</main>
		<!-- MAIN -->
	</section>
	<!-- CONTENT -->
	<script src="https://unpkg.com/hotkeys-js/dist/hotkeys.min.js"></script>
	<script src="plugins/simplebar/simplebar.min.js"></script>
	<script src="js/mono.js"></script>
	<script src="plugins/nprogress/nprogress.js"></script>
	<script src="js/custom.js"></script>
	<script src="script.js"></script>

	<script>

		document.getElementById("toggleFileInput").addEventListener("click", function() {
			document.getElementById("profile_image").click();
		});

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
			formData.append('action', 'save_user'); 

			$.ajax({
				url: 'ajax.php?action=save_user',
				method: 'POST',
				data: formData,
				processData: false,
				contentType: false,
				success: function(resp) {
					if(resp == 1) {
						alert_toast("New user successfully created", 'success');
						setTimeout(function(){
							location.reload();
						}, 1500);
					}
				}
			});
		});

	</script>

	<script>

		//viewfile 
			$('.file-item').dblclick(function(e){
				e.preventDefault()
				if($(this).find('input.rename_file').is(':visible') == true)
				return false;
				var fileId = $(this).attr('data-id');
				window.open('display_file.php?id=' + fileId);
				console.log('display_file.php?id=' + fileId);
			})

			$(document).keyup(function(e){

			if(e.keyCode === 27){
				$("div.custom-menu").hide();
			$('#file-item').removeClass('active')

			}

		});

		
	</script>

