<?php
if (!isset($_SESSION['cmsuno'])) exit();
?>
<?php
if (file_exists('data/multipage.json'))
	{
	$out1 = '<script type="text/javascript">var multiCur="[[name]]";</script>';
	$out1 .= '<script src="uno/data/multimenu.js"></script>'."\r\n";
	$Uhtml = str_replace('[[multimenu]]', $out1, $Uhtml);
	}
?>
