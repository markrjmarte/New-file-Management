
<head>
	<link rel="stylesheet" href="uicss/style1.css">
	<link rel="stylesheet" href="uicss/style2.css">
	<link rel="stylesheet" href="uicss/style4.css">
</head>
<style>
.notification-image {
    width: 50px; 
    height: 50px;
}
.slsulogo {
    width: 70%;
    margin: 30px 45px 0px;
	filter: drop-shadow(0px 0px 2px var(--blue));
}
.contact-avatar-image {
    width: 150px; 
    height: 150px;
}
</style>
<?php 
include 'db_connect.php';
$folder_parent = isset($_GET['fid']) ? $_GET['fid'] : 0;

// Modify the query to exclude the "Template" folder
$folders = $conn->query("SELECT * FROM folders where parent_id = $folder_parent and user_id = '".$_SESSION['login_id']."'  order by name asc");

// Fetch the number of files for each folder
$folderCounts = array();
$filesCountQuery = $conn->query("SELECT folder_id, COUNT(*) as count FROM files where user_id = '".$_SESSION['login_id']."' GROUP BY folder_id");
while ($row = $filesCountQuery->fetch_assoc()) {
    $folderCounts[$row['folder_id']] = $row['count'];
}

$queryPublicFiles = "SELECT COUNT(*) AS Public_Files FROM files WHERE is_public >= 1 ";
$resultPublicFiles = $conn->query($queryPublicFiles);

if ($resultPublicFiles) {
    $rowPublicFiles = $resultPublicFiles->fetch_assoc();
    $totalPublicFiles = $rowPublicFiles['Public_Files'];
} else {
    $totalPublicFiles = "Error fetching data";
}

if (isset($_GET['notification_id'])) {
    $notificationId = $_GET['notification_id'];
    $query = "SELECT files.*, users.*, files.name AS Filename, files.id AS ShareId, CASE 
        WHEN files.is_public = 0 THEN 'Public to all'
        WHEN files.is_public > 0 THEN 'Public to user'
        ELSE 'Personal files'
        END AS Status 
        FROM files 
        INNER JOIN users ON files.user_id = users.ID 
        INNER JOIN notification ON files.is_public = notification.is_public AND files.date_updated = notification.date_updated
        WHERE (files.is_public = 0 OR (files.is_public > 0 AND files.user_id = '" . $_SESSION['login_id'] . "')
        OR (files.is_public > 0 AND files.is_public = '" . $_SESSION['login_id'] . "')) 
        AND notification.id = $notificationId
        ORDER BY date_updated DESC;

		UPDATE notification
        SET status = 1
        WHERE id = $notificationId AND status = 0;";

		if (mysqli_multi_query($conn, $query)) {
			$files = $conn->store_result();
			mysqli_next_result($conn);
		}

} else {
	$files = $conn->query("SELECT files.*, users.*, files.name AS Filename, files.id AS ShareId, CASE 
	WHEN files.is_public = 0 THEN 'Public to all'
	WHEN files.is_public > 0 THEN 'Public to user'
	ELSE 'Personal files'
	END AS Status 
	FROM files 
	INNER JOIN users ON files.user_id = users.ID 
	WHERE files.is_public = 0 OR ( files.is_public > 0 AND  files.user_id = '" . $_SESSION['login_id'] . "') OR ( files.is_public > 0 AND  files.is_public = '" . $_SESSION['login_id'] . "')
	ORDER BY date_updated DESC");
}
$Userfiles = $conn->query("SELECT files.name AS Filename, users.username AS Uploader, files.date_updated AS Date, 
files.description AS Description FROM files INNER JOIN users ON files.user_id = users.ID where is_public = 1 or user_id = '".$_SESSION['login_id']."' order by date_updated desc");

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
			<li class="active">
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
					<h1 style = "font-weight: 600;">Shared Files</h1>
					<ol class="breadcrumb"><li class="breadcrumb-item"><a style= "color: var(--dark-grey);"> Home </a></li></ol>
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
									$truncated_filename = strlen($file['Filename']) > 30 ? substr($file['Filename'], 0, 30) . "..." : $file['Filename'];
									
									echo '<tr class="file-item" data-id="' . $fileId . '">';
										echo '<th scope="row">' . $count . '</th>';
										echo '<td><img src="assets/img/profiles/' . $file['profile_image'] . '"></td>';
										echo '<td>' . $truncated_filename . '.' . $file['file_type'] . '</td>';
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
			$('#new_folder').click(function(){
				uni_modal('','manage_folder.php?fid=<?php echo $folder_parent ?>')
			})

			$('#new_file').click(function(){
				uni_modal('','manage_files.php?fid=<?php echo $folder_parent ?>')
			})
			
			$('.folder-item').dblclick(function(){
				location.href = 'index.php?page=sharedfiles&fid='+$(this).attr('data-id')
			})

			$(".folder-item .file-option.edit").on("click", function(e){
				e.preventDefault()
				uni_modal('Rename Folder','manage_folder.php?fid=<?php echo $folder_parent ?>&id='+$(this).attr('data-id') )
			})

			$(".folder-item .file-option.delete").on("click", function(e){
				e.preventDefault()
				_conf("Are you sure to delete this Folder?",'delete_folder',[$(this).attr('data-id')])
			})

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
			
			$(document).ready(function() {
				
				$('#search').keyup(function() {
					var searchTerm = $(this).val().toLowerCase();

					// Filter folders
					$('.folder-item').each(function() {
						var folderName = $(this).find('.card-title').text().toLowerCase();
						$(this).toggle(folderName.includes(searchTerm));
					});

					// Filter files
					$('.file-item').each(function() {
						var fileName = $(this).find('b.to_file').text().toLowerCase();
						var fileDate = $(this).find('i.to_file').text().toLowerCase(); 
						$(this).toggle(fileName.includes(searchTerm) || fileDate.includes(searchTerm));
					});
				});
			});

			function delete_folder($id){
				start_load();
				$.ajax({
					url:'ajax.php?action=delete_folder',
					method:'POST',
					data:{id:$id},
					success:function(resp){
						if(resp == 1){
							alert_toast("Folder successfully deleted.",'success')
								setTimeout(function(){
									location.reload()
								},1500)
						}
					}
				})
			}
			function delete_file($id){
				start_load();
				$.ajax({
					url:'ajax.php?action=delete_file',
					method:'POST',
					data:{id:$id},
					success:function(resp){
						if(resp == 1){
							alert_toast("Folder successfully deleted.",'success')
								setTimeout(function(){
									location.reload()
								},1500)
						}
					}
				})
			}

	</script>
