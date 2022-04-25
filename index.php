<?php
require('./function.php');
$err=[];
if (!file_exists('./template.tpl'))
{
    $err=errorsPage(1,$err);
}
$tpl="";
if (count($err)===0)
$tpl=file_get_contents('./template.tpl');
session_start();
$arrVar=defineVar($tpl);
if ($arrVar===null) $err=errorsPage(2,$err);

if ((isset($_POST['destroy']))&&($_POST['destroy']==='destroy'))
{
    foreach($arrVar as $value){
	unset($_SESSION[$value]);
}
session_destroy();
}
else
{
if ((count($_SESSION)===0)&&(count($_POST)>0))
{

$err=validatePost($arrVar,$err);
if (count($err)===0)
{
foreach($arrVar as $value){

	$_SESSION[$value]=$_POST[$value];
}
}
}}
//form
if (count($_SESSION)===0)
{
   require('./header.php');
   ?>
   <H1>Fill out the form</H1>
   <?php
 foreach($err as $value)
    {
        echo "<div class=error>".$value."</div>";
    }
    ?>
<form action=index.php method=post>
    <div class=form>
                        
    <?php
    if (is_array($arrVar)){
    foreach($arrVar as $value)
    {?>
    <div class="input-section">
    <p class="input-title">

        <?=$value;?>
        :</p> <input type=text name=<?=$value." ";
        if (substr_count($value,"NUM")!==0) { echo 'onkeypress="numberInput()"';}?> required></div>
        <?php
    }?>
    <p class="description">*Please, enter only Number in Number field and at least 3 characters in String field</p>
    <input type=submit value=submit class="button save-button"></div>
    <?php }
    require('./footer.php');
}
//date
$nowDate=date('jS \of F Y');
$endDate=dateEnd();
$dateArray=['EXECDATE'=>$nowDate,'ENDDATE'=>$endDate];

//output

if (count($_SESSION)>0)
{
$newTpl=ParseStr('%',$tpl,$_SESSION);
$newTpl=ParseStr('#',$newTpl,$dateArray);
$newTpl=str_replace(array("\r\n","\r","\n"),'<br>',$newTpl);
require('./header.php');
echo '<div>'.$newTpl.'</div>';
?>
<br><div><form action=index.php method=post>
<input type=hidden name=destroy value=destroy>
<input type=submit value="Destroy session" class="button save-button"></div>
<?php
require('./footer.php');
}



?>