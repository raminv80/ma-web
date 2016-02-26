{block name=body}

<div class="row">
	<div class="col-sm-12 edit-page-header">
		Enquiries
	</div>
	<div class="col-sm-12">
		<table class="table table-bordered table-striped table-hover" style="margin-top:20px;">
			<tbody>
				<tr>
					<td><b>Site:</b></td>
					<td class="text-center">
						{$fields.contact_site}
					</td>
				</tr>
				<tr{if !$fields.contact_reference_name} style="color: #ccc"{/if}>
					<td><b>Store:</b></td>
					<td class="text-center">
						{$fields.contact_reference_name}
					</td>
				</tr>
				<tr>
					<td><b>Name:</b></td>
					<td class="text-center">
						{$fields.contact_name}
					</td>
				</tr>
				<tr>
					<td><b>Email:</b></td>
					<td class="text-center">
						 {if $fields.contact_email}<a href="mailto:{$fields.contact_email}" title="Click to send an email">{$fields.contact_email}</a>{/if}
					</td>
				</tr>
				<tr>
					<td><b>Phone:</b></td>
					<td class="text-center">
						 {if $fields.contact_phone}<a href="tel:{$fields.contact_phone}" title="Click to call">{$fields.contact_phone}</a>{/if}
					</td>
				</tr>
				<tr{if !$fields.contact_postcode} style="color: #ccc"{/if}>
					<td><b>Postcode:</b></td>
					<td class="text-center">
						 {$fields.contact_postcode}
					</td>
				</tr>
				<tr{if $fields.contact_file eq 'uploads_contact/' || $fields.contact_file eq ''} style="color: #ccc"{/if}>
					<td><b>File:</b></td>
					<td class="text-center">
						 {if $fields.contact_file neq 'uploads_contact/' && $fields.contact_file neq ''}<a href="/{$fields.contact_file}" target="_blank">File Link</a>{else}None{/if}
					</td>
				</tr>
				<tr>
					<td><b>Content:</b></td>
					<td class="text-center">
						 {$fields.contact_enquiry}
					</td>
				</tr>
			</tbody>
		</table>
	</div>
</div>


{/block}
