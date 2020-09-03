<?PHP
$authChk = true;
require('app-lib.php');
isset($_POST['a']) ? $action = $_POST['a'] : $action = "";
if (!$action) {
    isset($_REQUEST['a']) ? $action = $_REQUEST['a'] : $action = "";
}
isset($_POST['txtSearch']) ? $txtSearch = $_POST['txtSearch'] : $txtSearch = "";
if (!$txtSearch) {
    isset($_REQUEST['txtSearch']) ? $txtSearch = $_REQUEST['txtSearch'] : $txtSearch = "";
}
build_header($displayName);
?>
<?PHP build_navBlock(); ?>
    <div id="content">
        <div class="PageTitle">Users Management Search</div>

        <!-- Search Section Start -->
        <form name="frmUser" method="post"
              id="frmUser"
              action="<?PHP echo $_SERVER['PHP_SELF']; ?>">
            <div class="displayPane">
                <div class="displayPaneCaption">Search:</div>
                <div>
                    <input name="txtSearch" id="txtSearch" placeholder="Search User"
                           style="width: calc(100% - 115px)" value="<?PHP echo $txtSearch; ?>">
                    <button type="button" id="btnSearch">Search</button>
                    <button type="button" id="btnAddRec">Add</button>
                </div>
            </div>
            <input type="hidden" name="a" value="listUser">
        </form>
        <!-- Search Section End -->
        <!-- Search Section List Start -->
        <?PHP
        if ($action == "listUser") {
            ?>
            <div>
                <table style="width: calc(100% - 15px);border: #cccccc solid 1px">
                    <tr style="background: #eeeeee">
                        <td style="width: 80px;border-left: #cccccc solid 1px"><b>User ID</b></td>
                        <td style="border-left: #cccccc solid 1px"><b>Username</b></td>
                        <td style="width: 400px;text-align: left"><b>First Name</b></td>
                        <td style="width: 80px;text-align: left"><b>Last Name</b></td>
                        <td style="width: 80px;text-align: left"><b>Group</b></td>
                        <td style="width: 80px;text-align: center"><b>Edition</b></td>
                        <td style="width: 80px;text-align: center"><b>Remove</b></td>
                    </tr>
                    <?PHP
                    openDB();
                    $query =
                        "SELECT
            *
         FROM
            lpa_users
         WHERE
            lpa_user_id LIKE '%$txtSearch%' AND lpa_user_status <> '0'
         OR
            lpa_user_firstname LIKE '%$txtSearch%' AND lpa_user_status <> '0'

         ";
                    $result = $db->query($query);
                    $row_cnt = $result->num_rows;
                    if ($row_cnt >= 1) {
                        while ($row = $result->fetch_assoc()) {
                            $sid = $row['lpa_user_ID'];
                            ?>
                            <tr class="hl">
                                <td onclick="loadUserList(<?PHP echo $sid; ?>,'Edit')"
                                    style="width:5%; text-align: right">
                                    <?PHP echo $sid; ?>
                                </td>
                                <td onclick="loadUserList(<?PHP echo $sid; ?>,'Edit')"
                                    style="width:20%; text-align: right">
                                    <?PHP echo $row['lpa_user_username']; ?>
                                </td>
                                <td onclick="loadUserList(<?PHP echo $sid; ?>,'Edit')"
                                    style="width:20%; text-align: right">
                                    <?PHP echo $row['lpa_user_firstname']; ?>
                                </td>
                                <td onclick="loadUserList(<?PHP echo $sid; ?>,'Edit')"
                                    style=" width:20%;text-align: right">
                                    <?PHP echo $row['lpa_user_lastname']; ?>
                                </td>
                                <td onclick="loadUserList(<?PHP echo $sid; ?>,'Edit')"
                                    style="width:20%; text-align: right">
                                    <?PHP echo $row['lpa_user_group']; ?>
                                </td>
                                <td style="width:5%; text-align: center">
                                    <button id="edit" name="editUser" onclick="editUser('<?PHP echo $sid; ?>','Edit')">
                                        Edit
                                    </button>
                                </td>
                                <td style="width:5%; text-align: center">
                                    <button id="delete" name="removeUser"
                                            onclick="delUser('<?PHP echo $sid; ?>','delRec')">Delete
                                    </button>
                                </td>
                            </tr>
                        <?PHP }
                    } else { ?>
                        <tr>
                            <td colspan="3" style="text-align: center">
                                No Records Found for: <b><?PHP echo $txtSearch; ?></b>
                            </td>
                        </tr>
                    <?PHP } ?>
                </table>
            </div>
        <?PHP } ?>
        <!-- Search Section List End -->
    </div>
    <script>
        var action = "<?PHP echo $action; ?>";
        var search = "<?PHP echo $txtSearch; ?>";
        if (action == "recUpdate") {
            alert("Record Updated!");
            navMan("users.php?a=listUser&txtSearch=" + search);
        }
        if (action == "recInsert") {
            alert("Record Added!");
            navMan("users.php?a=listUser&txtSearch=" + search);
        }
        if (action == "recDel") {
            alert("Record Deleted!");
            navMan("users.php?a=listUser&txtSearch=" + search);
        }

        function loadUserList(ID, MODE) {
            window.location = "useraddedit.php?sid=" +
                ID + "&a=" + MODE + "&txtSearch=" + search;
        }

        function editUser(ID, MODE) {
            window.location = "useraddedit.php?sid=" +
                ID + "&a=" + MODE + "&txtSearch=" + search;
        }

        function delUser(ID, MODE) {
            window.location = "useraddedit.php?sid=" +
                ID + "&a=" + MODE + "&txtSearch=" + search;
        }

        $("#btnSearch").click(function () {
            $("#frmUser").submit();
        });
        $("#btnAddRec").click(function () {
            loadUserList("", "Add");
        });
        setTimeout(function () {
            $("#txtSearch").select().focus();
        }, 1);
    </script>
<?PHP
build_footer();
?>