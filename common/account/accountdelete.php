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

?>
<form action="" method="post" name="editaccount"><table width="757">
  <tr>
    <td width="275" align="right">Account Name :</td>
    <td width="470"><label for="username"></label>
      <input name="username" type="text" id="username" value="<?php echo $account['account']; ?>" readonly="readonly"></td>
  </tr>
  <tr>
    <td align="right">&nbsp;</td>
    <td><input type="submit" name="delete" id="delete" value="Delete Account" /></td>
  </tr>
</table>
</form>
&nbsp;
<?php
if($_POST['delete'])
{
	$delete = $sql->prepare("DELETE ".MSSQL_DB.".Log.BanAccount WHERE account = :p1");
	$delete->BindParam(":p1",$_POST['username']);
	$delete->execute();
	
	$delete = $sql->prepare("DELETE ".MSSQL_ACDB.".dbo.ACCOUNT_TBL WHERE account = :p1");
	$delete->BindParam(":p1",$_POST['username']);
	$delete->execute();
	
	$delete = $sql->prepare("DELETE ".MSSQL_ACDB.".dbo.ACCOUNT_TBL_DETAIL WHERE account = :p1");
	$delete->BindParam(":p1",$_POST['username']);
	$delete->execute();
	$bannedauth = "D";
	$update = $sql->prepare("UPDATE ".MSSQL_CHDB.".dbo.CHARACTER_TBL SET isblock = :p1 WHERE account = :p2");
	$update->BindParam(":p1",$bannedauth);
	$update->BindParam(":p2",$_POST['username']);
	$update->execute();
	$api->popup("Account Delete Success.");
	$api->go("index.php?page=accountmanage");
	
}
?>
</fieldset>
</div>
</p>