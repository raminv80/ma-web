
<style>
body {
  font-family: calibri, Helvetica, sans-serif;
  font-size: 13px;
  line-height: 18px;
}

table {
  max-width: 600px;
  width: 100%;
}

table th,table td {
  padding: 3px;
  text-align: left;
}

table th {
  text-align: left;
  background-color: #f3f3f3;
  line-height: 25px;
}

table td {
  text-align: left;
}
</style>
<br>
<img src="{$DOMAIN}/images/logo.png" alt="logo">
<br>
<br>
<table cellspacing="0" cellpadding="0" border="0">
  <tr>
    <th colspan="2">Store Details</th>
  </tr>
  <tr>
    <td width="50%">Name:</td>
    <td>{$storename}</td>
  </tr>
  <tr>
    <td width="50%">Phone:</td>
    <td>{$storephone}</td>
  </tr>
</table>
<br />
&nbsp;
<table cellspacing="0" cellpadding="0" border="0">
  <tr>
    <th colspan="2">Order Details</th>
  </tr>
  <tr>
    <td width="50%">Order Number:</td>
    <td>{$payment.payment_transaction_no}</td>
  </tr>
  <tr>
    <td>Order Date:</td>
    <td>{$order.cart_closed_date|date_format:"%e %B %Y"}</td>
  </tr>
</table>
<br />
&nbsp;
<br />
<table cellpadding="0" cellspacing="0" border="0">
  <tr>
    <th colspan="2">Contact information</th>
  </tr>
  <tr>
    <td width="50%">Name:</td>
    <td>{$address.address_name}</td>
  </tr>
  <tr>
    <td>Postcode:</td>
    <td>{$address.address_postcode}</td>
  </tr>
  <tr>
    <td>Preferred Contact Method:</td>
    <td>{$address.address_contact_method|capitalize}</td>
  </tr>
  <tr>
    <td>Email:</td>
    <td>{$user.email}</td>
  </tr>
  <tr>
    <td>Phone:</td>
    <td>{$address.address_telephone}</td>
  </tr>
</table>
<br />
&nbsp;
<br />
<table cellspacing="0" cellpadding="0" border="0">
  <tr>
    <th>Items</th>
    <th>Qty</th>
    <th>Variant</th>
  </tr>
  {foreach $orderItems as $item}
  <tr valign="top" aling="left">
    <td>{$item.cartitem_product_name}</td>
    <td width="20%">{$item.cartitem_quantity}</td>
    <td width="30%">{if $item.attributes}{foreach $item.attributes as $attr}{$attr.cartitem_attr_attribute_name}: {$attr.cartitem_attr_attr_value_name}{if $smarty.foreach.attrloop.last}{else}<br/>{/if}{/foreach}{/if}</td>
  </tr>
  {/foreach}
</table>
<br />
&nbsp;
<br />
<table>
  <tr>
    <td>Best regards<br>
    <br> Steeline <br>
    </td>
  </tr>
</table>