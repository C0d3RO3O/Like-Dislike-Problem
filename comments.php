<?php
function setComments($conn) {
   if (isset($_POST['commentSubmit'])) {
       $uid = $_POST['uid'];
       $date = $_POST['date'];
       $message = $_POST['message'];
       $message = preg_replace (
    "/(?<!a href=\")(?<!src=\")((http|ftp)+(s)?:\/\/[^<>\s]+)/i",
    "<a href=\"\\0\" target=\"blank\">\\0</a>",
    $message
);
     $sql = "INSERT INTO comments (uid, date, message) VALUES ('".mysqli_real_escape_string($conn,$uid)."','".mysqli_real_escape_string($conn,$date)."','".mysqli_real_escape_string($conn,$message)."')";
     $result = $conn->query($sql);
     }
 }

function getComments($conn) {
  $sql = "SELECT * FROM comments";
  $result = $conn->query($sql);
  while($row = $result->fetch_assoc()) {
    $id = $row['uid'];
    $sql2 = "SELECT * FROM users WHERE id='$id'";
    $result2 = $conn->query($sql2);
    if ($row2 = $result2->fetch_assoc()) {
       echo "<div class='comment-box'><p>";
    echo $row2['first_name']."<br>";
    echo $row['date']."<br>";
    echo nl2br($row['message']);
    echo '<span class="likebtn-wrapper" data-theme="custom" data-icon_l_url="https://images-wixmp-ed30a86b8c4ca887773594c2.wixmp.com/f/9271a440-f40d-481c-9558-6ff2560a2ce5/ddc06jv-d034c295-b4ee-4cb7-a5be-4868d5ea47bd.png?token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOiJ1cm46YXBwOjdlMGQxODg5ODIyNjQzNzNhNWYwZDQxNWVhMGQyNmUwIiwiaXNzIjoidXJuOmFwcDo3ZTBkMTg4OTgyMjY0MzczYTVmMGQ0MTVlYTBkMjZlMCIsIm9iaiI6W1t7InBhdGgiOiJcL2ZcLzkyNzFhNDQwLWY0MGQtNDgxYy05NTU4LTZmZjI1NjBhMmNlNVwvZGRjMDZqdi1kMDM0YzI5NS1iNGVlLTRjYjctYTViZS00ODY4ZDVlYTQ3YmQucG5nIn1dXSwiYXVkIjpbInVybjpzZXJ2aWNlOmZpbGUuZG93bmxvYWQiXX0.cofpzee61ElSqNpoVtvkV-5IFyOThaMRwFF8NrIPtWs" data-icon_d_url="https://images-wixmp-ed30a86b8c4ca887773594c2.wixmp.com/f/9271a440-f40d-481c-9558-6ff2560a2ce5/ddc06yv-4bf21a01-e074-4a2a-966e-5497ef6a472c.png?token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOiJ1cm46YXBwOjdlMGQxODg5ODIyNjQzNzNhNWYwZDQxNWVhMGQyNmUwIiwiaXNzIjoidXJuOmFwcDo3ZTBkMTg4OTgyMjY0MzczYTVmMGQ0MTVlYTBkMjZlMCIsIm9iaiI6W1t7InBhdGgiOiJcL2ZcLzkyNzFhNDQwLWY0MGQtNDgxYy05NTU4LTZmZjI1NjBhMmNlNVwvZGRjMDZ5di00YmYyMWEwMS1lMDc0LTRhMmEtOTY2ZS01NDk3ZWY2YTQ3MmMucG5nIn1dXSwiYXVkIjpbInVybjpzZXJ2aWNlOmZpbGUuZG93bmxvYWQiXX0.-KdkfGAAe9kMfjHaN3BE0FKETbhrtcli3_BlTcoF_gM" data-bg_c="rgba(255,255,255,0)" data-brdr_c="rgba(255,255,255,0)" data-identifier="i18n" data-i18n_like=" " data-rich_snippet="true" data-i18n_dislike=" " data-i18n_after_like=" "></span>
<script>(function(d,e,s){if(d.getElementById("likebtn_wjs"))return;a=d.createElement(e);m=d.getElementsByTagName(e)[0];a.async=1;a.id="likebtn_wjs";a.src=s;m.parentNode.insertBefore(a, m)})(document,"script","//w.likebtn.com/js/w/widget.js");</script>
<!-- LikeBtn.com END -->';
	    echo "</p>";
           if (isset($_SESSION['id'])) {
             if ($_SESSION['id'] == $row2['id']) {
           echo "<form class='delete-form' method='POST' action='".deleteComments($conn)."'>
	      <input type='hidden' name='cid' value='".$row['cid']."'>
	      <button type='submit' name='commentDelete'>Delete</button>
	    </form>";
	     
          } else {
          echo "<form class='edit-form' method='POST' action='replycomment.php'>
          <input type='hidden' name='cid' value='".$row['cid']."'>
	      <input type='hidden' name='uid' value='".$row['uid']."'>
	      <input type='hidden' name='date' value='".$row['date']."'>
	      <input type='hidden' name='reply' value='".$row['reply']."'>
	      <button>Reply</button>
	    </form>";
       }
     }  else {
       echo "<p class='commentmessage'>You need to be logged in to reply</p>";
     }
	 echo "</div>";
    }
  }
}

function replyComments($conn) {
   if (isset($_POST['replySubmit'])) {
       $cid = $_POST['cid'];
       $uid = $_POST['uid'];
       $date = $_POST['date'];
       $reply = $_POST['reply'];
       $first_name = $_POST['first_name'];
             $reply = preg_replace (
    "/(?<!a href=\")(?<!src=\")((http|ftp)+(s)?:\/\/[^<>\s]+)/i",
    "<a href=\"\\0\" target=\"blank\">\\0</a>",
    $reply
);
       $sql = "INSERT INTO replies (uid, first_name, date, reply) VALUES ('".mysqli_real_escape_string($conn,$uid)."','".mysqli_real_escape_string($conn,$first_name)."','".mysqli_real_escape_string($conn,$date)."','".mysqli_real_escape_string($conn,$reply)."')";
       $result = $conn->query($sql);
       header("Location: index1.php");
     }
 }


 function deleteComments($conn) {
   if (isset($_POST['commentDelete'])) {
     $cid = $_POST['cid'];
       
     $sql = "DELETE FROM comments WHERE cid='".mysqli_real_escape_string($conn,$cid)."'";
     $result = $conn->query($sql);
     header("Location: index1.php");
     }
}




function getLogin($conn) {
   if (isset($_POST['loginSubmit'])) {
      $email = $_POST['email'];
	  $password = md5($_POST['password']);

	  $sql = "SELECT * FROM users WHERE email='$email' AND password='$password'";
	  $result = $conn->query($sql);
	   if (mysqli_num_rows($result) > 0) {
	     if($row = $result->fetch_assoc()) {
	       $_SESSION['id'] = $row['id'];
	       header("Location: index1.php?loginsuccess");
	       exit();
	    }
	  } else {
	   header("Location: index.php?loginfailed");
	   exit();
	  }
	}
 }
 
 
 
?>
<!DOCTYPE html>
<html>
    <head>
        <script>
            var count = (function () {
            var counter = 0;
            return function () {return counter +=1;}
        })();
        
    function displaycount() {
        document.getElementById("carrier").innerHTML = count();
    }
        </script>
    </head>
    <body>
        
    </body>
</html>