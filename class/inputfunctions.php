<?php
require_once "/var/www/gravity/class/vars.php";
require_once "$php_dir/class/connection.php";

function validEmail($email)
{
   $isValid = true;
   $atIndex = strrpos($email, "@");
   if (is_bool($atIndex) && !$atIndex)
   {
      $isValid = false;
   }
   else
   {
      $domain = substr($email, $atIndex+1);
      $local = substr($email, 0, $atIndex);
      $localLen = strlen($local);
      $domainLen = strlen($domain);
      if ($localLen < 1 || $localLen > 64)
      {
         // local part length exceeded
         $isValid = false;
      }
      else if ($domainLen < 1 || $domainLen > 255)
      {
         // domain part length exceeded
         $isValid = false;
      }
      else if ($local[0] == '.' || $local[$localLen-1] == '.')
      {
         // local part starts or ends with '.'
         $isValid = false;
      }
      else if (preg_match('/\\.\\./', $local))
      {
         // local part has two consecutive dots
         $isValid = false;
      }
      else if (!preg_match('/^[A-Za-z0-9\\-\\.]+$/', $domain))
      {
         // character not valid in domain part
         $isValid = false;
      }
      else if (preg_match('/\\.\\./', $domain))
      {
         // domain part has two consecutive dots
         $isValid = false;
      }
      else if
(!preg_match('/^(\\\\.|[A-Za-z0-9!#%&`_=\\/$\'*+?^{}|~.-])+$/',
                 str_replace("\\\\","",$local)))
      {
         // character not valid in local part unless 
         // local part is quoted
         if (!preg_match('/^"(\\\\"|[^"])+"$/',
             str_replace("\\\\","",$local)))
         {
            $isValid = false;
         }
      }
      if ($isValid && !(checkdnsrr($domain,"MX") || checkdnsrr($domain,"A")))
      {
         // domain not found in DNS
         $isValid = false;
      }
   }
   return $isValid;
}

function register($name, $password, $email) {
    $name = trim($name);
    $name = stripslashes($name);
    $password = stripslashes($password);
    $email = stripcslashes($email);
    $name = mysql_real_escape_string($name);
    $password = mysql_real_escape_string($password);
    $email = mysql_real_escape_string($email);
    $isValid = validEmail($email);
    $hash = sha1($password);
    $sql="SELECT * FROM users WHERE name='$name'";
    $result=mysql_query($sql);
    $count=mysql_num_rows($result);
    $sql="SELECT * FROM users WHERE email='$email'";
    $result=mysql_query($sql);
    $count2=mysql_num_rows($result);
    //require_once('/var/www/class/recaptchalib.php');
    //$privatekey = "6Lf-0roSAAAAACLlnWBuUhOKwuM9w8zpozSuLIpw";
    //$resp = recaptcha_check_answer ($privatekey,
    //                            $_SERVER["REMOTE_ADDR"],
    //                            $_POST["recaptcha_challenge_field"],
    //                            $_POST["recaptcha_response_field"]);

    //if ($resp->is_valid) {
      //die ("The reCAPTCHA wasn't entered correctly. Go back and try it again." .
      //     "(reCAPTCHA said: " . $resp->error . ")");
        if ($isValid == true) {
            if($count==0) {
               if($count2==0) {
                  if(!strstr($name,' ')) {
                     $sql="INSERT INTO users (user_id, name, passwd, email) VALUES (NULL, '$name', '$hash', '$email')";
                     $result=mysql_query($sql)
                         or die(mysql_error());
                     $_SESSION['user_logged'] = $name;
                     $success = 2;
                     $to = $email;
                     $subject = "Registration";
                     $message = " Welcome to -blank-! \n You have successfully completed registration at -blank-. Thanks for registering. \n Your Username is: $name \n http://-blank- \n\n\n -------------------------------------------------------- \n Disclaimer: Internet communications are not secure, and therefore 1stvote.com does not accept legal responsibility for the contents of this message. However, 1stvote.com reserves the right to monitor the transmission of this message and to take corrective action against any misuse or abuse of its e-mail system or other components of its network. The information contained in this e-mail is confidential and may be legally privileged. It is intended solely for the addressee. If you are not the intended recipient, any disclosure, copying, distribution, or any action or act of forbearance taken in reliance on it, is prohibited and may be unlawful. Any views expressed in this e-mail are those of the individual sender. The recipient should check this e-mail for the presence of viruses. 1stvote.com accepts no liability for any damage caused by any viruses transmitted by this e-mail.";
                     $headers = 'From: noreply@-blank-' . "\r\n" .
                     'Reply-To: noreply@-blank-' . "\r\n" .
                     'X-Mailer: PHP/' . phpversion();
                     mail($to, $subject, $message, $headers);
                     $success = 2;
                  } else {
                     $success = 7;
                  }
               } else {
                  $success=8;
               }
            } else {
                $success = 1;
            }
        } else {
            $success = 4;
        }
    //} else {
    //    $success = 5;
    //}

    return $success;
}

