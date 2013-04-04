{block name=findmyproduct}
<div class="grid_6" id="find-vehicle-apps">
	<h1>Find my vehicle app</h1>
	<div id="find-vehicle-apps-form">
		<form id="find-vehicle-form" name="find-vehicle-form" action="/products" method="POST">
			<table cellspacing="0" cellpadding="0" border="0">
				<tbody>
					<tr>
						<td><strong>What are you looking for?</strong></td>
					</tr>
					<tr>
						<td><select name="category" id="category-select">
							<option selected="selected" value="">something</option>
						</select></td>
					</tr>
					<tr>
						<td><strong>Select manfacturer of your vehicle:</strong></td>
					</tr>
					<tr>
						<td><select name="oem" id="manfacturer-select">
							<option selected="selected" value="null">Please Select One</option>
							<option value="Citroen">Citroen</option>
							<option value="Daewoo">Daewoo</option>
							<option value="Daihatsu">Daihatsu</option>
							<option value="Fiat">Fiat</option>
							<option value="Ford">Ford</option>
							<option value="Great Wall">Great Wall</option>
							<option value="Holden">Holden</option>
							<option value="Honda">Honda</option>
							<option value="Hyundai">Hyundai</option>
							<option value="Isuzu">Isuzu</option>
							<option value="Jeep">Jeep</option>
							<option value="Kia">Kia</option>
							<option value="Land Rover">Land Rover</option>
							<option value="Lexus">Lexus</option>
							<option value="Mazda">Mazda</option>
							<option value="Mercedes Benz">Mercedes Benz</option>
							<option value="Mitsubishi">Mitsubishi</option>
							<option value="Nissan">Nissan</option>
							<option value="Peugeot">Peugeot</option>
							<option value="Renault">Renault</option>
							<option value="Skoda">Skoda</option>
							<option value="Ssangyong">Ssangyong</option>
							<option value="Subaru">Subaru</option>
							<option value="Suzuki">Suzuki</option>
							<option value="Toyota">Toyota</option>
							<option value="Truck">Truck</option>
							<option value="Volkswagen">Volkswagen</option>
						</select></td>
					</tr>
					<tr>
						<td><strong>Select make of your vehicle:</strong></td>
					</tr>
					<tr>
						<td><select name="make" id="make-select">
							<option selected="selected" value="null">Please Select One</option>
						</select></td>
					</tr>
					<tr>
						<td><strong>Select year of your vehicle:</strong></td>
					</tr>
					<tr>
						<td><select name="year" id="year-select">
							<option selected="selected" value="null">Please Select One</option>
						</select></td>
					</tr>
					<tr>
						<td><a id="find-car-btn"
							onclick="jQuery('#find-vehicle-form').submit();"
							href="JavaScript:void(0);" title="FIND"><img border="0"
							src="/images/template/find-btn.png" alt=""></a><br>
						<a href="/contact_us" title="Can't find vehicle?"><small>Can't
						find vehicle?</small></a></td>
					</tr>
				</tbody>
			</table>
		</form>
	</div>
</div>
{/block}




