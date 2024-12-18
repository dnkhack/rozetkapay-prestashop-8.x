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
{extends file="page.tpl"}

{block name="content"}
    <div class="alert alert-error">
        <h3>{l s='Something went wrong.' mod='rozetkapay'}</h3>
        <ul class="alert alert-danger ">
            {if isset($error)}
                <li>{$error|escape:'htmlall':'UTF-8'}.</li>
            {/if}
        </ul>
    </div>
    {if isset($error_link)}
        <div>
            <p style="text-align: center">
                <a class="btn btn-primary" href="{$error_link|escape:'htmlall':'UTF-8'}">{l s='Try to make purchase again or change the payment method!'  mod='rozetkapay'}</a>
            </p>
        </div>
    {/if}
    <div>
        <p style="text-align: center">
            <a class="btn btn-success" href="{$contact_link|escape:'htmlall':'UTF-8'}">{l s='CONTACT US'  mod='rozetkapay'}</a>
        </p>
    </div>
{/block}
