CMSUno - Plugins
================

<pre>
 uuuu      uuuu        nnnnnn           ooooooooo
u::::u    u::::u    nn::::::::nn     oo:::::::::::oo
u::::u    u::::u   nn::::::::::nn   o:::::::::::::::o
u::::u    u::::u  n::::::::::::::n  o:::::ooooo:::::o
u::::u    u::::u  n:::::nnnn:::::n  o::::o     o::::o
u::::u    u::::u  n::::n    n::::n  o::::o     o::::o
u::::u    u::::u  n::::n    n::::n  o::::o     o::::o
u:::::uuuu:::::u  n::::n    n::::n  o::::o     o::::o
u::::::::::::::u  n::::n    n::::n  o:::::ooooo:::::o
 u::::::::::::u   n::::n    n::::n  o:::::::::::::::o
  uu::::::::uu    n::::n    n::::n   oo:::::::::::oo
     uuuuuu        nnnn      nnnn       ooooooooo
        ___                                __
       / __\            /\/\              / _\
      / /              /    \             \ \
     / /___           / /\/\ \            _\ \
     \____/           \/    \/            \__/
</pre>

## Multipage ##

This plugin allows you to create and manage multiple pages in CMSUno.
Drag and Drop Menu Manager.
Simple and practical for a complete website.

[CMSUno](https://github.com/boiteasite/cmsuno)

### Menu creation from template ###

1. Create a file named fooMake.php if not exists (foo : name of the theme).
2. Multimenu is stored in a PHP Array __$multimenu__ with 'name', 'label', 'url' and 'sub' for each item.
Loop this array in fooMake.php. Example :

```
$o = '<ul>'; $c = 0; $level = 0;
foreach($multimenu as $k=>$v){
	// 1. Level change
	if($level==0 && $c && $v['sub']==1) {
		$o = substr($o,0,-5).'<ul>';
		$level = 1;
	}
	else if($level==1 && $v['sub']==0) {
		$o .= '</ul></li>';
		$level = 0;
	}
	// 2. Menu Content
	$o .= '<li id="'.$v['name'].'"'.($v['name']==$Ubusy?' class="current"':'').'>';
	$o .= '<a class="'.($v['sub']?'ul1':'ul2').'" href="'.$v['url'].'">'.$v['label'].'</a>';
	$o .= '</li>';
}
if($level==1) $o .= '</ul></li>';
$o .= '</ul>';
// Output
$Uhtml = str_replace('[[multimenu-theme]]', $o, $Uhtml);
```

3. In your template, add the shortcode __[[multimenu-theme]]__

### Versions ###

* 1.3.1 - 22/01/2018 : Fix slash display in Hard-multimenu.
* 1.3 - 03/01/2018 : Add Hard multimenu and Array multimenu to publish custom menu.
* 1.2.2 - 30/09/2017 : Fix issue with external url. Shortcode can be added in the content page.
* 1.2.1 - 15/03/2017 : Fix issue when unknow lang.
* 1.2 - 14/10/2016 : Use PHP-Gettext in place of gettext.
* 1.1.1 - 01/06/2016 : Translation correction.
* 1.1 - 24/05/2016 : Hook to add a dropdown page list under Page in CMSUno topMenu.
* 1.0 - 05/10/2015 : First stable version.
