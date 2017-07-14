{block name=testimonials}
<div id="testimonial_wrapper{$testimonialsno}" rel="{$testimonialsno}" class="testimonials-testimonial sub-form">
	<div class="row" >
		<div class="col-sm-8">
			<fieldset>
				<legend style="font-size:17px;">
					<div id="testimonials_title_{$testimonialsno}">{if $testimonials.testimonial_title}{$testimonials.testimonial_title}{else}testimonial #{$testimonialsno}{/if}</div>
				</legend>
			</fieldset>
		</div>
		<div class="col-sm-2">
			<a href="javascript:void(0);" class="btn btn-warning trainer-btn" onclick="toggleTestimonial('{$testimonialsno}')">Show / Hide</a>
		</div>
		<div class="col-sm-2">
			<a href="javascript:void(0);" class="btn btn-danger del-btn" onclick="deleteTestimonial('testimonial_wrapper{$testimonialsno}')">Delete</a>
		</div>
	</div>
	<div class="row testimonials" id="testimonial{$testimonialsno}">
		<div class="row form-group">
			<label class="col-sm-3 control-label" for="testimonials_title_{$testimonialsno}">Title (internal use only)</label>
			<div class="col-sm-5">
				<input type="hidden" value="{$gTableName}_id" name="default[testimonial_object_id]" />
				<input type="hidden" value="testimonial_id" name="field[10][tbl_testimonial][{$testimonialsno}][id]" id="id_{$testimonialsno}"/>
				<input type="hidden" value="{$testimonials.testimonial_id}" name="field[10][tbl_testimonial][{$testimonialsno}][testimonial_id]" class="key" >
				<input type="hidden" value="{$testimonials.testimonial_object_id}" name="field[10][tbl_testimonial][{$testimonialsno}][testimonial_object_id]" id="testimonial_object_id_{$testimonialsno}" class="key" >
				<input type="hidden" value="{if $testimonials.testimonial_created}{$testimonials.testimonial_created}{else}{'Y-m-d H:i:s'|date}{/if}" name="field[10][tbl_testimonial][{$testimonialsno}][testimonial_created]" id="testimonial_created_{$testimonialsno}">
				<input class="form-control" type="text" value="{$testimonials.testimonial_title}" name="field[10][tbl_testimonial][{$testimonialsno}][testimonial_title]" id="testimonials_title_{$testimonialsno}" onchange="$('#testimonial_title_{$testimonialsno}').html(this.value);">
			</div>
		</div>
		<div class="row form-group">
			<label class="col-sm-3 control-label" for="testimonials_name_{$testimonialsno}">Full name</label>
			<div class="col-sm-5">
				<input class="form-control" name="field[10][tbl_testimonial][{$testimonialsno}][testimonial_name]" id="testimonial_name_{$testimonialsno}" value="{$testimonials.testimonial_name}" >
			</div>
		</div>
		<div class="row form-group">
			<label class="col-sm-3 control-label" for="testimonials_description_{$testimonialsno}">Description</label>
			<div class="col-sm-5">
				<textarea class="form-control" maxlength="1500" rows="3" onkeyup="$(this).parent().find('.charcount').html($(this).val().length);"  name="field[10][tbl_testimonial][{$testimonialsno}][testimonial_description]" id="testimonial_description_{$testimonialsno}" >{$testimonials.testimonial_description}</textarea>
				<span class="small pull-right charcount">{$testimonials.testimonial_description|count_characters:true}</span><span class="small pull-right">characters: </span>
			</div>
		</div>
		<div class="row form-group">
			<label class="col-sm-3 control-label" for="testimonials_publish_{$testimonialsno}">Publish</label>
			<div class="col-sm-5">
				<input type="hidden" value="{if $testimonials.testimonial_publish eq 1}1{else}0{/if}" name="field[10][tbl_testimonial][{$testimonialsno}][testimonial_publish]" class="value">
				<input class="chckbx" type="checkbox" {if $testimonials.testimonial_publish eq 1} checked="checked" {/if} onclick="if($(this).is(':checked')){ $(this).parent().children('.value').val('1') }else{ $(this).parent().children('.value').val('0') }" id="testimonial_publish_{$testimonialsno}">
			</div>
		</div>
	</div>
</div>
{/block}