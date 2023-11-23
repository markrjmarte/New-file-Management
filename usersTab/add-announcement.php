
<head>
	<link rel="stylesheet" href="uicss/style1.css">
	<link rel="stylesheet" href="uicss/style2.css">
	<link rel="stylesheet" href="uicss/style5.css">
	<link rel="stylesheet" href="uicss/style4.css">
</head>
<style>
	.avatar-image {
    width: 100px; 
    height: 100px;
}
.slsulogo {
    top: 10%;
    left: 30%;
    width: 65%;
    margin: 30px 45px 0px;
    filter: drop-shadow(0px 0px 2px var(--blue));
    position: absolute;
    transform: translate(-50%, -50%);
    opacity: 0;
    transition: opacity 1s ease-in-out;
}
.notification-image {
    width: 50px; 
    height: 50px;
}
.contact-avatar-image {
    width: 150px; 
    height: 150px;
}
</style>
<?php 
include('db_connect.php');
if(isset($_SESSION['login_id'])){
	$user_id = $_SESSION['login_id'];
	$loginUsername = $_SESSION['login_username'];
	$user = $conn->query("SELECT * FROM users WHERE id = $user_id");
	if($user && $user->num_rows > 0) {
		$meta = $user->fetch_assoc();
	}
}

$users = $conn->query("SELECT * FROM users WHERE id != $user_id");
$selected_user = isset($_GET['user_id']) ? $_GET['user_id'] : ''; 
$announcement_query = $conn->query("SELECT * FROM announcement order by Date_uploaded DESC;");

?>
	<!-- SIDEBAR -->
	<section id="sidebar">
		<div>
			<img src="assets/img/avatar.png" class="slsulogo">
			<img src="assets/img/avatar2.png" class="slsulogo">
		</div>
		<ul class="side-menu top" style = "margin-top: 240px;">
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
			
			<li class="active">
				<a href="index.php?page=usersTab/Announcement">
					<i class='bx bxs-message-dots' ></i>
					<span class="text">Announcement</span>
				</a>
			</li>

			<li>
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
					<h1 style = "font-weight: 600;">Create Announcement</h1>
					<ol class="breadcrumb"><li class="breadcrumb-item"><a href="index.php?page=usersTab/Announcement"> Home</a></li> 
					<li class="breadcrumb-item"><a style= "color: var(--dark-grey);"> New announcement </a></ol>
				</div><!-- End Page Title -->
				<div class="toast" id="alert_toast" role="alert" aria-live="assertive" aria-atomic="true">
					<div class="toast-body text-white">
					</div>
				</div>
			</div>
			
				<form action="" id="add-announcement" style = "margin-top: 24px;">
					<div class="card card-default">
						<div class="card-body">

							<div class="form-group">
								<label for="firstName">Title:</label>
								<input name="title" id="title" type="text" class="form-control"  style = "padding-left: 10%;" required>
							</div>
							<div class="form-group">
								<label for="" class="control-label">Description</label>
								<textarea name="description" id="" cols="30" rows="10" class="form-control" required></textarea>
							</div>
							<div class="row mb-2">
								<div class= "col-lg-6 mb-3">
									<div class="custom-file">
										<input type="file" class="custom-file-input" name="upload" id="upload" onchange="displayname(this,$(this))">
										<label class="custom-file-label" for="upload">Choose file</label>
									</div>
								</div>
								<div class= "col-lg-6">
									<div class="form-group" id="userDropdown">
										<select name="user_id" id="user_id" class="form-control" required>
												<option value="">Select a user</option>
												<option value="0" <?php echo ($selected_user == '0') ? 'selected' : ''; ?>>All Users</option>
												<?php while ($user = $users->fetch_assoc()): ?>
													<option value="<?php echo $user['id']; ?>" <?php echo ($selected_user == $user['id']) ? 'selected' : ''; ?>><?php echo $user['username']; ?></option>
												<?php endwhile; ?>
										</select>
									</div>
								</div>
							</div>
						</div>
						<div class="modal-footer px-4">
							<button type="submit" class="btn btn-primary btn-pill">Post</button>
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

		function displayname(input, _this) {
			if (input.files && input.files[0]) {
				var reader = new FileReader();
				reader.onload = function (e) {
					_this.siblings('label').html(input.files[0]['name']);
				}
				reader.readAsDataURL(input.files[0]);
			}
		}

		$('#add-announcement').submit(function(e){
			e.preventDefault();
			start_load();

			var formData = new FormData(this);
			formData.append('action', 'add_announcement'); 

			$.ajax({
				url: 'ajax.php?action=add_announcement',
				method: 'POST',
				data: formData,
				processData: false,
				contentType: false,
				success: function(resp) {
					if(resp == 1) {
						alert_toast("Announcement successfully posted", 'success');
						setTimeout(function(){
							location.reload();
						}, 1500);
						
					}
				}
			});
		});

	</script>
	<script>
	// JavaScript or jQuery to toggle avatar visibility
		document.addEventListener("DOMContentLoaded", function () {
		var avatars = document.querySelectorAll(".slsulogo");
		var index = 0;
		var intervalTime = 3000; // Time between avatar changes in milliseconds (adjust as needed)

		function toggleAvatar() {
		avatars[index].style.opacity = 0; // Hide the current avatar
		index = (index + 1) % avatars.length; // Move to the next avatar or back to the start if reached the end
		avatars[index].style.opacity = 1; // Show the next avatar
		}

		// Initial visibility setup
		avatars[0].style.opacity = 1; // Show the first avatar

		setInterval(toggleAvatar, intervalTime); // Start the interval to toggle avatars
	});
	</script>
	