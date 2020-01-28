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

// Account Detail
$detail_sql = $sql->prepare("SELECT * FROM ".MSSQL_ACDB.".dbo.ACCOUNT_TBL_DETAIL WHERE account = :p1");
$detail_sql->BindParam(":p1",$account['account']);
$detail_sql->execute();
$detail = $detail_sql->fetch(PDO::FETCH_ASSOC);
?>
<form action="" method="post" name="editaccount"><table width="757">
  <tr>
    <td width="343" align="right">Account Name :</td>
    <td width="402"><label for="username"></label>
      <input type="text" name="username" id="username" value="<?php echo $account['account']; ?>"></td>
  </tr>
  <tr>
    <td align="right">Account Password :</td>
    <td><label for="password"></label>
      <input type="text" name="password" id="password" value="<?php echo $account['password']; ?>"></td>
  </tr>
  <tr>
    <td align="right">Account Email :</td>
    <td><label for="email"></label>
      <input type="email" value="<?php echo $detail['email']; ?>" name="email" id="email" /></td>
  </tr>
  <tr>
    <td align="right">Account Pin&nbsp;:</td>
    <td><label for="pin"></label>
      <input type="text" name="pin" id="pin" value="<?php echo $account['TempPassword']; ?>" /></td>
  </tr>
  <tr>
    <td align="right">Register Date :</td>
    <td><?php echo $detail['regdate']; ?></td>
  </tr>
  <tr>
    <td align="right">Account Authority :</td>
    <td><label for="auth"></label>
      <select name="auth" id="auth">
        <option value="F"<?php if($detail['m_chLoginAuthority'] == "F"){ echo 'selected="selected"'; }?>>Normal</option>
        <option value="D"<?php if($detail['m_chLoginAuthority'] == "D"){ echo 'selected="selected"'; }?>>ObServer</option>
        <option value="G"<?php if($detail['m_chLoginAuthority'] == "G"){ echo 'selected="selected"'; }?>>Log Chat</option>
        <option value="H"<?php if($detail['m_chLoginAuthority'] == "H"){ echo 'selected="selected"'; }?>>Journalist</option>
        <option value="J"<?php if($detail['m_chLoginAuthority'] == "J"){ echo 'selected="selected"'; }?>>Helper</option>
        <option value="L"<?php if($detail['m_chLoginAuthority'] == "L"){ echo 'selected="selected"'; }?>>Game Master 1</option>
        <option value="M"<?php if($detail['m_chLoginAuthority'] == "M"){ echo 'selected="selected"'; }?>>Game Master 2</option>
        <option value="N"<?php if($detail['m_chLoginAuthority'] == "N"){ echo 'selected="selected"'; }?>>Game Master 3</option>
        <option value="O"<?php if($detail['m_chLoginAuthority'] == "O"){ echo 'selected="selected"'; }?>>Operator</option>
        <option value="P"<?php if($detail['m_chLoginAuthority'] == "P"){ echo 'selected="selected"'; }?>>Administrator</option>
        <option value="Z"<?php if($detail['m_chLoginAuthority'] == "Z"){ echo 'selected="selected"'; }?>>Developer</option>
      </select></td>
  </tr>
  <tr>
    <td align="right">Account Cash :</td>
    <td><label for="cash"></label>
      <input type="text" name="cash" id="cash" value="<?php echo $account['cash']; ?>" /></td>
  </tr>
  <tr>
    <td align="right">&nbsp;</td>
    <td><input type="submit" name="chg" id="chg" value="Update Account"></td>
  </tr>
</table>
</form>
&nbsp;
<?php
if(isset($_POST['chg']))
{
	if(empty($_POST['username']) || empty($_POST['password']))
	{
		$api->popup("Please Fill all field");
	}
	else if(!is_numeric($_POST['pin']))
	{
		$api->popup("PIN Is only number");
	}
	else
	{
		$update1_sql = $sql->prepare("UPDATE ".MSSQL_ACDB.".dbo.ACCOUNT_TBL SET account = :p1, password = :p2, TempPassword = :p3, cash = :p4 WHERE account = :p5");
		$update1_sql->BindParam(":p1",$_POST['username']);
		$update1_sql->BindParam(":p2",$_POST['password']);
		$update1_sql->BindParam(":p3",$_POST['pin']);
		$update1_sql->BindParam(":p4",$_POST['cash']);
		$update1_sql->BindParam(":p5",$a);
		$update1_sql->execute();
		
		$update2_sql = $sql->prepare("UPDATE ".MSSQL_ACDB.".dbo.ACCOUNT_TBL_DETAIL SET account = :p1, email = :p2 WHERE account = :p3");
		$update2_sql->BindParam(":p1",$_POST['username']);
		$update2_sql->BindParam(":p2",$_POST['email']);
		$update2_sql->BindParam(":p3",$a);
		$update2_sql->execute();
		
		$api->popup("Account Update Success.");
		$api->go("index.php?page=accountchange&id=".$a."");
	}
}
?>
</fieldset>
</div>
</p>