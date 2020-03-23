<?php
session_start(); 
if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH'])!='xmlhttprequest') {sleep(2);exit;} // ajax request
if(!isset($_POST['unox']) || $_POST['unox']!=$_SESSION['unox']) {sleep(2);exit;} // appel depuis uno.php
?>
<?php
include('../../config.php');
include('lang/lang.php');
// ********************* actions *************************************************************************
if (isset($_POST['action']))
	{
	switch ($_POST['action'])
		{
		// ********************************************************************************************
		case 'plugin': ?>
		<div class="blocForm">
		 <style>
			#multipageMenu{list-style-type:none;margin:0;padding:0;width:600px;}
			#multipageMenu .bouton{position:relative;display:block;margin:0 3px 3px;padding:7px 5px 5px 10px;font-size:105%;vertical-align:middle;}
			#multipageMenu li span.ui-icon{float:left;margin-top:0;}
			#multipageMenu li span.mri{margin-right:10px;}
			#multipageMenu li input{float:right;margin-right:25px;width:200px;border:3px}
			.multipageSupp {display:block;position:absolute;right:5px;top:8px;width:18px;background-image:url(uno/plugins/multipage/img/close.png);background-repeat:no-repeat;background-position:0 0;}
			.ui-icon{width:16px;}
			li li{margin-left:30px;}
			#multipageMenu>li:first-child li{margin-left:0;}
		</style>
			<h2><?php echo T_("Multipage");?></h2>
			<p><?php echo T_("Allowing multiple pages with CMSUno.");?></p>
			<p><?php echo T_("Use Code in the template to insert the menu containing all the pages :");?></p>
			<p style="padding-left:10px;">
				<code>[[multimenu]]</code> :&nbsp;<?php echo T_("Code created dynamically in JavaScript (UL LI A).");?>
				<br />
				<code>[[multimenu-hard]]</code> :&nbsp;<?php echo T_("Code created during publication (UL LI A). Best for SEO.");?>
				<br />
				<code>[[multimenu-theme]]</code> :&nbsp;<?php echo T_("Code created by the theme during publication. Only for some themes.");?>
			</p>
			<h3><?php echo T_("Pages management");?></h3>
			<table class="hForm">
				<tr>
					<td><label><?php echo T_("Active Page");?></label></td>
					<td>
						<select id="multipageBusy">
							<?php
							$page = ''; $menu = '';
							$q = file_get_contents('../../data/multipage.json'); $a = json_decode($q,true);
							$level = 0;
							if ($h=opendir('../../data/'))
								{
								foreach($a['menu'] as $k=>$v)
									{
									if($k!='.' && $k!='..')
										{
										if(!isset($a['sub'][$k]))
											{
											if(!isset($a['ext'][$k]) && is_dir('../../data/'.$k) && file_exists('../../data/'.$k.'/site.json')) $menu .= '<li id="M'.$k.'"><div class="bouton"><span class="ui-icon ui-icon-arrowthickstop-1-w" onClick="f_level_multipage(this,1);"></span><span class="ui-icon ui-icon-arrowthick-2-n-s"></span><span class="ui-icon ui-icon-arrowthickstop-1-e mri" onClick="f_level_multipage(this,0);"></span>'.$k.'<input type="text" value="'.stripslashes($v).'" /></div></li>';
											else if(isset($a['ext'][$k])) $menu .= '<li id="M'.$k.'"><div class="bouton" style="color:blue"><span class="ui-icon ui-icon-arrowthickstop-1-w" onClick="f_level_multipage(this,1);"></span><span class="ui-icon ui-icon-arrowthick-2-n-s"></span><span class="ui-icon ui-icon-arrowthickstop-1-e mri" onClick="f_level_multipage(this,0);"></span>'.$a['ext'][$k].'<span class="multipageSupp" onClick="f_otherDel_multipage(\''.$k.'\');">&nbsp;</span><input type="text" value="'.stripslashes($v).'" /></div></li>';
											}
										else
											{
											if(!isset($a['ext'][$k]) && is_dir('../../data/'.$k) && file_exists('../../data/'.$k.'/site.json')) $menu .= '<li id="M'.$k.'"><ul><li><div class="bouton"><span class="ui-icon ui-icon-arrowthickstop-1-w" onClick="f_level_multipage(this,1);"></span><span class="ui-icon ui-icon-arrowthick-2-n-s"></span><span class="ui-icon ui-icon-arrowthickstop-1-e mri" onClick="f_level_multipage(this,0);"></span>'.$k.'<input type="text" value="'.stripslashes($v).'" /></div></li></ul></li>';
											else if(isset($a['ext'][$k])) $menu .= '<li id="M'.$k.'"><ul><li><div class="bouton" style="color:blue"><span class="ui-icon ui-icon-arrowthickstop-1-w" onClick="f_level_multipage(this,1);"></span><span class="ui-icon ui-icon-arrowthick-2-n-s"></span><span class="ui-icon ui-icon-arrowthickstop-1-e mri" onClick="f_level_multipage(this,0);"></span>'.$a['ext'][$k].'<span class="multipageSupp" onClick="f_otherDel_multipage(\''.$k.'\');">&nbsp;</span><input type="text" value="'.stripslashes($v).'" /></div></li></ul></li>';
											}
										}
									}
								while(($d=readdir($h))!==false)
									{
									if(is_dir('../../data/'.$d) && file_exists('../../data/'.$d.'/site.json') && $d!='.' && $d!='..')
										{
										$page .= '<option value="'.$d.'">'.$d.'</option>';
										if($level==1)
											{
											$menu .= "</ul></li>";
											$level = 0;
											}
										if(!isset($a['menu'][$d])) $menu .= '<li id="M'.$d.'"><div class="bouton"><span class="ui-icon ui-icon-arrowthickstop-1-w" onClick="f_level_multipage(this,1);"></span><span class="ui-icon ui-icon-arrowthick-2-n-s"></span><span class="ui-icon ui-icon-arrowthickstop-1-e mri" onClick="f_level_multipage(this,0);"></span>'.$d.'<input type="text" value="" /></div></li>';
										}
									}
								closedir($h);
								}
							if($level==1) $menu .= "</ul></li>"; // last
							echo $page;
							?>
						</select>
						<div class="bouton" style="margin:0 0 0 30px;" onClick="f_saveBusy_multipage(document.getElementById('multipageBusy').options[document.getElementById('multipageBusy').selectedIndex].value);" title="<?php echo T_("Activate this page");?>"><?php echo T_("Activate");?></div>
					</td>
					<td>
						<em><?php echo T_("Select the page you want to edit. You can publish it and then edit another page.");?></em>
					</td>
				</tr>
				<tr>
					<td><label><?php echo T_("Master Page");?></label></td>
					<td>
						<select id="multipageMaster">
						<?php echo $page; ?>
						</select>
						<div class="bouton" style="margin:0 0 0 30px;" onClick="f_saveMaster_multipage(document.getElementById('multipageMaster').options[document.getElementById('multipageMaster').selectedIndex].value);" title="<?php echo T_("Save");?>"><?php echo T_("Save");?></div>
					</td>
					<td>
						<em><?php echo T_("Reference page that can be used for plugins configuration (Contact...).");?></em>
					</td>
			</table>
			<hr />
			<h3><?php echo T_("Menu Manager");?></h3>
			<p><?php echo T_("Drag and Drop items to reorder your menu. Change the item title. Don't forget to save.");?></p>
			<p><?php echo T_("Leave title empty to remove item.");?></p>
				<ul id="multipageMenu">
					<?php echo $menu; ?>
				</ul>
			<div class="clear"></div>
			<div class="bouton fr" style="margin-top:-36px;" onClick="f_saveMenu_multipage();" title="<?php echo T_("Save Menu");?>"><?php echo T_("Save Menu");?></div>
			<div class="clear"></div>
			<h3><?php echo T_("Add External Link");?></h3>
			<table class="hForm">
				<tr>
					<td><label><?php echo T_("URL");?></label></td>
					<td><input type="text" class="input" style="width:200px;" id="multipageUrl" /></td>
					<td><em><?php echo T_("Page URL to insert in the menu. Appear in blue in Menu Manager.");?></em></td>
				</tr>
				<tr>
					<td><label><?php echo T_("Title");?></label></td>
					<td>
					<input type="text" class="input" style="width:200px;" id="multipageTit" />
					<div class="bouton" style="margin:5px 0 0 30px;" onClick="f_other_multipage(document.getElementById('multipageUrl').value,document.getElementById('multipageTit').value);" title="<?php echo T_("Add External Link");?>"><?php echo T_("Add External Link");?></div>
					</td>
					<td><em><?php echo T_("Title that will appear in the menu.");?></em></td>
				</tr>
			</table>
			<hr />
			<h3><?php echo T_("Create new page");?></h3>
			<table class="hForm">
				<tr>
					<td><label><?php echo T_("Filename");?></label></td>
					<td>
						<input type="text" class="input" style="text-align:right;width:100px;" id="multipageNom" />.html
						<div class="bouton fr" onClick="f_newPage_multipage();" title="<?php echo T_("Create page");?>"><?php echo T_("Create");?></div>
					</td>
					<td>
						<em><?php echo T_("Name of the HTML file (appears in URL). The used words are important for SEO."); ?></em><br />
						<em><?php echo T_("Check and Save CONFIG tab after creation.");?></em>
					</td>
				</tr>
			</table>
			<h3><?php echo T_("Remove page");?></h3>
			<table class="hForm">
				<tr>
					<td><label><?php echo T_("Filename");?></label></td>
					<td>
						<select id="multipageDel"><?php echo $page; ?></select><br />
						<div class="bouton" style="margin:5px 0 0 30px;" onClick="f_delPage_multipage(document.getElementById('multipageDel').options[document.getElementById('multipageDel').selectedIndex].value,'<?php echo T_("Are you sure ?");?>');" title="<?php echo T_("Remove this page");?>"><?php echo T_("Remove");?></div>
					</td>
					<td><em><?php echo T_("Remove all data created for this page. Did you make a backup ?");?></em></td>
				</tr>
			</table>
			<h3><?php echo T_("Publish all pages");?></h3>
			<table class="hForm">
				<tr>
					<td><label><?php echo T_("Publish all pages");?></label></td>
					<td>
						<div class="bouton" style="margin:5px 0 0 30px;" onClick="f_pubAll_multipage(<?php echo "'".T_("Are you sure ?")."','".T_("OK")."','".T_("Error")."'"; ?>);" title="<?php echo T_("Publish");?>"><?php echo T_("Publish");?></div>
					</td>
					<td><em><?php echo T_("Publish all existing pages in CMSUno. Useful if you change your template.");?></em></td>
				</tr>
			</table>
			<div id="pubResult"></div>
			<div class="clear"></div>
		</div>
		<?php break;
		// ********************************************************************************************
		case 'getBusy':
		if(file_exists('../../data/busy.json'))
			{
			$q = file_get_contents('../../data/busy.json'); $a = json_decode($q,true);
			if(empty($a['nom'])) $a['nom'] = '';
			if(empty($a['master'])) $a['master'] = '';
			echo json_encode($a);
			}
		else echo false;
		break;
		// ********************************************************************************************
		case 'saveBusy':
		$a = array();
		if(file_exists('../../data/busy.json')) {
			$q = file_get_contents('../../data/busy.json'); $a = json_decode($q,true);
		}
		if(file_put_contents('../../data/busy.json', '{"nom":"'.strip_tags($_POST['busy']).'","master":"'.(!empty($a['master'])?$a['master']:'').'"}')) echo T_('Saved');
		else echo '!'.T_('Backup missed');
		break;
		// ********************************************************************************************
		case 'saveMaster':
		$a = array();
		if(file_exists('../../data/busy.json')) {
			$q = file_get_contents('../../data/busy.json'); $a = json_decode($q,true);
		}
		if(file_put_contents('../../data/busy.json', '{"nom":"'.(!empty($a['nom'])?$a['nom']:'').'","master":"'.strip_tags($_POST['master']).'"}')) echo T_('Saved');
		else echo '!'.T_('Backup missed');
		break;
		// ********************************************************************************************
		case 'hookBusy':
		$a = array();
		if(file_exists('../../data/busy.json')) {
			$q = file_get_contents('../../data/busy.json'); $a = json_decode($q,true);
		}
		if(file_put_contents('../../data/busy.json', '{"nom":"'.strip_tags($_POST['busy']).'","master":"'.(!empty($a['master'])?$a['master']:'').'"}')) echo T_('Page selected');
		else echo '!'.T_('Error');
		break;
		// ********************************************************************************************
		case 'saveMenu':
		if(isset($_POST['menu']))
			{
			$q = explode(',', strip_tags($_POST['menu']));
			$out = 'document.write(\'<ul id="multimenu">';
			$a = array(); $a1 = 0; $a2 = ''; $a3 = ''; $c = 0; $level = 0;
			if(file_exists('../../data/multipage.json'))
				{
				$q1 = file_get_contents('../../data/multipage.json');
				$a1 = json_decode($q1,true);
				unset($a1['sub']);
				}
			// JSON DATA
			foreach($q as $r)
				{
				if($r)
					{
					$r1 = explode('||', $r); // ID sans le M || input value
					if(isset($r1[1]))
						{
						if($r1[1]==$a3 && $a2!='' && $c>1) $a1['sub'][$a2] = 1; // sub menu
						$r1[1] = trim(str_replace("'","\'",stripslashes($r1[1])));
						$r1[1] = str_replace('"','',$r1[1]);
						if($r1[0])
							{
							$a['menu'][$r1[0]] = $r1[1];
							$a2 = $r1[0];
							$a3 = $r1[1];
							++$c;
							}
						}
					}
				}
			$a['ext'] = (isset($a1['ext'])?$a1['ext']:'');
			$a['sub'] = (isset($a1['sub'])?$a1['sub']:'');
			$c = 0;
			// JS MENU
			foreach($a['menu'] as $k=>$v)
				{
				if($k)
					{
					// level
					if($level==0 && $c && isset($a['sub'][$k]) && $a['sub'][$k]==1) { $out = substr($out,0,-5).'<ul>'; $level = 1; }
					else if($level==1 && !isset($a['sub'][$k])) { $out .= '</ul></li>'; $level = 0; }
					//
					if(!isset($a['ext'][$k])) $out .= '<li id="'.$k.'"><a class="'.(!isset($a['sub'][$k])?'ul1':'ul2').'" href="'.$k.'.html">'.$v.'</a></li>';
					else
						{
						$u = $a['ext'][$k];
						if(!preg_match("~^(?:f|ht)tps?://~i", $u)) $u = "//".$u;
						$out .= '<li id="'.$k.'"><a class="'.(!isset($a['sub'][$k])?'ul1':'ul2').'" href="'.$u.'">'.$v.'</a></li>';
						}
					++$c;
					}
				}
			if($level==1) $out .= '</ul></li>';
			$out .= '</ul>\');'."\r\n";
			$out .= 'var cur=document.getElementById(multiCur);if(cur!==null){cur.className="current";var par=cur.parentElement.parentElement;if(par.tagName=="LI")par.className="inpath";}'."\r\n";
			if(file_put_contents('../../data/multimenu.js', $out) && file_put_contents('../../data/multipage.json', json_encode($a))) echo T_('Menu Saved');
			else echo '!'.T_('Error');
			}
		break;
		// ********************************************************************************************
		case 'add':
		if(file_exists('../../data/multipage.json') && isset($_POST['u']) && isset($_POST['t']))
			{
			$u = strip_tags($_POST['u']);
			$t = strip_tags($_POST['t']);
			$q = file_get_contents('../../data/multipage.json');
			$a = json_decode($q,true);
			if(!isset($a['menu'][preg_replace("/[^A-Za-z0-9-_]/", "", $u)]))
				{
				if(empty($a['menu']) || !is_array($a['menu'])) $a['menu'] = array();
				if(empty($a['ext']) || !is_array($a['ext'])) $a['ext'] = array();
				$a['menu'][preg_replace("/[^A-Za-z0-9-_]/", "", $u)] = stripslashes($t);
				$a['ext'][preg_replace("/[^A-Za-z0-9-_]/", "", $u)] = $u;
				if(file_put_contents('../../data/multipage.json', json_encode($a))) echo T_('Link added');
				}
			else echo '!'.T_('Already Exist');
			}
		else echo '!'.T_('Save Menu First');
		break;
		// ********************************************************************************************
		case 'del':
		if(file_exists('../../data/multipage.json') && isset($_POST['u']))
			{
			$u = strip_tags($_POST['u']);
			$q = file_get_contents('../../data/multipage.json');
			$a = json_decode($q,true);
			unset($a['menu'][$u]);
			unset($a['ext'][$u]);
			if(file_put_contents('../../data/multipage.json', json_encode($a))) echo T_('Link removed');
			}
		else echo '!'.T_('Save Menu First');
		break;
		// ********************************************************************************************
		case 'newPage':
		$Ubusy = preg_replace("/[^A-Za-z0-9-_]/",'',strip_tags($_POST['page']));
		while(substr($Ubusy,0,1)=="_") $Ubusy = substr($Ubusy,1);
		if(!is_dir('../../data/'.$Ubusy))
			{
			mkdir('../../data/'.$Ubusy, 0755, true);
			mkdir('../../data/_sdata-'.$sdata.'/'.$Ubusy, 0711, true);
			$a = array();
			if(file_exists('../../data/busy.json')) {
				$q = file_get_contents('../../data/busy.json'); $a = json_decode($q,true);
			}
			if(file_put_contents('../../data/'.$Ubusy.'/site.json', '{"chap":[{"d":"0","t":"Welcome"}],"pub":0,"nom":"'.$Ubusy.'","plug":{"multipage":1}}'))
				{
				file_put_contents('../../data/'.$Ubusy.'/chap0.txt', 'blabla...');
				file_put_contents('../../data/busy.json', '{"nom":"'.$Ubusy.'","master":"'.(!empty($a['master'])?$a['master']:'').'"}');
				echo T_('Saved');
				exit;
				}
			}
		echo '!'.T_('Backup missed');
		break;
		// ********************************************************************************************
		case 'delPage':
		if(isset($_POST['del']))
			{
			if(f_rmdirR('../../data/'.strip_tags($_POST['del'])))
				{
				echo T_('Deletion made');
				if(is_dir('../../data/_sdata-'.$sdata.'/'.strip_tags($_POST['del']))) f_rmdirR('../../data/_sdata-'.$sdata.'/'.strip_tags($_POST['del']));
				if(file_exists('../../../'.strip_tags($_POST['del']).'.html')) unlink('../../../'.strip_tags($_POST['del']).'.html');
				}
			else echo '!'.T_('Impossible deletion');
			}
		else echo '!'.T_('Error');
		break;
		// ********************************************************************************************
		case 'pubAll':
		$a = array();
		if($h=opendir('../../data/'))
			{
			while(($d=readdir($h))!==false) if(is_dir('../../data/'.$d) && file_exists('../../data/'.$d.'/site.json') && $d!='.' && $d!='..') $a[] = $d;
			closedir($h);
			echo json_encode($a);
			}
		else echo '!'.T_('Error');
		break;
		// ********************************************************************************************
		case 'hook':
		$a = '';
		if($h=opendir('../../data/'))
			{
			while(($d=readdir($h))!==false) if(is_dir('../../data/'.$d) && file_exists('../../data/'.$d.'/site.json') && $d!='.' && $d!='..')
				{
				if($d==$_POST['Ubusy']) $a = '<li><a style="text-decoration:underline;color:#fff;" href="javascript:void(0)">'.$d.'</a></li>' . $a;
				else $a .= '<li><a href="javascript:void(0)" onClick="f_hookBusy_multipage(\''.$d.'\');">'.$d.'</a></li>';
				}
			closedir($h);
			}
		if($a) echo '<ul>'.$a.'</ul>';
		else echo '';
		break;
		// ********************************************************************************************
		}
	clearstatcache();
	exit;
	}
//
function f_rmdirR($dir)
	{
	$files = array_diff(scandir($dir), array('.','..'));
	foreach ($files as $file)
		{
		(is_dir("$dir/$file")) ? f_rmdirR("$dir/$file") : unlink("$dir/$file");
		}
	return rmdir($dir);
	}
//
?>
