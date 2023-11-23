
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

			<li>
				<a href="#">
					<i class='bx bxs-message-dots' ></i>
					<span class="text">Announcement</span>
				</a>
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
				<a href="index.php?page=faculties">
					<i class='bx bxs-group' ></i>
					<span class="text">Manage</span>
				</a>
			</li>

			<li>
				<a href="index.php?page=faculties">
					<i class='bx bxs-group' ></i>
					<span class="text">Logs</span>
				</a>
			</li>

			<?php endif; ?>
			
		</ul>
		<ul class="side-menu">
			<li>
				<a href="#">
					<i class='bx bxs-cog' ></i>
					<span class="text">Settings</span>
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
			<a href="#" class="notification">
				<i class='bx bxs-bell' ></i>
				<span class="num">8</span>
			</a>
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
					(SELECT COUNT(*) FROM files WHERE user_id = is_public) AS total_shared_files
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
								data-job="<?php echo $row['job'] ?>">
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
								<button type="button" class="btn-edit-icon edit_user " data-dismiss="modal" aria-label="Close">
										<i class="mdi mdi-pencil"></i>
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
											<p class="text-dark font-weight-medium pt-4 mb-2">Email</p>
											<p id="user-contact-email"></p>
											<p class="text-dark font-weight-medium pt-4 mb-2">Phone Number</p>
											<p id="user-phone"></p>
											<p class="text-dark font-weight-medium pt-4 mb-2">Address</p>
											<p id="user-address"></p>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			<!-- End Contact Modal -->

			<!-- Facluty Modal -->
				<div class="modal fade" id="modal-add-contact" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
					<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
						<div class="modal-content">
							<form >
								<div class="modal-header px-4">
								<h5 class="modal-title" id="exampleModalCenterTitle">Create New Faculty Info.</h5>
								</div>
								<div class="modal-body px-4">
								<div class="form-group row mb-6">
									<label for="coverImage" class="col-sm-4 col-lg-2 col-form-label">User Image</label>
									<div class="col-sm-8 col-lg-10">
									<div class="custom-file mb-1">
										<input type="file" class="custom-file-input" id="coverImage" required>
										<label class="custom-file-label" for="coverImage">Choose file...</label>
										<div class="invalid-feedback">Example invalid custom file feedback</div>
									</div>
									</div>
								</div>

								<div class="row mb-2">
									<div class="col-lg-6">
									<div class="form-group">
										<label for="firstName">First name</label>
										<input type="text" class="form-control" id="firstName">
									</div>
									</div>

									<div class="col-lg-6">
									<div class="form-group">
										<label for="UserType">User Type</label>
										<input type="text" class="form-control" id="UserType">
									</div>
									</div>

									<div class="col-lg-6">
									<div class="form-group mb-4">
										<label for="userName">User name</label>
										<input type="text" class="form-control" id="userName">
									</div>
									</div>

									<div class="col-lg-6">
									<div class="form-group mb-4">
										<label for="email">Email</label>
										<input type="email" class="form-control" id="email">
									</div>
									</div>

									<div class="col-lg-6">
									<div class="form-group mb-4">
										<label for="password">Password</label>
										<input type="text" class="form-control" id="password">
									</div>
									</div>

									<div class="col-lg-6">
									<div class="form-group mb-4">
										<label for="phone">Phone No.</label>
										<input type="text" class="form-control" id="phone">
									</div>
									</div>

									<div class="col-lg-6">
									<div class="form-group mb-4">
										<label for="position">Position</label>
										<input type="text" class="form-control" id="position">
									</div>
									</div>

									<div class="col-lg-6">
									<div class="form-group mb-4">
										<label for="address">Address</label>
										<input type="text" class="form-control" id="address">
									</div>
									</div>
								</div>
								</div>
								<div class="modal-footer px-4">
								<button type="button" class="btn btn-smoke btn-pill" data-dismiss="modal">Cancel</button>
								<button type="button" class="btn btn-primary btn-pill">Save Contact</button>
								</div>
							</form>
						</div>
					</div>
				</div>
			
		</main>
		<!-- MAIN -->
	</section>
	<!-- CONTENT -->
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
		$('#user-image').attr('src', imageSrc);

		$('.edit_user').click(function () {
			edit_user(id);
		});
	});


	$('#new_user').click(function(){
        uni_modal('New User','manage_user.php')
    })

	function edit_user(userId) {
		uni_modal('Edit User', 'manage_user.php?id=' + userId);
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

