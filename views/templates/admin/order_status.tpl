{*
 * RozetkaPay
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
    <label class="control-label col-lg-3 text-right">{l s='Success' mod='rozetkapay'}</label>
    <div class="col-lg-3">
        <select name="ROZETKAPAY_ORDER_STATUS_SUCCESS" class="fixed-width-xl" id="ROZETKAPAY_ORDER_STATUS_SUCCESS">
            {foreach from=$order_statuses item=order_status}
                <option value="{$order_status.id_order_state}"
                        {if $ROZETKAPAY_ORDER_STATUS_SUCCESS == $order_status.id_order_state } selected="selected"{/if}
                        >{$order_status.name}</option>
            {/foreach}
        </select>
        <p class="help-block"></p>
    </div>
    <label class="control-label col-lg-3 text-right">{l s='Failure' mod='rozetkapay'}</label>
    <div class="col-lg-3">
        <select name="ROZETKAPAY_ORDER_STATUS_FAILURE" class="fixed-width-xl" id="ROZETKAPAY_ORDER_STATUS_FAILURE">
            {foreach from=$order_statuses item=order_status}
                <option value="{$order_status.id_order_state}"
                        {if $ROZETKAPAY_ORDER_STATUS_FAILURE == $order_status.id_order_state } selected="selected"{/if}
                        >{$order_status.name}</option>
            {/foreach}
        </select>
        <p class="help-block"></p>
    </div>
</div>