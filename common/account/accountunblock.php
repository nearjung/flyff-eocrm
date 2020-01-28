<fieldset class="field_box">
<form name="accountsearch" method="post" action="">
  <table width="500">
    <tr>
      <td width="136" align="right">Account Name :</td>
      <td width="127"><label for="account"></label>
      <input type="text" name="account" id="account" autocomplete="off"></td>
      <td width="221"><input type="submit" class="btn_search" name="submit" id="submit" value=" " /></td>
    </tr>
  </table>
</form>
</fieldset><p><div class="animated fadeIn">
<?php
$a = $_GET['id'];
// Account Information
$account_sql = $sql->prepare("SELECT * FROM ".MSSQL_ACDB.".dbo.ACCOUNT_TBl_DETAIL WHERE account = :p1");
$account_sql->BindParam(":p1",$a);
$account_sql->execute();
$account = $account_sql->fetch(PDO::FETCH_ASSOC);
?>
<form action="" method="post" name="unblock">
<table width="757">
  <tr>
    <td align="right">Account :</td>
    <td><?php echo $account['account']; ?></td>
  </tr>
  <tr>
    <td align="right"><input name="unblock" type="submit" value="Unblock" /></td>
    <td><input name="cancel" type="button" value="Cancel" /></td>
  </tr>
</table>

</form>
<center>
<?php
if($_POST['unblock'])
{
	// Unblock
	$blocktime = "20100101";
	$update = $sql->prepare("UPDATE ".MSSQL_ACDB.".dbo.ACCOUNT_TBL_DETAIL SET BlockTime = :p1 WHERE account = :p2");
	$update->BindParam(":p1",$blocktime);
	$update->BindParam(":p2",$account['account']);
	$update->execute();
	
	$delete = $sql->prepare("DELETE ".MSSQL_DB.".Log.BanAccount WHERE Account = :p1");
	$delete->BindParam(":p1",$account['account']);
	$delete->execute();
	$api->popup("Account Update Success.");
	$api->go("index.php?page=accountban");
}
?>
</center></div>
</p>