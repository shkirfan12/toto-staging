<?php
/*
  Uploadify v2.1.4
  Release Date: November 8, 2010

  Copyright (c) 2010 Ronnie Garcia, Travis Nickels

  Permission is hereby granted, free of charge, to any person obtaining a copy
  of this software and associated documentation files (the "Software"), to deal
  in the Software without restriction, including without limitation the rights
  to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
  copies of the Software, and to permit persons to whom the Software is
  furnished to do so, subject to the following conditions:

  The above copyright notice and this permission notice shall be included in
  all copies or substantial portions of the Software.

  THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
  IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
  FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
  AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
  LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
  OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
  THE SOFTWARE.
 */
if (!empty($_FILES)) {
    $tempFile   = $_FILES['Filedata']['tmp_name'];
    $targetPath = $_SERVER['DOCUMENT_ROOT'] .'/'. $_REQUEST['folder'] . '/'; 
   // $targetPath = $_SERVER['DOCUMENT_ROOT'] . 'batchUpload/';
    // $targetFile =  str_replace('//','/',$targetPath) . $_FILES['Filedata']['name'];
    $fileTypes = str_replace('*.', '', $_REQUEST['fileext']);
    // $fileTypes = array('jpg','jpeg','gif','png'); // File extensions
    $fileTypes = str_replace(';', '|', $fileTypes);
    $typesArray = split('\|', $fileTypes);
    $fileParts = pathinfo($_FILES['Filedata']['name']);
    
    /* 3D File Validation for Upload 3D_dwg folder */
    $checkExtension = explode(".", $_FILES['Filedata']['name']);
    $res        = substr($checkExtension['0'], -2);
    /* */


    switch (strtolower($fileParts['extension'])) {
        /*case 'sif':
            $targetFile =  str_replace('//','/',$targetPath.'sif/') . $_FILES['Filedata']['name'];
            break;
        case 'dwg':
            if($res == '3D'){
                $targetFile =  str_replace('//','/',$targetPath.'3D_dwg/') . $_FILES['Filedata']['name'];
            }else{
                $targetFile =  str_replace('//','/',$targetPath.'dwg/') . $_FILES['Filedata']['name'];
            }
            break;
        case 'pdf':
            $targetFile =  str_replace('//','/',$targetPath.'pdf/') . $_FILES['Filedata']['name'];
            break;*/
        case 'jpeg':
        case 'jpg':
        case 'png':
        case 'gif':
        case 'bmp':
            $targetFile =  str_replace('//','/',$targetPath) . $_FILES['Filedata']['name'];
            $thumbPath = str_replace('//','/',$targetPath.'thumb') ;
            break;
       /* case 'rfa':
        case 'rvt':
        case 'rft':
            $targetFile =  str_replace('//','/',$targetPath.'revit/') . $_FILES['Filedata']['name'];
            break;
        case 'skp':
        case 'skb':
            $targetFile =  str_replace('//','/',$targetPath.'sketchUp/') . $_FILES['Filedata']['name'];
            break;
       default:
            $targetFile =  str_replace('//','/',$targetPath.'otherExtFiles/') . $_FILES['Filedata']['name'];*/
    }



      echo $targetFile;


 //if (in_array($fileParts['extension'],$typesArray)) {
// Uncomment the following line if you want to make the directory if it doesn't exist
// mkdir(str_replace('//','/',$targetPath), 0755, true);
     // if (file_exists($targetFile)) { 
      // echo $_FILES['Filedata']['name'] . ' file already exists';
         //echo json_encode(array('ok' => 0,'message' =>  $_FILES['Filedata']['name']." file already exists"));
    //} else {
    
    
    /*---------------------------------*/
   /* if ($targetFile)
        { $stringData = "Error: ".$_FILES['userfile']['error']." Error Info: ".$targetFile; }
    else
        { $stringData = "1"; } // This is required for onComplete to fire on Mac OSX
    echo $stringData; */
    /*--------------------------------*/
    
    
    
    
    
    
    
    
    
        move_uploaded_file($tempFile, $targetFile);
        // echo str_replace($_SERVER['DOCUMENT_ROOT'],'',$targetFile);
        
        if (!empty($thumbPath)) {
            $image_name  = $_FILES['Filedata']['name'];
            $filename    = $thumbPath;
            $thumb_width = '90';
            $thumb_hight = '90';
            $MainImgPath = str_replace('//','/',$targetPath.'main') ;
           // thumbnail($targetFile, $thumbPath, $image_name, $thumb_width, $thumb_hight, $fileParts['extension']);
           // $this->set_thumb($file, $_SERVER['DOCUMENT_ROOT'] . "/app_thumb_img", $_SERVER['DOCUMENT_ROOT'] . "/app_thumb_img/thumb", '100', '100', $checkExtension['1']);
          
            copy($targetFile,$MainImgPath.'/'.$image_name);
            //thumbnail($targetFile, $MainImgPath, $image_name, '520', '400', $fileParts['extension']); //Main Image                 
            set_thumb($image_name, $targetPath, $thumbPath, '90', '100', $fileParts['extension']); //Thumb Image
            /*if (file_exists($targetFile)) {
                unlink($targetFile);
            }*/
                            
        }
   // }
 //} else {
 //	echo 'Invalid file type.';
// }
        
        /*DB <<*/
        /*if (isset($targetFile)) {
            $pid = $_POST['pid'];
            $image_name  = $_FILES['Filedata']['name'];
            $sql = "INSERT INTO  product_img (product_img_name,product_id)   VALUES('$image_name','$pid')";
            mysql_query($sql) or die("Error in Query: " . mysql_error());
            $path = $_SERVER['DOCUMENT_ROOT'] . "/batchUpload/" . $image_name;
            unlink($path);
        } else { // Required to trigger onComplete function on Mac OSX
        echo '1';
        }*/
        /*DB >>*/
        
}


