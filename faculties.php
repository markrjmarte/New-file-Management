
<head>
	<link rel="stylesheet" href="uicss/style1.css">
	<link rel="stylesheet" href="uicss/style2.css">
	<link rel="stylesheet" href="uicss/style4.css">
</head>
<style>
.avatar-image {
    width: 100px; 
    height: 100px;
}

.notification-image {
    width: 50px; 
    height: 50px;
}
.slsulogo {
    padding-top: 200px;
    left: 30%;
    width: 65%;
    margin: 30px 50px 0px;
    filter: drop-shadow(0px 0px 2px var(--blue));
    position: absolute;
    transform: translate(-50%, -50%);
    opacity: 0;
    transition: opacity 1s ease-in-out;
}
.contact-avatar-image {
    width: 150px; 
    height: 150px;
}
</style>
<?php 
include('db_connect.php');
if (isset($_GET['id'])) {
    $user = $conn->query("SELECT * FROM users where id =" . $_GET['id']);
    foreach ($user->fetch_array() as $k => $v) {
        $meta[$k] = $v;
    }
}
?>
	<!-- SIDEBAR -->
	<section id="sidebar">
		<div>
			<img src="assets/img/avatar.png" class="slsulogo">
			<img src="assets/img/avatar2.png" class="slsulogo">
		</div>
		<ul class="side-menu top" style = "margin-top: 260px;">
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

			<li class="active">
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
					<h1 style = "font-weight: 600;">Manage faculty</h1>
					<ol class="breadcrumb"><li class="breadcrumb-item"><a style= "color: var(--dark-grey);"> Home </a></li>
					<li class="breadcrumb-item"><a href="index.php?page=usersTab/add_user"> Add faculty </a></li></ol>
				</div><!-- End Page Title -->
				<div class="toast" id="alert_toast" role="alert" aria-live="assertive" aria-atomic="true">
					<div class="toast-body text-white">
					</div>
				</div>
			</div>

			<!-- Folder Display -->
			<ul class="box-info">
				
			</ul>
			<!-- END Folder display -->
			
			<!-- User field -->
			<div class="row">
				<?php
				$users = $conn->query("SELECT users.*, 
					(SELECT COUNT(*) FROM files WHERE user_id = users.id) AS total_personal_files,
					(SELECT COUNT(*) FROM folders WHERE user_id = users.id) AS total_folders,
					(SELECT COUNT(*) FROM files WHERE user_id = users.id AND is_public >= 0 ) AS total_shared_files
					FROM users ORDER BY id DESC;");
				while ($row = $users->fetch_assoc()):
				?>
				<div style = "margin-bottom: 10px;" class="col-lg-6 col-xl-4 col-xxl-3" data-id="<?php echo $row['id'] ?>">
					<div class="card card-default mt-7">
						<div class="card-body text-center">
							<a class="d-block mb-2" href="javascript:void(0)" data-toggle="modal" data-target="#modal-contact"
								data-shares="<?php echo $row['total_shared_files'] ?>" 
								data-files="<?php echo $row['total_personal_files'] ?>" 
								data-folders="<?php echo $row['total_folders'] ?>" 

								data-id="<?php echo $row['id'] ?>" 
								data-email="<?php echo $row['email'] ?>" 
								data-phone="<?php echo $row['phone'] ?>" 
								data-address="<?php echo $row['adress'] ?>" 
								data-job="<?php echo $row['job'] ?>"
								data-type="<?php echo ($row['type'] == 1) ? 'Administrator' : 'User' ?>">
								<div class="image mb-3 d-inline-flex mt-n8">
									<img src="assets/img/profiles/<?php echo $row['profile_image'] ?>"
										class="img-fluid rounded-circle d-inline-block avatar-image"
										alt="Avatar Image">
								</div>
								<h5 class="card-title"><?php echo $row['name'] ?></h5>
							</a>

							<div class="row justify-content-center" style = "padding-top: 5px";>
								<div class="col-4 px-1" style = " background: var(--light-blue); color: var(--blue); padding: 8px">
									<i class='bx bxs-folder-open' ></i>
									<h6><?php echo $row['total_folders'] ?></h6>
								</div>

								<div class="col-4 px-1" style = " background: var(--light-yellow); color: var(--yellow); padding: 8px">
									<i class='bx bxs-file' ></i>
									<h6><?php echo $row['total_personal_files'] ?></h6>
								</div>

								<div class="col-4 px-1" style = " background: var(--light-orange); color: var(--orange); padding: 8px">
									<i class='bx bxs-share-alt' ></i>
									<h6><?php echo $row['total_shared_files'] ?></h6>
								</div>
							</div>
						</div>
					</div>
				</div>
				<?php endwhile; ?>
			</div>
			<!-- End user field -->


			<!-- Contact Modal -->
				<div class="modal fade" id="modal-contact" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
					<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
						<div class="viewprofile_card modal-content">
							<div class="modal-header justify-content-end border-bottom-0">
								<button type="button" class="btn-view-accoount view_user " data-dismiss="modal" aria-label="Close">
										<i class="mdi mdi-account"></i>
								</button>
								<button type="button" class="btn-edit-icon delete_user " data-dismiss="modal" aria-label="Close">
										<i class="mdi mdi-delete-empty"></i>
								</button>
								<button type="button" class="btn-close-icon" data-dismiss="modal" aria-label="Close">
									<i class="mdi mdi-close"></i>
								</button>
							</div>
							<div class="modal-body pt-0">
								<div class="row no-gutters">
									<div class="viewprofile_card col-md-6" style = "padding-bottom: 20px;">
										<div class="profile-content-left px-4">
											<div class="text-center px-0 border-0">
												<div class="card-img mx-auto">
													<img class="rounded-circle contact-avatar-image" id="user-image" src="" alt="user image">
												</div>
												<div class="card-body">
													<h4 class="py-2" id="user-name"></h4>
													<p id="user-position"></p>
												</div>
												<div class="d-flex justify-content-between ">
													<div class="col-4 px-1" style = " background: var(--light-blue); color: var(--blue); padding: 8px">
															<h6 class="pb-2 " id = 'user-folders' style = " font-size: x-large; font-weight: bold;"></h6>
															<p>Foldes</p>
													</div>
														
													<div class="col-4 px-1" style = " background: var(--light-yellow); color: var(--yellow); padding: 8px">
															<h6 class="pb-2" id = 'user-files' style = " font-size: x-large; font-weight: bold;"></h6>
															<p>Files</p>
													</div>
														
													<div class="col-4 px-1" style = " background: var(--light-orange); color: var(--orange); padding: 8px">
															<h6 class="pb-2" id = 'user-shares' style = " font-size: x-large; font-weight: bold;"></h6>
															<p>Shares</p>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="col-md-6">
										<div class="contact-info px-8">
											<br>
											<h4 class="mb-1">Details</h4>
											<!-- <p class="text-dark font-weight-medium pt-4 mb-2">About</p>
											<p id="user-about"></p> -->
											<p class="text-dark font-weight-medium pt-4 mb-2">User type</p>
											<p id="user-type"></p>
											<p class="text-dark font-weight-medium pt-4 mb-2">Email</p>
											<p id="user-contact-email"></p>
											<p class="text-dark font-weight-medium pt-4 mb-2">Phone number</p>
											<p id="user-phone"></p>
											<p class="text-dark font-weight-medium pt-4 mb-2">Address</p>
											<p id="user-address" style = "padding-bottom: 20px;"></p>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			<!-- End Contact Modal -->
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
	<script src="plugins/simplebar/simplebar.min.js"></script>
	<!-- <script src="js/mono.js"></script> -->
	<script src="script.js"></script>
	<script>
	// JavaScript to update the modal content when a user is clicked
	$('.card-body a[data-toggle="modal"]').click(function () {
		var name = $(this).find('.card-title').text();

		var shares = $(this).data('shares'); 
		var files = $(this).data('files'); 
		var folders = $(this).data('folders'); 

		// var about = $(this).data('about'); 
		var id = $(this).data('id'); 
		var mail = $(this).data('email'); 
		var phone = $(this).data('phone'); 
		var address = $(this).data('address'); 
		var position = $(this).data('job');
		var type = $(this).data('type'); 
		var imageSrc = $(this).find('img').attr('src');
		
		// Update the modal content with the retrieved data
		$('#user-id').text(id);
		$('#user-name').text(name);

		$('#user-shares').text(shares);
		$('#user-files').text(files);
		$('#user-folders').text(folders);

		// $('#user-about').text(about);
		$('#user-contact-email').text(mail);
		$('#user-phone').text(phone);
		$('#user-address').text(address);
		$('#user-position').text(position);
		$('#user-type').text(type);
		$('#user-image').attr('src', imageSrc);

		$('.delete_user').click(function (e) {
			e.preventDefault();
			confirm("Are you sure to delete this user?", "delete_user", [id]);
		});

		$('.view_user').click(function () {
			view_user(id);
		});
	});

	window.confirm = function ($msg = '', $func = '', $params = []) {
        $('#confirm_modal #confirm').attr('onclick', $func + "(" + $params.join(',') + ")");
        $('#confirm_modal .modal-body').html($msg);
        $('#confirm_modal').modal('show');
    };

    function delete_user(userId) {
        $.ajax({
            url: 'ajax.php?action=delete_user',
            method: 'POST',
            data: {
                action: 'delete_user',
                id: userId
            },
            success: function (response) {
                if (response === '1') {
                    alert_toast("Data successfully saved", 'success');
                    setTimeout(function(){
                        location.reload();
                    }, 1500);
                }
            }
        });
    }

	function view_user(userId) {
        window.location.href = 'index.php?page=usersTab/user-profile&id=' + userId;
    }

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

	document.addEventListener("DOMContentLoaded", function () {
    var avatars = document.querySelectorAll(".slsulogo");
    
    function positionAvatars() {
        var windowHeight = window.innerHeight;
        var sidebarHeight = document.getElementById("sidebar").offsetHeight;

        // Calculate the top position for the avatars
        var topPosition = (windowHeight - sidebarHeight) / 2;

        // Apply the top position to each avatar
        avatars.forEach(function (avatar) {
            avatar.style.top = topPosition + "px";
        });
    }

    // Call positionAvatars initially and on window resize
    positionAvatars();
    window.addEventListener("resize", positionAvatars);
	});
	</script>

