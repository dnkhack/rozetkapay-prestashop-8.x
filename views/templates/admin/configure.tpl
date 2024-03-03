{*
* RozetkaPay
*
*  @author RozetkaPay <business@rozetkapay.com>
*  @copyright RozetkaPay
*  @license http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 *}
<form class="defaultForm form-horizontal" action="" method="post" enctype="multipart/form-data">
<prestashop-accounts></prestashop-accounts>
<br>
<div id="prestashop-cloudsync"></div>

<div id="ps-billing"></div>
<div id="ps-modal"></div>

<br>
        


<!-- Nav tabs -->
<ul class="nav nav-tabs" role="tablist">
    <li class="active"><a href="#general" role="tab" data-toggle="tab">{l s='General' mod='rozetkapay'}</a></li>
    <li><a href="#order_status" role="tab" data-toggle="tab">{l s='Order Statuses' mod='rozetkapay'}</a></li>
    <li><a href="#view" role="tab" data-toggle="tab">{l s='View' mod='rozetkapay'}</a></li>
    <li><a href="#sandbox" role="tab" data-toggle="tab">{l s='Testing' mod='rozetkapay'}</a></li>
    <li><a href="#system_info" role="tab" data-toggle="tab">{l s='System Information' mod='rozetkapay'}</a></li>
</ul>

<!-- Tab panes -->
<div class="tab-content">
    <div class="tab-pane active" id="general">
        <div class="panel"><div class="row">{include file='./general.tpl'}</div></div>
    </div>
    <div class="tab-pane" id="order_status">
        <div class="panel"><div class="row">{include file='./order_status.tpl'}</div></div>
    </div>
    <div class="tab-pane" id="view">
        <div class="panel"><div class="row">{include file='./view.tpl'}</div></div>
    </div>
    <div class="tab-pane" id="sandbox">
        <div class="panel"><div class="row">{include file='./sandbox.tpl'}</div></div>
    </div>
    <div class="tab-pane" id="system_info">
        <div class="panel"><div class="row">{include file='./system_info.tpl'}</div></div>
    </div>
</div>

<script src="{$urlAccountsCdn|escape:'htmlall':'UTF-8'}" rel=preload></script>
<script src="{$urlCloudsync|escape:'htmlall':'UTF-8'}"></script>
<script src="{$urlBilling|escape:'htmlall':'UTF-8'}"></script>

<script>
    window?.psaccountsVue?.init();

    if(window.psaccountsVue.isOnboardingCompleted() != true)
    {
    	document.getElementById("module-config").style.opacity = "0.5";
    }

	// Cloud Sync
	const cdc = window.cloudSyncSharingConsent;

	cdc.init('#prestashop-cloudsync');
	cdc.on('OnboardingCompleted', (isCompleted) => {
		console.log('OnboardingCompleted', isCompleted);
		
	});
	cdc.isOnboardingCompleted((isCompleted) => {
		console.log('Onboarding is already Completed', isCompleted);
	});


	window.psBilling.initialize(window.psBillingContext.context, '#ps-billing', '#ps-modal', (type, data) => {
		// Event hook listener
		switch (type) {
		  case window.psBilling.EVENT_HOOK_TYPE.BILLING_INITIALIZED:
		    console.log('Billing initialized', data);
		    break;
		  case window.psBilling.EVENT_HOOK_TYPE.SUBSCRIPTION_UPDATED:
		    console.log('Sub updated', data);
		    break;
		  case window.psBilling.EVENT_HOOK_TYPE.SUBSCRIPTION_CANCELLED:
		    console.log('Sub cancelled', data);
		    break;
		}
	});
</script>

<button type="submit" value="1" id="submitSetting" name="submitSetting" class="btn btn-default pull-right">
    <i class="process-icon-save"></i> {l s='Save' mod='rozetkapay'}
</button>
</form>