function checkProfileEdit($name) {
    $email = mysql_real_escape_string(stripslashes($_POST['email']));
    $about = mysql_real_escape_string(stripslashes($_POST['about']));
    $emails = mysql_real_escape_string(stripslashes($_POST['emails']));
    $facebook = mysql_real_escape_string($_POST['facebook']);
    $twitter = mysql_real_escape_string($_POST['twitter']);
    $website = mysql_real_escape_string($_POST['website']);
    $ImageName = "$php_dir/profile/photos/" . $name .".jpg";
    $ImageNameSmall = "$php_dir/profile/photos/" . $name ."-m.jpg";
    $ImageNameTiny = "$php_dir/profile/photos/" . $name ."-s.jpg";
    $image=stripslashes($_FILES['image_filename']['tmp_name']);
    if ($image) {
        list($width, $height, $type, $attr) = getimagesize($_FILES['image_filename']['tmp_name']);
        if ($type == 2) {
            if (move_uploaded_file($_FILES['image_filename']['tmp_name'], $ImageName)) {
                $sql = "UPDATE cms_users SET photo='$name' WHERE name='$name'";
                $result = mysql_query($sql)
                    or die("Invalid query: " . mysql_error());
            }
            //**INSERT THESE LINES
        
            /*$newthumbname = $ImageName;
            $image_old = imagecreatefromjpeg($ImageName);*/
        
            //get the dimensions for the thumbnail
            $thumb_width = 150;
            $thumb_height = 113;
        
            //create the thumbnail
            //$largeimage = imagecreatefromjpeg($newfilename);
            /*$thumb = imagecreatetruecolor($thumb_width, $thumb_height);
            imagecopyresampled($thumb, $image_old, 0, 0, 0, 0, $thumb_width, $thumb_height, $thumb_width, $thumb_height);
            imagejpeg($thumb, $newthumbname);
            //imagedestroy($largeimage);
            imagedestroy($thumb);
            imagedestroy($image_old);*/
            $src = imagecreatefromjpeg($ImageName);
	    $src_green = imagecreatefromjpeg($ImageName);
    
            # Calculate width and height based on larger dimension
            if ( ($oldW = imagesx($src)) < ($oldH = imagesy($src)) ) {
            	$newW = $oldW * (181 / $oldH);
            	$newH = 181;
            } else {
            	$newW = 181;
            	$newH = $oldH * (181 / $oldW);
            }
	    if ($newW == 181 && $newH == 181) {
                $dx = 0;
                $dy = 0;
            } elseif ($newW < 181 && $newH == 181) {
                $dx = (181 - $newW)/2;
                $dy = 0;
            } elseif ($newW == 181 && $newH < 181) {
                $dx = 0;
                $dy = (181 - $newH)/2;
            }
            # Create a blank target image
            $dst = @imagecreatetruecolor( $newW, $newH ); //@
	    $im2 = imagecreatetruecolor( 181, 181 );
            $bg = imagecreatetruecolor( 181, 181 );
            $bg = imagecolorallocate ( $im2, 255, 255, 255 );
            imagefill ( $im2, 0, 0, $bg );
            @imagecopyresampled( $dst, $src, 0, 0, 0, 0, $newW, $newH, imagesx( $src ), imagesy( $src ) ); //@
            @imagecopymerge( $im2, $dst, $dx, $dy, 0, 0, $newW, $newH, 100); //@
            imagejpeg($im2, $ImageName);
            imagedestroy( $src );
            imagedestroy( $dst );
	    
	    // create small image
	    
	    $src = imagecreatefromjpeg($ImageName);
    
            # Calculate width and height based on larger dimension
            if ( ($oldW = imagesx($src)) < ($oldH = imagesy($src)) ) {
            	$newW = $oldW * (53 / $oldH);
            	$newH = 53;
            } else {
            	$newW = 53;
            	$newH = $oldH * (53 / $oldW);
            }
	    if ($newW == 53 && $newH == 53) {
                $dx = 0;
                $dy = 0;
            } elseif ($newW < 53 && $newH == 53) {
                $dx = (53 - $newW)/2;
                $dy = 0;
            } elseif ($newW == 53 && $newH < 53) {
                $dx = 0;
                $dy = (53 - $newH)/2;
            }
            # Create a blank target image
            $dst = @imagecreatetruecolor( $newW, $newH ); //@
	    $im2 = imagecreatetruecolor( 53, 53 );
            $bg = imagecreatetruecolor( 53, 53 );
            $bg = imagecolorallocate ( $im2, 255, 255, 255 );
            imagefill ( $im2, 0, 0, $bg );
            @imagecopyresampled( $dst, $src, 0, 0, 0, 0, $newW, $newH, imagesx( $src ), imagesy( $src ) ); //@
            @imagecopymerge( $im2, $dst, $dx, $dy, 0, 0, $newW, $newH, 100); //@
            imagejpeg($im2, $ImageNameSmall);
            imagedestroy( $src );
            imagedestroy( $dst );
	    
	    // create tiny image
	    
	    $src = $src_green;
    
            # Calculate width and height based on larger dimension
            if ( ($oldW = imagesx($src)) < ($oldH = imagesy($src)) ) {
            	$newW = $oldW * (18 / $oldH);
            	$newH = 18;
            } else {
            	$newW = 18;
            	$newH = $oldH * (18 / $oldW);
            }
	    if ($newW == 18 && $newH == 18) {
                $dx = 0;
                $dy = 0;
            } elseif ($newW < 18 && $newH == 18) {
                $dx = (18 - $newW)/2;
                $dy = 0;
            } elseif ($newW == 18 && $newH < 18) {
                $dx = 0;
                $dy = (18 - $newH)/2;
            }
            # Create a blank target image
            $dst = @imagecreatetruecolor( $newW, $newH ); //@
	    $im2 = imagecreatetruecolor( 18, 18 );
            $bg = imagecreatetruecolor( 18, 18 );
            $bg = imagecolorallocate ( $im2, 64, 123, 153 );
            imagefill ( $im2, 0, 0, $bg );
	    @imagecopyresampled( $dst, $src, 0, 0, 0, 0, $newW, $newH, imagesx( $src ), imagesy( $src ) ); //@
            @imagecopymerge( $im2, $dst, $dx, $dy, 0, 0, $newW, $newH, 100); //@
            @imagejpeg($im2, $ImageNameTiny); //@
            imagedestroy( $src );
            imagedestroy( $dst );
	    imagedestroy( $src_green );
            
            //echo "<br />";
            //echo "<img scr='/profile/photos/".$name.".jpg' />";
        } else {
            //print error message
            $error = 'Not a .jpg';
        }
    }
    if ($emails == 1) {
    } else {
	$emails = 0;
    }
    if ($facebook == 'http://') {
	$facebook = '';
    }
    if ($twitter == 'http://') {
	$twitter = '';
    }
    if ($website == 'http://') {
	$website = '';
    }
    $sql = "UPDATE cms_users SET email='$email', about='$about', facebook='$facebook', twitter='$twitter', website='$website', emails='$emails' WHERE name='$name'";
    $result = mysql_query($sql)
        or die("Invalid query: " . mysql_error());

	
    
}

