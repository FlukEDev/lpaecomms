<?PHP
$authChk = true;
require('app-lib.php');
isset($_REQUEST['invid'])? $invid = $_REQUEST['invid'] : $invid = "";
if(!$invid) {
    isset($_POST['invid'])? $invid = $_POST['invid'] : $invid = "";
}
isset($_REQUEST['a'])? $action = $_REQUEST['a'] : $action = "";
if(!$action) {
    isset($_POST['a'])? $action = $_POST['a'] : $action = "";
}
isset($_POST['txtSearch'])? $txtSearch = $_POST['txtSearch'] : $txtSearch = "";
if(!$txtSearch) {
    isset($_REQUEST['txtSearch'])? $txtSearch = $_REQUEST['txtSearch'] : $txtSearch = "";
}

//Start the action to drop the invoice
if($action == "delRec") {
    $query =
        "UPDATE lpa_invoices SET
			lpa_inv_status = 'D'
		WHERE
			lpa_inv_no = '$invid' LIMIT 1
		";
    openDB();
    $result = $db->query($query);
    if($db->error) {
        printf("Error message: %s\n", $db->error);
        exit;
    } else {
        header("Location: sales.php?a=recDel&txtSearch=$txtSearch");
        exit;
    }
}//end of the action to drop the invoice

isset($_POST['txtInvID'])? $invoiceID = $_POST['txtInvID'] : $invoiceID = null;
isset($_POST['txtDate'])? $invDate = $_POST['txtDate'] : $invDate = "";
isset($_POST['txtClientID'])? $invClientID = $_POST['txtClientID'] : $invClientID = "";
isset($_POST['txtClientName'])? $invClientName = $_POST['txtClientName'] : $invClientName = "";
isset($_POST['txtClientAddress'])? $invClientAddress = $_POST['txtClientAddress'] : $invClientAddress = "";
isset($_POST['txtInvAmount'])? $invAmount = $_POST['txtInvAmount'] : $invAmount = "0.00";
isset($_POST['txtStatus'])? $invStatus = $_POST['txtStatus'] : $invStatus = "";
$mode = "insertRec";

//Start the action to update the invoice
if($action == "updateRec") {
    $query =
        "UPDATE lpa_invoices SET 
			lpa_inv_date = '$invDate',
			lpa_inv_client_ID = '$invClientID',
			lpa_inv_client_name = '$invClientName',
			lpa_inv_client_address = '$invClientAddress',
			lpa_inv_amount = '$invAmount',
			lpa_inv_status = '$invStatus'
		WHERE
			lpa_inv_no = '$invid' LIMIT 1";
    openDB();
    $result = $db->query($query);
    if($db->error) {
        printf("Error message: %s\n", $db->error);
        exit;
    } else {
        header("Location: sales.php?a=recUpdate&txtSearch=$txtSearch");
        exit;
    }
}//end of the action to update the invoice

if($action == "insertRec") {
    $dateTime = $invDate." ".date("h:i:s");
    $query =
        "INSERT INTO lpa_invoices ( 
				lpa_inv_date,
				lpa_inv_client_ID,
				lpa_inv_client_name,
				lpa_inv_client_address,
				lpa_inv_amount,
				lpa_inv_status
			) VALUES (
				'$dateTime',
				'$invClientID',
				'$invClientName',
				'$invClientAddress',
				'$invAmount',
				'$invStatus')";
    openDB();
    $result = $db->query($query);
    if($db->error) {
        printf("Error message: %s\n", $db->error);
        exit;
    } else {
        header("Location: sales.php?a=recInsert&txtSearch=".$invClientName);
        exit;
    }
}

