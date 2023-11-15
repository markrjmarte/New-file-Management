
<head>
	<link rel="stylesheet" href="style1.css">
	<link rel="stylesheet" href="style2.css">
	<link rel="stylesheet" href="style5.css">
	<link rel="stylesheet" href="style4.css">
</head>
<style>
	.avatar-image {
    width: 200px; 
    height: 200px;
}
.slsulogo {
    width: 70%;
    margin: 20px 45px 0px;
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
					<h1 style = "font-weight: 600;">User profile</h1>
					<ol class="breadcrumb"><li class="breadcrumb-item"><a href="index.php?page=faculties"> Home</a></li> 
					<li class="breadcrumb-item"><a style= "color: var(--dark-grey);"> User profile </a></ol>
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

				<!-- user datas -->
				<div class="row" id="user-profile">
					<?php
					if (isset($_GET['id'])) {
						$user_id = $_GET['id'];
					
						// Query to fetch user data with the specified ID
						$user_query = "SELECT users.*, 
							(SELECT COUNT(*) FROM files WHERE user_id = users.id) AS total_personal_files,
							(SELECT COUNT(*) FROM folders WHERE user_id = users.id) AS total_folders,
							(SELECT COUNT(*) FROM files WHERE user_id = users.id AND is_public >= 0 ) AS total_shared_files
							FROM users WHERE id = $user_id ORDER BY id DESC;";
					
						$user_result = $conn->query($user_query);

						$files = $conn->query("SELECT files.*, users.*, files.name AS Filename, files.id AS ShareId, CASE 
							WHEN files.is_public = 0 THEN 'Public to all'
							WHEN files.is_public > 0 THEN 'Public to user'
							ELSE 'Personal files'
							END AS Status 
							FROM files 
							INNER JOIN users ON files.user_id = users.ID 
							WHERE files.is_public >= 0 AND  files.user_id = $user_id
							ORDER BY date_updated DESC");

						if(isset($_POST['update_user'])) {
							$user_id_update = $_GET['id'];
							$username = $_POST['username'];
							$password = $_POST['password'];
							$name = $_POST['name'];
							$job = $_POST['job'];
							$adress = $_POST['adress'];
							$phone = $_POST['phone'];
							$email = $_POST['email'];
						}

						if ($user_result && $user_result->num_rows > 0) {
							$row = $user_result->fetch_assoc();
						?>
							<div class="col-lg-3 col-md-4 col-sm-4">
								<div class="main-box clearfix">
									<h2><?php echo $row['name'] ?></h2>
									<img src="assets/img/profiles/<?php echo $row['profile_image'] ?>" alt="" 
									class="profile-img img-responsive center-block" style ="width: 100%;">
									<div class="profile-label">
										<h4><?php echo $row['job'] ?></h4>
									</div>

									<div class="profile-details">
										<ul class="box-info-user">
											<li><i class="bx bxs-folder-open"></i>Folder: <span class = "text-success"><?php echo $row['total_folders'] ?></span></li>
											<li><i class="bx bxs-file"></i>Files: <span class = "text-success"><?php echo $row['total_personal_files'] ?></span></li>
											<li><i class="bx bxs-share-alt"></i>Shares: <span class = "text-success"><?php echo $row['total_shared_files'] ?></span></li>
										</ul>
									</div>
								</div>
							</div>
							<div class="col-lg-9 col-md-8 col-sm-8">
								<div class="card card-default">
									<div class="card-body">
										<div class="mb-5">
												<ul class="nav nav-tabs" id="myTab" role="tablist">
													<li class="nav-item">
														<a class="nav-link active" id="home-tab" data-toggle="tab" href="#home"
														role="tab" aria-controls="home" aria-selected="true"><i class="bx bxs-info-circle mr-1"></i></a>
													</li>

													<li class="nav-item">
														<a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" 
														role="tab" aria-controls="profile" aria-selected="false"><i class="bx bxs-data mr-1"></i></a>
													</li>

													<li class="nav-item">
														<a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" 
														role="tab" aria-controls="contact" aria-selected="false"><i class="bx bxs-message-square-edit mr-1"></i></a>
													</li>
												</ul>
											<div class="tab-content" id="myTabContent1">

												<!-- Info -->
												<div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">

													<div class="row mb-2" style = "padding-top: 70px;">
														<div class="col-lg-6">
														<div class="form-group">
															<label for="firstName">Name:</label>
															<input type="text" class="form-control"  style = "color: var(--blue); padding-left: 10%;" 
															value="<?php echo $row['name'] ?>" readonly>
														</div>
														</div>	

														<div class="col-lg-6">
														<div class="form-group">
															<label for="UserType">User type:</label>
															<input type="text" class="form-control"  style = "color: var(--blue); padding-left: 10%;" 
															value="<?php echo ($row['type'] == 1) ? 'Administrator' : 'User' ?>" readonly>
														</div>
														</div>

														<div class="col-lg-6">
														<div class="form-group mb-4">
															<label for="userName">Username:</label>
															<input type="text" class="form-control"  style = "color: var(--blue); padding-left: 10%;" 
															value="<?php echo $row['username'] ?>" readonly>
														</div>
														</div>

														<div class="col-lg-6">
														<div class="form-group mb-4">
															<label for="email">Email:</label>
															<input type="text" class="form-control"  style = "color: var(--blue); padding-left: 10%;" 
															value="<?php echo $row['email'] ?>" readonly>
														</div>
														</div>

														<div class="col-lg-6">
														<div class="form-group mb-4">
															<label for="password">Password:</label>
															<input type="text" class="form-control"  style = "color: var(--blue); padding-left: 10%;" 
															value="<?php echo $row['password'] ?>" readonly>
														</div>
														</div>

														<div class="col-lg-6">
														<div class="form-group mb-4">
															<label for="phone">Phone No.:</label>
															<input type="text" class="form-control"  style = "color: var(--blue); padding-left: 10%;" 
															value="<?php echo $row['phone'] ?>" readonly>
														</div>
														</div>

														<div class="col-lg-6">
														<div class="form-group mb-4">
															<label for="position">Position:</label>
															<input type="text" class="form-control"  style = "color: var(--blue); padding-left: 10%;" 
															value="<?php echo $row['job'] ?>" readonly>
														</div>
														</div>

														<div class="col-lg-6">
														<div class="form-group mb-4">
															<label for="address">Address:</label>
															<input type="text" class="form-control"  style = "color: var(--blue); padding-left: 10%;" 
															value="<?php echo $row['adress'] ?>" readonly>
														</div>
														</div>
													</div>
												</div>

												<!-- Share file table -->
												<div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">

														<!-- Data Table -->
														<div class="table-data">
															<div class="order">
															<div class="card-body">
																	<table id="productsTableShares" style="width:100%">
																		<h4 class = "table-title">List of files</h4>
																		<thead>
																			<tr>
																				<th scope="col"> No.</th>
																				<th scope="col"> Uploader</th>
																				<th scope="col"> Filename</th>
																				<th scope="col"> Date</th>
																				<th scope="col"> Decription</th>
																				<th scope="col"> Extension</th>
																				<th scope="col"> Status</th>
																				<!-- <th scope="col"> Action</th> -->
																			</tr>
																		</thead>
																		<tbody>
																			<?php
																				$count = 1;
																				foreach ($files as $file) {
																					$fileId = $file['ShareId'];
																					$filename = $file['file_path'];
																					$fileExtension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

																					// Define an array to map file extensions to icons
																					$iconMap = array(
																						'png' => 'img/img.png',
																						'jpg' => 'img/img.png',
																						'jpeg' => 'img/img.png',
																						'gif' => 'img/img.png',
																						'psd' => 'img/img.png',
																						'tif' => 'img/img.png',
																						'doc' => 'img/word.png',
																						'docx' => 'img/word.png',
																						'pdf' => 'img/pdf.png',
																						'ps' => 'img/pdf.png',
																						'eps' => 'img/pdf.png',
																						'prn' => 'img/pdf.png',
																						'xlsx' => 'img/excel.png',
																						'xls' => 'img/excel.png',
																						'xlsm' => 'img/excel.png',
																						'xlsb' => 'img/excel.png',
																						'xltm' => 'img/excel.png',
																						'xlt' => 'img/excel.png',
																						'xla' => 'img/excel.png',
																						'xlr' => 'img/excel.png',
																						'zip' => 'img/compress.png',
																						'rar' => 'img/compress.png',
																						'tar' => 'img/compress.png',
																						'pptx' => 'img/ppt.png',
																					);

																				$icon = isset($iconMap[$fileExtension]) ? $iconMap[$fileExtension] : 'img/unknown.png';
																				$truncated_message = strlen($file['description']) > 50 ? substr($file['description'], 0, 50) . "..." : $file['description'];
																				
																				echo '<tr class="file-item" data-id="' . $fileId . '">';
																					echo '<th scope="row">' . $count . '</th>';
																					echo '<td><img src="assets/img/profiles/' . $file['profile_image'] . '"></td>';
																					echo '<td>' . $file['Filename'] . '.' . $file['file_type'] . '</td>';
																					echo '<td>' . $file['date_updated'] . '</td>';
																					echo '<td class="ellipsis">' . $truncated_message . '</td>';
																					echo '<td><img src="' . $icon . '" alt="' . $fileExtension . '"></td>';
																					echo '<td>';
																					// Check the value of 'Status' and use <span> with appropriate class Public to user 
																					if ($file['Status'] === 'Public to all') {
																						echo '<img src="img/folder.png" alt="share to all" style = "border-radius: 0;">';
																					} else if ($file['Status'] === 'Public to user') {
																						$sharedUserId = $file['is_public'];
																						$sharedUserQuery = $conn->query("SELECT profile_image FROM users WHERE id = $sharedUserId");
																						$sharedUser = $sharedUserQuery->fetch_assoc();
																						echo '<img src="assets/img/profiles/' . $sharedUser['profile_image'] . '">';
																					}else {
																						echo '<img src="img/dont-share.png" alt="share to all" style = "border-radius: 0;">';
																					}
																					
																				echo '</tr>';
																				$count++;
																			}
																			?>
																		</tbody>
																	</table>
																</div>
															</div>
														</div>
														<!-- END Data Table -->
												</div>

												<!-- update user info -->
												<div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
													<form action="" id="update-user">
														<input type="hidden" name="id" value="<?php echo isset($meta['id']) ? $meta['id'] : ''; ?>">
														<div class="row mb-2" style="padding-top: 70px;">

															<!-- Left Column (Profile Image) -->
															<div class="col-lg-6 mb-3">
																<div class="form-group">
																	<label for="profile_image">Profile Image</label>
																	<div class="preview">
																	<center><img src="assets/img/profiles/<?php echo isset($meta['profile_image']) ? $meta['profile_image']: '' ?>" id="img" alt="Preview" class="profile-img img-responsive center-block" style="width: 50%;"></center>
																	</div>
																	<center><input type="file" name="profile_image" id="profile_image" class="form-control-file" style="display: none;">
            														<a style="color: var(--blue);" href="javascript:void(0);" id="toggleFileInput" class="btn btn-link">Change Profile Image</a></center>
																</div>

																<div class="form-group">
																	<label for="userName">Username:</label>
																	<input type="text" name="username" id="username" class="form-control" style="color: var(--blue); padding-left: 10%;" value="<?php echo isset($meta['username']) ? $meta['username']: '' ?>">
																</div>

																<div class="form-group">
																	<label for="password">Password:</label>
																	<input type="text" name="password" id="password" class="form-control" style="color: var(--blue); padding-left: 10%;" value="<?php echo isset($meta['password']) ? $meta['password']: '' ?>">
																</div>

															</div>

															<!-- Right Column (User Information) -->
															<div class="col-lg-6">
																<div class="form-group">
																	<label for="firstName">Name:</label>
																	<input type="text" name="name" id="name" class="form-control" style="color: var(--blue); padding-left: 10%;" value="<?php echo isset($meta['name']) ? $meta['name']: '' ?>">
																</div>

																<div class="form-group">
																	<label for="UserType">User type:</label>
																	<select style="color: var(--blue); padding-left: 10%;" name="type" id="type" class="form-control">
																		<option value="1" <?php echo isset($meta['type']) && $meta['type'] == 1 ? 'selected' : ''; ?>>Administrator</option>
																		<option value="2" <?php echo isset($meta['type']) && $meta['type'] == 2 ? 'selected' : ''; ?>>User</option>
																	</select>
																</div>

																<div class="form-group mb-4">
																	<label for="email">Email:</label>
																	<input type="text" name="email" id="email" class="form-control" style="color: var(--blue); padding-left: 10%;" value="<?php echo isset($meta['email']) ? $meta['email']: '' ?>">
																</div>

																<div class="form-group mb-4">
																	<label for="phone">Phone No.:</label>
																	<input type="text" name="phone" id="phone"class="form-control" style="color: var(--blue); padding-left: 10%;" value="<?php echo isset($meta['phone']) ? $meta['phone']: '' ?>">
																</div>

																<div class="form-group mb-4">
																	<label for="position">Position:</label>
																	<input type="text" name="job" id="job" class="form-control" style="color: var(--blue); padding-left: 10%;" value="<?php echo isset($meta['job']) ? $meta['job']: '' ?>">
																</div>

																<div class="form-group mb-4">
																	<label for="adress">Address:</label>
																	<input type="text" name="adress" id="adress" class="form-control" style="color: var(--blue); padding-left: 10%;" value="<?php echo isset($meta['adress']) ? $meta['adress']: '' ?>">
																</div>
															</div>
														</div>
														<div class="modal-footer px-4">
															<button type="submit" class="btn btn-primary btn-pill">Save Contact</button>
														</div>
													</form>
													<!-- End Profile Edit Form -->
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						<?php
						} else {
							echo "User not found"; 
						}
					} else {
						echo "User ID not provided";
					}
					?>
				</div>


			
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

		$('#update-user').submit(function(e){
			e.preventDefault();
			start_load();

			var formData = new FormData(this);
			formData.append('action', 'update_user'); 

			$.ajax({
				url: 'ajax.php?action=update_user',
				method: 'POST',
				data: formData,
				processData: false,
				contentType: false,
				success: function(resp) {
					if(resp == 1) {
						alert_toast("Data successfully updated", 'success');
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
			// 	$(document).bind("click", function(event) {
			// 	$("div.custom-menu").hide();
			// 	$('#file-item').removeClass('active')
			// });

			$(document).keyup(function(e){

			if(e.keyCode === 27){
				$("div.custom-menu").hide();
			$('#file-item').removeClass('active')

			}

		});

		
	</script>