function fbLogin($cookie) {
   if ($cookie) {
      $user = json_decode(@file_get_contents(
         'https://graph.facebook.com/me?access_token=' .
         $cookie['access_token']))->name;
      $_SESSION['user_logged'] = $user;
      $sql = "SELECT * FROM users WHERE name='$user'";
      $result=mysql_query($sql);
      $count=mysql_num_rows($result);
      if ($count == 1){
      } else {
         $fb_email = json_decode(@file_get_contents(
            'https://graph.facebook.com/me?access_token=' .
            $cookie['access_token']))->email;
         $hash = sha1(rand());
         if (!$user || !$fb_email) {
         } else {
            $sql="INSERT INTO users (user_id, name, passwd, email) VALUES (NULL, '$user', '$hash', '$fb_email')";
            $result=mysql_query($sql)
               or die(mysql_error());
         }
      }
      
   }
}

function checkLogin($name, $password) {
    $name = trim($name);
    $name = stripslashes($name);
    $password = stripslashes($password);
    $name = mysql_real_escape_string($name);
    $password = mysql_real_escape_string($password);
    $hash = sha1($password);
    $sql="SELECT * FROM users WHERE name='$name' and passwd='$hash'";
    $result=mysql_query($sql);
    $count=mysql_num_rows($result);
    if($count==1){
        $_SESSION['user_logged'] = $name;
        //if(isset($_POST['remember'])){
        //    setcookie("cookname", $_SESSION['user_logged'], time()+60*60*24*60, "/");
        //    setcookie("cookpass", $hash, time()+60*60*24*60, "/");
        //}
        $success1 = 2;
    } else {
        $success1 = 1;
    }
    return $success1;
}