/* Thumbanail Creation For Batch Upload */
function thumbnail($image_path,$thumb_path,$image_name,$thumb_width,$thumb_hight,$ext)
{
    //$src_img = imagecreatefromjpeg("$image_path");

   /* if(!strcmp("jpg",$ext) || !strcmp("jpeg",$ext)){
    $src_img=imagecreatefromjpeg($image_path);
    }*/

    if(!strcmp("png",$ext)){
        $src_img=imagecreatefrompng($image_path);
    }else{
        $src_img=imagecreatefromjpeg($image_path);
    }

    $new_w = $thumb_width;
    $new_h = $thumb_hight;

    $dst_img = imagecreatetruecolor($new_w,$new_h);
    imagecopyresampled($dst_img,$src_img,0,0,0,0,$new_w,$new_h,imagesx($src_img),imagesy($src_img));
    //imagejpeg($dst_img, "$thumb_path/th_$image_name");
    if(!strcmp("png",$ext)){
        imagepng($dst_img, "$thumb_path/$image_name");
    }else{
         imagejpeg($dst_img, "$thumb_path/$image_name");
    }
   // RETURN TRUE;

}

/* Scale thumbnail Image */

    function set_thumb($file, $photos_dir, $thumbs_dir, $square_size, $quality, $ext) { 
        
        //check if thumb exists
        if (!file_exists($thumbs_dir . "/" . $file)) {
            //get image info
            list($width, $height, $type, $attr) = getimagesize($photos_dir . "/" . $file);

            //set dimensions
            if ($width > $height) {
                $width_t = $square_size;
                //respect the ratio
                $height_t = round($height / $width * $square_size);
                //set the offset
                $off_y = ceil(($width_t - $height_t) / 2);
                $off_x = 0;
            } elseif ($height > $width) {
                $height_t = $square_size;
                $width_t = round($width / $height * $square_size);
                $off_x = ceil(($height_t - $width_t) / 2);
                $off_y = 0;
            } else {
                $width_t = $height_t = $square_size;
                $off_x = $off_y = 0;
            }

            //$thumb=imagecreatefromjpeg($photos_dir."/".$file);
            if (!strcmp("png", $ext)) {
                $thumb = imagecreatefrompng($photos_dir . "/" . $file);
            } else {
                $thumb = imagecreatefromjpeg($photos_dir . "/" . $file);
            }

            $thumb_p = imagecreatetruecolor($square_size, $square_size);
            //default background is black
            $bg = imagecolorallocate($thumb_p, 255, 255, 255);
            imagefill($thumb_p, 0, 0, $bg);
            imagecopyresampled($thumb_p, $thumb, $off_x, $off_y, 0, 0, $width_t, $height_t, $width, $height);

            //imagejpeg($thumb_p,$thumbs_dir."/th_".$file,$quality);
            if (!strcmp("png", $ext)) {
                imagepng($thumb_p, $thumbs_dir . "/" . $file);
            } else {
                imagejpeg($thumb_p, $thumbs_dir . "/" . $file, $quality);
            }
        }
    }


 

?>

   
<?php

/*
  if (!empty($_FILES)) {
  $tempFile = $_FILES['Filedata']['tmp_name'];
  $targetPath = $_SERVER['DOCUMENT_ROOT'] . $_REQUEST['folder'] . '/';
  $targetFile =  str_replace('//','/',$targetPath) . $_FILES['Filedata']['name'];

  // $fileTypes  = str_replace('*.','',$_REQUEST['fileext']);
  // $fileTypes  = str_replace(';','|',$fileTypes);
  // $typesArray = split('\|',$fileTypes);
  // $fileParts  = pathinfo($_FILES['Filedata']['name']);

  // if (in_array($fileParts['extension'],$typesArray)) {
  // Uncomment the following line if you want to make the directory if it doesn't exist
  // mkdir(str_replace('//','/',$targetPath), 0755, true);

  move_uploaded_file($tempFile,$targetFile);
  echo str_replace($_SERVER['DOCUMENT_ROOT'],'',$targetFile);
  // } else {
  // 	echo 'Invalid file type.';
  // }
  }
 */
?>