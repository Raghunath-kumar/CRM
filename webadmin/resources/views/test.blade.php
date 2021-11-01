  
<?php

require "conn.php";
 $action=$_POST['action'];
 
if($action=="registration")
	{
	    
	    
	 if($_POST['cid'] && $_POST['name'] && $_POST['mobile'] && $_POST['aadhar'] && isSponserOk($_POST['cid']))
	 {
	     
	 $cid=strtoupper($_POST['cid']);
	 $name=$_POST['name'];
	 $mobile=$_POST['mobile'];
	 $aadhar=$_POST['aadhar'];
	 require("./conn.php");
     
		    
			$pass=rand(111111,999999);
			$tpass=rand(1111,9999);
			$tmp=rand(100000,999999);
			$tmp1=substr($mobile,6,10);
			$newcid="MPM".$tmp1.$tmp;
			
			$sql5="select * from parent_child where cid='$cid'";
	        $smt5=mysqli_query($conn,$sql5);
	        $rs5=mysqli_fetch_assoc($smt5);
	
			if($cid=="NULL")
			{
			$unilevel=0;
			$dna="/".$cid;
			}
			else
			{
			$unilevel=$rs5['unilevel']+1;
			$dna=$rs5['dna']."/".$newcid;	
			} 
			
			$sql="insert into candidate(cid,pid,
			jdate,
			sponser_id,
			unilevel,
			dna,
			name,
			aadharnumber,
			mobile,
			password,
			transaction_pass,
			activation_date,
			rank,
			pkg_amt,
			bv,
			wallet,
			status)values('$newcid','$cid',CURDATE(),'$cid','$unilevel','$dna','$name','$aadhar','$mobile','$pass','$tpass',CURDATE(),'Associate','750','750','0','0')";
		
			$result=mysqli_query($conn,$sql);
			if($result)
			{
			
			updateRelation($cid,$newcid,$cid);
			
			$smstext="Dear ".$name.",\nThank you for joining MPMLife.\nCard No- ".$newcid."\nSecurity Code- ".$pass."\nTransaction Password- ".$tpass."\nwww.mpmlife.com";
			
			echo $smstext;
			
			$newcid=$newcid." ";
			$pass=$pass." ";
			$tpass=$tpass."\n";
			
			$sms="Dear ".$name.", Thank you for joining MPMLife.Card No- ".$newcid."Security Code- ".$pass."Transaction Password- ".$tpass."www.mpmlife.com
";
			send_sms($mobile,$sms);
			}
			else
			{
		    echo "Sorry ID not created, Your mobile may be allready registered.".mysqli_error($conn);
			}
			
			
		}
	 else
		{
			 echo "Invalid Data/Sponser, Please check and try again.".mysqli_error($conn);
		}
}
else if ($action=="save-member")
  {
	        $cid=$_POST['cid'];
			$name=$_POST['name'];
			$gardian=$_POST['gardian'];
			$address=$_POST['address'];
			$district=$_POST['district'];
			$state=$_POST['state'];
			$email=$_POST['email'];
			$mobile=$_POST['mobile'];
			$status=$_POST['status'];
			
			$sql="update candidate set name='$name',guardian='$gardian',address='$address',district='$district',state='$state',email='$email',mobile='$mobile',status='$status' where cid='$cid'";
			
			mysqli_query($conn,$sql);
			if(mysql_affected_rows()>0)
			{
				echo "Member Details Updated Successflly.";
			}
			
			
  }
 
////Functions*.....

function paySponser($cid,$amt)
{
    if($cid!=NULL ||$cid!="")
    {
      $mid=$cid;
      $sql="select sponser_id as sponser from candidate WHERE cid='$cid'";
      $smt=mysqli_query($conn,$sql);
      $rs=mysqli_fetch_assoc($smt);
      $sponser_id=$rs['sponser'];
      date_default_timezone_set('Asia/Kolkata');
      $ctime= date("H:i:s"); 
      $sql2="INSERT INTO  tbl_sposor_income(trans_dt,trans_time,cid,team_id,total_income)VALUES(CURDATE(),'$ctime','$sponser_id','$mid','$amt')";
    			
			mysqli_query($conn,$sql2);
			if(mysql_affected_rows()>0)
			{	
		      $sql1="update  candidate set wallet=wallet+$amt where cid='$sponser_id'";	
	           mysqli_query($conn,$sql1);
			}
			else{
				echo mysql_error($conn);
			}
    }
  
}
 
 
 function getSponser($cid)
{
	require("./conn.php");
  $sql="select sponser_id as sponser from candidate where cid='$cid'";
  $smt=mysqli_query($conn,$sql);
  $rs=mysqli_fetch_assoc($smt);
  return($rs['sponser']);
}

function isSponser($pid)
   {
     require("./conn.php");
     $sql="select * from candidate where cid='$pid'";
     $smt=mysqli_query($conn,$sql);
     if($rs=mysqli_fetch_assoc($smt))
     {
      return(true);
     }
     return(false);
     
   }

function isRegister($mobile)
   {
     require("./conn.php");
     $sql="select * from candidate where mobile='$mobile'";
     $smt=mysqli_query($conn,$sql);
     if($rs=mysqli_fetch_assoc($smt))
     {
      return(true);
     }
     return(false);
     
   }
   
   function isSponserOk($cid)
   {
     require("./conn.php");
     $sql="select * from candidate where cid='$cid' and status='1'";
     $smt=mysqli_query($conn,$sql);
     if(mysqli_num_rows($smt))
      return(true);
    else
     return(false);
     
   }

function isAvailable($pid,$side)
   {
     require("./conn.php");
     $sql="select * from parent_child where cid='$pid'";
     $smt=mysqli_query($conn,$sql);
     if($rs=mysqli_fetch_assoc($smt))
     {
          if($side=="Left" && $rs['lcid']=="NULL")
           return(true);
          else if($side=="Right" && $rs['rcid']=="NULL")
           return(true);
          else
           return(false);
     }
     return(false);
     
   }


function updateRelation($pid,$cid,$sponser)
{  
	require("./conn.php");
    $sql="select * from parent_child where cid='$pid'";
	$smt=mysqli_query($conn,$sql);
	$rs=mysqli_fetch_assoc($smt);
	
			if($pid=="NULL")
			{
			$unilevel=0;
			$dna="/".$cid;
			}
			else
			{
			$unilevel=$rs['unilevel']+1;
			$dna=$rs['dna']."/".$cid;	
			} 
		$sql="INSERT INTO parent_child (doj,cid,pid,unilevel,dna,sponser)VALUES (SYSDATE(),'$cid','$sponser','$unilevel','$dna','$sponser')";
		mysqli_query($conn,$sql);
				
			
}

function getCurrentId()
{
require("./conn.php");
$sql="SELECT max(cid) as cid from candidate";
$smt=mysqli_query($conn,$sql);
$rs=mysqli_fetch_assoc($smt);
$curid=$rs['cid'];
return $curid;
}

function send_sms($mob,$smstext)
    {
  	    $url="http://bhashsms.com/api/sendmsg.php";
		$user="prakashgroup";
		$password="7544991791";
		$snderid="MPMIND";
		$mobile=$mob;
		$msg=$smstext;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, 'user='.$user.'&pass='.$password.'&sender='.$snderid.'&phone='.$mobile.'&text='.$msg.'&priority=ndnd&stype=normal');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($ch);
	
     }
     
?>