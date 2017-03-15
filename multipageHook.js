//
// CMSUno
// Plugin Multipage
//
function f_hookBusy_multipage(f){
	jQuery.post('uno/plugins/multipage/multipage.php',{'action':'hookBusy','busy':f,'unox':Unox},function(r){
		f_alert(r);window.location.reload();
	});
}
//
jQuery(document).ready(function(){
	jQuery.post('uno/plugins/multipage/multipage.php',{'action':'hook','unox':Unox,'Ubusy':Ubusy},function(r){
		jQuery('#apage').parent().append(r.substr(0,r.length-1));
	});
});
