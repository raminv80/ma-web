/**
 * THIS FILE NEEDS WORK.
 *
 * THIS IS THE CODE WHICH HUMANBROCHURE USES TO SHARE TO DIFFERENT MEDIA STREAMS
 *
 */

/* SHARE STREAM
$('.stream-share-facebook').live('click', function(event) {
	event.preventDefault();
	var id = $(this).attr('data-id');
	var network = $(this).attr('data-network');
	var width = 575,
	height = 250,
	left = (screen.availWidth - width) / 2,
	top = (screen.availHeight- height) / 2,
//	url = this.href,
	opts = 'status=1' +
	',width=' + width +
	',height=' + height +
	',top=' + top +
	',left=' + left;
	window.open("http://www.facebook.com/sharer.php?u=" + url + "stream/" + id + "&t=Check%20out%20this%20Canberra%20moment%20at%20the%20world's%20first%20Human%20Brochure." , 'facebook', opts);
	gaTrackEvent('stream', 'fb-shared', network, id);
	return false;
//	shareFacebook();
});
$('.stream-share-twitter').live('click', function(event) {
	event.preventDefault();
	var id = $(this).attr('data-id');
	var network = $(this).attr('data-network');
	var width = 575,
	height = 250,
	left = (screen.availWidth - width) / 2,
	top = (screen.availHeight- height) / 2,
//	url = this.href,
	opts = 'status=1' +
	',width=' + width +
	',height=' + height +
	',top=' + top +
	',left=' + left;
	window.open("https://twitter.com/share?url=" + url + "stream/" + id + "&text=Check%20out%20this%20Canberra%20moment%20at%20the%20world's%20first%20Human%20Brochure.", 'twitter', opts);
	gaTrackEvent('stream', 'tw-shared', network, id);
	return false;
});
$('.stream-share-twitter-reply').live('click', function(event) {
	event.preventDefault();
	var id = $(this).attr('data-id');
	var network = $(this).attr('data-network');
	var width = 575,
	height = 375,
	left = (screen.availWidth - width) / 2,
	top = (screen.availHeight- height) / 2,
	opts = 'status=1' +
	',width=' + width +
	',height=' + height +
	',top=' + top +
	',left=' + left;
	window.open("https://twitter.com/intent/tweet?in_reply_to=" + id, 'twitter', opts);
	gaTrackEvent('stream', 'tw-reply', network, id);
	return false;
});
$('.stream-share-twitter-retweet').live('click', function(event) {
	event.preventDefault();
	var id = $(this).attr('data-id');
	var network = $(this).attr('data-network');
	var width = 575,
	height = 250,
	left = (screen.availWidth - width) / 2,
	top = (screen.availHeight- height) / 2,
	opts = 'status=1' +
	',width=' + width +
	',height=' + height +
	',top=' + top +
	',left=' + left;
	window.open("https://twitter.com/intent/retweet?tweet_id=" + id, 'twitter', opts);
	gaTrackEvent('stream', 'tw-retweet', network, id);
	return false;
});
$('.stream-share-pinterest').live('click', function(event) {
	event.preventDefault();
	var id = $(this).attr('data-id');
	var network = $(this).attr('data-network');
	var img = $(this).attr('data-img');
	if(img == "") {
		img = url + 'img/facebook-share.jpg';
	}
	var width = 675,
	height = 575,
	left = (screen.availWidth - width) / 2,
	top = (screen.availHeight- height) / 2,
//	url = this.href,
	opts = 'status=1' +
	',width=' + width +
	',height=' + height +
	',top=' + top +
	',left=' + left;
//	javascript:void((function()%7Bvar%20e=document.createElement('script');e.setAttribute('type','text/javascript');e.setAttribute('charset','UTF-8');e.setAttribute('src','http://assets.pinterest.com/js/pinmarklet.js?r='+Math.random()*99999999);document.body.appendChild(e)%7D)());
	window.open("http://pinterest.com/pin/create/button/?url=" + url + "/stream/" + id + "&media=" + img + "&description=Check%20out%20this%20Canberra%20moment%20at%20the%20world's%20first%20Human%20Brochure.", 'pinterest', opts);
	gaTrackEvent('stream', 'pin', network, id);
	return false;
});
/* END SHARE STREAM */

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
