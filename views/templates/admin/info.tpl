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

<div class="panel">
	<div class="row rozetkapay-header">
		<img src="{$module_dir|escape:'html':'UTF-8'}views/img/rozetka-pay-logo.svg" class="col-xs-6 col-md-4 text-center" id="payment-logo" />
		<div class="col-xs-6 col-md-4 text-center">
			<h4>{l s='Online payment processing' mod='rozetkapay'}</h4>
			<h4>{l s='Fast - Secure - Reliable' mod='rozetkapay'}</h4>
		</div>
		<div class="col-xs-12 col-md-4 text-center">
			<a href="https://rozetkapay.com/" target="_blank" class="btn btn-primary" id="create-account-btn">{l s='Create an account now!' mod='rozetkapay'}</a><br />
			{l s='Already have an account?' mod='rozetkapay'}<a href="https://business.rozetkapay.com/" target="_blank"> {l s='Log in' mod='rozetkapay'}</a>
		</div>
	</div>

	<hr />
	
	<div class="rozetkapay-content">
		<div class="row">
			<div class="col-md-6">
				<h5>{l s='My payment module offers the following benefits' mod='rozetkapay'}</h5>
				<dl>
					<dt>&middot; {l s='Increase customer payment options' mod='rozetkapay'}</dt>
					<dd>{l s='Visa®, Mastercard®, Diners Club®, American Express®, Discover®, Network and CJB®, plus debit, gift cards and more.' mod='rozetkapay'}</dd>
					
					<dt>&middot; {l s='Help to improve cash flow' mod='rozetkapay'}</dt>
					<dd>{l s='Receive funds quickly from the bank of your choice.' mod='rozetkapay'}</dd>
					
					<dt>&middot; {l s='Enhanced security' mod='rozetkapay'}</dt>
					<dd>{l s='Multiple firewalls, encryption protocols and fraud protection.' mod='rozetkapay'}</dd>
					
					<dt>&middot; {l s='One-source solution' mod='rozetkapay'}</dt>
					<dd>{l s='Conveniance of one invoice, one set of reports and one 24/7 customer service contact.' mod='rozetkapay'}</dd>
				</dl>
			</div>
			
			<div class="col-md-6">
				<h5>{l s='FREE My Payment Module Glocal Gateway (Value of 400$)' mod='rozetkapay'}</h5>
				<ul>
					<li>{l s='Simple, secure and reliable solution to process online payments' mod='rozetkapay'}</li>
					<li>{l s='Virtual terminal' mod='rozetkapay'}</li>
					<li>{l s='Reccuring billing' mod='rozetkapay'}</li>
					<li>{l s='24/7/365 customer support' mod='rozetkapay'}</li>
					<li>{l s='Ability to perform full or patial refunds' mod='rozetkapay'}</li>
				</ul>
				<br />
				<em class="text-muted small">
					* {l s='New merchant account required and subject to credit card approval.' mod='rozetkapay'}
					{l s='The free My Payment Module Global Gateway will be accessed through log in information provided via email within 48 hours.' mod='rozetkapay'}
					{l s='Monthly fees for My Payment Module Global Gateway will apply.' mod='rozetkapay'}
				</em>
			</div>
		</div>

		<hr />
		
		<div class="row">
			<div class="col-md-12">
				<h4>{l s='Accept payments in the Ukraine States using all major credit cards' mod='rozetkapay'}</h4>
				<p class="text-branded">{l s='Call +38 (044) 390-02-38 if you have any questions or need more information!' mod='rozetkapay'}</p>
				<img src="{$module_dir|escape:'html':'UTF-8'}views/img/template_1_cards.png" class="col-md-6" id="card-payment-logo" />
			</div>
		</div>
	</div>
</div>

<div class="panel">
	<h3><i class="icon icon-tags"></i> {l s='Documentation' mod='rozetkapay'}</h3>
	<p class="text-muted">
		<i class="icon icon-info-circle"></i>
		<ul>
			<li>{l s='In order to create a secure account with My Payment Module, please complete the fields in the settings panel below:' mod='rozetkapay'}</li>
			<li>{l s='By clicking the "Save" button you are creating secure connection details to your store.' mod='rozetkapay'}</li>
			<li>{l s='Payment Module signup only begins when you client on "Activate your account" in the registration panel below.' mod='rozetkapay'}</li>
			<li>{l s='If you already have an account you can create a new shop within your account.' mod='rozetkapay'}</li>
		</ul>
	</p>
	<p>
		&raquo; {l s='You can get a PDF documentation to configure this module' mod='rozetkapay'} :
	<ul>
		<li><a href="{$module_dir|escape:'htmlall':'UTF-8'}guides/guide_en.pdf" target="_blank">{l s='English' mod='rozetkapay'}</a></li>
		<li><a href="{$module_dir|escape:'htmlall':'UTF-8'}guides/guide_uk.pdf" target="_blank">{l s='Ukraine' mod='rozetkapay'}</a></li>
	</ul>
	</p>
</div>