{block name=body}
<div class="container_16">
	<div class="grid_16"><small><a href="/">Home</a> | FAQ</small><br><br></div>
    <div class="grid_8 faq">
		<h1>Frequently asked questions</h1> <br>
		{$content}
		<br/>
		{foreach $data as $faq_item}
		<div class="faq-item" id="faq-{$faq_item.faq_id}" name="faq-{$faq_item.faq_id}">
	        <div class="faq-header">{$faq_item.faqs_question|strip_tags}</div>
	        <div class="faq-content">{$faq_item.faqs_content}</div>
        </div>
		{/foreach}	
		
    </div><!-- end of grid_12 -->
</div>
{/block}
		
     
     
       
     