/**
 * THIS FILE NEEDS WORK.
 *
 * THIS IS THE CODE WHICH HUMANBROCHURE USES TO SHARE TO DIFFERENT MEDIA STREAMS
 *
 */

function LoadOnLB(id){
		var href_str = '/includes/processes/processes-general.php?action=getitem&itemid='+id+'&ajax=true&width=520&height=550';
		$('#viewonlb').attr('href',href_str);
		$('#viewonlb').trigger('click');
}
	function fbShare(url, title, descr, image) {
		openshare('http://www.facebook.com/sharer.php?s=100&p[title]=' + encodeURIComponent(title) + '&p[summary]=' + encodeURIComponent(descr) + '&p[url]=http://socialwall.themserver.com/item/' + encodeURIComponent(url) + '&p[images][0]=' + encodeURIComponent(image));
 }
	function TwShare(url,text){
		openshare('https://twitter.com/intent/tweet?original_referer=http://socialwall.themserver.com/item/'+url+'&text='+encodeURIComponent(text)+'&url=http://socialwall.themserver.com/item/'+url);
	}
	function PiShare(url,image,text){
		openshare('http://pinterest.com/pin/create/button/?url=http://socialwall.themserver.com/item/'+url+'&media='+encodeURIComponent(image)+'&description='+encodeURIComponent(text));
	}
	function openshare(url){
	    var winTop = (screen.height / 2) - (530 / 2);
      var winLeft = (screen.width / 2) - (520 / 2);
     window.open(url, 'sharer', 'top=' + winTop + ',left=' + winLeft + ',toolbar=0,status=0,width=520,height=530');
}
