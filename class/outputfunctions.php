<?php

function varProfilePhoto($name) {
    $row = dbSelectQuerySimple('*', 'gravity_users', 'name', $name);
    if ($row['photo']) {
        $photo = $row['photo'].'.jpg';
    } else {
        $photo = 'default.png';
    }
    return $photo;
}

function varProductPhoto($product_id) {
    
    $sql="SELECT * FROM products WHERE product_id='$product_id'";
    $result=mysql_query($sql);
    $row = mysql_fetch_array($result);
    if ($row['photo']) {
        $photo = $row['photo'];
    } else {
        $photo = 'default.jpg';
    }
    return $photo;
}
function varProductTitle($product_id) {
    $sql="SELECT * FROM products WHERE product_id='$product_id'";
    $result=mysql_query($sql);
    $row = mysql_fetch_array($result);
    $title = $row['title'];
    return $title;
}
function varProductDescription($product_id) {
    $sql="SELECT * FROM products WHERE product_id='$product_id'";
    $result=mysql_query($sql);
    $row = mysql_fetch_array($result);
    $description = $row['description'];
    return $description;
}
function varProductCompany($product_id) {
    $sql="SELECT * FROM products WHERE product_id='$product_id'";
    $result=mysql_query($sql);
    $row = mysql_fetch_array($result);
    $company = $row['company'];
    return $company;
}
function varProductPrice($product_id) {
    $sql="SELECT * FROM products WHERE product_id='$product_id'";
    $result=mysql_query($sql);
    $row = mysql_fetch_array($result);
    $price = $row['price'];
    return $price;
}
function varProductRating($product_id) {
    $sql="SELECT * FROM products WHERE product_id='$product_id'";
    $result=mysql_query($sql);
    $row = mysql_fetch_array($result);
    $sum = $row['rating5'] + $row['rating4'] + $row['rating3'] + $row['rating2'] + $row['rating1'];
    $average = 0;
    if ($sum != 0) {
        $average = ( ($row['rating5']*5) + ($row['rating4']*4) + ($row['rating3']*3) + ($row['rating2']*2) + $row['rating1'] ) / $sum;
    }
    return $average;
}
function displayProductVar($product_id) {
    $sql="SELECT * FROM cms_products WHERE product_id='$product_id'";
    $result=mysql_query($sql);
    $row = mysql_fetch_array($result);
    $sql_review = "SELECT * FROM reviews WHERE product_id='$product_id'";
    $result_review = mysql_query($sql_review);
    $reviews = mysql_num_rows($result_review);
        $votes = $row['votes'];
        if ($_SESSION['user_logged']) {
            $sql1="SELECT * FROM gravity_users WHERE name='".$_SESSION['user_logged']."'";
            $result1=mysql_query($sql1);
            $row1 = mysql_fetch_array($result1);
            $user_id = $row1['user_id'];
            $sql2 = "SELECT * FROM who_voted_p WHERE user_id='$user_id' AND product_id='$product_id'";
            $result2 = mysql_query($sql2);
            $count=mysql_num_rows($result2);
            if ($count == 1) {
                $vote_disp = 'Voted';
            } else {
                $vote_disp = 'Vote';
            }
        } else {
            $vote_disp = 'Vote_login';
        }
        if ($_SESSION['user_logged']) {
            $sql1="SELECT * FROM gravity_users WHERE name='".$_SESSION['user_logged']."'";
            $result1=mysql_query($sql1)
                or die(mysql_error());
            $row1 = mysql_fetch_array($result1);
            $user_id = $row1['user_id'];
            $sql2 = "SELECT * FROM who_voted_p WHERE user_id='$user_id' AND product_id='$product_id'";
            $result2 = mysql_query($sql2)
                or die(mysql_error());
            $count=mysql_num_rows($result2);
            if ($count == 1) {
            } else {
                $vote_disp = 'Vote';
            }
        } else {
            $vote_disp = 'Vote_login';
        }
        $sum = $row['rating5'] + $row['rating4'] + $row['rating3'] + $row['rating2'] + $row['rating1'];
        $average = 0;
        if ($sum != 0) {
            $average = ( ($row['rating5']*5) + ($row['rating4']*4) + ($row['rating3']*3) + ($row['rating2']*2) + $row['rating1'] ) / $sum;
        }
        if ($average == 0) {
            $stars = 0;
        } else {
            $ave = round($average);
            if ($ave == 1) {
                $stars = 1;
            }
            if ($ave == 2) {
                $stars = 2;
            }
            if ($ave == 3) {
                $stars = 3;
            }
            if ($ave == 4) {
                $stars = 4;
            }
            if ($ave == 5) {
                $stars = 5;
            }
        }
        
        $sql2 = "SELECT * FROM gravity_users WHERE user_id ='".$row['user_id']."'";
        $result2 = mysql_query($sql2)
            or die(mysql_error());
        $row2 = mysql_fetch_array($result2);
        $name = $row2['name'];
        // echo image tag with appropriate photo if it exists
        if ($row2['photo']) {
            $photo = $row2['photo'];
        } else {
            $photo = 'default';
        }
        // end echoing photo
        //share links
        $price = $row['price'];
        $title = $row['title'];
        $title_1 = substr($title, 0, 46);
        if (strlen($title) > 46) {
            $title_1 = $title_1 . "...";
        }
        $title_1u = urlencode($title_1);
        //regex url matching goes here
        $piece = explode("//", $row['link']);
        $piece2 = explode("/", $piece[1]);
        echo '<p id="url">'.$piece2[0].'</p>';
        $body = stripcslashes(stripcslashes(stripslashes($row['body'])));
        $body_1 = substr($body, 0, 2166);
        if (strlen($body) > 2166) {
            $body_1 = $body_1 . "...";
        }
}

