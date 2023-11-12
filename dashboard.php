<head>
	<link rel="stylesheet" href="style.css">
	<link rel="stylesheet" href="style2.css">
	<link rel="stylesheet" href="style4.css">
</head>
<style>
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
include 'db_connect.php';
$query = "SELECT COUNT(*) AS total_files FROM files";
$result = $conn->query($query);

if ($result) {
    $row = $result->fetch_assoc();
    $totalFiles = $row['total_files'];
} else {
    $totalFiles = "Error fetching data";
}

$queryPersonalFiles = "SELECT COUNT(*) AS total_personal_files FROM files WHERE user_id = '".$_SESSION['login_id']."' ";
$resultPersonalFiles = $conn->query($queryPersonalFiles);

if ($resultPersonalFiles) {
    $rowPersonalFiles = $resultPersonalFiles->fetch_assoc();
    $totalPersonalFiles = $rowPersonalFiles['total_personal_files'];
} else {
    $totalPersonalFiles = "Error fetching data";
}

$queryNumberofFaculties = "SELECT COUNT(*) AS number_of_faculties FROM users WHERE type = 2";
$resultNumberofFaculties = $conn->query($queryNumberofFaculties);

if ($resultNumberofFaculties) {
    $rowNumberofFaculties = $resultNumberofFaculties->fetch_assoc();
    $totalNumberofFaculties = $rowNumberofFaculties['number_of_faculties'];
} else {
  $totalNumberofFaculties = "Error fetching data";
}

$queryPublicFiles = "SELECT COUNT(*) AS Public_Files FROM files WHERE is_public >= 1 ";
$resultPublicFiles = $conn->query($queryPublicFiles);

if ($resultPublicFiles) {
    $rowPublicFiles = $resultPublicFiles->fetch_assoc();
    $totalPublicFiles = $rowPublicFiles['Public_Files'];
} else {
    $totalPublicFiles = "Error fetching data";
}

// Get the user's type from the session
$userType = $_SESSION['login_type'];
if ($userType == 1){
	$files = $conn->query("SELECT files.*, files.file_path AS Filename, files.file_path AS Extension, users.username AS Uploader, files.date_updated AS Date, 
	files.description AS Description, CASE 
		WHEN files.is_public = 0 THEN 'Public to all'
		WHEN files.is_public > 0 THEN 'Public to user'
		ELSE 'Personal files'
	END AS Status FROM files INNER JOIN users ON files.user_id = users.ID order by date_updated desc;");
}else if ($userType == 2){
	$files = $conn->query("SELECT files.*, files.file_path AS Filename, files.file_path AS Extension, users.username AS Uploader, files.date_updated AS Date, 
    files.description AS Description, CASE 
		WHEN files.is_public = 0 THEN 'Public to all'
		WHEN files.is_public > 0 THEN 'Public to user'
        ELSE 'Personal files'
    END AS Status FROM files INNER JOIN users ON files.user_id = users.ID 
    WHERE (files.user_id = '".$_SESSION['login_id']."' OR files.is_public = '".$_SESSION['login_id']."' OR files.is_public = 0)
    ORDER BY date_updated DESC;");
}


$Userfiles = $conn->query("SELECT files.name AS Filename, users.username AS Uploader, files.date_updated AS Date, 
files.description AS Description FROM files INNER JOIN users ON files.user_id = users.ID where is_public = 1 or user_id = '".$_SESSION['login_id']."' order by date_updated desc");

$notifi_count = "SELECT COUNT(*) AS notificount FROM notification  WHERE by_who != '".$_SESSION['login_id']."' 
						AND (is_public >= 0 OR is_public = '".$_SESSION['login_id']."') ";
$resultnotifi_count = $conn->query($notifi_count);

if ($resultnotifi_count) {
    $rowresultnotifi_count = $resultnotifi_count->fetch_assoc();
    $notificount = $rowresultnotifi_count['notificount'];
} else {
    $notificount = "Error fetching data";
}					

?>
	<!-- SIDEBAR -->
	<section id="sidebar">
		<a href="#" class="brand">
			<i class='bx bxs-smile'></i>
			<span class="text">SLSU</span>
		</a>
		<ul class="side-menu top">
			<li class="active">
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

			<li>
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
			<!-- <a href="#" class="notification">
				<i class='bx bxs-bell' ></i>
				<span class="num">8</span>
			</a> -->

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
					<h1 style = "font-weight: 600;">Dashboard</h1>
					<ol class="breadcrumb"><li class="breadcrumb-item"><a style= "color: var(--dark-grey);"> Home </a></li></ol>
				</div><!-- End Page Title -->
			</div>

			<ul class="box-info">
				<?php if($_SESSION['login_type'] == 1): ?>
				<li>
					<i class='bx bxs-group' ></i>
					<span class="text">
						<h3><?php echo $totalNumberofFaculties; ?></h3>
						<p>Number of Faculty</p>
					</span>
				</li>
				<li>
					<i class='bx bxs-add-to-queue' ></i>
					<span class="text">
						<h3><?php echo $totalFiles; ?></h3> 
						<p>Total Files</p>
					</span>
				</li>
				<?php endif; ?>
				<li>
					<i class='bx bxs-file' ></i>
					<span class="text">
						<h3><?php echo $totalPersonalFiles; ?></h3>
						<p>Personal Files</p>
					</span>
				</li>
				<?php if($_SESSION['login_type'] == 2): ?>
				<li>
					<i class='bx bxs-message-dots' ></i>
					<span class="text">
						<h3><?php echo $notificount; ?></h3> 
						<p>Total Announcement</p>
					</span>
				</li>
				<li>
					<i class='bx bxs-share-alt' ></i>
					<span class="text">
						<h3><?php echo $totalPublicFiles; ?></h3> 
						<p>Shared Files</p>
					</span>
				</li>
				<?php endif; ?>
			</ul>


			<!-- Data Table -->
			<div class="table-data">
				<div class="order">
					<div class="card-body">
						<table id="productsTabledashboard" style="width:100%">
							<h4 class = "table-title">List of all files</h4>
							<thead>
								<tr>
									<th scope="col">No.</th>
									<th scope="col"> Filename</th>
									<th scope="col">Uploader</th>
									<th scope="col">Date</th>
									<th scope="col">Decription</th>
									<th scope="col"> Extension</th>
									<th>Status</th>
								</tr>
							</thead>
							<tbody>
								<?php
									$count = 1;
									foreach ($files as $file) {
										$filename = $file['Extension'];
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
									
									$truncated_message = strlen($file['Description']) > 50 ? substr($file['Description'], 0, 50) . "..." : $file['Description'];
									$truncated_filename = strlen($file['Filename']) > 30 ? substr($file['Filename'], 0, 30) . "..." : $file['Filename'];
									
									echo '<tr class="file-item">';
									echo '<th scope="row">' . $count . '</th>';
									echo '<td>' . $truncated_filename . '</td>';
									echo '<td>' . $file['Uploader'] . '</td>';
									echo '<td>' . $file['Date'] . '</td>';
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
									$count++;
								}
								?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<!-- END Data Table -->
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