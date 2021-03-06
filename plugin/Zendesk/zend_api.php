<script type="text/javascript">
    $(document).ready(function() {
    $('#submitaction').click(function(event) {
		
		var zend_url = $('#zend_url').val();
		var zend_email = $('#zend_email').val();
		var zend_api = $('#zend_api').val();
		var idproject = $( "#idproject" ).val();
		if((zend_url == '') || (zend_email == '') || (zend_api == '') || (idproject == '')){
			alert('Please fill in all the details metioned.');
			event.preventDefault();
		}		
	});
});
</script>

<?php
    $iduser = $_SESSION['do_User']->iduser;
	
	$do_zend = new Zendesk();
	$data = $do_zend->GetUserZendeskDetails($iduser);
	?>
	<?php if(isset($_SESSION['msg'])){?>
	<div id="msg"><?php echo $_SESSION['msg'];?></div>
	<?php 
		$_SESSION['msg'] = ''; 
		unset($_SESSION['msg']); 
	} ?>
	<?php if(is_array($data)){ ?>
	<table border=0 width="75%">
		<tr>
			<td><b>Zend Email</b> </td> <td><b>Zend API</b> </td> <td><b>Zend URL</b></td><td><b>Project</b></td><td><b>Unlink</b></td>
		</tr>
	<?php
	
	for($i=0;$i <= max(array_map('count', $data)) - 1; $i++){
		  $un_link = new Event("Zendesk->eventunlinkZend");
		  $un_link->addParam("iduser_zendesk", $data['iduser_zendesk'][$i]);		 
		  $path = '/Setting/Zendesk/zend_api';
		  $un_link->addParam("goto", $path);
		  $un_link = $un_link->getUrl();	  
		  
		  $idproject = $data['idproject'][$i];
		  $do_project  = new Project();
		  $project_name = $do_project->getProjectName($idproject);
		  $d_email = $data['zend_email'][$i];
		  $d_zend_api = $data['zend_api'][$i];
		  $d_zend_url = $data['zend_url'][$i];
		  echo'<tr><td>'.$d_email.'</td><td>'.$d_zend_api.'</td><td>'.$d_zend_url.'</td><td>'.$project_name.'</td><td><a href="'.$un_link.'" onclick="return confirm(\'Do you really want to unlink from Zendesk?\')">Unlink</a></td></tr>';
		//echo'<tr><td>'.$d_email.'</td><td>'.$d_zend_api.'</td><td>'.$d_zend_url.'</td><td>'.$project_name.'</td><td><a href="'.$un_link.'" class="confirmation">Unlink</a></td></tr>';
	}
	?>
	</table>
	<?php }?>
	<br /><br />
	<b><u>Connect with Zendesk</u></b><br /><br />
	<?php
	$path = '/Setting/Zendesk/zend_api';
	$do_repo = new Event("Zendesk->eventAddZend");
	$do_repo->addParam("goto", $path); 
	$do_repo->addParam("iduser", $iduser); 
	echo $do_repo->getFormHeader();
	echo $do_repo->getFormEvent();
	?>
	<table>
	<tr><td>Zend URL:</td><td> <input type="text" name="zend_url" id="zend_url" value="<?php echo $zend_url;?>" />(https://your_project.zendesk.com/api/v2)</td></tr>	
	<tr><td>Zend Email:</td><td> <input type="text" name="zend_email" id="zend_email" value="<?php echo $zend_email;?>" />(Zendesk login Email)</td></tr>
	<tr><td>Zend API Key:</td><td><input type="text" name="zend_api" id="zend_api" value="<?php echo $zend_api;?>" />(Zendesk API Key)</td></tr>
	<tr><td>Project to Connect:</td><td><select id="idproject" name="idproject"><option value="">[Select Project]</option><?php echo $do_zend->getProjectList($iduser);?></select></td></tr>
	<tr><td colspan="3"><input type="submit" id="submitaction" name="submitaction" value="Submit"></td></tr></table>