function displayComments($product_id) {
    $sql = "SELECT * FROM gravity_comments WHERE product_id = '$product_id'";
    $result = mysql_query($sql)
        or die(mysql_error());
    while ($row = mysql_fetch_array($result)) {
        $comment_id = $row['comment_id'];
        $sql1="SELECT name FROM gravity_users WHERE user_id='".$row['user_id']."'";
        $result1=mysql_query($sql1)
            or die(mysql_error());
        $row1 = mysql_fetch_array($result1);
        $name = $row1['name'];
        $level = $row['level'];
        $comment_votes = $row['comment_votes'];
        // echo image tag with appropriate photo if it exists
        $sql3="SELECT * FROM gravity_users WHERE name='$name'";
        $result3=mysql_query($sql3);
        $row3 = mysql_fetch_array($result3);
        if ($row3['photo']) {
            $photo = $row3['photo'];
        } else {
            $photo = 'default';
        }
        // end echoing photo
        if ($_SESSION['user_logged']) {
            $sql1="SELECT * FROM gravity_users WHERE name='".$_SESSION['user_logged']."'";
            $result1=mysql_query($sql1)
                or die(mysql_error());
            $row1 = mysql_fetch_array($result1);
            $user_id = $row1['user_id'];
            $sql2 = "SELECT * FROM who_voted_c WHERE user_id='$user_id' AND comment_id='$comment_id'";
            $result2 = mysql_query($sql2)
                or die(mysql_error());
            $row2 = mysql_fetch_array($result2);
            $count=mysql_num_rows($result2);
            if ($name == $_SESSION['user_logged']) {
                $vote = 'voted';
            } else {
                if ($count == 1 && $row2['type'] == 1) {
                    $vote = 'voted';
                } else {
                    $vote = 'vote';
                }
            }
        } else {
            $vote = 'login';
        }
        if ($_SESSION['user_logged']) {
            $sql1="SELECT * FROM gravity_users WHERE name='".$_SESSION['user_logged']."'";
            $result1=mysql_query($sql1)
                or die(mysql_error());
            $row1 = mysql_fetch_array($result1);
            $user_id = $row1['user_id'];
            $sql2 = "SELECT * FROM who_voted_c WHERE user_id='$user_id' AND comment_id='$comment_id'";
            $result2 = mysql_query($sql2)
                or die(mysql_error());
            $row2 = mysql_fetch_array($result2);
            $count=mysql_num_rows($result2);
            if ($name == $_SESSION['user_logged']) {
                // echo "<span class='vote_down_buttons' id='vote_down_buttons$comment_id'>Voted</span>";
            } else {
                if ($count == 1 && $row2['type'] == -1) {
                    $vote = 'voted';
                } else {
                    $vote = 'vote_down';
                }
            }
        } else {
            $vote = 'login';
        }
    }
}

