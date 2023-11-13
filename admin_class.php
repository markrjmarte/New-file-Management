<?php
session_start();
Class Action {
	private $db;

	public function __construct() {
		ob_start();
   	include 'db_connect.php';
    
    $this->db = $conn;
	}
	function __destruct() {
	    $this->db->close();
	    ob_end_flush();
	}

	// Function to log actions
    private function logAction($username, $action) {
        $this->db->query("INSERT INTO users_logs (users, status, dates) VALUES ('$username', '$action', NOW())");
    }

	function login(){
		extract($_POST);
		$qry = $this->db->query("SELECT * FROM users where username = '".$username."' and password = '".$password."' ");
		if($qry->num_rows > 0){
			foreach ($qry->fetch_array() as $key => $value) {
				if($key != 'passwors' && !is_numeric($key))
					$_SESSION['login_'.$key] = $value;
			}
			// Update user status to "Login" and store login date and time
			$this->logAction($username, 'Login');
			return 1;
		} else {
			return 2;
		}
	}
	
	function logout(){
		$username = $_SESSION['login_username'];
		$this->logAction($username, 'Logout');
	
		session_destroy();
		foreach ($_SESSION as $key => $value) {
			unset($_SESSION[$key]);
		}
		header("location:login.php");
	}
	

	function save_folder(){
		extract($_POST);
		$data = " name ='".$name."' ";
		$data .= ", parent_id ='".$parent_id."' ";
		
		if(empty($id)){
			$data .= ", user_id ='".$_SESSION['login_id']."' ";
			
			// Check if the folder name exists for the given parent_id
			$check = $this->db->query("SELECT * FROM folders where user_id ='".$_SESSION['login_id']."' and name ='".$name."' and parent_id ='".$parent_id."'")->num_rows;
			if($check > 0){
				return json_encode(array('status'=>2,'msg'=> 'Folder name already exists'));
			}else{
				$save = $this->db->query("INSERT INTO folders set ".$data);
				if($save){
					$username = $_SESSION['login_username'];
					$this->logAction($username, "Created the folder $name");
					return json_encode(array('status'=>1));
				}
			}
		}else{
			// Check if the folder name exists for the given parent_id, excluding the current folder
			$check = $this->db->query("SELECT * FROM folders where user_id ='".$_SESSION['login_id']."' and name ='".$name."' and parent_id ='".$parent_id."' and id !=".$id)->num_rows;
			if($check > 0){
				return json_encode(array('status'=>2,'msg'=> 'Folder name already exists'));
			}else{
				$save = $this->db->query("UPDATE folders set ".$data." where id =".$id);
				if($save)
					return json_encode(array('status'=>1));
			}
		}
	}
	
	function delete_folder() {
		extract($_POST);
		$folder = $this->db->query("SELECT name FROM folders where id = $id")->fetch_array();
		$delete = $this->db->query("DELETE FROM folders where id = $id");
		if ($delete) {
			$username = $_SESSION['login_username'];
			$this->logAction($username, "Deleted the folder: $folder[name]");
			echo 1;
		}
	}

	function delete_file() {
		extract($_POST);
		$file = $this->db->query("SELECT name FROM files where id = $id")->fetch_array();
		$path = $this->db->query("SELECT file_path from files where id = $id")->fetch_array()['file_path'];
		$delete = $this->db->query("DELETE FROM files where id = $id");
		if ($delete) {
			$username = $_SESSION['login_username'];
			$this->logAction($username, "Deleted the file: $file[name]");
			unlink('assets1/uploads/' . $path);
			return 1;
		}
	}
	
	function save_files(){
		extract($_POST);
		if (empty($id)) {
			if ($_FILES['upload']['tmp_name'] != '') {
				$fname = strtotime(date('y-m-d H:i')).'_'.$_FILES['upload']['name'];
				$move = move_uploaded_file($_FILES['upload']['tmp_name'],'assets1/uploads/'. $fname);
	
				if ($move) {
					$file = $_FILES['upload']['name'];
					$file = explode('.',$file);
					$chk = $this->db->query("SELECT * FROM files where SUBSTRING_INDEX(name,' ||',1) = '".$file[0]."' and folder_id = '".$folder_id."' and file_type='".$file[1]."' ");
					if ($chk->num_rows > 0) {
						$file[0] = $file[0] .' ||'.($chk->num_rows);
					}
					$data = " name = '".$file[0]."' ";
					$data .= ", folder_id = '".$folder_id."' ";
					$data .= ", description = '".$description."' ";
					$data .= ", user_id = '".$_SESSION['login_id']."' ";
					$data .= ", file_type = '".$file[1]."' ";
					$data .= ", file_path = '".$fname."' ";
					if (isset($is_public) && $is_public == 'on') {
						$data .= ", is_public = 0 ";
						$log_message = "$file[0] is shared to all";
					} else {
						$data .= ", is_public = -1 ";
						$log_message = "Added a file $file[0].$file[1]";
					}
	
					$save = $this->db->query("INSERT INTO files set ".$data);
					$username = $_SESSION['login_username'];
					$this->logAction($username, $log_message);
					if ($save) {
						return json_encode(array('status'=>1));
					}
				}
			}
		} else {
			$data = " description = '".$description."' ";
			if (isset($is_public) && $is_public == 'on') {
				$data .= ", is_public = 0 ";
			} else {
				// Check if user_id is set and not empty
				if (isset($user_id)) {
					$data .= ", is_public = $user_id ";
				} else {
					$data .= ", is_public = -1 ";
				}
			}
			$save = $this->db->query("UPDATE files set ".$data. " where id=".$id);
			if ($save) {
				if (isset($user_id) && $user_id !=0 ) {
					$file = $this->db->query("SELECT name, file_type FROM files WHERE is_public = '$user_id' AND date_updated = NOW()")->fetch_array();
					$username = $this->db->query("SELECT name FROM users where id = $user_id")->fetch_array();
					$log_message = "Shared $file[name].$file[file_type] to $username[name] ";
				} else if (isset($user_id) && $user_id == 0 ){
					$file = $this->db->query("SELECT name, file_type FROM files WHERE date_updated = NOW()")->fetch_array();
					$log_message = "Shared $file[name].$file[file_type] to all users ";
				}
				$username = $_SESSION['login_username'];
            	$this->logAction($username, $log_message);
				return json_encode(array('status'=>1));
			}
		}
	}
	
	function share_files(){
		extract($_POST);
		if (empty($id)) {
			if ($_FILES['upload']['tmp_name'] != '') {
				$fname = strtotime(date('y-m-d H:i')).'_'.$_FILES['upload']['name'];
				$move = move_uploaded_file($_FILES['upload']['tmp_name'],'assets1/uploads/'. $fname);
	
				if ($move) {
					$file = $_FILES['upload']['name'];
					$file = explode('.',$file);
					$chk = $this->db->query("SELECT * FROM files where SUBSTRING_INDEX(name,' ||',1) = '".$file[0]."' and folder_id = '".$folder_id."' and file_type='".$file[1]."' ");
					if ($chk->num_rows > 0) {
						$file[0] = $file[0] .' ||'.($chk->num_rows);
					}
					$data = " name = '".$file[0]."' ";
					$data .= ", folder_id = '".$folder_id."' ";
					$data .= ", description = '".$description."' ";
					$data .= ", user_id = '".$_SESSION['login_id']."' ";
					$data .= ", file_type = '".$file[1]."' ";
					$data .= ", file_path = '".$fname."' ";
					if (isset($is_public) && $is_public == 'on') {
						$data .= ", is_public = 0 ";
						$log_message = "$file[0] is shared to all";
					} else {
						$data .= ", is_public = -1 ";
						$log_message = "Added a file $file[0].$file[1]";
					}
	
					$save = $this->db->query("INSERT INTO files set ".$data);
					$username = $_SESSION['login_username'];
					$this->logAction($username, $log_message);
					if ($save) {
						return json_encode(array('status'=>1));
					}
				}
			}
		} else {
			$data = " description = '".$description."' ";
			if (isset($is_public) && $is_public == 'on') {
				$data .= ", is_public = 0 ";
			} else {
				// Check if user_id is set and not empty
				if (isset($user_id)) {
					$data .= ", is_public = $user_id ";
				} else {
					$data .= ", is_public = -1 ";
				}
			}
			$save = $this->db->query("UPDATE files set ".$data. " where id=".$id);
			if ($save) {
				if (isset($user_id) && $user_id !=0 ) {
					$file = $this->db->query("SELECT name, file_type FROM files WHERE is_public = '$user_id' AND date_updated = NOW()")->fetch_array();
					$username = $this->db->query("SELECT name FROM users where id = $user_id")->fetch_array();
					$log_message = "Shared $file[name].$file[file_type] to $username[name] ";
				} else if (isset($user_id) && $user_id == 0 ){
					$file = $this->db->query("SELECT name, file_type FROM files WHERE date_updated = NOW()")->fetch_array();
					$log_message = "Shared $file[name].$file[file_type] to all users ";
				}
				
				$notification_data = "by_who = '". $_SESSION['login_id']. "' ";
				$notification_data .= ", description = '".$description."' ";
				$notification_data .= ", is_public = '$user_id' ";
				$notification_data .= ", kind = 1 ";

				$this->db->query("INSERT INTO notification SET " .$notification_data);

				$username = $_SESSION['login_username'];
            	$this->logAction($username, $log_message);
				return json_encode(array('status'=>1));
			}
		}
	}

	function file_rename(){
		extract($_POST);
		$file[0] = $name;
		$file[1] = $type;
		$chk = $this->db->query("SELECT * FROM files where SUBSTRING_INDEX(name,' ||',1) = '".$file[0]."' and folder_id = '".$folder_id."' and file_type='".$file[1]."' and id != ".$id);
		if($chk->num_rows > 0){
			$file[0] = $file[0] .' ||'.($chk->num_rows);
			}
		$save = $this->db->query("UPDATE files set name = '".$name."' where id=".$id);
		$username = $_SESSION['login_username'];
		$this->logAction($username, "Renamed a file into $name.$type");
		if($save){
				return json_encode(array('status'=>1,'new_name'=>$file[0].'.'.$file[1]));
		}
	}

	function save_user(){
		extract($_POST);

		$data = " name = '$name' ";
		$data .= ", username = '$username' ";
		$data .= ", password = '$password' ";
		$data .= ", type = '$type' ";
		$data .= ", phone = '$phone' ";
		$data .= ", job = '$job' ";
		$data .= ", email = '$email' ";
		$data .= ", adress = '$adress' ";
	
	
		if(empty($id)){
			$save = $this->db->query("INSERT INTO users set ".$data);
		}else{
			$save = $this->db->query("UPDATE users set ".$data." where id = ".$id);
		}
		if($save){
			$this->logAction($username, 'Created a user');
			return 1;
		}
	}
	function update_user(){
		extract($_POST);
	
		$data = " name = '$name' ";
		$data .= ", username = '$username' ";
		$data .= ", password = '$password' ";
		$data .= ", phone = '$phone' ";
		$data .= ", job = '$job' ";
		$data .= ", email = '$email' ";
		$data .= ", adress = '$adress' ";
	
		if (!empty($_FILES['profile_image']['name'])) {
			$profile_image = $_FILES['profile_image']['name'];
			$temp_image = $_FILES['profile_image']['tmp_name'];
			$profile_image_path = 'assets/img/profiles/' . $profile_image; // Update this path
			move_uploaded_file($temp_image, $profile_image_path);
			$data .= ", profile_image = '$profile_image'";
		}
		if(empty($id)){
			$save = $this->db->query("INSERT INTO users set ".$data);
		}else{
			$save = $this->db->query("UPDATE users set ".$data." where id = ".$id);
		}
		if($save){
			$this->logAction($username, 'updated user info');
			return 1;
		}
	}

	function delete_user(){
		extract($_POST);
		$username = $_SESSION['login_username'];
		$delete = $this->db->query("DELETE FROM users where id =".$id);
		$this->logAction($username, 'Deleted a user');
		if($delete)
			echo 1;
	}

	function add_announcement(){
		extract($_POST);
		$username = $_SESSION['login_username'];
		$user = $_SESSION['login_id'];

		$data = " announce_to = '$user_id' ";
		$data .= ", message = '$description' ";
		$data .= ", title = '$title' ";
	
		$save = $this->db->query("INSERT INTO announcement set ".$data);
		
		if ($save) {
			$announcement_id = $this->db->insert_id;
	
			
			if ($_FILES['upload']['tmp_name'] != '') {
				$fname = strtotime(date('y-m-d H:i')).'_'.$_FILES['upload']['name'];
				$move = move_uploaded_file($_FILES['upload']['tmp_name'],'assets1/uploads/'. $fname);
	
				if ($move) {
					$file = $_FILES['upload']['name'];
					$file = explode('.',$file);
					
					$file_data = " name = '".$file[0]."' ";
					$file_data .= ", description = 'From announcement: $title'";
					$file_data .= ", user_id = $user";
					$file_data .= ", file_type = '".$file[1]."' ";
					$file_data .= ", file_path = '".$fname."' ";
					$file_data .= ", is_public = $user_id";
	
					$this->db->query("INSERT INTO files SET $file_data");
				}
			}

			$notification_data = "by_who = '$user' ";
			$notification_data .= ", description = '$title' ";
			$notification_data .= ", is_public = '$user_id' ";
			$notification_data .= ", kind = 0 ";

			$this->db->query("INSERT INTO notification SET " .$notification_data);
			
			$this->logAction($username, 'added an announcement');
			return 1;
		} 
	}
}