function editProfile($name) {
    $sql="SELECT * FROM users WHERE name='$name'";
    $result=mysql_query($sql);
    while ($row = mysql_fetch_array($result)) {
        $email = $row['email'];
        $about = $row['about'];
        $photo = $row['photo'];
        $emails = $row['emails'];
        $facebook = $row['facebook'];
        $twitter = $row['twitter'];
        $website = $row['website'];
    }
    if ($_SESSION['user_logged']) {
                $sql2 = "SELECT * FROM users WHERE name ='".$_SESSION['user_logged']."'";
                $result2 = mysql_query($sql2)
                    or die(mysql_error());
                $row2 = mysql_fetch_array($result2);
                if ($row2['photo']) {
                    $photo_display = $html_dir.'/profile/photos/'.$row2['photo'];
                } else {
                    $photo_display = $html_dir.'/style/default-m.png';
                }
            }
    if ($emails == 1) {
        $checked = 'checked';
    } else {
        $checked = '';
    }
}

function imageGrabber($url, $unique = 1) {
    $startTag = '<img';
    $srcTag = 'src=';
    $endTag = '>';
    $counter = 0;
   
    if(!is_array($url))
    {
        $url = array($url);
    }
   
    if ($unique !== 0 && $unique !== 1)
    {
        printf('Invalid parameter for $unique. The parameter must be either 1 or 0.');
        exit();
    }
   
    foreach ($url as $value)
    {
        $contents = file_get_contents($value);
       
        $domain = $value;
        $domain = substr($domain, 7);
        $pos = stripos($domain, '/');
       
        if ($pos)
        {
            $domain = substr($domain, 0, stripos($domain, '/'));
        }
       
        while ($contents)
        {
            set_time_limit(0);                                    # In case we have several large pages
           
            $quotes = array('"', "'", "\n");
            $contents = str_replace($quotes, '', $contents);    # Strip " and ' as well as \n from input string
            $contents = stristr($contents, $startTag);            # Drop everything before the start tag '<img'
            $contents = stristr($contents, $srcTag);            # Drop everything before the 'src'
           
            $endTagPosition = stripos($contents, $endTag);        # Position of the end tag '>'
            $src = substr($contents, 4, $endTagPosition - 4);    # Get everything from src to end tag --> 'src="path" something>'
           
            $spacePosition = stripos($src, ' ');                # Position of space (if it exists)               
           
            if ($spacePosition !== false)
            {
                $src = substr($src, 0, $spacePosition);            # Drop everything after space, keeping 'src="path"'
            }
           
            $questionMarkPosition = stripos($src, '?');
           
            if ($questionMarkPosition !== false)
            {
                $src = substr($src, 0, $questionMarkPosition);    # Remove any part after a '?'
            }
           
            $contents = stristr($contents, $endTag);            # Drop everything before the end tag '>'
           
            if ($src)
            {
                if (stripos($src, '/') === 0)
                {
                    $src = 'http://'.$domain.$src;                # Relative link, so add domain before '/'
                }
                else
                {
                    if (stripos($src, 'http://') !== 0 && stripos($src, 'https://') !== 0 && stripos($src, 'ftp://') !== 0)
                    {
                        $src = 'http://'.$domain.'/'.$src;        # Relative link, so add domain and '/'
                    }
                }
               
                $paths[] = $src;
            }
        }
       
        if ($unique === 1)
        {
            $results[] = @array_unique($paths);        # Create final array with unique $paths
        }
        else
        {
            $results[] = $paths;                    # Create final array with all $paths
        }
       
        $paths = array();                            # Reset links
        $counter++;                                    # Increment counter
    }
   
    return $results;
}