function displayReviewsVar($product_id) {
    //write a review section
    if ($_SESSION['user_logged']) {
        $sql = "SELECT * FROM reviews WHERE user_id='$user_id' AND product_id='$product_id'";
        $result = mysql_query($sql);
        $count = mysql_num_rows($result);
        if ($count == 0) {
            $review_text = 'Write a review';
            $review_link = 'href="/review/?product='.$product_id.'"';
        } else {
            $review_text = 'Reviewed';
            $review_link = '';
        }
    } else {
        $review_text = 'Write a review';
        $review_link = 'href="/class/login.php?wp='.$_SERVER['REQUEST_URI'].'"';
    }
    //bar graph section
    $sql2 = "SELECT * FROM cms_products WHERE product_id='$product_id'";
    $result2 = mysql_query($sql2)
        or die(mysql_error());
    $row2 = mysql_fetch_array($result2);
    $total = $row2['rating5'] + $row2['rating4'] + $row2['rating3'] + $row2['rating2'] + $row2['rating1'];
    if ( $total != 0 ) {
        $fiveDec = ($row2['rating5']/$total)*100;
        $fivePer = round($fiveDec);
        $fourDec = ($row2['rating4']/$total)*100;
        $fourPer = round($fourDec);
        $threeDec = ($row2['rating3']/$total)*100;
        $threePer = round($threeDec);
        $twoDec = ($row2['rating2']/$total)*100;
        $twoPer = round($twoDec);
        $oneDec = ($row2['rating1']/$total)*100;
        $onePer = round($oneDec);
    }
    $sql = "SELECT * FROM reviews WHERE product_id='$product_id'";
    $result = mysql_query($sql)
        or die(mysql_error());
    while ($row = mysql_fetch_array($result)) {
        echo '<div class="review">';
        $sql1="SELECT name FROM gravity_users WHERE user_id='".$row['user_id']."'";
        $result1=mysql_query($sql1)
            or die(mysql_error());
        $row1 = mysql_fetch_array($result1);
        $name = $row1['name'];
        //get individual stars from review rating
        $sql4 = "SELECT * FROM reviews WHERE user_id='".$row['user_id']."' AND product_id='$product_id'";
        $result4 = mysql_query($sql4)
            or die(mysql_error());
        $row4 = mysql_fetch_array($result4);
        $rating = $row4['rating'];
        if ($rating == 1) {
            $stars = 1;
        } elseif ($rating == 2) {
            $stars = 2;
        } elseif ($rating == 3) {
            $stars = 3;
        } elseif ($rating == 4) {
            $stars = 4;
        } elseif ($rating == 5) {
            $stars = 5;
        }
        //rating user image
        // echo image tag with appropriate photo if it exists
        $sql3="SELECT * FROM gravity_users WHERE name='$name'";
        $result3=mysql_query($sql3);
        $row3 = mysql_fetch_array($result3);
        if ($row3['photo']) {
            $photo = $row3['photo'];
        } else {
            $photo = 'default';
        }
        // end echoing photo
    }
}

