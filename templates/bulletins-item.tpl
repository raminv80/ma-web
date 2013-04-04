{block name=bulletins-item}
{$bcont = "{$bulletin.bulletin_title}:{$bulletin.bulletin_content|strip_tags}"}
<h1 class="grid_15">{$bcont|truncate:85:"..."} <small><a href="/bulletin">Read More</a></small><small class="right"><a href="/bulletin">View All</a></small></h1>
<div class="clear"></div>
{/block}
