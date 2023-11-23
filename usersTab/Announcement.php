
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

if (isset($_GET['notification_id'])) {
    $notificationId = $_GET['notification_id'];
	$query = "SELECT announcement.*, 
        CASE 
            WHEN announcement.announce_to = 0 THEN 'all'
            ELSE 'Personal files'
        END AS profile_image
        FROM announcement 
        INNER JOIN notification ON announcement.title = notification.description AND announcement.date_uploaded = notification.date_updated
        WHERE notification.id = $notificationId
        ORDER BY Date_uploaded DESC;
        
        UPDATE notification
        SET status = 1
        WHERE id = $notificationId AND status = 0;";

	if (mysqli_multi_query($conn, $query)) {
		$announcement_query = $conn->store_result();
		mysqli_next_result($conn);
	}
} else {
    $announcement_query = $conn->query("SELECT announcement.*, 
        CASE 
            WHEN announcement.announce_to = 0 THEN 'all'
            ELSE 'Personal files'
        END AS profile_image
        FROM announcement 
        ORDER BY Date_uploaded DESC");
}

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
				<li class="active">
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
					<h1 style = "font-weight: 600;">Announcement</h1>
					<ol class="breadcrumb">
						<li class="breadcrumb-item"><a style= "color: var(--dark-grey);"> Home </a></li> 
						<?php if($_SESSION['login_type'] == 1): ?>
							<li class="breadcrumb-item"><a href = 'index.php?page=usersTab/add-announcement'> New announcement </a>
						<?php endif; ?></li>
					</ol>
				</div><!-- End Page Title -->
				<div class="toast" id="alert_toast" role="alert" aria-live="assertive" aria-atomic="true">
					<div class="toast-body text-white">
					</div>
				</div>
			</div>

			<!-- Data Table -->
			<div class="table-data">
				<div class="order">
					<div class="card-body">
						<table id="productsTableAnnouncement" style="width:100%">
							<h4 class="table-title">Announcement list</h4>
							<thead>
								<tr>
									<th scope="col">No.</th>
									<th scope="col">Title</th>
									<th scope="col">Recipients</th>
									<th scope="col">Message</th>
									<th scope="col">Attached file</th>
									<th scope="col">Date</th>
								</tr>
							</thead>
							<tbody>
								<?php
								$count = 1;
								while ($log = $announcement_query->fetch_assoc()) {
									$sharedUserId = $log['announce_to'];
									$truncated_message = strlen($log['message']) > 50 ? substr($log['message'], 0, 50) . "..." : $log['message'];
									$truncated_title = strlen($log['title']) > 15 ? substr($log['title'], 0, 15) . "..." : $log['title'];
									$trackfile = $conn->query ("SELECT * FROM files WHERE is_public = $sharedUserId AND description = 'From announcement: $log[title]' ");
									$filename = $trackfile->fetch_assoc();
									echo '<tr class="table-row" data-title="' . $log['title'] . '"
												data-announce_to="' . $log['announce_to'] . '"
												data-message="' . $log['message'] . '"
												data-filename = "' . ($filename ? $filename['name'] . '.' . $filename['file_type'] : 'No attached file') . '"
												data-date_uploaded="' . $log['date_uploaded'] . '"
												data-id="' . ($filename ? $filename['id'] : 'No attached file') . '"
											>';
									// echo '<tr class="file-item" data-id="' . $sharedUserId . '">';
										echo '<th scope="row">' . $count . '</td>';
										echo '<td class="ellipsis">' . $truncated_title . '</td>';
										echo '<td>';
											if ($log['profile_image'] === 'all') {
												echo '<img src="img/folder.png" alt="share to all" style="border-radius: 0;">';
											} else {
												$sharedUserId = $log['announce_to'];
												$sharedUserQuery = $conn->query("SELECT profile_image FROM users WHERE id = $sharedUserId");
												$sharedUser = $sharedUserQuery->fetch_assoc();
												echo '<img src="assets/img/profiles/' . $sharedUser['profile_image'] . '">';
											}
										echo '</td>';

										echo '<td class="ellipsis">' . $truncated_message . '</td>';
										echo '<td>' .  ($filename ? $filename['name'] . '.' . $filename['file_type'] : 'No attached file') . '</td>';
										echo '<td>' . $log['date_uploaded'] . '</td>';
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

			<!-- Contact Modal -->
			<div class="modal fade" id="modal-contact" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
				<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
					<div class="viewprofile_card modal-content">
						<div class="modal-header justify-content-end border-bottom-0">
							<button type="button" class="btn-close-icon" data-dismiss="modal" aria-label="Close">
								<i class="mdi mdi-close"></i>
							</button>
						</div>
						<div class="modal-body pt-0" style="margin: 0px 20px 10px 20px;">
							<h3 id="announce_title" style="color: var(--blue); padding-bottom: 10px;"></h3>
							<p id="announce_message" style="padding-bottom: 20px;"></p>
							<p style="color: var(--blue);" id="downloadLabel">View file below:</p>
							<button class="btn btn-primary" id="viewFileBtn"><span id="announce_filename"></span></button>
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
	<script src="https://unpkg.com/hotkeys-js/dist/hotkeys.min.js"></script>
	<script src="plugins/simplebar/simplebar.min.js"></script>
	<script src="js/mono.js"></script>
	<script src="plugins/nprogress/nprogress.js"></script>
	<script src="js/custom.js"></script>
	<script src="script.js"></script>
	
	<script>
	$(document).ready(function () {
		$('.table-row').click(function () {
			var title = $(this).data('title');
			var announce_to = $(this).data('announce_to');
			var message = $(this).data('message');
			var date_uploaded = $(this).data('date_uploaded');
			var filename = $(this).data('filename');
			var id = $(this).data('id');

			$('#announce_title').text(title);
			$('#announce_to').text(announce_to);
			$('#announce_message').text(message);
			$('#announce_uploaded').text(date_uploaded);
			$('#announce_filename').text(filename);

			$('#modal-contact').modal('show');
			
			if (filename === 'No attached file') {
				$('#downloadLabel').hide();
           	 	$('#viewFileBtn').hide(); 
			} else {
				$('#downloadLabel').show();
				$('#viewFileBtn').show();
				
				$('#viewFileBtn').click(function () {
					viewFileBtn(id);
				});
			}
		});

		$('.btn-close-icon').click(function () {
			$('#modal-contact').modal('hide');
		});

		function viewFileBtn(userId) {
        	window.open('display_file.php?id=' + userId);
    	}

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