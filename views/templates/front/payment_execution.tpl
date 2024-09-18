{*
* 2007-2024 PrestaShop
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
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2024 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

{extends file='page.tpl'}

{block name="page_content"}
    <div class="row">

        <div class="col-sm-12">
            <a href="{$urlPay}" class="button_large">{l s='Proceed to payment' mod='rozetkapay'}</a>
        </div>
        
    </div>
    {if $isPay && $isPayQRcode }
    <div class="col-sm-12">
        <img rpay_qrcode src="{$urlGenQrCode}">
    </div>
    {/if}
{if !$isPay }
    <div class="row" style="color: red">
        {$message}

    </div>
{/if}

{/block}