function saveImages($results, $localPath)
{
    $j=0;
    foreach ($results as $v)
    {           
        foreach ($v as $value)
        {
            set_time_limit(0);
       
            $path = $value;
           
            if (!file_exists($localPath))
            {
                mkdir($localPath);                    # Create the dir if it doesn't exist
            }
           
            $localFile = $localPath.basename($path);
            if (!@copy($path, $localFile)) {
                //echo "<font color=red>Failed to copy $path</font><br>";
            } else {
                //echo "<font color=blue>Successfully copied $path</font><br>";
                $ImageName = $localFile;
                list($width, $height, $type, $attr) = getimagesize($ImageName);
                switch ($type) {
                    case 1:
                        $src = imagecreatefromgif($ImageName);
                        break;
                    case 2:
                        $src = imagecreatefromjpeg($ImageName);
                        break;
                    case 3:
                        $src = imagecreatefrompng($ImageName);
                        break;
                    default:
                        $src = '';
                        echo '';
                }
                if ($src) {
                    # Calculate width and height based on larger dimension
                    if ( ($oldW = imagesx($src)) < ($oldH = imagesy($src)) ) {
                        $newW = $oldW * (140 / $oldH);
                        $newH = 140;
                    } elseif ( ($oldW = imagesx($src)) == ($oldH = imagesy($src)) ) {
                        $newW = 140;
                        $newH = 140;
                    } else {
                        $newW = 140;
                        $newH = $oldH * (140 / $oldW);
                    }
                    if ($newW == 140 && $newH == 140) {
                        $dx = 0;
                        $dy = 0;
                    } elseif ($newW < 140 && $newH == 140) {
                        $dx = (140 - $newW)/2;
                        $dy = 0;
                    } elseif ($newW == 140 && $newH < 140) {
                        $dx = 0;
                        $dy = (140 - $newH)/2;
                    }
                    $dst = @imagecreatetruecolor( $newW, $newH );
                    $im2 = imagecreatetruecolor( 140, 140 );
                    $bg = imagecreatetruecolor( 140, 140 );
                    $bg = imagecolorallocate ( $im2, 255, 255, 255 );
                    imagefill ( $im2, 0, 0, $bg );
                    @imagecopyresampled( $dst, $src, 0, 0, 0, 0, $newW, $newH, imagesx( $src ), imagesy( $src ) );
                    @imagecopymerge( $im2, $dst, $dx, $dy, 0, 0, $newW, $newH, 100);
                    $localPath2 = $localPath . '2/';
                    if (!file_exists($localPath2))
                    {
                        mkdir($localPath2);                    # Create the dir if it doesn't exist
                    }
                    $j=$j+1;
                    $ImageName2 = $localPath2 . $j . '.jpg';
                    @imagejpeg($im2, $ImageName2);
                    imagedestroy( $src );
                    imagedestroy( $im2 );
                    @imagedestroy( $dst );
                }
            }
        }
    }
}

