<h2>Discount Management System</h2>
<br>
<b>Website:</b> <a href="{$domain}" title="website">{$domain}</a>
<br>
<br>
<b>Name:</b> {$discount.discount_name}
<br>
<b>Code:</b> {$discount.discount_code}
<br>
<br>
<b>User:</b> {$user.gname}
<br>
<b>Email:</b> {$user.email}
<br>
<br>
<b>Order No:</b> {$payment.payment_transaction_no}
<br>
<b>Order Date:</b> {$payment.payment_created|date_format:"%e %B %Y"}
<br>
<b>Discount Amount:</b> $ -{$payment.payment_discount|number_format:2:".":","}
<br>