if($action == "Edit") {
    $query = "SELECT * FROM lpa_invoices WHERE lpa_inv_no = '$invid' LIMIT 1";
    $result = $db->query($query);
    $row_cnt = $result->num_rows;
    $row = $result->fetch_assoc();
    $invoiceID     = $row['lpa_inv_no'];
    $invDate   = $row['lpa_inv_date'];
    $invClientID   = $row['lpa_inv_client_ID'];
    $invClientName = $row['lpa_inv_client_name'];
    $invClientAddress  = $row['lpa_inv_client_address'];
    $invAmount  = $row['lpa_inv_amount'];
    $invStatus = $row['lpa_inv_status'];
    $mode = "updateRec";
}
build_header($displayName);
build_navBlock();
?>

    <div id="content">
        <div class="PageTitle">Sales Record Management (<?PHP echo $action; ?>)</div>
        <form name="frmInvoiceRec" id="frmInvoiceRec" method="post" action="<?PHP echo $_SERVER['PHP_SELF']; ?>">
            <div class="divTable">
                <?PHP
                if(isset($invoiceID)){
                    ?>
                    <div class="divTableRow">
                        <div class="divTableCell">
                            Invoice Code:
                        </div>
                        <div class="divTableCell">
                            <input name="txtInvID" id="txtInvID" value="<?PHP echo $invoiceID; ?>" style="width: 100px;" title="Invoice ID" disabled>
                        </div>
                    </div>
                    <?PHP
                }
                ?>
                <div class="divTableRow">
                    <div class="divTableCell">
                        Invoice Date:
                    </div>
                    <div class="divTableCell">
                        <?PHP
                        if($action == "Add") {
                            ?>
                            <input type ="date" name="txtDate" id="txtDate" placeholder="Invoice date" style="width: 200px;"  title="Invoice date">
                            <?PHP
                        } else {
                            ?>
                            <input type ="datetime" name="txtDate" id="txtDate" placeholder="Invoice date" value="<?PHP echo $invDate; ?>" style="width: 200px;"  title="Invoice date">
                            <?PHP
                        }
                        ?>
                    </div>
                </div>
                <div class="divTableRow">
                    <div class="divTableCell">
                        Client ID:
                    </div>
                    <div class="divTableCell">
                        <input maxlength="20" name="txtClientID" id="txtClientID" placeholder="Client ID" value="<?PHP echo $invClientID; ?>" style="width: 100px;"  title="Client ID">
                        <input type="button" value="Search..." id="lookup1" />
                    </div>
                </div>
                <div class="divTableRow">
                    <div class="divTableCell">
                        Client name:
                    </div>
                    <div class="divTableCell">
                        <input maxlength="50" name="txtClientName" id="txtClientName" placeholder="Client name" value="<?PHP echo $invClientName; ?>" style="width: 300px"  title="Client name">
                    </div>
                </div>
                <div class="divTableRow">
                    <div class="divTableCell">
                        Client address:
                    </div>
                    <div class="divTableCell">
                        <input maxlength="250" name="txtClientAddress" id="txtClientAddress" placeholder="Client address" value="<?PHP echo $invClientAddress; ?>" style="width: 400px"  title="Client address">
                    </div>
                </div>
                <div class="divTableRow">
                    <div class="divTableCell">
                        Invoice amount:
                    </div>
                    <div class="divTableCell">
                        <input name="txtInvAmount" id="txtInvAmount" placeholder="Invoice amount" value="<?PHP echo $invAmount; ?>" style="width: 90px;text-align: right"  title="Invoice amount">
                    </div>
                </div>
                <div class="divTableRow">
                    <div class="divTableCell">
                        Invoice status:
                    </div>
                    <div class="divTableCell">
                        <input name="txtStatus" id="txtInoviceStatusPaid" type="radio" value="P">
                        <label for="txtInoviceStatusPaid">Paid</label>
                        <input name="txtStatus" id="txtInoviceStatusUnpaid" type="radio" value="U">
                        <label for="txtInoviceStatusUnpaid">Unpaid</label>
                    </div>
                </div>
            </div>
            <input name="a" id="a" value="<?PHP echo $mode; ?>" type="hidden">
            <input name="invid" id="invid" value="<?PHP echo $invid; ?>" type="hidden">
            <input name="txtSearch" id="txtSearch" value="<?PHP echo $txtSearch; ?>" type="hidden">
        </form>
        <div class="optBar">
            <button type="button" id="btnInvoiceSave">Save</button>
            <button type="button" onclick="navMan('sales.php')">Close</button>
            <?PHP if($action == "Edit") { ?>
                <button type="button" onclick="delRec('<?PHP echo $invid; ?>')" style="color: darkred; margin-left: 20px">DELETE</button>
            <?PHP } ?>
        </div>
    </div>
    <script>
        var invoiceRecStatus = "<?PHP echo $invStatus; ?>";
        if(invoiceRecStatus == "P") {
            $('#txtInoviceStatusPaid').prop('checked', true);
        } else {
            $('#txtInoviceStatusUnpaid').prop('checked', true);
        }
        $("#btnInvoiceSave").click(function(){
            $("#frmInvoiceRec").submit();
        });
        function delRec(ID) {
            navMan("salesAddEdit.php?invid=" + ID + "&a=delRec");
        }
        setTimeout(function(){
            $("#txtDate").focus();
        },1);

        $(document).ready(function () {
            $("#lookup1").lookupbox({
                title: 'Search Client',
                url: 'searchClient.php?chars=',
                imgLoader: 'Loading...',
                width: 600,
                onItemSelected: function(data){
                    $('input[name=txtClientID]').val(data.lpa_client_ID);
                    $('input[name=txtClientName]').val(data.lpa_client_name);
                    $('input[name=txtClientAddress]').val(data.lpa_client_address);
                },
                tableHeader: ['Client ID', 'Client name', 'Client address']
            });
        });
    </script>
<?PHP
build_footer();
?>