function getMoreImages($url, $localPath, $rnd) {
   $url = escapeshellarg($url);
   $connection = ssh2_connect('popcopy.webhop.org', 22);
   ssh2_auth_password($connection, 'spider', 'digibull');
   $stream = ssh2_exec($connection, 'sh /home/spider/img1v/img1v.sh "'.$url.'" "'.$rnd.'"');
   $path = "http://popcopy.webhop.org:8000/img1v/$rnd.jpg";
   $localFile = "/var/www/product/img1v/$rnd.jpg";
   $x=0;
   $sec = 10; // number of seconds to wait, retry once per sec
   while ($x<$sec) {
      if (@copy($path, $localFile)) {
         $x = $sec;
      } else {
         sleep(1);
         $x = $x + 1;
      }
   }
   $ImageName = $localFile;
   $src = @imagecreatefromjpeg($ImageName);
   list($width, $height, $type, $attr) = @getimagesize($ImageName);
   $srcx = 0;
   $srcx = 0;
   for ($i=0; $i<15; $i++) {
      $dst = @imagecreatetruecolor( 140, 140 );
      @imagecopyresampled( $dst, $src, 0, 0, $srcx, $srcy, 140, 140, 300, 300 );
      $ImageName2 = $localPath . $i . '-'.$rnd.'.jpg';
      @imagejpeg($dst, $ImageName2);
      if ($srcx < 400) {
         $srcx = $srcx + 105;
      } else {
         $srcx = 0;
         $srcy = $srcy + 140;
      }
   }
   @imagedestroy( $src );
   @imagedestroy( $dst );
}

function get_match($regex,$content) 
{ 
  preg_match($regex,$content,$matches);
  return $matches[1];
} 

function get_data($url) {
   // source http://davidwalsh.name/php-imdb-information-grabber
   $ch = curl_init();
   $timeout = 5;
   curl_setopt($ch,CURLOPT_URL,$url);
   curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
   curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);
   $data = curl_exec($ch);
   curl_close($ch);
   return $data;
}

function getContent($link) {
   $page_content = get_data($link);
   return $page_content;
}

function getTitle($page_content) {
   $title = get_match('/<title>(.*)<\/title>/isU',$page_content);
   return $title;
}

function getDescription($page_content) {
   $descripton = get_match('/<p>(.*)<\/p>/isU',$page_content);
   return $descripton;
}

function getPrice($page_content) {
   $price = get_match('/$(.*)/isU',$page_content);
   return $price;
}

function getPrice2($page_content) {
   $price = get_match('/&#36;(.*)/isU',$page_content);
   return $price;
}

function formReview($product_id, $user_id) {
    $sql = "SELECT * FROM reviews WHERE user_id='$user_id' AND product_id='$product_id'";
    $result = mysql_query($sql)
        or die(mysql_error());
    $count = mysql_num_rows($result);
    if ($count == 0) {
        require_once('/var/www/class/recaptchalib.php');
        $publickey = "6Lf-0roSAAAAABs6gkWoTG45HfeCdjy1blUj9Nqv"; // you got this from the signup page
        $captcha = recaptcha_get_html($publickey);
    } else {
        echo 'Already Reviewed';
    }
}

