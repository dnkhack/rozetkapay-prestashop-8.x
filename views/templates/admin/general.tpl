{*
 * 2023 RozetkaPay
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 *  versions in the future. If you wish to customize PrestaShop for your
 *  needs please refer to http://www.prestashop.com for more information.
 *
 *  @author RozetkaPay <business@rozetkapay.com>
 *  @copyright RozetkaPay
 *  @license http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 *}
<div class="form-group required">
    <label class="control-label col-lg-3 text-right">{l s='Login(RozetkaPay)' mod='rozetkapay'}</label>
    <div class="col-lg-9">
        <input type="password" name="ROZETKAPAY_LOGIN" value="{$ROZETKAPAY_LOGIN}">
        <p class="help-block"></p>
    </div>
</div>
<div class="form-group required">
    <label class="control-label col-lg-3 text-right">{l s='Password(RozetkaPay)' mod='rozetkapay'}</label>
    <div class="col-lg-9">
        <input type="password" name="ROZETKAPAY_PASSWORD" value="{$ROZETKAPAY_PASSWORD}">
        <p class="help-block"></p>
    </div>
</div>

<div class="form-group required">

</div> 
<div class="form-group">
    <label class="control-label col-lg-3 text-right">{l s='Qr-code' mod='rozetkapay'}</label>
    <div class="col-lg-9">
        <span class="switch prestashop-switch fixed-width-lg">
            <input type="radio" name="ROZETKAPAY_QRCODE" id="qr_code_on" value="1"{if $ROZETKAPAY_QRCODE == 1 }checked="checked"{/if}>
            <label for="qr_code_on">{l s='Enabled' mod='rozetkapay'}</label>
            <input type="radio" name="ROZETKAPAY_QRCODE" id="qr_code_off" value="0"{if $ROZETKAPAY_QRCODE == 0 }checked="checked"{/if}>
            <label for="qr_code_off">{l s='Disabled' mod='rozetkapay'}</label>
            <a class="slide-button btn"></a>
        </span>
        <p class="help-block"></p>
        
    </div>
</div>

<div class="form-group">
    <label class="control-label col-lg-3">{l s='Sending additional data' mod='rozetkapay'}</label>

    <label class="control-label col-lg-2 text-right">{l s='Customer' mod='rozetkapay'}</label>
    <div class="col-lg-3">
        <span class="switch prestashop-switch fixed-width-lg">
            <input type="radio" name="ROZETKAPAY_SEND_DATA_CUSTOMER" id="CUSTOMER_on" value="1"{if $ROZETKAPAY_SEND_DATA_CUSTOMER == 1 }checked="checked"{/if}>
            <label for="CUSTOMER_on">{l s='Enabled' mod='rozetkapay'}</label>
            <input type="radio" name="ROZETKAPAY_SEND_DATA_CUSTOMER" id="CUSTOMER_off" value="0"{if $ROZETKAPAY_SEND_DATA_CUSTOMER == 0 }checked="checked"{/if}>
            <label for="CUSTOMER_off">{l s='Disabled' mod='rozetkapay'}</label>
            <a class="slide-button btn"></a>
        </span>
        <p class="help-block"></p>
    </div>
    <label class="control-label col-lg-2 text-right">{l s='Products' mod='rozetkapay'}</label>          
    <div class="col-lg-2">
        <span class="switch prestashop-switch fixed-width-lg">
            <input type="radio" name="ROZETKAPAY_SEND_DATA_PRODUCT" id="product_on" value="1"{if $ROZETKAPAY_SEND_DATA_CUSTOMER == 1 }checked="checked"{/if}>
            <label for="product_on">{l s='Enabled' mod='rozetkapay'}</label>
            <input type="radio" name="ROZETKAPAY_SEND_DATA_PRODUCT" id="product_off" value="0"{if $ROZETKAPAY_SEND_DATA_CUSTOMER == 0 }checked="checked"{/if}>
            <label for="product_off">{l s='Disabled' mod='rozetkapay'}</label>
            <a class="slide-button btn"></a>
        </span>
        <p class="help-block"></p>
    </div>

</div>
