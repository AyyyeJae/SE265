<!DOCTYPE html>
<?php
ob_start();
//call other files
include_once "../model/userController.php";
include_once "../include/functions.php";
include_once "../include/header.php";


$configFile = '../model/dbconfig.ini';
try {
   $userDatabase = new Users($configFile);
} catch (Exception $error) {
   echo "<h2>" . $error->getMessage() . "</h2>";
}

/* get the list ID from the URL, send it to the method */
$parentID = NULL;
$listID = "";
$listID = $_GET['listID'];
$listDetails = $userDatabase->getListForm($listID);
/* get the seller's info */
$userID = $listDetails['userID'];
$sellerInfo = $userDatabase->getUserDetails($userID);
/* get customer's info */
$customerID = $_SESSION['userID'];
$customerInfo = $userDatabase->getCustomerDetails($customerID);
/* Set empty arrays */
$fileDestination = "";




if (!array_key_exists('isLoggedIn', $_SESSION) || !$_SESSION['isLoggedIn']) {
   $visitCrumb = ('backend/productDetails.php?listID='.$listID);
   header("location: ../login.php?visitCrumb=$visitCrumb");
   exit;
}


if (isPostRequest()) {
   $parentID = uniqid();
   $listID = filter_input(INPUT_POST, 'listID');
   $customerID = $_SESSION['userID'];
   /* get the $userID thensent it to the method */
   $sellerID = filter_input(INPUT_POST, 'sellerID');
   $sellerInnie = filter_input(INPUT_POST, 'sellerInnie');
   $customerInnie = filter_input(INPUT_POST, 'customerInnie');
   $messageTitle = filter_input(INPUT_POST, 'messageTitle');
   $messageDesc = filter_input(INPUT_POST, 'messageDesc');
   $fileDestination = filter_input(INPUT_POST, 'fileDestination');
   $listDetails = $userDatabase->getListForm($listID);
   $sellerInfo = $userDatabase->getUserDetails($userID);
   $customerInfo = $userDatabase->getCustomerDetails($customerID);
   $fileDestination = filter_input(INPUT_POST, 'fileDestination');
   $fileDestination2 = filter_input(INPUT_POST, 'fileDestination2');
   $fileDestination3 = filter_input(INPUT_POST, 'fileDestination3');
   $fileDestination4 = filter_input(INPUT_POST, 'fileDestination4');
   $isMessageReplied = filter_input(INPUT_POST, 'isMessageReplied');
   echo $isMessageReplied;


   #--- Profile Picture Traveling -- #
   $file = $_FILES['sendPic'];
   if ($file['error'] != UPLOAD_ERR_NO_FILE) {
      $fileDestination = '../backend/messageUpload/' . $file['name'];
      move_uploaded_file($file['tmp_name'], $fileDestination);
   }
   $file2 = $_FILES['sendPic2'];
   if ($file2['error'] != UPLOAD_ERR_NO_FILE) {
      $fileDestination2 = '../backend/messageUpload/' . $file2['name'];
      move_uploaded_file($file2['tmp_name'], $fileDestination2);
   }
   $file3 = $_FILES['sendPic3'];
   if ($file3['error'] != UPLOAD_ERR_NO_FILE) {
      $fileDestination3 = '../backend/messageUpload/' . $file3['name'];
      move_uploaded_file($file3['tmp_name'], $fileDestination3);
   }
   $file4 = $_FILES['sendPic4'];
   if ($file4['error'] != UPLOAD_ERR_NO_FILE) {
      $fileDestination4 = '../backend/messageUpload/' . $file4['name'];
      move_uploaded_file($file4['tmp_name'], $fileDestination4);
   }

   if ($userDatabase->sendMessage($parentID,$customerID, $sellerID, $listID, 
   $messageTitle, $messageDesc, $fileDestination, $customerInnie, 
   $sellerInnie, $fileDestination2, $fileDestination3, $fileDestination4,$isMessageReplied)) {
      header("location: displayResults.php");
      $message = "Your Request Was Sent!";

   } else {
      $message = "Error sending message, please try again.";
   } 
}
?>
<style>
   .container {
      padding: 15px;
   }

   .pageTitle{
      font-size: 20px;
      font-weight: bold;
   }
   .listProdTitle {
      font-weight: bold;
      font-size: 25px;

      /* limit title width to the same width of the of the image */
   }

   .listProdCat {
      font-weight: bold;
      color: #506D90;
      font-size: 13px;

   }

   .listProdPrice {
      font-weight: bold;
      padding-top: 3px;
      font-size: 18px;
      color: #001829;
   }

   .listCond {
      font-weight: bold;
      color: #506d90;
      font-size: 13px;

   }

   .listState {
      font-weight: bold;
      color: #506D90;
      font-size: 13px;

   }
   .listPostedOn{
      font-weight: bold;
      color: #506D90;
      font-size: 13px;
   }

   .listSeller {
      font-size: 13px;
      color: #506d90;
   }
   .listDesc{
      border: 1px solid #E5E5E5;
      box-shadow: 0px 10px 10px #e9ecef;
      padding: 15px;
      border-radius: 15px;
      margin-top: 25px;
   }

   .listImgBox {}

   .listInfoBox {

   }

   .requestInfo {
      background-color: #F8F9FA;
      border: 1px solid #E5E5E5;
      box-shadow: 5px 10px 10px #E5E5E5;
      padding: 15px;
      border-radius: 15px;
   }
   .requestForm{
      margin-top: 15px;
      background-color: #F8F9FA;
      border: 1px solid #E5E5E5;
      box-shadow: 5px 10px 10px #E5E5E5;
      padding: 25px;
      border-radius: 15px;
   }

   .btnSend {
      margin-top: 15px;
      margin-left: 95%;
   }
   #sendPic,
   #sendPic2,
   #sendPic3,
   #sendPic4 {
      width: 73px;
      height: 25px;
      margin-right: 5px;
      padding: 0px;
      border: 0px;
   }
