<?php
  session_start();
  include_once __DIR__ . '/include/functions.php';
  include_once __DIR__ . '/model/userController.php';

  $_SESSION['isLoggedIn'] = false;


  $message = "";
  $configFile = 'model/dbconfig.ini';
  try 
  {
     $userDatabase = new Users($configFile);
  } 
  catch ( Exception $error ) 
  {
     echo "<h2>" . $error->getMessage() . "</h2>";
  }   
 
  $stateList = $userDatabase->getAllStates();

  if (isPostRequest()) 
  {
    $userName = filter_input(INPUT_POST, 'userName');
    $PW = filter_input(INPUT_POST, 'userPW');
    $userInnie = filter_input(INPUT_POST, 'userInnie');
    $userBio = filter_input(INPUT_POST, 'userBio');
    $userState = filter_input(INPUT_POST, 'userState');

    
    # Check for unique user innie, username, and then allow the userSignUp function to run.
    if (!$userDatabase->userUniqueInnie($userInnie) && !$userDatabase->userUniqueUN($userName) && $userDatabase->userSignup($userName, $PW, $userInnie, $userBio, $userState)) {
      $message = "Signed up! You can now login.";
    }else {
      $message = "Sorry, your Username and/or Innie handle must be unique. Try again!";
    }
     
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <title>Sign Up Account</title>
   <meta charset="utf-8">
   <meta name="viewport" content="min-width=device-min-width, initial-scale=1">
   <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
   <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
</head>
<body>
  <div class="container">
    <div id="mainDiv">

      <h1>User Registration</h1>
      <form action="signup.php" method="POST" enctype="multipart/form-data">
        <div>
          <label for="username">Username</label>
          <input type="text" id="userName" name="userName" class="form-control" required>
          <small id="innieHelp" class="form-text text-muted">Create a login username, do not share this with anyone.</small>
        </div>

        <br>
        <div>
          <label for="userPW">Password</label>
          <input type="password" id="userPW" name="userPW" class="form-control" required>
          <small id="innieHelp" class="form-text text-muted">Create a login password, do not share this with anyone.</small>
        </div>

        <br>
        <div>
          <label for="userInnie">Your Innie Handle (@)</label>
          <input type="text" id="userInnie" name="userInnie" class="form-control" maxlength="15" required>
          <small id="innieHelp" class="form-text text-muted">This will be what people on PlugIn see you as.</small>
        </div>

        <div>
          <!-- Hidden input, that will create the default bio "Say something about yourself". This connects the bio to the user's id upon sign up. -->
          <input type="hidden" id="userBio" name="userBio" class="form-control" value="Say something about yourself..." required>
        </div>

        <div>
          <label for="userState">State:</label>
          <select class="form-control" id="userState" name="userState" required>
            <option value="" disabled selected>Choose your state</option>
            <?php
              foreach ($stateList as $states) {
                echo '<option value="' . $states['stateName'] . '">' . $states['stateName'] . '</option>';
              }
            ?>
          </select>
        </div>

        <br>
        
        <div>
          <br>
          <button type="submit" class="btn btn-primary">Sign Up</button>
        </div>
      </form>

      <a href="login.php">Back to login page</a>
    </div>

    <br>
    <?php echo $message ?>
  </div>
</body>
</html>
</body>
</html>
