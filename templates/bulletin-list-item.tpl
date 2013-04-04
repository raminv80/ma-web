{block name=bulletins-item}
<div class="bulletin-item-wrapper" id="bulletin-{$bulletin.bulletin_id}">
<div class="bltn_title">{$bulletin.bulletin_title}</div>
<div class="bulletin-item-date">{msyql_to_aus_date datetime=$bulletin.bulletin_date}</div>
<div class="bltn_content">{$bulletin.bulletin_content}</div>
</div><!-- end of bulletin-item-wrapper -->
{/block}
