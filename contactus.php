<?php 
require_once "Mail.php";
$your_email ='chefs@sltnet.lk';

session_start();
$errors = '';
$name = '';
$visitor_email = '';
$user_message = '';
$from_email = 'no-reply@sriebiz.com';

if(isset($_POST['submit']))
{
	
	$name = $_POST['name'];
	$visitor_email = $_POST['email'];
	$user_message = $_POST['message'];
	///------------Do Validations-------------
	if(empty($name)||empty($visitor_email))
	{
		$errors .= "\n Name and Email are required fields. ";	
	}
	if(IsInjected($visitor_email))
	{
		$errors .= "\n Bad email value!";
	}
	if(empty($_SESSION['6_letters_code'] ) ||
	  strcasecmp($_SESSION['6_letters_code'], $_POST['6_letters_code']) != 0)
	{

		$errors .= "<div style='border: 1px solid #CC0000;color: #CC0000;font-weight: bold;padding: 0 32px 13px 24px;width: 210px;background: none repeat scroll 0 0 #F7CBCA;'>\n The captcha code does not match!</div>";
	}
	
	if(empty($errors))
	{
		//send the email
		$to = $your_email;
		$subject="New contact us form submission";
		$from = $visitor_email;
		$ip = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '';
		
		$body = "A Visitor  $name submitted the following message form:\n".
		"Name: $name\n".
		"Email: $visitor_email \n".
		"Message: \n ".
		"$user_message\n".
		"Visitor's IP Address: $ip\n";	
		"EOM: \n ".
		
		$headers = "From: $visitor_email \r\n";
		$headers .= "Sender: $visitor_email \r\n";
		
		
		 mail ($to, $subject, $body,$headers);
		 //$mail = $smtp->send($to, $subject, $body,$headers);

		
		header('Location: sending.html');
	}
}


function IsInjected($str)
{
  $injections = array('(\n+)',
              '(\r+)',
              '(\t+)',
              '(%0A+)',
              '(%0D+)',
              '(%08+)',
              '(%09+)'
              );
  $inject = join('|', $injections);
  $inject = "/$inject/i";
  if(preg_match($inject,$str))
    {
    return true;
  }
  else
    {
    return false;
  }
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd"> 
<html>
<head>
	<title>Contact Us</title>

<style>
label,a, body 
{
	font-family : Arial, Helvetica, sans-serif;
	font-size : 12px; 
}
#caperr{
	background: none repeat scroll 0 0 #F7CBCA;
    border: 1px solid #CC0000;
    color: #CC0000;
    font-weight: bold;
    /*margin-left: 30px;
	position: absolute;*/
    padding: 8px 8px 8px 49px;
    width: 210px;
}
div.err{
	background: none repeat scroll 0 0 #F7CBCA;
    border: 1px solid #CC0000;
    color: #CC0000;
    font-weight: bold;
    /*margin-left: 30px;
	position: absolute;*/
    padding: 8px 8px 8px 49px;
    width: 210px;
	}
#contact_form_errorloc
{
	display:none;
}
</style>	
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
<script type="text/javascript" language="javascript">
<!--
$(document).ready(function() {
$("#contact_form_errorloc").addClass("contact_form_errorloc");setTimeout(function(){
$("#contact_form_errorloc").fadeOut("contact_form_errorloc")}, 4000);
});
function messagebox(){
$("#contact_form_errorloc").removeClass().addClass("confirmbox").html("Item has been saved").fadeIn(2000).fadeOut(4000);
}
function alertbox(){
$("#contact_form_errorloc").removeClass().addClass("errorbox").html("Oops, there was an error!").fadeIn(2000).fadeOut(4000);
}
-->
</script>
<script language="JavaScript" src="scripts/gen_validatorv31.js" type="text/javascript"></script>
</head>

<body>


<form method="POST" name="contact_form" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>"> 
<p>
<label for='name'>Name: </label><br>
<input type="text" name="name" value='<?php echo htmlentities($name) ?>'>
</p>
<p>
<label for='email'>Email: </label><br>
<input type="text" name="email" value='<?php echo htmlentities($visitor_email) ?>'>
</p>
<p>
<label for='message'> Your Message:</label> <br>
<textarea name="message" rows=8 cols=30><?php echo htmlentities($user_message) ?></textarea>
</p>
<?php
if(!empty($errors)){
echo "<p class='err'>".nl2br($errors)."</p>";
}
?>
<div id='contact_form_errorloc' class='err'></div>
<p>
<img src="captcha_code_file.php?rand=<?php echo rand(); ?>" id='captchaimg' ><br>
<label for='message'>Enter the code above here :</label><br>
<input id="6_letters_code" name="6_letters_code" type="text"><br>
<small>Can't read the image? click <a href='javascript: refreshCaptcha();'>here</a> to refresh</small>
</p>
<input type="submit" value="Submit" name='submit' class='btn btn-small'>
</form>

<script language="JavaScript">

var frmvalidator  = new Validator("contact_form");

frmvalidator.EnableOnPageErrorDisplaySingleBox();
frmvalidator.EnableMsgsTogether();

frmvalidator.addValidation("name","req","Please provide your name"); 
frmvalidator.addValidation("email","req","Please provide your email"); 
frmvalidator.addValidation("email","email","Please enter a valid email address"); 
</script>
<script language='JavaScript' type='text/javascript'>
function refreshCaptcha()
{
	var img = document.images['captchaimg'];
	img.src = img.src.substring(0,img.src.lastIndexOf("?"))+"?rand="+Math.random()*1000;
}
</script>

</body>
</html>