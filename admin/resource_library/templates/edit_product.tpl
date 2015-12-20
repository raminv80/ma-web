{block name=body}

{* Define the function *} {function name=options_list level=0}
	{foreach $opts as $opt}
		{if $ignore neq $opt.id}
			<option value="{$opt.id}" {if  $selected eq $opt.id}selected="selected"{/if}>{for $var=1 to $level}- {/for}{$opt.value}</option>
			{if count($opt.subs) > 0} {call name=options_list opts=$opt.subs level=$level+1 selected=$selected} {/if}
		{/if}
	{/foreach}
{/function}

<div class="row">
	<div class="col-sm-12" >
		<form class="well form-horizontal" id="Edit_Record" accept-charset="UTF-8" method="post">
			<div class="row">
				<div class="col-sm-12 edit-page-header">
							<span class="edit-page-title">{if $fields.product_id neq ""}Edit{else}New{/if} {$zone}</span>
							{if $cnt eq ""}{assign var=cnt value=0}{/if}
							<a href="javascript:void(0);" onClick="$('#Edit_Record').submit();" class="btn btn-primary pull-right top-btn"><span class="glyphicon glyphicon-floppy-saved"></span> Save</a>
							<div class="published" {if $fields.product_published eq 0}style="display:none;"{/if}>
								<!-- PUBLISHED -->
								<a href="javascript:void(0);" onClick="unpublish('product_published');" class="btn btn-warning pull-right top-btn"><span class="glyphicon glyphicon-thumbs-down"></span> Unpublish</a>
								<a href="javascript:void(0);" onClick="saveDraft('field[1][tbl_product][{$cnt}][id]','product_object_id','product_published','field[1][tbl_product][{$cnt}][product_deleted]', false);" class="btn btn-info pull-right top-btn published"><span class="glyphicon glyphicon-floppy-disk"></span> Save Draft Version</a>
							</div>
							<div class="drafts" {if $fields.product_published eq 1}style="display:none;"{/if}>
								<!-- DRAFT -->
								<a href="javascript:void(0);" onClick="publish('field[1][tbl_product][{$cnt}][id]','product_object_id','product_published','field[1][tbl_product][{$cnt}][product_deleted]');" class="btn btn-primary pull-right top-btn drafts"><span class="glyphicon glyphicon-thumbs-up"></span> Save &amp; Publish</a>
							</div>
							<a href="javascript:void(0);" onClick="saveDraft('field[1][tbl_product][{$cnt}][id]','product_object_id','product_published','field[1][tbl_product][{$cnt}][product_deleted]', true);" class="btn btn-info pull-right top-btn"><span class="glyphicon glyphicon-eye-open"></span> Preview</a>
					<input type="hidden" value="product_id" name="primary_id" id="primary_id"/>
					<input type="hidden" value="product_id" name="field[1][tbl_product][{$cnt}][id]" id="id"/>
					<input type="hidden" value="{$fields.product_id}" name="field[1][tbl_product][{$cnt}][product_id]" id="product_id" class="key">
					<input type="hidden" value="{if $fields.product_object_id}{$fields.product_object_id}{else}{$objID}{/if}" name="field[1][tbl_product][{$cnt}][product_object_id]" id="product_object_id">
					<input type="hidden" value="{if $fields.product_created}{$fields.product_created}{else}{'Y-m-d H:i:s'|date}{/if}" name="field[1][tbl_product][{$cnt}][product_created]" id="product_created">
					<input type="hidden" value="{$fields.product_published}" name="field[1][tbl_product][{$cnt}][product_published]" id="product_published">

					<input type="hidden" name="formToken" id="formToken" value="{$token}" />
				</div>
			</div>
			<div class="row published" {if $fields.product_published eq 0}style="display:none;"{/if}>
				<div class="alert alert-success text-center">
					<strong>PUBLISHED</strong>
				</div>
			</div>
			<div class="row drafts" {if $fields.product_published eq 1}style="display:none;"{/if}>
				<div class="alert alert-info text-center">
					<strong>DRAFT</strong>
				</div>
			</div>
			<ul class="nav nav-tabs" id="myTab">
				<li class="active"><a href="#details" data-toggle="tab">Details</a></li>
				<li><a href="#pricing" data-toggle="tab">Pricing</a></li>
				<li><a href="#images" data-toggle="tab">Images</a></li>
				<li><a href="#attributes" data-toggle="tab">Attributes</a></li>
				<li><a href="#associated" data-toggle="tab">Associated Products</a></li>
				<li><a href="#share" data-toggle="tab">Social Sharing</a></li>
				<li><a href="#symptom" data-toggle="tab">Symptoms</a></li>
				<li><a href="#log" data-toggle="tab">Log</a></li>
				<!-- <button class="btn btn-primary" onClick="$('#Edit_Record').submit();" type="submit">Submit</button> -->
			</ul>

			<div class="tab-content">
				<!--===+++===+++===+++===+++===+++ DETAILS TAB +++===+++===+++===+++===+++====-->
				<div class="tab-pane active" id="details">
					<div class="row form" data-error="Error found on <b>Details tab</b>. View <b>Details tab</b> to see specific error notices.">
						<div class="row form-group">
							<label class="col-sm-3 control-label" for="id_product_name">Name *</label>
							<div class="col-sm-5">
								<input class="form-control" type="text" value="{$fields.product_name}" name="field[1][tbl_product][{$cnt}][product_name]" id="id_product_name" onchange="seturl(this.value);" required>
								<span class="help-block"></span>
							</div>
						</div>
						<div class="row form-group">
              <label class="col-sm-3 control-label" for="id_product_uid">Code (ISDN,Product code,etc)</label>
              <div class="col-sm-5">
                <input class="form-control" type="text" value="{$fields.product_uid}" name="field[1][tbl_product][{$cnt}][product_uid]" id="id_product_uid" >
                <span class="help-block"></span>
              </div>
            </div>
						<div class="row form-group">
								<label class="col-sm-3 control-label" for="id_product_url">URL *</label>
							<div class="col-sm-5 ">
								<input class="form-control" type="hidden" value="{$fields.product_url}" name="field[1][tbl_product][{$cnt}][product_url]" id="id_product_url" onchange="seturl(this.value, true);" required>
                <span style="display:inline-block;width:74%;" id="id_product_url_text" class="form-control url-text edit-url">{$fields.product_url}&nbsp;</span>
                <a href="javascript:void(0);" class="btn btn-info btn-sm marg-5r edit-url" onclick="$('.edit-url').removeClass('url-text').hide();$('#id_product_url').get(0).type='text';">Edit URL</a>
                <span class="help-block"></span>
							</div>
						</div>
						<div class="row form-group">
							<label class="col-sm-3 control-label" for="id_product_listing">Category</label>
							<div class="col-sm-5 ">
								<select  class="form-control" name="field[1][tbl_product][{$cnt}][product_listing_id]" id="id_product_listing" data-value="{$fields.product_listing_id}">
									<option value="{$rootParentID}">Select one</option>
									{call name=options_list opts=$fields.options.product_listing_id selected=$fields.product_listing_id ignore=$fields.product_object_id}
								</select>
							</div>
						</div>
						<div class="row form-group">
							<label class="col-sm-3 control-label" for="id_product_brand">Brand</label>
							<div class="col-sm-5 ">
								<input class="form-control" type="text" value="{$fields.product_brand}" name="field[1][tbl_product][{$cnt}][product_brand]" id="id_product_brand" >
								<span class="help-block"></span>
							</div>
						</div>
						<div class="row form-group">
							<label class="col-sm-3 control-label" for="id_product_seo_title">SEO Title *</label>
							<div class="col-sm-5 ">
								<input class="form-control" type="text" onkeyup="$(this).parent().find('.charcount').html($(this).val().length);" value="{$fields.product_seo_title}" name="field[1][tbl_product][{$cnt}][product_seo_title]" id="id_product_seo_title" required>
								<span class="small pull-right charcount">{$fields.product_seo_title|count_characters:true}</span><span class="small pull-right">characters: </span><span class="help-block"></span>
							</div>
						</div>
						<div class="row form-group">
							<label class="col-sm-3 control-label" for="id_product_meta_description">Meta Description</label>
							<div class="col-sm-5 ">
								<textarea class="form-control" rows="3" onkeyup="$(this).parent().find('.charcount').html($(this).val().length);" name="field[1][tbl_product][{$cnt}][product_meta_description]" id="id_product_meta_description">{$fields.product_meta_description}</textarea>
								<span class="small pull-right charcount">{$fields.listing_meta_description|count_characters:true}</span><span class="small pull-right">characters: </span>
							</div>
						</div>
						<div class="row form-group">
							<label class="col-sm-3 control-label" for="id_product_meta_words">Meta Words</label>
							<div class="col-sm-5 ">
								<input class="form-control" type="text" value="{$fields.product_meta_words}" name="field[1][tbl_product][{$cnt}][product_meta_words]" id="id_product_meta_words">
							</div>
						</div>
						<div class="row form-group">
              <label class="col-sm-3 control-label" for="id_product_order">Order(#position)</label>
              <div class="col-sm-5 ">
                <input class="form-control number" type="text" value="{$fields.product_order}" name="field[1][tbl_product][{$cnt}][product_order]" id="id_product_order">
              </div>
            </div>
            <div class="row form-group">
              <label class="col-sm-3 control-label" for="id_product_flag1">Featured Product</label>
              <div class="col-sm-5 ">
                <input type="hidden" value="{if $fields.product_flag1 eq 1}1{else}0{/if}" name="field[1][tbl_product][{$cnt}][product_flag1]" class="value">
                <input class="chckbx" type="checkbox" {if $fields.product_flag1 eq 1}checked="checked"{/if}
                  onclick="if($(this).is(':checked')){ $(this).parent().children('.value').val('1') }else{ $(this).parent().children('.value').val('0') }" id="id_product_flag1">
              </div>
            </div>
						<div class="row form-group">
							<label class="col-sm-3 control-label" for="id_product_content1">Description</label>
							<div class="col-sm-5 ">
								<textarea name="field[1][tbl_product][{$cnt}][product_content1]" id="id_product_content1" class="tinymce">{$fields.product_content1}</textarea>
							</div>
						</div>
						<div class="row form-group">
              <label class="col-sm-3 control-label" for="id_product_content2">Features</label>
              <div class="col-sm-5 ">
                <textarea name="field[1][tbl_product][{$cnt}][product_content2]" id="id_product_content2" class="tinymce">{$fields.product_content2}</textarea>
              </div>
            </div>
            <div class="row form-group">
              <label class="col-sm-3 control-label" for="id_product_content3">Specifications</label>
              <div class="col-sm-5 ">
                <textarea name="field[1][tbl_product][{$cnt}][product_content3]" id="id_product_content3" class="tinymce">{$fields.product_content3}</textarea>
              </div>
            </div>
            <div class="row form-group">
              <label class="col-sm-3 control-label" for="id_product_content4">Downloads</label>
              <div class="col-sm-5 ">
                <textarea name="field[1][tbl_product][{$cnt}][product_content4]" id="id_product_content4" class="tinymce">{$fields.product_content4}</textarea>
              </div>
            </div>
					</div>
				</div>
				<!--===+++===+++===+++===+++===+++ PRICING TAB +++===+++===+++===+++===+++====-->
				<div class="tab-pane" id="pricing">
					<div class="row form" data-error="Error found on <b>Pricing tab</b>. View <b>Pricing tab</b> to see specific error notices.">
						<div class="row form-group">
							<label class="col-sm-3 control-label" for="id_product_price">Price ($)</label>
							<div class="col-sm-5 ">
								<input class="form-control double" type="text" value="{$fields.product_price}" name="field[1][tbl_product][{$cnt}][product_price]" id="id_product_price">
							</div>
						</div>
						<div class="row form-group">
							<label class="col-sm-3 control-label" for="id_product_specialprice">Special Price ($)</label>
							<div class="col-sm-5 ">
								<input class="form-control double" type="text" value="{$fields.product_specialprice}" name="field[1][tbl_product][{$cnt}][product_specialprice]" id="id_product_specialprice">
							</div>
						</div>
						<div class="row form-group">
							<label class="col-sm-3 control-label" for="id_product_weight">Weight (Kg)</label>
							<div class="col-sm-5 ">
								<input class="form-control double" type="text" value="{$fields.product_weight}" name="field[1][tbl_product][{$cnt}][product_weight]" id="id_product_weight">
							</div>
						</div>
						<div class="row form-group">
							<label class="col-sm-3 control-label" for="id_product_width">Width (cm)</label>
							<div class="col-sm-5 ">
								<input class="form-control double" type="text" value="{$fields.product_width}" name="field[1][tbl_product][{$cnt}][product_width]" id="id_product_width">
							</div>
						</div>
						<div class="row form-group">
							<label class="col-sm-3 control-label" for="id_product_height">Height (cm)</label>
							<div class="col-sm-5 ">
								<input class="form-control double" type="text" value="{$fields.product_height}" name="field[1][tbl_product][{$cnt}][product_height]" id="id_product_height">
							</div>
						</div>
						<div class="row form-group">
							<label class="col-sm-3 control-label" for="id_product_length">Length (cm)</label>
							<div class="col-sm-5 ">
								<input class="form-control double" type="text" value="{$fields.product_length}" name="field[1][tbl_product][{$cnt}][product_length]" id="id_product_length">
							</div>
						</div>
						<div class="row form-group">
							<label class="col-sm-3 control-label" for="id_product_gst">Incl. GST</label>
							<div class="col-sm-5 ">
								<input type="hidden" value="{if $fields.product_id}{if $fields.product_gst eq 1}1{else}0{/if}{else}1{/if}" name="field[1][tbl_product][{$cnt}][product_gst]" class="value">
								<input class="chckbx" type="checkbox" {if $fields.product_gst eq 1 || $fields.product_id eq ""}checked="checked" {/if}
									onclick="if($(this).is(':checked')){ $(this).parent().children('.value').val('1') }else{ $(this).parent().children('.value').val('0') }" id="id_product_gst">
							</div>
						</div>
						<div class="row form-group">
							<label class="col-sm-3 control-label" for="id_product_instock">In stock</label>
							<div class="col-sm-5 ">
								<input type="hidden" value="{if $fields.product_id}{if $fields.product_instock eq 1}1{else}0{/if}{else}1{/if}" name="field[1][tbl_product][{$cnt}][product_instock]" class="value">
								<input class="chckbx" type="checkbox" {if $fields.product_instock eq 1 || $fields.product_id eq ""}checked="checked" {/if}
									onclick="if($(this).is(':checked')){ $(this).parent().children('.value').val('1') }else{ $(this).parent().children('.value').val('0') }" id="id_product_instock">
							</div>
						</div>
						<div class="row form-group">
						  <div class="col-sm-3"></div>
              <label class="col-sm-2 control-label" for="id_product_instock">Quantity(>)</label>
              <div class="col-sm-2"></div>
              <label class="col-sm-2 control-label" for="id_product_instock">Modifier(%)</label>
            </div>
						{assign var=count value=0}
						{foreach $fields.qty_modifier as $mod}
            {assign var=count value=$count+1}
            <div class="row form-group">
              <label class="col-sm-3 control-label" for="id_product_instock">Quantity/Price Modifier ({$count})</label>
              <div class="col-sm-4">
	              <input type="hidden" value="product_id" name="default[productqty_product_id]" />
				        <input type="hidden" value="{$mod.productqty_id}" name="field[20][tbl_productqty][{$count}][productqty_id]" id="productqty_id" class="key"/>
				        <input type="hidden" value="productqty_id" name="field[{20}][tbl_productqty][{$count}][id]" id="id" />
				        <input type="hidden" value="{$mod.productqty_product_id}" name="field[20][tbl_productqty][{$count}][productqty_product_id]" id="productqty_product_id" class="key"/>
	              <input class="form-control double" type="text" value="{$mod.productqty_qty}" name="field[20][tbl_productqty][{$count}][productqty_qty]" id="id_productqty_qty">
              </div>
              <div class="col-sm-4">
               <input class="form-control double" type="text" value="{$mod.productqty_modifier}" name="field[20][tbl_productqty][{$count}][productqty_modifier]" id="id_productqty_modifier">
              </div>
            </div>
						{/foreach}
						{while $count < 4}
						  {assign var=count value=$count+1}
						 <div class="row form-group">
              <label class="col-sm-3 control-label" for="id_product_instock">Quantity/Price Modifier ({$count})</label>
              <div class="col-sm-4">
                <input type="hidden" value="product_id" name="default[productqty_product_id]" />
                <input type="hidden" value="" name="field[20][tbl_productqty][{$count}][productqty_id]" id="productqty_id" class="key"/>
                <input type="hidden" value="productqty_id" name="field[{20}][tbl_productqty][{$count}][id]" id="id" />
                <input type="hidden" value="{$fields.product_id}" name="field[20][tbl_productqty][{$count}][productqty_product_id]" id="productqty_product_id" class="key"/>
                <input class="form-control double" type="text" value="" name="field[20][tbl_productqty][{$count}][productqty_qty]" id="id_productqty_qty">
              </div>
              <div class="col-sm-4">
                <input class="form-control double" type="text" value="" name="field[20][tbl_productqty][{$count}][productqty_modifier]" id="id_productqty_modifier">
              </div>
            </div>
						{/while}
					</div>
				</div>

				<!--===+++===+++===+++===+++===+++ IMAGES TAB +++===+++===+++===+++===+++====-->
				<div class="tab-pane" id="images">
					<!--  gallery -->

					<!-- <div class="row form-group" id="select-hero-img">
							<label class="col-sm-3 control-label" for="id_product_hero_image">Hero Image</label>
						<div class="col-sm-5 ">
							<select  class="form-control" name="product_hero_image" id="id_product_hero_image">
								{assign var='cntlst' value=0}
								{foreach $fields.gallery as $opt}
									{assign var='cntlst' value=$cntlst+1}
									<option value="{$cntlst}" {if $opt.gallery_ishero eq '1'}selected="selected"{/if}>{$opt.gallery_file}</option>
								{/foreach}
							</select>
						</div>
					</div>	 -->


					<div class="form" id="images-wrapper">
						{assign var='imageno' value=0}
						{assign var='gTableName' value='product'}
						{assign var='image_size' value='Size: 1170px Wide x 560px Tall'}
						{foreach $fields.gallery as $images}
							{assign var='imageno' value=$imageno+1}
							{include file='gallery.tpl'}
						{/foreach}
					</div>
					<div class="row btn-inform">
						<a href="javascript:void(0);" class="btn btn-success btn-add-new" onclick="$('.images').slideUp();newImage();"> Add New Image</a>
					</div>
					<input type="hidden" value="{$imageno}" id="imageno">
					<!--  gallery -->
				</div>
				<!--===+++===+++===+++===+++===+++ ATTRIBUTES TAB +++===+++===+++===+++===+++====-->
				<div class="tab-pane" id="attributes">
					<div class="row form" id="attributes-wrapper">
					{assign var='attributeno' value=0}
					{foreach $fields.attribute as $attribute}
						{assign var='attributeno' value=$attributeno+1}
						{include file='form_attribute.tpl'}
					{/foreach}
					</div>
					<div class="row btn-inform">
						<a href="javascript:void(0);" class="btn btn-success btn-add-new" onclick="$('.attributes').slideUp();newAttribute();"> Add New Attribute</a>
					</div>
					<input type="hidden" value="{$attributeno}" id="attributeno">
				</div>
				<!--===+++===+++===+++===+++===+++ ASSOCIATED PRODUCTS TAB +++===+++===+++===+++===+++====-->
        <div class="tab-pane" id="associated">
          <div class="form">
						<input type="hidden" value="product_id" name="default[productassoc_product_id]" />
            {assign var='data' value=$fields.options.products}
						{assign var='proccess_order' value='5'}
						{assign var='table_primary_key_field' value='product_id'}
						{assign var='table_primary_key_value' value=$fields.product_id}
						{assign var='linking_table' value='tbl_productassoc'}
						{assign var='linking_table_primary_key_field' value='productassoc_id'}
						{assign var='linking_table_link_field' value='productassoc_product_id'}
						{assign var='linking_table_associated_field' value='productassoc_product_object_id'}
						{assign var='linking_table_deleted_field' value='productassoc_deleted'}
						{assign var='selected_array' value=$fields.productassoc}
						{assign var='ignore_value' value='0'}
            {include file='form_linking_input.tpl'}
	        </div>
        </div>
				<!--===+++===+++===+++===+++===+++ SHARE TAB +++===+++===+++===+++===+++====-->
        <div class="tab-pane" id="share">
          <div class="row form" data-error="Error found on <b>Social Sharing tab</b>. View <b>Details tab</b> to see specific error notices.">
            <div class="row form-group">
              <label class="col-sm-3 control-label" for="id_product_share_title">Share Title</label>
              <div class="col-sm-5">
                <input class="form-control" type="text" value="{$fields.product_share_title}" name="field[1][tbl_product][{$cnt}][product_share_title]" id="id_product_share_title" >
                <span class="help-block"></span>
              </div>
            </div>
            <div class="row form-group">
                <label class="col-sm-3 control-label" for="product_share_image">Share Image<br>
                <small>Size: 1200px Wide x 630px Tall (less than 1Mb) <br>("None" for default image)</small></label>
              <div class="col-sm-9">
                <input type="hidden" value="{$fields.product_share_image}" name="field[1][tbl_product][{$cnt}][product_share_image]" id="product_share_image_link" class="fileinput">
                <span class="file-view" id="product_share_image_path"> {if $fields.product_share_image}<a href="{$fields.product_share_image}" target="_blank" >View</a>{else}None{/if} </span>
                <a href="javascript:void(0);" class="btn btn-info marg-5r" onclick="getFileType('product_share_image','','');">Select File</a>
                <a href="javascript:void(0);" class="btn btn-info" onclick="$('#product_share_image_link').val('');$('#product_share_image_path').html('None');">Remove File</a>
              </div>
            </div>
            <div class="row form-group">
              <label class="col-sm-3 control-label" for="id_product_share_text">Share Text <br><span class="small">(120 Characters)</span></label>
              <div class="col-sm-5">
                <textarea class="form-control"name="field[1][tbl_product][{$cnt}][product_share_text]" id="id_product_share_text" maxlength="120">{$fields.product_share_text}</textarea>
              </div>
            </div>
          </div>
        </div>
				<!--===+++===+++===+++===+++===+++ SYMPTOMS TAB +++===+++===+++===+++===+++====-->
				<div class="tab-pane" id="symptom">
					<div class="form">
						<div class="row form-group">
							<label class="col-sm-2 control-label" for="new_symptom">Symptom</label>
							<div class="col-sm-5">
								<div class="ui-widget">
									<!--<input class="form-control" id="new_symptom">-->
									<select class="form-control" id="new_symptom">
									  {foreach $fields.options.symptom as $sym}
											<option value="{$sym.value}">{$sym.value}</option>
										{/foreach}
									</select>
									<a href="javascript:void(0);" class="btn btn-success btn-add-new" onclick="newSymptom();"> Add Symptom</a>
								</div>
							</div>
						</div>
					</div>
					<div class="row form" id="symptoms-wrapper">
					{assign var='symptomno' value=0}
					{assign var='table_name' value='tbl_product'}
					{assign var='default' value='product_id'}
					{foreach $fields.symptoms as $symptom}
						{assign var='symptomno' value=$symptomno+1}
						{include file='symptom.tpl'}
					{/foreach}
					</div>
					<input type="hidden" value="{$symptomno}" id="symptomno">
				</div>
				<!--===+++===+++===+++===+++===+++ LOG TAB +++===+++===+++===+++===+++====-->
				<div class="tab-pane" id="log">
					<div class="row form" id="symptoms-wrapper">
						<div class="col-sm-12">
							{if $fields.logs}
								<table class="table table-bordered table-striped table-hover">
									<thead>
										<tr>
											<th>Date-Time</th>
											<th>Action</th>
											<th>User</th>
										</tr>
									</thead>
									<tbody>
									{foreach $fields.logs as $log}
										<tr {if $log.product_id eq $fields.product_id}class="info"{/if}>
											<td>{$log.log_created|date_format:"%d/%b/%Y %r"}</td>
											<td>{$log.log_action}{if $log.log_action eq 'Add' || $log.log_action eq 'Delete'} draft{/if}</td>
											<td>{$log.admin_name}</td>
										</tr>
									{/foreach}
									</tbody>
								</table>
							{else}
								Log empty.
							{/if}
						</div>
					</div>
				</div>
			</div>

			<div class="row form-group form-bottom-btns">
				<a href="javascript:void(0);" onClick="$('#Edit_Record').submit();" class="btn btn-primary pull-right top-btn"><span class="glyphicon glyphicon-floppy-saved"></span> Save</a>
				<div class="published" {if $fields.product_published eq 0}style="display:none;"{/if}>
					<!-- PUBLISHED -->
					<a href="javascript:void(0);" onClick="unpublish('product_published');" class="btn btn-warning pull-right top-btn"><span class="glyphicon glyphicon-thumbs-down"></span> Unpublish</a>
					<a href="javascript:void(0);" onClick="saveDraft('field[1][tbl_product][{$cnt}][id]','product_object_id','product_published','field[1][tbl_product][{$cnt}][product_deleted]', false);" class="btn btn-info pull-right top-btn published"><span class="glyphicon glyphicon-floppy-disk"></span> Save Draft Version</a>
				</div>
				<div class="drafts" {if $fields.product_published eq 1}style="display:none;"{/if}>
					<!-- DRAFT -->
					<a href="javascript:void(0);" onClick="publish('field[1][tbl_product][{$cnt}][id]','product_object_id','product_published','field[1][tbl_product][{$cnt}][product_deleted]');" class="btn btn-primary pull-right top-btn drafts"><span class="glyphicon glyphicon-thumbs-up"></span> Save &amp; Publish</a>
				</div>
				<a href="javascript:void(0);" onClick="saveDraft('field[1][tbl_product][{$cnt}][id]','product_object_id','product_published','field[1][tbl_product][{$cnt}][product_deleted]', true);" class="btn btn-info pull-right top-btn"><span class="glyphicon glyphicon-eye-open"></span> Preview</a>
			</div>
		</form>
	</div>
</div>

{include file='jquery-validation.tpl'}

<script type="text/javascript">
	$(document).ready(function() {

		$('#Edit_Record').validate({
			onkeyup : false
		});

		$('#id_product_url').rules("add", {
			uniqueURL : {
				id : $('#product_object_id').val(),
        	  	idfield: "product_object_id",
	        	table : "tbl_product",
	        	field : "product_url",
	        	field2 : "product_listing_id",
	        	value2 : "id_product_listing"
			}
		});

		$('.images').hide();

		if ($('#imageno').val() == 0) {
			$('#select-hero-img').hide();
		}

		$('.attributes').hide();
		$('.attr_values').hide();

		$('#id_product_hero_image').change(function() {
			$('.ishero').val('0');
			$('#gallery_ishero_' + $(this).val()).val('1');
		});

// 		var availableTags = [
// 			{foreach $fields.options.products_list as $opt}
// 				'{$opt.value}',
// 			{/foreach}
//          			] ;

// 		$("#new_tag").autocomplete({
// 			source : availableTags
// 		});
	});


	function saveDraft(id_name,objId_name,publish_name, field_name, preview){
		if ($('#Edit_Record').valid()) {
			$('body').css('cursor', 'wait');
			$('#'+publish_name).val('0');
			var id_key0 = encodeURIComponent(id_name+'[0]');
			var id_key1 = encodeURIComponent(id_name+'[1]');
			var objId_key = encodeURIComponent($('#'+objId_name).attr('name'));
			var publish_key = encodeURIComponent($('#'+publish_name).attr('name'));
			var field_key = encodeURIComponent(field_name);
			var field_value = encodeURIComponent(mysql_now());
			$.ajax({
				type : "POST",
				url : "/admin/includes/processes/processes-record.php",
				cache: false,
				async: false,
				data : id_key0+'='+objId_name+'&'+id_key1+'='+publish_name+'&'+objId_key+"="+$('#'+objId_name).val()+"&"+publish_key+"=0&"+field_key+"="+field_value+'&formToken='+$('#formToken').val(),
				dataType: "html",
				success : function(data, textStatus) {
					try {
						var obj = $.parseJSON(data);
						if(obj.notice){
							$('.key').val('');
							$('#Edit_Record').submit();
							$('.published').hide();
							$('.drafts').show();
							buildUrl('tbl_product','product_listing_id',objId_name, preview);
						}
					} catch (err) {}
					$('body').css('cursor', 'default');
				}
			});
			$('body').css('cursor', 'default');
		}
	}

	function publish(id_name,objId_name,publish_name,field_name){
		if ($('#Edit_Record').valid()) {
			$('body').css('cursor', 'wait');
			$('#'+publish_name).val('1');
			var id_key0 = encodeURIComponent(id_name+'[0]');
			var id_key1 = encodeURIComponent(id_name+'[1]');
			var objId_key = encodeURIComponent($('#'+objId_name).attr('name'));
			var publish_key = encodeURIComponent($('#'+publish_name).attr('name'));
			var field_key = encodeURIComponent(field_name);
			var field_value = encodeURIComponent(mysql_now());
			$.ajax({
				type : "POST",
				url : "/admin/includes/processes/processes-record.php",
				cache: false,
				data : id_key0+'='+objId_name+'&'+id_key1+'='+publish_name+'&'+objId_key+"="+$('#'+objId_name).val()+"&"+publish_key+"=1&"+field_key+"="+field_value+'&formToken='+$('#formToken').val(),
				dataType: "html",
				success : function(data, textStatus) {
					try {
						var obj = $.parseJSON(data);
						if(obj.notice){
							$('#Edit_Record').submit();
							$('.drafts').hide();
							$('.published').show();
						}
					} catch (err) {}
					$('body').css('cursor', 'default');
				}
			});
			$('body').css('cursor', 'default');
		}
	}

	function unpublish(publish_name){
		$('#'+publish_name).val('0');
		$('#Edit_Record').submit();
		$('.published').hide();
		$('.drafts').show();
	}


	function seturl(str){
		seturl(str,false);
	}

	function seturl(str,editexisting){
		$.ajax({
			type : "POST",
			url : "/admin/includes/processes/urlencode.php",
			cache : false,
			data : "value=" + encodeURIComponent(str),
			dataType : "json",
			success : function(res, textStatus) {
				try {
					if($('#product_id').val() == "" || editexisting == true){
					$('#id_product_url').val(res.url);
					$('#id_product_url_text').html(res.url);
					}
				} catch (err) {
				}
			}
		});
	}

	function newAttribute() {
		$('body').css('cursor', 'wait');
		var no = $('#attributeno').val();
		no++;
		$('#attributeno').val(no);
		$.ajax({
			type : "POST",
			url : "/admin/includes/processes/load-template.php",
			cache : false,
			data : "template=form_attribute.tpl&attributeno=" + no
					+ "&attrvalueno=0",
			dataType : "html",
			success : function(data, textStatus) {
				try {
					$('#attributes-wrapper').append(data);
					$('body').css('cursor', 'default');
					scrolltodiv('#attribute_wrapper' + no);
				} catch (err) {
					$('body').css('cursor', 'default');
				}
			}
		});
	}

	function toggleAttribute(ID) {
		if ($('#attribute' + ID).is(':visible')) {
			$('.attributes').slideUp();
		} else {
			$('.attributes').slideUp();
			$('#attribute' + ID).slideDown();
		}
	}

	function toggleAttr_value(ID) {
		if ($('#attr_value' + ID).is(':visible')) {
			$('.attr_values').slideUp();
		} else {
			$('.attr_values').slideUp();
			$('#attr_value' + ID).slideDown();
			;
		}
	}

	function newAttr_value(attribute_no) {
		$('body').css('cursor', 'wait');
		var attribute = new String(attribute_no);
		var no = $('#attr_valueno' + attribute).val();
		no++;
		$('#attr_valueno' + attribute).val(no);

		$.ajax({
			type : "POST",
			url : "/admin/includes/processes/load-template.php",
			cache : false,
			data : "template=form_value.tpl&attributeno="
					+ attribute_no + "&attrvalueno=" + no,
			dataType : "html",
			success : function(data, textStatus) {
				try {
					$('#attr_value-wrapper' + attribute_no)
							.append(data);
					displayResults();
					$('body').css('cursor', 'default');
					scrolltodiv('#attr_value_wrapper' + attribute_no
							+ '-' + no);
				} catch (err) {
					$('body').css('cursor', 'default');
				}
			}
		});
	}
	function deleteAttribute(rid) {
		if (ConfirmDelete()) {
			var ID = 'attribute_wrapper' + rid;
			var count = $('#' + ID).attr('rel');
			var fieldno = $('#' + ID).attr('fieldno');
			var today = new Date();
			var dd = today.getDate();
			var mm = today.getMonth() + 1;//January is 0!
			var yyyy = today.getFullYear();
			var hh = today.getHours();
			var MM = today.getMinutes();
			var ss = today.getSeconds();

			html = '<input type="hidden" value="'+yyyy+'-'+mm+'-'+dd+' '+hh+':'+MM+':'+ss+'" name="field['+fieldno+'][tbl_attribute]['+count+'][attribute_deleted]" />';
			$('#' + ID).append(html);
			var entno = $('.attribute_attr_values' + rid).length;

			for ( var i = 1; i <= entno; i++) {
				var elem = new String(rid * 20 + i);
				var del = 'attr_value_wrapper' + elem;
				//deleteAttr_value(del);
				var acount = $('#' + del).attr('rel');
				var afieldno = $('#' + del).attr('fieldno');
				html = '<input type="hidden" value="'+yyyy+'-'+mm+'-'+dd+' '+hh+':'+MM+':'+ss+'" name="field['+afieldno+'][tbl_attr_value]['+acount+'][attr_value_deleted]" />';
				$('#' + del).append(html);
				$('#' + del).css('display', 'none');
				$('#' + del).removeClass('attr_values');
			}

			$('#' + ID).css('display', 'none');
			$('#' + ID).removeClass('attributes');
		} else {
			return false;
		}

	}
	function deleteAttr_value(ID) {
		if (ConfirmDelete()) {
			var count = $('#' + ID).attr('rel');
			var fieldno = $('#' + ID).attr('fieldno');
			var today = new Date();
			var dd = today.getDate();
			var mm = today.getMonth() + 1;//January is 0!
			var yyyy = today.getFullYear();
			var hh = today.getHours();
			var MM = today.getMinutes();
			var ss = today.getSeconds();

			html = '<input type="hidden" value="'+yyyy+'-'+mm+'-'+dd+' '+hh+':'+MM+':'+ss+'" name="field['+fieldno+'][tbl_attr_value]['+count+'][attr_value_deleted]" />';
			$('#' + ID).append(html);
			$('#' + ID).css('display', 'none');
			$('#' + ID).removeClass('attr_values');

		} else {
			return false;
		}
	}

	function scrolltodiv(id) {
		$('html,body').animate({
			scrollTop : $(id).offset().top
		});
	}

	function newImage() {
		$('body').css('cursor', 'wait');
		var no = $('#imageno').val();
		no++;
		$('#imageno').val(no);
		$.ajax({
			type : "POST",
			url : "/admin/includes/processes/load-template.php",
			cache : false,
			data : "template=gallery.tpl&imageno=" + no + "&gTableName=product&image_size="+ encodeURIComponent('Size: 1170px Wide x 560px Tall'),
			dataType : "html",
			success : function(data, textStatus) {
				try {
					$('#images-wrapper').append(data);
					$('body').css('cursor', 'default');
					scrolltodiv('#image_wrapper' + no);
					if (no == 1) {
						$('#gallery_ishero_1').val('1');
					}
					$('#select-hero-img').show();
					$('#id_product_hero_image').append($('<option>', {
						value : no,
						text : 'Image #' + no
					}));
				} catch (err) {
					$('body').css('cursor', 'default');
				}
			}
		});
	}

	function toggleImage(ID) {
		if ($('#image' + ID).is(':visible')) {
			$('.images').slideUp();
		} else {
			$('.images').slideUp();
			$('#image' + ID).slideDown();
		}
	}

	function deleteImage(ID) {
		if (ConfirmDelete()) {
			var count = $('#' + ID).attr('rel');
			var today = new Date();
			var dd = today.getDate();
			var mm = today.getMonth() + 1;//January is 0!
			var yyyy = today.getFullYear();
			var hh = today.getHours();
			var MM = today.getMinutes();
			var ss = today.getSeconds();

			html = '<input type="hidden" value="'+yyyy+'-'+mm+'-'+dd+' '+hh+':'+MM+':'+ss+'" name="field[10][tbl_gallery]['+count+'][gallery_deleted]" />';
			$('#' + ID).append(html);
			$('#' + ID).css('display', 'none');
			$('#' + ID).removeClass('images');
			$('#id_product_hero_image option[value=' + count + ']').remove();
			$('#id_product_hero_image').trigger('change');
			if ($('#id_product_hero_image option').length == 0) {
				$('#select-hero-img').hide();
			}
		} else {
			return false;
		}
	}

	function newSymptom() {
		if ( $('#new_symptom').val() != '' ) {
			$('body').css('cursor', 'wait');
			var no = $('#symptomno').val();
			no++;
			$('#symptomno').val(no);
			$.ajax({
				type : "POST",
				url : "/admin/includes/processes/processes-tags.php",
				cache : false,
				data : "template=symptom.tpl&symptomno=" + no + "&symptom%5Bsymptom_value%5D="	+  $('#new_symptom').val() + "&table_name=tbl_product&default=product_id&objId=" + $('#product_id').val(),
				dataType : "html",
				success : function(data, textStatus) {
					try {
						$('#symptoms-wrapper').prepend(data);
						$('#new_symptom').val('');
						$('body').css('cursor', 'default');
						$('#new_symptom').closest('.form-group').removeClass('has-success').removeClass('has-error');
					} catch (err) {
						$('body').css('cursor', 'default');
					}
				}
			});
		} else {
			$('#new_symptom').closest('.form-group').removeClass('has-success').addClass('has-error');
		}
	}

	function deleteSymptom(ID) {
		if (ConfirmDelete()) {
			var count = $('#' + ID).attr('rel');
			var today = new Date();
			var dd = today.getDate();
			var mm = today.getMonth() + 1;//January is 0!
			var yyyy = today.getFullYear();
			var hh = today.getHours();
			var MM = today.getMinutes();
			var ss = today.getSeconds();

			html = '<input type="hidden" value="'+yyyy+'-'+mm+'-'+dd+' '+hh+':'+MM+':'+ss+'" name="field[15][tbl_symptom]['+count+'][symptom_deleted]" />';
			$('#' + ID).append(html);
			$('#' + ID).css('display', 'none');
			$('#' + ID).removeClass('symptoms');
		} else {
			return false;
		}
	}

	function newTag() {
		if ( $('#new_tag').val() != '' ) {
			$('body').css('cursor', 'wait');
			var no = $('#tagno').val();
			no++;
			$('#tagno').val(no);
			$.ajax({
				type : "POST",
				url : "/admin/includes/processes/processes-tags.php",
				cache : false,
				data : "template=tag.tpl&tagno=" + no + "&tag%5Btag_value%5D="	+  $('#new_tag').val() + "&table_name=tbl_product&default=product_id&objId=" + $('#product_id').val(),
				dataType : "html",
				success : function(data, textStatus) {
					try {
						$('#tags-wrapper').prepend(data);
						$('#new_tag').val('');
						$('body').css('cursor', 'default');
						$('#new_tag').closest('.form-group').removeClass('has-success').removeClass('has-error');
					} catch (err) {
						$('body').css('cursor', 'default');
					}
				}
			});
		} else {
			$('#new_tag').closest('.form-group').removeClass('has-success').addClass('has-error');
		}
	}

	function deleteTag(ID) {
		if (ConfirmDelete()) {
			var count = $('#' + ID).attr('rel');
			var today = new Date();
			var dd = today.getDate();
			var mm = today.getMonth() + 1;//January is 0!
			var yyyy = today.getFullYear();
			var hh = today.getHours();
			var MM = today.getMinutes();
			var ss = today.getSeconds();

			html = '<input type="hidden" value="'+yyyy+'-'+mm+'-'+dd+' '+hh+':'+MM+':'+ss+'" name="field[15][tbl_tag]['+count+'][tag_deleted]" />';
			$('#' + ID).append(html);
			$('#' + ID).css('display', 'none');
			$('#' + ID).removeClass('tags');
		} else {
			return false;
		}
	}

	function displayResults() {
		$(".modifier").each(function(i) {
			refreshResult(this.id);
		});
	}

	function refreshResult(ID) {
		var obj = document.getElementById(ID);
		var modifierValue = obj.value;
		var productValue = parseFloat($(
				'#id_product_' + obj.getAttribute('modify')).val());
		if ($.isNumeric(modifierValue)) {
			var result = productValue + parseFloat(modifierValue);
			$(obj).closest('.form-group').find('.form-help-value').text(
					result.toFixed(2));
		} else {
			$(obj).closest('.form-group').find('.form-help-value').text(
					productValue.toFixed(2));
		}
	}

	$('#myTab a[href="#attributes"]').click(function() {
		displayResults();
	})


	function preview() {
		if ($('#Edit_Record').valid()) {
			$('body').css('cursor', 'wait');
			var datastring = $("#Edit_Record").serialize();
			$.ajax({
				type : "POST",
				url : "/admin/includes/processes/processes-preview.php",
				cache: false,
				data: datastring,
				dataType: "html",
				success : function(data, textStatus) {
					try {

					} catch (err) {

					}
				}
			});
			$('body').css('cursor', 'default');
		} else {
			alert('has-error');
		}
	}
</script>
{/block}
