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

<div>
	<h3>{l s='Redirect your customer' mod='rozetkapay'}:</h3>
	<ul class="alert alert-info">
			<li>{l s='This action should be used to redirect your customer to the website of your payment processor' mod='rozetkapay'}.</li>
	</ul>
	
	<div class="alert alert-warning">
		{l s='You can redirect your customer with an error message' mod='rozetkapay'}:
		<a href="{$link->getModuleLink('rozetkapay', 'redirect', ['action' => 'error'], true)|escape:'htmlall':'UTF-8'}" title="{l s='Look at the error' mod='rozetkapay'}">
			<strong>{l s='Look at the error message' mod='rozetkapay'}</strong>
		</a>
	</div>
	
	<div class="alert alert-success">
		{l s='You can also redirect your customer to the confirmation page' mod='rozetkapay'}:
		<a href="{$link->getModuleLink('rozetkapay', 'confirmation', ['cart_id' => $cart_id, 'secure_key' => $secure_key], true)|escape:'htmlall':'UTF-8'}" title="{l s='Confirm' mod='rozetkapay'}">
			<strong>{l s='Go to the confirmation page' mod='rozetkapay'}</strong>
		</a>
	</div>
</div>