function displayFriends($name) {
    $sql1="SELECT * FROM gravity_users WHERE name='$name'";
    $result1=mysql_query($sql1)
        or die(mysql_error());
    $row1 = mysql_fetch_array($result1);
    $user_id = $row1['user_id'];
    $sql = "SELECT * FROM friends WHERE user_id=$user_id";
    $result = mysql_query($sql)
        or die(mysql_error());
    $count = mysql_num_rows($result);
    if ($count == 0) {
        $friend_list = 'No Friends to display';
    } else {
        while ($row = mysql_fetch_array($result)) {
            $sql2 = "SELECT * from gravity_users WHERE user_id ='".$row['friend']."'";
            $result2 = mysql_query($sql2)
                or die(mysql_error());
            $row2 = mysql_fetch_array($result2);
            $friend_name = $row2['name'];
            $sql2="SELECT * FROM gravity_users WHERE name='$friend_name'";
            $result2=mysql_query($sql2)
                or die(mysql_error());
            $row2 = mysql_fetch_array($result2);
            if ($row2['photo']) {
                $photo = $row2['photo'];
            } else {
                $photo = 'default';
            }
        }
    }
}

function getPaginationString($page, $totalitems, $targetpage)
{          
        //found function and edited, atribution
        //defaults
        $limit = 21;
        if ($_GET['timespan']) {
            $pagestring = "&page=";
        } else {
            $pagestring = "?page=";
        }
         if ($_SERVER['PHP_SELF'] == '/profile/index.php' || $_SERVER['PHP_SELF'] == '/profile/submissions/index.php' || $_SERVER['PHP_SELF'] == '/profile/comments/index.php') {
            $pagestring = "page=";
            $limit = 6;
         }

        $adjacents = 1;
        if(!$adjacents) $adjacents = 1;
        if(!$limit) $limit = 21;
        if(!$page) $page = 1;
        if(!$targetpage) $targetpage = "/";
        
        //other vars
        $prev = $page - 1;                                                                      //previous page is page - 1
        $next = $page + 1;                                                                      //next page is page + 1
        $lastpage = ceil($totalitems / $limit);                         //lastpage is = total items / items per page, rounded up.
        $lpm1 = $lastpage - 1;                                                          //last page minus 1
        
        /* 
                Now we apply our rules and draw the pagination object. 
                We're actually saving the code to a variable in case we want to draw it more than once.
        */
        $pagination = "";
        if($lastpage > 1)
        {       
                $pagination .= "<div class=\"pagination\"";
                if($margin || $padding)
                {
                        $pagination .= " style=\"";
                        if($margin)
                                $pagination .= "margin: $margin;";
                        if($padding)
                                $pagination .= "padding: $padding;";
                        $pagination .= "\"";
                }
                $pagination .= ">";

                //previous button
                if ($page > 1) {
                        $pagination .= "<a href=\"$targetpage$pagestring$prev\">«prev</a>";
                        $pagination .= '&nbsp;';
                } else {
                        $pagination .= "<span class=\"disabled\">«prev</span>";
                        $pagination .= "&nbsp;";                        
                }
                //pages 
                if ($lastpage < 7 + ($adjacents * 2))   //not enough pages to bother breaking it up
                {       
                        for ($counter = 1; $counter <= $lastpage; $counter++)
                        {
                                if ($counter == $page) {
                                        $pagination .= "<span class=\"current\">$counter</span>";
                                        $pagination .= "&nbsp;";
                                } else {
                                        $pagination .= "<a href=\"" . $targetpage . $pagestring . $counter . "\">$counter</a>";
                                        $pagination .= "&nbsp;";
                                }
                        }
                }
                elseif($lastpage >= 7 + ($adjacents * 2))       //enough pages to hide some
                {
                        //close to beginning; only hide later pages
                        if($page < 1 + ($adjacents * 3))                
                        {
                                for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++)
                                {
                                        if ($counter == $page) {
                                                $pagination .= "<span class=\"current\">$counter</span>";
                                                $pagination .= "&nbsp;";
                                        } else {
                                                $pagination .= "<a href=\"" . $targetpage . $pagestring . $counter . "\">$counter</a>";
                                                $pagination .= "&nbsp;";
                                        }
                                }
                                $pagination .= "<span class=\"elipses\">...</span>";
                                $pagination .= "&nbsp;";
                                $pagination .= "<a href=\"" . $targetpage . $pagestring . $lpm1 . "\">$lpm1</a>";
                                $pagination .= "&nbsp;";
                                $pagination .= "<a href=\"" . $targetpage . $pagestring . $lastpage . "\">$lastpage</a>";
                                $pagination .= "&nbsp;";
                        }
                        //in middle; hide some front and some back
                        elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
                        {
                                $pagination .= "<a href=\"" . $targetpage . $pagestring . "1\">1</a>";
                                $pagination .= "&nbsp;";
                                $pagination .= "<a href=\"" . $targetpage . $pagestring . "2\">2</a>";
                                $pagination .= "&nbsp;";
                                $pagination .= "<span class=\"elipses\">...</span>";
                                $pagination .= "&nbsp;";
                                for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
                                {
                                        if ($counter == $page) {
                                                $pagination .= "<span class=\"current\">$counter</span>";
                                                $pagination .= "&nbsp;";
                                        } else {
                                                $pagination .= "<a href=\"" . $targetpage . $pagestring . $counter . "\">$counter</a>";
                                                $pagination .= "&nbsp;";
                                        }
                                }
                                $pagination .= "...";
                                $pagination .= "&nbsp;";
                                $pagination .= "<a href=\"" . $targetpage . $pagestring . $lpm1 . "\">$lpm1</a>";
                                $pagination .= "&nbsp;";
                                $pagination .= "<a href=\"" . $targetpage . $pagestring . $lastpage . "\">$lastpage</a>";
                                $pagination .= "&nbsp;";
                        }
                        //close to end; only hide early pages
                        else
                        {
                                $pagination .= "<a href=\"" . $targetpage . $pagestring . "1\">1</a>";
                                $pagination .= "&nbsp;";
                                $pagination .= "<a href=\"" . $targetpage . $pagestring . "2\">2</a>";
                                $pagination .= "&nbsp;";
                                $pagination .= "<span class=\"elipses\">...</span>";
                                $pagination .= "&nbsp;";
                                for ($counter = $lastpage - (1 + ($adjacents * 3)); $counter <= $lastpage; $counter++)
                                {
                                        if ($counter == $page) {
                                                $pagination .= "<span class=\"current\">$counter</span>";
                                                $pagination .= "&nbsp;";
                                        } else {
                                                $pagination .= "<a href=\"" . $targetpage . $pagestring . $counter . "\">$counter</a>";
                                                $pagination .= "&nbsp;";
                                        }
                                }
                        }
                }

 //next button
                if ($page < $counter - 1) {
                        $pagination .= "<a href=\"" . $targetpage . $pagestring . $next . "\">next»</a>";
                        $pagination .= "&nbsp;";
                } else {
                        $pagination .= "<span class=\"disabled\">next»</span>";
                        $pagination .= "&nbsp;";
                }
                $pagination .= "</div>\n";
                $pagination .= "&nbsp;";
        }
        
        return $pagination;

}

function getNameFromUID($user_id) {
    $sql = "SELECT name FROM gravity_users WHERE user_id='$user_id'";
    $result = mysql_query($sql);
    $row = mysql_fetch_array($result);
    $name = $row['name'];
    return $name;
}


function monthToName($month) {
    if ($month == 1) {
        return 'January';
    } elseif ($month == 2) {
        return 'February';
    } elseif ($month == 3) {
        return 'March';
    } elseif ($month == 4) {
        return 'April';
    } elseif ($month == 5) {
        return 'May';
    } elseif ($month == 6) {
        return 'June';
    } elseif ($month == 7) {
        return 'July';
    } elseif ($month == 8) {
        return 'August';
    } elseif ($month == 9) {
        return 'September';
    } elseif ($month == 10) {
        return 'October';
    } elseif ($month == 11) {
        return 'November';
    } elseif ($month == 12) {
        return 'December';
    }
}

function getBlogIdFromTitle($title) {
    $sql = "SELECT id FROM blog WHERE title='$title'";
    $result = mysql_query($sql)
        or die(mysql_error());
    $count = mysql_num_rows($result);
    if ($count == 0) {
        return 0;
    } else {
        $row = mysql_fetch_array($result);
        return $row['id'];
    }
}


?>
