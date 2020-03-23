//
// CMSUno
// Plugin Multipage
//
function f_load_multipage(){
	jQuery(document).ready(function(){
		jQuery.post('uno/plugins/multipage/multipage.php',{'action':'getBusy','unox':Unox},function(r){
			var a=JSON.parse(r),t=document.getElementById('multipageBusy'),to,m=document.getElementById('multipageMaster');
			to=t.options;
			for(v=0;v<to.length;v++){if(to[v].value==a.nom){to[v].selected=true;v=to.length;}}
			to=m.options;
			for(v=0;v<to.length;v++){if(to[v].value==a.master){to[v].selected=true;v=to.length;}}
		});
		jQuery('#multipageMenu').sortable();
	});
}
//
function f_level_multipage(f,g){
	var a=f.parentNode.parentNode,b,c;
	if(a.parentNode.parentNode.tagName.toLowerCase()!='li'&&g==0){
		b=document.createElement('ul');
		c=document.createElement('li');
		b.appendChild(c);a.appendChild(b);
		jQuery(f.parentNode).appendTo(c);
	}
	else if(a.parentNode.parentNode.tagName.toLowerCase()=='li'&&g!=0){
		a.parentNode.id='byby';
		jQuery(f.parentNode).appendTo(a.parentNode.parentNode);
		jQuery('#byby').remove();
	}
}
//
function f_saveBusy_multipage(f){
	jQuery.post('uno/plugins/multipage/multipage.php',{'action':'saveBusy','busy':f,'unox':Unox},function(r){
		f_alert(r);window.location.reload();
	});
}
//
function f_saveMaster_multipage(f){
	jQuery.post('uno/plugins/multipage/multipage.php',{'action':'saveMaster','master':f,'unox':Unox},function(r){
		f_alert(r);
	});
}
//
function f_newPage_multipage(){
	var a=document.getElementById('multipageNom').value,b;
	jQuery.post('uno/plugins/multipage/multipage.php',{'action':'newPage','unox':Unox,'page':document.getElementById('multipageNom').value},function(r){
		f_alert(r);
		b=document.createElement("option");b.value=a;b.innerHTML=a;
		document.getElementById('multipageDel').options.add(b);
		b=document.createElement("option");b.value=a;b.innerHTML=a;
		document.getElementById('multipageBusy').options.add(b);b.selected=true;
	});
}
//
function f_delPage_multipage(f,g){
	if(confirm(f+' - '+g)){
		jQuery.post('uno/plugins/multipage/multipage.php',{'action':'delPage','unox':Unox,'del':f},function(r){
			f_alert(r);
			var t=document.getElementById('multipageBusy'),to;
			to=t.options;
			for(v=0;v<to.length;v++){if(to[v].value==f){t.remove(v);v=to.length;}}
			t=document.getElementById('multipageDel'),to;
			to=t.options;
			for(v=0;v<to.length;v++){if(to[v].value==f){t.remove(v);v=to.length;}}
			t=document.getElementById('M'+f);
			t.parentNode.removeChild(t);
		});
	}
}
//
function f_saveMenu_multipage(){
	var a=document.getElementById('multipageMenu').getElementsByTagName('li'),b='';
	for(v=0;v<a.length;v++){
		if(a[v].getElementsByTagName('input')[0].value.length>2) b+=a[v].id.substr(1)+'||'+a[v].getElementsByTagName('input')[0].value+',';
	}
	jQuery.post('uno/plugins/multipage/multipage.php',{'action':'saveMenu','unox':Unox,'menu':b},function(r){
		f_alert(r);
	});
}
//
function f_other_multipage(f,g){
	if(f.length>1&&g.length>1)jQuery.post('uno/plugins/multipage/multipage.php',{'action':'add','unox':Unox,'u':f,'t':g},function(r){
		if(r.substr(0,1)!='!'){
			var a=document.createElement("li"),b,c,d=f.replace(/[^a-z0-9-_]/gi,'');
			c=document.createElement("div");c.className="bouton";c.style.color="blue";
			a.id="M"+d;
			b=document.createElement("span");b.className="ui-icon ui-icon-arrowthickstop-1-w";b.id='A'+d;c.appendChild(b);
			b=document.createElement("span");b.className="ui-icon ui-icon-arrowthick-2-n-s";c.appendChild(b);
			b=document.createElement("span");b.className="ui-icon ui-icon-arrowthickstop-1-e mri";b.id='B'+d;c.appendChild(b);c.innerHTML+=f;
			b=document.createElement("span");b.className="multipageSupp";b.id='S'+d;b.innerHTML='&nbsp;';c.appendChild(b);
			b=document.createElement("input");b.type="text";b.value=g;c.appendChild(b);
			a.appendChild(c);
			document.getElementById('multipageMenu').appendChild(a);
			document.getElementById('multipageUrl').value='';document.getElementById('multipageTit').value='';
			document.getElementById('A'+d).onclick=function(){f_level_multipage(document.getElementById('A'+d),1)};
			document.getElementById('B'+d).onclick=function(){f_level_multipage(document.getElementById('B'+d),0)};
			document.getElementById('S'+d).onclick=function(){f_otherDel_multipage(d)};
		}
		f_alert(r);
	});
}
//
function f_otherDel_multipage(f){
	jQuery.post('uno/plugins/multipage/multipage.php',{'action':'del','unox':Unox,'u':f},function(r){
		if(r.substr(0,1)!='!'){
			var a=document.getElementById('M'+f);
			a.parentNode.removeChild(a);
		}
		f_alert(r);
	});
}
//
function f_pubAll_multipage(f,ok,err){
	if(confirm(f)){
		jQuery.ajax({type:"POST",url:'uno/plugins/multipage/multipage.php',data:{'action':'pubAll','unox':Unox},dataType:'json',async:true,success:function(r){
			jQuery.each(r,function(k,v){
				jQuery.post('uno/central.php',{'action':'publier','unox':Unox,'Ubusy':v},function(r1){
					document.getElementById('pubResult').innerHTML+='<div style="color:'+(r1.substr(0,1=='!')?'red">'+v+' : '+err:'green">'+v+' : '+ok)+'</div>';
					window.scrollTo(0,document.body.scrollHeight);
				});
			});
		}});
	}
}
//
f_load_multipage();
