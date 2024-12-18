{*
 * NOTICE OF LICENSE
 *
 * This file is not open source! Each license that you purchased is only available for 1 website only.
 * If you want to use this file on more websites (or projects), you need to purchase additional licenses.
 * You are not allowed to redistribute, resell, lease, license, sub-license or offer our resources to any third party.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please contact us for extra customization service at an affordable price
 *
 * @author    RozetkaPay <ecomsupport@rozetkapay.com>
 * @copyright 2020-2024 RozetkaPay
 * @license   Valid for 1 website (or project) for each purchase of license
*}

<div class="test-card">
	<table border="1">
		<thead>
		<tr>
			<th>Card</th>
			<th>Exp</th>
			<th>CVV</th>
			<th>3DS</th>
			<th>Result</th>
		</tr>
		</thead>
		<tbody>
		<tr>
			<td>4242424242424242</td>
			<td>any</td>
			<td>any</td>
			<td>Yes</td>
			<td>success</td>
		</tr>
		<tr>
			<td>5454545454545454</td>
			<td>any</td>
			<td>any</td>
			<td>Yes</td>
			<td>success</td>
		</tr>
		<tr>
			<td>4111111111111111</td>
			<td>any</td>
			<td>any</td>
			<td>No</td>
			<td>success</td>
		</tr>
		<tr>
			<td>4200000000000000</td>
			<td>any</td>
			<td>any</td>
			<td>Yes</td>
			<td>rejected</td>
		</tr>
		<tr>
			<td>5105105105105100</td>
			<td>any</td>
			<td>any</td>
			<td>Yes</td>
			<td>rejected</td>
		</tr>
		<tr>
			<td>4444333322221111</td>
			<td>any</td>
			<td>any</td>
			<td>No</td>
			<td>rejected</td>
		</tr>
		<tr>
			<td>5100000020002000</td>
			<td>any</td>
			<td>any</td>
			<td>No</td>
			<td>rejected</td>
		</tr>
		<tr>
			<td>4000000000000044</td>
			<td>any</td>
			<td>any</td>
			<td>No</td>
			<td>insufficient-funds</td>
		</tr>
		</tbody>
	</table>

</div>