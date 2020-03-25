<?php
error_reporting(0);
session_start();
//include('config.php');`
//define session id
$session_id='1'; 
define ("MAX_SIZE","9000"); 
function getExtension($str)
{
         $i = strrpos($str,".");
         if (!$i) { return ""; }
         $l = strlen($str) - $i;
         $ext = substr($str,$i+1,$l);
         return $ext;
}

//set the image extentions
if(isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST") 
{
    
    $uploaddir = "upload/"; //image upload directory
    $fileArray = $_FILES['photos'];
    $response = validateFiles($fileArray);
    if($response['status']=='true'){
            foreach ($fileArray['name'] as $name => $value)
            {
                    $filename = stripslashes($fileArray['name'][$name]);
                    $image_name=time().$filename;
//                   echo "<img src='".$uploaddir.$image_name."' class='imgList'>";
                   $newname=$uploaddir.$image_name;

                   if (move_uploaded_file($fileArray['tmp_name'][$name], $newname)) 
                   {
                       //insert in database
        //           mysql_query("INSERT INTO user_uploads(image_name,user_id_fk,created) VALUES('$image_name','$session_id','$time')");
                   }
                   else
                   {
                    echo '<span class="imgList">You have exceeded the size limit! so moving unsuccessful! </span>';
                    }
            }
    } else {
    echo    $error = $response['fileinvalid']['message'];
    }
    echo '<pre>';
    print_r($response);
    die;

}

function validateFiles($files){
    $valid_formats = array("jpg", "png", "gif", "bmp","jpeg");
    $response = array('status' => 'true', 'message' => '');
    foreach ($files['name'] as $name => $value)
    {
        $filename = stripslashes($files['name'][$name]);
        $size=filesize($files['tmp_name'][$name]);
        //get the extension of the file in a lower case format
          $ext = getExtension($filename);
          $ext = strtolower($ext);

         if(in_array($ext,$valid_formats))
         {
           if ($size < (MAX_SIZE*1024))
           {
             $response['filevalid'][] = $filename;
           }
           else
           {
            $responsef['name'] = $filename;
            $responsef['message'] = 'You have exceeded the size limit!';
            $response['fileinvalid'][] = $responsef;
           }
          }
          else
         { 
             $response['fileinvalid']['name'] = $filename;
            $response['fileinvalid']['message'] = 'Unknown extension!';
         }
     }
    if(isset($response['fileinvalid']) && count($response['fileinvalid'])>0){
        $response['status'] = 'false';        
    }
    return $response;
}

?>
<form method='post' action='' enctype='multipart/form-data'>

 <input type="file" name="photos[]" id="file_id" multiple 
        accept="image/png, image/jpeg" onchange="upload_check()">
 <input type="submit">
     
</form>

<script>
    
</script>