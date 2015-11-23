{block name=archive-menu}

		<div class="col-sm-3" id="sidebar">
				<div class="row sidetop">
					<div class="col-sm-12">
						<h5>Archive</h5>
						<!-- <a class="sider" href="/blog">Show All</a> -->
					</div>
				</div>
				{foreach $archive_list as $al}
					<div class="row">
						<div class="col-sm-12">
							{$al.date}
							<a class="sider" href="/blog?date={$al.date|replace:' ':''}">({$al.posts})</a>
						</div>
					</div>
				{/foreach}
			</div>
{/block}