</style>


<div class="container">

   <div class="row requestInfo">
      <div class="col-sm-12 pageTitle">
         About... 
      </div>
      <div class="col-sm-5 listImgBox">
         <!-- posted from this user -->
         <input type="hidden" name="sellerID" value="<?= $listDetails['userID']; ?>" />
         <!-- product listID -->
         <input type="hidden" name="p_id" value="<?= $listDetails['listID']; ?>" />
         <div class="listProdPic"><img src="<?= $listDetails['listProdPic']; ?>"
               style="object-fit: contain; object-position: center; width: 250px; height: 250px; background-color: #F6F6F6;">
         </div>
      </div>

      <div class="col-sm-7 listInfoBox">
         <div class="listProdTitle">
            <?= $listDetails['listProdTitle']; ?>
         </div>
         <div class="listProdCat"> Listed in:
            <?= $listDetails['listProdCat']; ?>
         </div>
         <div class="listPostedOn">Posted on:
            <?php echo date("Y-m-d", strtotime($listDetails['listPostedOn'])); ?>
         </div>
         <div class="listState">State:
            <?= $listDetails['listState']; ?>
         </div>
         <div class="listProdPrice">$
            <?= $listDetails['listProdPrice']; ?>
         </div>
         <div class="listCond">
            <?= $listDetails['listCond']; ?>
         </div>

      </div>

         <div class="col-sm-12 listDesc">
            <b>Description</b>
               <?= $listDetails['listDesc']; ?>
         </div>  

   </div> <!-- close requestInfo row-->

   <div class="row">
      <div class="col-sm-12 requestForm">
         <!-- message info -->
         <form action="requestProduct.php" method="post" enctype="multipart/form-data">
            <br>
            <!-- hidden listID -->
            <div>
               <input type="hidden" class="form-control" id="listID" name="listID" value="<?= $listDetails['listID']; ?>">
            </div>
            <!-- hidden userID SEND TO -->
            <div>
               <input type="hidden" class="form-control" id="customerID" name="customerID" value="<?php echo $customerID; ?>">
            </div>
            <div>
               <input type="hidden" class="form-control" id="customerInnie" name="customerInnie"
                  value="<?= $customerInfo['userInnie']; ?>">
            </div>
            <div>
               <input type="hidden" class="form-control" id="sellerID" name="sellerID" value="<?= $listDetails['userID']; ?>">
            </div>
            <!-- hidden condition -->
            <div>
               <input type="hidden" class="form-control" id="isMessageReplied" name="isMessageReplied" value="No">
            </div>
            <div>
               <label for="sellerInnie">To:</label>
               <input type="text" class="form-control" id="sellerInnie" name="sellerInnie"
                  value="<?= $sellerInfo['userInnie']; ?>" readonly>
            </div>
            <!-- hidden title -->
            <div>
               <input type="hidden" class="form-control" id="messageTitle" name="messageTitle"
                  value="Requested: <?= $listDetails['listProdTitle']; ?>">
            </div>
            <div>
               <br>
               <label for="messageDesc">Message</label>
               <textarea class="form-control" id="messageDesc" name="messageDesc" rows="3" maxlength="275"
                  required></textarea>
            </div>
            <br>
            <div class="container insertPics">
                     <div class="row">
                        <div class="col-xs-1">
                           <input type="file" id="sendPic" name="sendPic" class="form-control" accept="image/*">
                        </div>
                        <div class="col-xs-1">
                           <input type="file" id="sendPic2" name="sendPic2" class="form-control" accept="image/*">
                        </div>
                        <div class="col-xs-1">
                           <input type="file" id="sendPic3" name="sendPic3" class="form-control" accept="image/*">
                        </div>
                        <div class="col-xs-1">
                           <input type="file" id="sendPic4" name="sendPic4" class="form-control" accept="image/*">
                        </div>
                     </div>
                  </div>
            <div class="btnSend">
               <button class="btn btn-md btn-primary">Send</button>
            </div>
         </form>
      </div>
   </div>
</div> <!-- close container -->

</body>
</html>
<?php include_once '../include/footer.php'; ?>