function submitReview($product_id, $rating, $title, $review, $user_id) {
    $sql = "SELECT * FROM reviews WHERE user_id='$user_id' AND product_id='$product_id'";
    $result = mysql_query($sql)
        or die(mysql_error());
    $count = mysql_num_rows($result);
    if ($count == 0) {
        $title = stripcslashes($title);
        $title = mysql_real_escape_string($title);
        $review = stripcslashes($review);
        $review = mysql_real_escape_string($review);
        require_once('/var/www/class/recaptchalib.php');
        $privatekey = "6Lf-0roSAAAAACLlnWBuUhOKwuM9w8zpozSuLIpw";
        $resp = recaptcha_check_answer ($privatekey,
            $_SERVER["REMOTE_ADDR"],
            $_POST["recaptcha_challenge_field"],
            $_POST["recaptcha_response_field"]);
        if ($resp->is_valid) {
            if ($rating == 1 || $rating == 2 || $rating == 3 || $rating == 4 || $rating == 5) {
                if ($title) {
                    if ($review) {
                        $DateOfRequest = date("Y-m-d H:i:s");
                        $sql = "INSERT INTO reviews (product_id, user_id, title, review, date_submitted, rating) VALUES ('$product_id', '$user_id', '$title', '$review', '$DateOfRequest', '$rating')";
                        $result = mysql_query($sql)
                            or die(mysql_error());
                        $sql = "SELECT * FROM cms_products WHERE product_id='$product_id'";
                        $result = mysql_query($sql)
                            or die(mysql_error());
                        $row = mysql_fetch_array($result);
                        if ($rating == 1) {
                            $rating1 = $row['rating1'] + 1;
                            $sql = "UPDATE cms_products SET rating1='$rating1' WHERE product_id='$product_id'";
                        }
                        if ($rating == 2) {
                            $rating2 = $row['rating2'] + 1;
                            $sql = "UPDATE cms_products SET rating2='$rating2' WHERE product_id='$product_id'";
                        }
                        if ($rating == 3) {
                            $rating3 = $row['rating3'] + 1;
                            $sql = "UPDATE cms_products SET rating3='$rating3' WHERE product_id='$product_id'";
                        }
                        if ($rating == 4) {
                            $rating4 = $row['rating4'] + 1;
                            $sql = "UPDATE cms_products SET rating4='$rating4' WHERE product_id='$product_id'";
                        }
                        if ($rating == 5) {
                            $rating5 = $row['rating5'] + 1;
                            $sql = "UPDATE cms_products SET rating5='$rating5' WHERE product_id='$product_id'";
                        }
                        $result = mysql_query($sql)
                        or die(mysql_error());
                        $error = 0;
                        //success
                    } else {
                        // Review is empty
                        $error = 1;
                    }
                } else {
                    // Title is empty
                    $error = 1;
                }
            } else {
                // Please Select a Rating
                $error = 1;
            }
        } else {
            // ReCaptcha not correct
            $error = 1;
        }
        if ($error == 1) {
            // new form
            $selected = 'selected="selected"';
            require_once('/var/www/class/recaptchalib.php');
            $publickey = "6Lf-0roSAAAAABs6gkWoTG45HfeCdjy1blUj9Nqv"; // you got this from the signup page
            echo recaptcha_get_html($publickey);
        }
    } else {
        //
        $result = 'Already Reviewed';
    }
    return $result;
}

