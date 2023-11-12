
<head>
	<link rel="stylesheet" href="style1.css">
	<link rel="stylesheet" href="style2.css">
	<link rel="stylesheet" href="style4.css">
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
		<a href="#" class="brand">
			<i class='bx bxs-smile'></i>
			<span class="text">SLSU</span>
		</a>
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
					<i class='bx bxs-shopping-bag-alt' ></i>
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
					<i class='bx bxs-group' ></i>
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
			<i class='bx bx-menu' ></i>
			<a href="#" class="nav-link">Categories</a>
			<form action="#">
				<div class="form-input">
					<input type="search" placeholder="Search...">
					<button type="submit" class="search-btn"><i class='bx bx-search' ></i></button>
				</div>
			</form>
			<!-- <input type="checkbox" id="switch-mode" hidden>
			<label for="switch-mode" class="switch-mode"></label> -->
			<!-- Notification -->
			<li class="custom-dropdown">
                    <button class="notify-toggler custom-dropdown-toggler">
                      <i class="bx bxs-bell icon"></i>
					  <?php
						// Fetch and display the notification count
						// $notificationCountQuery = $conn->query("SELECT COUNT(*) AS notification_count FROM notification WHERE by_who = '".$_SESSION['login_id']."'");
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
										echo '<a href="user-profile.html">';

										
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
                     <!-- <footer class="border-top dropdown-notify-footer">
                        <div class="d-flex justify-content-between align-items-center py-2 px-4">
                          <span>Last updated</span>
                          <a id="refress-button" href="javascript:" class="btn mdi mdi-cached btn-refress"></a>
                        </div>
                      </footer> -->
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
					<ol class="breadcrumb"><li class="breadcrumb-item"><a style= "color: var(--dark-grey);"> Home </a></li></ol>
				</div><!-- End Page Title -->
				<button id="new_user" type="button" class="btn btn-primary btn-pill">Add faculty</button>
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
				<div class="col-lg-6 col-xl-4 col-xxl-3" data-id="<?php echo $row['id'] ?>">
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
			
		</main>
		<!-- MAIN -->
	</section>
	<!-- CONTENT -->
	<script src="plugins/simplebar/simplebar.min.js"></script>
	<script src="js/mono.js"></script>
	<script src="script.js"></script>
	<script>
		document.getElementById("search-icon").addEventListener("click", function() {
				// Call the search function when the search icon is clicked.
				search();
		});

		document.getElementById("search-input").addEventListener("input", function() {
				// Call the search function when the user types in the search input.
				search();
		});
		
		function search() {
				var input = document.getElementById("search-input").value.toLowerCase();
				var table = document.querySelector("table");
				var rows = table.getElementsByTagName("tr");

				for (var i = 1; i < rows.length; i++) {
					var row = rows[i];
					var data = row.textContent.toLowerCase();

					if (data.includes(input)) {
						row.style.display = "";
					} else {
						row.style.display = "none";
					}
				}
			}
	</script>
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


	$('#new_user').click(function(){
        uni_modal('New User','manage_user.php')
    })

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

