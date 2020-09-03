<?PHP
$authChk = true;
require('app-lib.php');
isset($_REQUEST['sid'])? $sid = $_REQUEST['sid'] : $sid = "";
if(!$sid) {
    isset($_POST['sid'])? $sid = $_POST['sid'] : $sid = "";
}
isset($_REQUEST['a'])? $action = $_REQUEST['a'] : $action = "";
if(!$action) {
    isset($_POST['a'])? $action = $_POST['a'] : $action = "";
}
isset($_POST['txtSearch'])? $txtSearch = $_POST['txtSearch'] : $txtSearch = "";
if(!$txtSearch) {
    isset($_REQUEST['txtSearch'])? $txtSearch = $_REQUEST['txtSearch'] : $txtSearch = "";
}
if($action == "delRec") {
    $query =
        "DELETE FROM lpa_users
       WHERE
         lpa_user_ID = '$sid' LIMIT 1
      ";
    openDB();
    $result = $db->query($query);
    if($db->error) {
        printf("Errormessage: %s\n", $db->error);
        exit;
    } else {
        header("Location: users.php?a=recDel&txtSearch=$txtSearch");
        exit;
    }
}

isset($_POST['txtUserID'])? $userID = $_POST['txtUserID'] : $userID = gen_ID();
isset($_POST['txtUsername'])? $username = $_POST['txtUsername'] : $username = "";
isset($_POST['txtUserFirstName'])? $userFirstName = $_POST['txtUserFirstName'] : $userFirstName = "";
isset($_POST['txtUserLastName'])? $userLastName = $_POST['txtUserLastName'] : $userLastName = "";
isset($_POST['userGroup'])? $userGroup = $_POST['userGroup'] : $userGroup = "";
isset($_POST['txtPassword'])? $password = $_POST['txtPassword'] : $password = "";
isset($_POST['txtStatus'])? $userStatus = $_POST['txtStatus'] : $userStatus = "";
$mode = "insertRec";
if($action == "updateRec") {
    $query =
        "UPDATE lpa_users SET
         lpa_user_ID = '$userID',
         lpa_user_username = '$username',
         lpa_user_password = '$password',
         lpa_user_firstname = '$userFirstName',
         lpa_user_lastname = '$userLastName',
         lpa_user_group = '$userGroup',
         lpa_user_status = '$userStatus'
       WHERE
         lpa_user_ID = '$sid' LIMIT 1
      ";
    openDB();
    $result = $db->query($query);
    if($db->error) {
        printf("Errormessage: %s\n", $db->error);
        exit;
    } else {
        header("Location: users.php?a=recUpdate&txtSearch=$txtSearch");
        exit;
    }
}
if($action == "insertRec") {
    $query =
        "INSERT INTO lpa_users (
         lpa_user_ID,
         lpa_user_username,
         lpa_user_password,
         lpa_user_firstname,
         lpa_user_lastname,
         lpa_user_group,
         lpa_user_status
       ) VALUES (
         '$userID',
         '$username',
         '$password',
         '$userFirstName',
         '$userLastName',
         '$userGroup',
         '$userStatus'
       )
      ";
    openDB();
    $result = $db->query($query);
    if($db->error) {
        printf("Errormessage: %s\n", $db->error);
        exit;
    } else {
        header("Location: users.php?a=recInsert&txtSearch=".$userID);
        exit;
    }
}

if($action == "Edit") {
    $query = "SELECT * FROM lpa_users WHERE lpa_user_ID = '$sid' LIMIT 1";
    $result = $db->query($query);
    $row_cnt = $result->num_rows;
    $row = $result->fetch_assoc();
    $userID     = $row['lpa_user_ID'];
    $username   = $row['lpa_user_username'];
    $password   = $row['lpa_user_password'];
    $userFirstName = $row['lpa_user_firstname'];
    $userLastName  = $row['lpa_user_lastname'];
    $userGroup  = $row['lpa_user_group'];
    $userStatus = $row['lpa_user_status'];
    $mode = "updateRec";
}
build_header($displayName);
build_navBlock();
$fieldSpacer = "5px";
?>

    <div id="content">
        <div class="PageTitle">User Record Management (<?PHP echo $action; ?>)</div>
        <form name="frmUserRec" id="frmUserRec" method="post" action="<?PHP echo $_SERVER['PHP_SELF']; ?>">
            <div>
                <input name="txtUserID" id="txtUserID" placeholder="User ID" value="<?PHP echo $userID; ?>" style="width: 100px;" title="User ID">
            </div>
            <div style="margin-top: <?PHP echo $fieldSpacer; ?>">
                <input name="txtUsername" id="txtUsername" placeholder="Username" value="<?PHP echo $username; ?>" style="width: 400px;"  title="Username">
            </div>
            <div style="margin-top: <?PHP echo $fieldSpacer; ?>">
                <input name="txtUserFirstName" id="txtUserFirstName" placeholder="First Name" value="<?PHP echo $userFirstName; ?>" style="width: 400px;"  title="First Name">
            </div>
            <div style="margin-top: <?PHP echo $fieldSpacer; ?>">
                <input name="txtUserLastName" id="txtUserLastName" placeholder="Last Name" value="<?PHP echo $userLastName; ?>" style="width: 400px;"  title="Last Name">
            </div>
            <div style="margin-top: <?PHP echo $fieldSpacer; ?>">
                 <select id="userGroup" name="userGroup">
                     <option value="user">User</option>
                     <option value="administrator">Admin</option>
                 </select>
            </div>
            <div style="margin-top: <?PHP echo $fieldSpacer; ?>">
                <input name="txtPassword" id="txtPassword" placeholder="Password" value="<?PHP echo $password; ?>" style="width: 400px;"  title="Password">
            </div>
            <div style="margin-top: <?PHP echo $fieldSpacer; ?>">
                <div>User Status:</div>
                <input name="txtStatus" id="txtUserStatusActive" type="radio" value="a">
                <label for="txtUserStatusActive">Active</label>
                <input name="txtStatus" id="txtUserStatusInactive" type="radio" value="i">
                <label for="txtUserStatusInactive">Inactive</label>
            </div>
            <input name="a" id="a" value="<?PHP echo $mode; ?>" type="hidden">
            <input name="sid" id="sid" value="<?PHP echo $sid; ?>" type="hidden">
            <input name="txtSearch" id="txtSearch" value="<?PHP echo $txtSearch; ?>" type="hidden">
        </form>
        <div class="optBar">
            <button type="button" id="btnUserSave">Save</button>
            <button type="button" onclick="navMan('users.php')">Close</button>
            <?PHP if($action == "Edit") { ?>
                <button type="button" onclick="delRec('<?PHP echo $sid; ?>')" style="color: darkred; margin-left: 20px">DELETE</button>
            <?PHP } ?>
        </div>
    </div>
    <script>
        var userRecStatus = "<?PHP echo $userStatus; ?>";
        if(userRecStatus == "a") {
            $('#txtUserStatusActive').prop('checked', true);
        } else {
            $('#txtUserStatusInactive').prop('checked', true);
        }
        $("#btnUserSave").click(function(){
            $("#frmUserRec").submit();
        });
        function delRec(ID) {
            navMan("useraddedit.php?sid=" + ID + "&a=delRec");
        }
        setTimeout(function(){
            $("#txtUserName").focus();
        },1);
    </script>
<?PHP
build_footer();
?>