<?php
session_start();

require "../../../../_classes/com/util/Util.php";

$name = $_FILES['upload_file']['name'];

$g_physical_path = $_SERVER[DOCUMENT_ROOT]."/"; 
$savedir1 = $g_physical_path."upload_data/editor";
$file_nm		= upload($_FILES['upload_file'], $savedir1, 10000 , array('gif', 'jpeg', 'jpg','png','GIF', 'JPEG', 'JPG','PNG'));
$file_rnm		= $_FILES['upload_file']['name'];
$file_size	= $_FILES['upload_file']['size'];
$file_ext		= end(explode('.', $_FILES['upload_file'][name]));


$file_array = array (
							'imageurl' => "/upload_data/editor/".$file_nm,
							'filename' => $file_nm,
							'filesize' => $file_size
							);

$file_array_json_string = json_encode($file_array);

echo $file_array_json_string;
?>