function addFriend($name, $friend) {
    $sql = "SELECT * FROM cms_users WHERE name='$name'";
    $result = mysql_query($sql)
        or die(mysql_error());
    $row = mysql_fetch_array($result);
    $user_id = $row['user_id'];
    $sql = "SELECT * FROM cms_users WHERE name='$friend'";
    $result = mysql_query($sql)
        or die(mysql_error());
    $row = mysql_fetch_array($result);
    $friend_id = $row['user_id'];
    $sql = "SELECT * FROM friends WHERE user_id='$user_id' AND friend='$friend_id'";
    $result = mysql_query($sql)
        or die(mysql_error());
    $count = mysql_num_rows($result);
    $row = mysql_fetch_array($result);
    $sql1 = "SELECT * FROM friends WHERE user_id='$friend_id' AND friend='$user_id'";
    $result1 = mysql_query($sql1)
        or die(mysql_error());
    $count1 = mysql_num_rows($result1);
    $row1 = mysql_fetch_array($result1);
    if ($count == 0 && $count1 == 0) {
        $sql = "INSERT INTO friends (user_id, friend_level, friend) VALUES ('$user_id', '1', '$friend_id')";
        $result = mysql_query($sql)
            or die(mysql_error());
        $sql = "SELECT * FROM cms_users WHERE user_id='$friend_id'";
        $result = mysql_query($sql)
            or die(mysql_error());
        $row = mysql_fetch_array($result);
        if ($row['emails'] == 1) {
            $to = $row['email'];
            $subject = "1stvote.com Friend Added, $name added you as a friend";
            $message = "$name added you as a friend on 1stvote.com.\nIf you would also like to add $name as a friend and become\nmutual friends click here (or copy and paste url) and click add friend\nafter logging on.\n\nhttp://www.1stvote.com/profile?user=$name\n\nLog on:\nhttp://www.1stvote.com/\n\nTo stop getting emails about friend additions change your email settings at:\nhttp://www.1stvote.com/profile?user=$friend\nAnd click on Edit under email settings\n\n--------------------------------------------------------\nDisclaimer: Internet communications are not secure, and therefore 1stvote.com does not accept legal responsibility for the contents of this message. However, 1stvote.com reserves the right to monitor the transmission of this message and to take corrective action against any misuse or abuse of its e-mail system or other components of its network. The information contained in this e-mail is confidential and may be legally privileged. It is intended solely for the addressee. If you are not the intended recipient, any disclosure, copying, distribution, or any action or act of forbearance taken in reliance on it, is prohibited and may be unlawful. Any views expressed in this e-mail are those of the individual sender. The recipient should check this e-mail for the presence of viruses. 1stvote.com accepts no liability for any damage caused by any viruses transmitted by this e-mail./n";
            $headers = 'From: noreply@1stvote.com' . "\r\n" .
            'Reply-To: noreply@1stvote.com' . "\r\n" .
            'X-Mailer: PHP/' . phpversion();
            mail($to, $subject, $message, $headers);
        }
        $ans = 'Friend Added';
    } elseif ($count == 0 && $count1 == 1) {
        $sql = "INSERT INTO friends (user_id, friend_level, friend) VALUES ('$user_id', '2', '$friend_id')";
        $result = mysql_query($sql)
            or die(mysql_error());
        $sql = "UPDATE friends SET friend_level='2' WHERE user_id='$friend_id' AND friend='$user_id'";
        $result = mysql_query($sql)
            or die(mysql_error());
        
        $ans = 'Friend Added';
    } elseif ($row['friend_level'] == 2) {
        $ans = 'Friend Already Added';
    } elseif ($count == 1) {
        $ans = 'Friend Already Added';
    }
    return $ans;
}

function removeFriend($name, $friend) {
    $sql = "SELECT * FROM cms_users WHERE name='$name'";
    $result = mysql_query($sql)
        or die(mysql_error());
    $row = mysql_fetch_array($result);
    $user_id = $row['user_id'];
    $sql = "SELECT * FROM cms_users WHERE name='$friend'";
    $result = mysql_query($sql)
        or die(mysql_error());
    $row = mysql_fetch_array($result);
    $friend_id = $row['user_id'];
    $sql = "SELECT * FROM friends WHERE user_id='$user_id' AND friend='$friend_id'";
    $result = mysql_query($sql)
        or die(mysql_error());
    $count = mysql_num_rows($result);
    $row = mysql_fetch_array($result);
    $sql1 = "SELECT * FROM friends WHERE user_id='$friend_id' AND friend='$user_id'";
    $result1 = mysql_query($sql1)
        or die(mysql_error());
    $count1 = mysql_num_rows($result1);
    $row1 = mysql_fetch_array($result1);
    if ($count == 0 && $count1 == 0) {
        $ans = 'Already not Friends';
    } elseif ($count == 1 && $count1 == 0) {
        $sql = "DELETE FROM friends WHERE user_id='$user_id' AND friend='$friend_id' LIMIT 1";
        $result = mysql_query($sql);
        $ans = 'Friend Removed';
    } elseif ($count == 1 && $count1 == 1) {
        $sql = "DELETE FROM friends WHERE user_id='$user_id' AND friend='$friend_id' LIMIT 1";
        $result = mysql_query($sql);
        $ans = 'Friend Removed';
        $sql = "UPDATE friends SET friend_level='1' WHERE user_id='$friend_id' AND friend='$user_id'";
        $result = mysql_query($sql)
            or die(mysql_error());
    }
    return $ans;
}

function checkBlock($site) {
    $sql8 = "SELECT * FROM block";
    $result8 = mysql_query($sql8);
    $match = 0;
    while ($row8 = mysql_fetch_array($result8)) {
        $blk = $row8['site'];
        if (stristr($site, $blk)) {
            $match = 1;
            break;
        } else {
            $match = 0;
        }
    }
    return $match;
}

?>