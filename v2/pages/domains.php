<? require_once('../initialize.php'); 
	if(!$session->isLogedIn() && !$security->checkURL()) {
		exit;
	}
	$id = $crypt->encrypt($user->id);
?>
<div id="domains_container">
	<table border="0" cellpadding="0" cellspacing="0" style="vertical-align:top;" width="100%" height="100%">
    	<tr style="vertical-align:top;">
        	<td width="180px" style="vertical-align:top;">
                <ul class="tabsDomain" >
                    <li id="addtab"><a href="#add"><?=$lang->MOD_209?></a></li>
                    <?
						$query=$db->query('SELECT * FROM cms_domains WHERE alias="0"');
						while($result=$db->fetch($query)) {
							echo '<li id="domain_'.$result['ID'].'tab"><a href="#domain_'.$result['ID'].'">'.$result['name'].'</a></li>';
						}
					?>
                </ul>
            </td>
            <td style="vertical-align:top;" width="100%">
            <div id="domains_tab_container_id" class="domain_container" style="width:100% !important">
            	<input id="domains_current_tab" type="hidden" value="#tab1" /> 
                <div id="add" class="domain_content" style="overflow:auto;">
                    <? include("domains.add.php") ?>
                </div>  
                <?
					$query=$db->query('SELECT * FROM cms_domains WHERE alias="0"');
					while($result=$db->fetch($query)) {
						 echo '<div id="domain_'.$result['ID'].'" class="domain_content" style="overflow:auto;">
								<form action="" id="a_domains_edit_'.$result['ID'].'" method="post" class="form2">
								<input type="hidden" value="'.$crypt->encrypt($result['ID']).'" name="id" />';
						include("domains.view.php");
						echo '</form></div>';
					}
				?>          
            </div>
            </td>
        </tr>
    </table>
</div>