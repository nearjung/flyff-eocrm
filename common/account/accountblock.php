<fieldset class="field_box">
<form name="accountsearch" method="post" action="index.php?page=accountmanage">
  <table width="500">
    <tr>
      <td width="136" align="right">Account Name :</td>
      <td width="127"><label for="account"></label>
      <input type="text" name="account" id="account" autocomplete="off"></td>
      <td width="221"><input type="submit" class="btn_search" name="submit" id="submit" value=" " /></td>
    </tr>
  </table>
</form>
</fieldset><p><div class="animated fadeIn"><fieldset class="field_box">
<?php 
$a = $_GET['id'];
// Account Information
$account_sql = $sql->prepare("SELECT * FROM ".MSSQL_ACDB.".dbo.ACCOUNT_TBL WHERE account = :p1");
$account_sql->BindParam(":p1",$a);
$account_sql->execute();
$account = $account_sql->fetch(PDO::FETCH_ASSOC);

// Check Account Status
$status_sql = $sql->prepare("SELECT * FROM ".MSSQL_ACDB.".dbo.ACCOUNT_TBL_DETAIL WHERE account = :p1");
$status_sql->BindParam(":p1",$a);
$status_sql->execute();
$status = $status_sql->fetch(PDO::FETCH_ASSOC);

// Found Log
$log_sql = $sql->prepare("SELECT * FROM ".MSSQL_DB.".Log.BanAccount WHERE account = :p1");
$log_sql->BindParam(":p1",$a);
$log_sql->execute();
$log = $log_sql->fetch(PDO::FETCH_ASSOC);
?>
<form action="" method="post" name="editaccount"><table width="757">
  <tr>
    <td width="275" align="right">Account Name :</td>
    <td width="470"><label for="username"></label>
      <input name="username" type="text" id="username" value="<?php echo $account['account']; ?>" readonly="readonly"></td>
  </tr>
  <tr>
    <td align="right">Date End :</td>
    <td><input name="dateban" type="text" id="datepicker" value="<?php echo $status['BlockTime']; ?>" autocomplete="off" /></td>
  </tr>
  <tr>
    <td align="right">Reason :</td>
    <td><label for="reason"></label>
      <input type="text" name="reason" id="reason" value="<?php echo $log['Reason']; ?>" /></td>
  </tr>
  <tr>
    <td align="right">&nbsp;</td>
    <td><input type="submit" name="Block" id="Block" value="Block Account" /></td>
  </tr>
</table>
</form>
&nbsp;
<?php
if($_POST['Block'])
{
	//01/09/2017
	$month = substr($_POST['dateban'],0,2);
	$day = substr($_POST['dateban'],3,2);
	$year = substr($_POST['dateban'],6,8);
	$dateconvert = "".$year."".$month."".$day."";
	$date = substr($dateconvert,0,10);
	$time = date('Ymd', time());
	if(trim($_POST['dateban']) == "")
	{
		$api->popup("Please selected Date end.");
	}
	else
	{
		$update_sql = $sql->prepare("UPDATE ".MSSQL_ACDB.".dbo.ACCOUNT_TBL_DETAIL SET BlockTime = :p1 WHERE account = :p2");
		$update_sql->BindParam(":p1",$date);
		$update_sql->BindParam(":p2",$_POST['username']);
		$update_sql->execute();
		
		// Found Log
		$log_sql = $sql->prepare("SELECT * FROM ".MSSQL_DB.".Log.BanAccount WHERE account = :p1");
		$log_sql->BindParam(":p1",$_POST['username']);
		$log_sql->execute();
		$log = $log_sql->fetch(PDO::FETCH_ASSOC);
		
		if(!$log)
		{
			// Insert Log
			$insert = $sql->prepare("INSERT INTO ".MSSQL_DB.".Log.BanAccount(Account,BlockDate,EndDate,Reason) VALUES(:p1,:p2,:p3,:p4)");
			$insert->BindParam(":p1",$_POST['username']);
			$insert->BindParam(":p2",$time);
			$insert->BindParam(":p3",$date);
			$insert->BindParam(":p4",$_POST['reason']);
			$insert->execute();
		}
		else
		{
			// Update Log
			$update_log = $sql->prepare("UPDATE ".MSSQL_DB.".Log.BanAccount SET BlockDate = :p1, EndDate = :p2, Reason = :p3 WHERE account = :p4");
			$update_log->BindParam(":p1",$time);
			$update_log->BindParam(":p2",$date);
			$update_log->BindParam(":p3",$_POST['reason']);
			$update_log->BindParam(":p4",$_POST['username']);
			$update_log->execute();
		}
		
		$api->popup("Account Banned Success.");
		$api->go("index.php?page=accountblock&id=".$a."");
	}
	
}
?>
</fieldset>
</div>
</p>