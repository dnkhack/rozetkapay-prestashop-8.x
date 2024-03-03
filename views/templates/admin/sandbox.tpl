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
<div class="tab-pane" id="sandbox">
    <div class="panel">

        <div class="row"> 
            
            <div class="form-group">
                <label class="control-label col-lg-2 text-right">
                    {l s='Sandbox' mod='rozetkapay'}
                </label>
                <div class="col-lg-9">                            
                    <span class="switch prestashop-switch fixed-width-lg">
                        <input type="radio" name="ROZETKAPAY_LIVE_MODE" id="ROZETKAPAY_LIVE_MODE_on" 
                               {if $ROZETKAPAY_LIVE_MODE == 1 }checked="checked"{/if} value="1">
                        <label for="ROZETKAPAY_LIVE_MODE_on">{l s='Enabled' mod='rozetkapay'}</label>
                        <input type="radio" name="ROZETKAPAY_LIVE_MODE" id="ROZETKAPAY_LIVE_MODE_off" 
                               {if $ROZETKAPAY_LIVE_MODE == 0 }checked="checked"{/if}value="0">
                        <label for="ROZETKAPAY_LIVE_MODE_off">{l s='Disabled' mod='rozetkapay'}</label>                                
                        <a class="slide-button btn"></a>
                    </span>
                </div>
            </div>


            <div class="form-group">

                <label class="control-label col-lg-2 text-right">
                    {l s='Log' mod='rozetkapay'}
                </label>
                <div class="col-lg-2">                            
                    <span class="switch prestashop-switch fixed-width-lg">
                        <input type="radio" name="ROZETKAPAY_LOG" id="ROZETKAPAY_LOG_on" 
                               {if $ROZETKAPAY_LOG == "1" }checked="checked"{/if} value="1">
                        <label for="ROZETKAPAY_LOG_on">{l s='Enabled' mod='rozetkapay'}</label>
                        <input type="radio" name="ROZETKAPAY_LOG" id="ROZETKAPAY_LOG_off" 
                               {if $ROZETKAPAY_LOG == "0" }checked="checked"{/if} value="0">
                        <label for="ROZETKAPAY_LOG_off">{l s='Disabled' mod='rozetkapay'}</label>
                        <a class="slide-button btn"></a>
                    </span>
                </div>
                        
                <div class="col-lg-2">                    
                        <a href="{$urlLogDownload}" target="_blank" data-toggle="tooltip" class="btn btn-primary">
                            <i class="fa fa-download"></i>{$button_log_download}
                        </a>
                        <a href="{$urlLogClear}" data-toggle="tooltip" class="btn btn-danger">
                            <i class="fa fa-eraser"></i>{$button_log_clear}
                        </a>
                </div>
                        
            </div>


            <div class="form-group">
                <label class="col-sm-2 control-label" for="input-status">{l s='Testing cards' mod='rozetkapay'}</label>
                <div class="col-sm-10">
                    <div class="well well-sm">
                        card=4242424242424242  exp=any cvv=any  3ds=Yes result=success<br>
                        card=5454545454545454  exp=any cvv=any  3ds=Yes result=success<br>
                        card=4111111111111111  exp=any cvv=any  3ds=No result=success<br>
                        card=4200000000000000  exp=any cvv=any  3ds=Yes result=rejected<br>
                        card=5105105105105100  exp=any cvv=any  3ds=Yes result=rejected<br>
                        card=4444333322221111  exp=any cvv=any  3ds=No result=rejected<br>
                        card=5100000020002000  exp=any cvv=any  3ds=No result=rejected<br>
                        card=4000000000000044  exp=any cvv=any  3ds=No result=insufficient-funds<br>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</div>