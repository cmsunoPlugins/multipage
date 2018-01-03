<?php
if (!isset($_SESSION['cmsuno'])) exit();
?>
<?php
if(file_exists('data/multipage.json'))
	{
	// JS MENU
	$out1 = '<script type="text/javascript">var multiCur="[[name]]";</script>';
	$out1 .= '<script src="uno/data/multimenu.js"></script>'."\r\n";
	$Uhtml = str_replace('[[multimenu]]', $out1, $Uhtml);
	$Ucontent = str_replace('[[multimenu]]', $out1, $Ucontent);
	// HARD MENU
	$out1h = '<ul id="multimenu">';
	$c = 0; $level = 0;
	$q = file_get_contents('data/multipage.json'); $a = json_decode($q,true);
	foreach($a['menu'] as $k=>$v)
		{
		if($k)
			{
			// level
			if($level==0 && $c && isset($a['sub'][$k]) && $a['sub'][$k]==1)
				{
				$out1h = substr($out1h,0,-5).'<ul>';
				$level = 1;
				}
			else if($level==1 && !isset($a['sub'][$k]))
				{
				$out1h .= '</ul></li>';
				$level = 0;
				}
			//
			if(!isset($a['ext'][$k])) $out1h .= '<li id="'.$k.'"'.($k==$Ubusy?' class="current"':'').'><a class="'.(!isset($a['sub'][$k])?'ul1':'ul2').'" href="'.$k.'.html">'.$v.'</a></li>';
			else
				{
				$u = $a['ext'][$k];
				if(!preg_match("~^(?:f|ht)tps?://~i", $u)) $u = '//'.$u;
				$out1h .= '<li id="'.$k.'"><a class="'.(!isset($a['sub'][$k])?'ul1':'ul2').'" href="'.$u.'">'.$v.'</a></li>';
				}
			++$c;
			}
		}
	if($level==1) $out1h .= '</ul></li>';
	$out1h .= '</ul>';
	$c = 0;
	foreach(array_reverse($a['menu']) as $k=>$v)
		{
		if($k==$Ubusy && isset($a['sub'][$k])) $c = 1; // Current is sub
		if($c && !isset($a['sub'][$k]))
			{
			$out1h = str_replace('id="'.$k.'">', 'id="'.$k.'" class="inpath">', $out1h); // parent Current
			break;
			}
		}
	$Uhtml = str_replace('[[multimenu-hard]]', $out1h, $Uhtml);
	$Ucontent = str_replace('[[multimenu-hard]]', $out1h, $Ucontent);
	// THEME (Array)
	$multimenu = array();
	foreach($a['menu'] as $k=>$v)
		{
		if(isset($a['ext'][$k]))
			{
			$u = $a['ext'][$k];
			if(!preg_match("~^(?:f|ht)tps?://~i", $u)) $u = "//".$u;
			}
		else $u = $k.'.html';
		$s = (isset($a['sub'][$k])?1:0);
		$multimenu[] = array('name'=>$k, 'label'=>$v, 'url'=>$u, 'sub'=>$s);
		}
	}
?>
