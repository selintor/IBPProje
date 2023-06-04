<?php
require_once('../config.php');
Class Master extends DBConnection {
	private $settings;
	public function __construct(){
		global $_settings;
		$this->settings = $_settings;
		parent::__construct();
	}
	public function __destruct(){
		parent::__destruct();
	}
	function capture_err(){
		if(!$this->conn->error)
			return false;
		else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
			return json_encode($resp);
			exit;
		}
	}
	function save_message(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k =>$v){
			if(!in_array($k,array('id'))){
				if(!is_numeric($v))
					$v = $this->conn->real_escape_string($v);
				if(!empty($data)) $data .=",";
				$data .= " `{$k}`='{$v}' ";
			}
		}
		if(empty($id)){
			$sql = "INSERT INTO `message_list` set {$data} ";
		}else{
			$sql = "UPDATE `message_list` set {$data} where id = '{$id}' ";
		}
		
		$save = $this->conn->query($sql);
		if($save){
			$mid = !empty($id) ? $id : $this->conn->insert_id;
			$resp['status'] = 'success';
			if(empty($id))
				$resp['msg'] = "Your message has successfully sent.";
			else
				$resp['msg'] = "Message details has been updated successfully.";
		}else{
			$resp['status'] = 'failed';
			$resp['msg'] = "An error occured.";
			$resp['err'] = $this->conn->error."[{$sql}]";
		}
		if($resp['status'] =='success' && !empty($id))
		$this->settings->set_flashdata('success',$resp['msg']);
		if($resp['status'] =='success' && empty($id))
		$this->settings->set_flashdata('pop_msg',$resp['msg']);
		return json_encode($resp);
	}
	function delete_message(){
		extract($_POST);
		$del = $this->conn->query("DELETE FROM `message_list` where id = '{$id}'");
		if($del){
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success',"Message has been deleted successfully.");

		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);

	}
	function save_genre(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k =>$v){
			if(!in_array($k,array('id'))){
				if(!is_numeric($v))
					$v = $this->conn->real_escape_string($v);
				if(!empty($data)) $data .=",";
				$data .= " `{$k}`='{$v}' ";
			}
		}
		if(empty($id)){
			$sql = "INSERT INTO `genre_list` set {$data} ";
		}else{
			$sql = "UPDATE `genre_list` set {$data} where id = '{$id}' ";
		}
		$check = $this->conn->query("SELECT * FROM `genre_list` where `name` ='{$name}' ".($id > 0 ? " and id != '{$id}' " : ""))->num_rows;
		if($check > 0){
			$resp['status'] = 'failed';
			$resp['msg'] = "Genre already exists.";
		}else{
			$save = $this->conn->query($sql);
			if($save){
				$mid = !empty($id) ? $id : $this->conn->insert_id;
				$resp['status'] = 'success';
				if(empty($id))
					$resp['msg'] = "Genre has successfully added.";
				else
					$resp['msg'] = "Genre has been updated successfully.";
			}else{
				$resp['status'] = 'failed';
				$resp['msg'] = "An error occured.";
				$resp['err'] = $this->conn->error."[{$sql}]";
			}
			if($resp['status'] =='success' && !empty($id))
			$this->settings->set_flashdata('success',$resp['msg']);
			if($resp['status'] =='success' && empty($id))
			$this->settings->set_flashdata('pop_msg',$resp['msg']);
		}
		return json_encode($resp);
	}
	function delete_genre(){
		extract($_POST);
		$del = $this->conn->query("DELETE FROM `genre_list` where id = '{$id}'");
		if($del){
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success',"Genre has been deleted successfully.");

		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);
	}
	function save_keyword(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k =>$v){
			if(!in_array($k,array('id'))){
				if(!is_numeric($v))
					$v = $this->conn->real_escape_string($v);
				if(!empty($data)) $data .=",";
				$data .= " `{$k}`='{$v}' ";
			}
		}
		if(empty($id)){
			$sql = "INSERT INTO `sentiment_keywords` set {$data} ";
		}else{
			$sql = "UPDATE `sentiment_keywords` set {$data} where id = '{$id}' ";
		}
		$check = $this->conn->query("SELECT * from `sentiment_keywords` where keyword = '{$this->conn->real_escape_string($keyword)}' ".($id > 0 ? " and id != '{$id}' " : ""))->num_rows;
		if($check > 0){
			$resp['status'] = 'failed';
			$resp['msg'] = "Sentiment Keyword already exists.";
		}else{
			$save = $this->conn->query($sql);
			if($save){
				$mid = !empty($id) ? $id : $this->conn->insert_id;
				$resp['status'] = 'success';
				if(empty($id))
					$resp['msg'] = "Sentiment Keyword has been added successfully.";
				else
					$resp['msg'] = "Sentiment Keyword has been updated successfully.";
			}else{
				$resp['status'] = 'failed';
				$resp['msg'] = "An error occured.";
				$resp['err'] = $this->conn->error."[{$sql}]";
			}
			if($resp['status'] =='success' && !empty($id))
			$this->settings->set_flashdata('success',$resp['msg']);
		}
		return json_encode($resp);
	}
	function delete_keyword(){
		extract($_POST);
		$del = $this->conn->query("DELETE FROM `sentiment_keywords` where id = '{$id}'");
		if($del){
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success',"Sentiment Keyword has been deleted successfully.");

		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);
	}
	function save_movie(){
		if(isset($_POST['genre_arr'])){
			$_POST['genres'] = implode(",",$_POST['genre_arr']);
			unset($_POST['genre_arr']);
		}
		extract($_POST);
		$data = "";
		foreach($_POST as $k =>$v){
			if(!in_array($k,array('id')) && !is_array($_POST[$k])){
				if(!is_numeric($v))
					$v = $this->conn->real_escape_string($v);
				if(!empty($data)) $data .=",";
				$data .= " `{$k}`='{$v}' ";
			}
		}
		if(empty($id)){
			$sql = "INSERT INTO `movie_list` set {$data} ";
		}else{
			$sql = "UPDATE `movie_list` set {$data} where id = '{$id}' ";
		}
		
		$save = $this->conn->query($sql);
		if($save){
			$mid = !empty($id) ? $id : $this->conn->insert_id;
			$resp['id'] = $mid;
			$resp['status'] = 'success';
			if(empty($id))
				$resp['msg'] = "New Movie has successfully added.</b>.";
			else
				$resp['msg'] = "Movie Details has been updated successfully.";
			if(isset($_FILES['img']) && $_FILES['img']['tmp_name'] != ''){
				$fname = 'uploads/movie-'.$mid.'.png';
				$dir_path =base_app. $fname;
				$upload = $_FILES['img']['tmp_name'];
				$type = mime_content_type($upload);
				$allowed = array('image/png','image/jpeg');
				if(!in_array($type,$allowed)){
					$resp['msg'].=" But Image failed to upload due to invalid file type.";
				}else{
					$new_height = 385; 
					$new_width = 260; 
			
					list($width, $height) = getimagesize($upload);
					$t_image = imagecreatetruecolor($new_width, $new_height);
					imagealphablending( $t_image, false );
					imagesavealpha( $t_image, true );
					$gdImg = ($type == 'image/png')? imagecreatefrompng($upload) : imagecreatefromjpeg($upload);
					imagecopyresampled($t_image, $gdImg, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
					if($gdImg){
							if(is_file($dir_path))
							unlink($dir_path);
							$uploaded_img = imagepng($t_image,$dir_path);
							imagedestroy($gdImg);
							imagedestroy($t_image);
					}else{
					$resp['msg'].=" But Image failed to upload due to unkown reason.";
					}
				}
				if(isset($uploaded_img)){
					$this->conn->query("UPDATE movie_list set `image_path` = CONCAT('{$fname}','?v=',unix_timestamp(CURRENT_TIMESTAMP)) where id = '{$mid}' ");
				}
			}
		}else{
			$resp['status'] = 'failed';
			$resp['msg'] = "An error occured.";
			$resp['err'] = $this->conn->error."[{$sql}]";
		}
		if($resp['status'] =='success')
		$this->settings->set_flashdata('success',$resp['msg']);
		return json_encode($resp);
	}
	function delete_movie(){
		extract($_POST);
		$del = $this->conn->query("DELETE FROM `movie_list` where id = '{$id}'");
		if($del){
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success',"movie has been deleted successfully.");
		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);
	}
	function save_review(){
		$rating = 0;
		if(isset($_POST['comment'])){
			$words = preg_replace('/[.,]/',"",$_POST['comment']);
			$words = explode(" ",$words);
			if(count($words) > 0){
				$skw = 0;
				foreach($words as $kw){
					$kw = $this->conn->real_escape_string($kw);
					//echo "SELECT `score` from `sentiment_keywords` where `keyword` = '{$kw}' ";
					$get = $this->conn->query("SELECT `score` from `sentiment_keywords` where `keyword` = '{$kw}' ");
					if($get->num_rows > 0){
						$skw++;
						$rating += $get->fetch_array()['score'];
					}
				}
				$rating = ceil($rating / $skw);
			}
		}
		$_POST['rating'] = $rating;
		extract($_POST);
		$data = "";
		foreach($_POST as $k =>$v){
			if(!in_array($k,array('id'))){
				if(!is_numeric($v))
					$v = $this->conn->real_escape_string($v);
				if(!empty($data)) $data .=",";
				$data .= " `{$k}`='{$v}' ";
			}
		}
		if(empty($id)){
			$sql = "INSERT INTO `review_list` set {$data} ";
		}else{
			$sql = "UPDATE `review_list` set {$data} where id = '{$id}' ";
		}
		
		$save = $this->conn->query($sql);
		if($save){
			$mid = !empty($id) ? $id : $this->conn->insert_id;
			$resp['status'] = 'success';
			if(empty($id))
				$resp['msg'] = "Review has been added successfully.";
			else
				$resp['msg'] = "Review has been updated successfully.";
		}else{
			$resp['status'] = 'failed';
			$resp['msg'] = "An error occured.";
			$resp['err'] = $this->conn->error."[{$sql}]";
		}
		if($resp['status'] =='success' && !empty($id))
			$this->settings->set_flashdata('success',$resp['msg']);
		return json_encode($resp);
	}
	function delete_review(){
		extract($_POST);
		$del = $this->conn->query("DELETE FROM `review_list` where id = '{$id}'");
		if($del){
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success',"Review has been deleted successfully.");

		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);
	}
}

$Master = new Master();
$action = !isset($_GET['f']) ? 'none' : strtolower($_GET['f']);
$sysset = new SystemSettings();
switch ($action) {
	case 'save_storage':
		echo $Master->save_storage();
	break;
	case 'delete_storage':
		echo $Master->delete_storage();
	break;
	case 'save_movie':
		echo $Master->save_movie();
	break;
	case 'delete_movie':
		echo $Master->delete_movie();
	break;
	case 'save_message':
		echo $Master->save_message();
	break;
	case 'delete_message':
		echo $Master->delete_message();
	break;
	case 'save_genre':
		echo $Master->save_genre();
	break;
	case 'delete_genre':
		echo $Master->delete_genre();
	break;
	case 'save_keyword':
		echo $Master->save_keyword();
	break;
	case 'delete_keyword':
		echo $Master->delete_keyword();
	break;
	case 'save_review':
		echo $Master->save_review();
	break;
	case 'delete_review':
		echo $Master->delete_review();
	break;
	default:
		// echo $sysset->index();
		break;
}