{*
 *  RozetkaPay
 *
 *  @author RozetkaPay <business@rozetkapay.com>
 *  @copyright RozetkaPay
 *  @license http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 *}
<div class="form-group">
    <label class="control-label col-lg-3 text-right">
        {l s='Name(default)' mod='rozetkapay'}
    </label>
    <div class="col-lg-9">                            
        <span class="switch prestashop-switch fixed-width-lg">
            <input type="radio" name="ROZETKAPAY_VIEW_NAME_DEFAULT" id="view_title_default_on" 
                   {if $ROZETKAPAY_VIEW_NAME_DEFAULT == 1 }checked="checked"{/if} value="1">
            <label for="view_title_default_on">{l s='Enabled' mod='rozetkapay'}</label>
            <input type="radio" name="ROZETKAPAY_VIEW_NAME_DEFAULT" id="view_title_default_off" 
                   {if $ROZETKAPAY_VIEW_NAME_DEFAULT == 0 }checked="checked"{/if}value="0">
            <label for="view_title_default_off">{l s='Disabled' mod='rozetkapay'}</label>                                
            <a class="slide-button btn"></a>
        </span>
    </div>
</div>

<div class="form-group">
    <label class="col-sm-2 control-label text-right" >{l s='Proper name' mod='rozetkapay'}</label>
    <div class="col-sm-10">
        {foreach $languages as $language}
            <div class="input-group">
                <span class="input-group-addon">                    
                    {$language.name|escape:'htmlall':'UTF-8'}
                </span>
                <input type="text" name="ROZETKAPAY_VIEW_NAME[{$language.iso_code}]"
                       value="{if isset($ROZETKAPAY_VIEW_NAME[$language.iso_code])}{$ROZETKAPAY_VIEW_NAME[$language.iso_code]}{/if}"
                       minlength="5" maxlength="80" class="form-control" />
            </div>
        {/foreach}                            
    </div>
</div>


<div class="form-group">
    <label class="control-label col-lg-3 text-right">
        {l s='Show logo' mod='rozetkapay'}
        <img src="../modules/rozetkapay/img/logo.png" height="32">
    </label>
    <div class="col-lg-9">                            
        <span class="switch prestashop-switch fixed-width-lg">
            <input type="radio" name="ROZETKAPAY_VIEW_ICON" id="view_icon_status_on" 
                   {if $ROZETKAPAY_VIEW_ICON == 1 }checked="checked"{/if} value="1">
            <label for="view_icon_status_on">{l s='Enabled' mod='rozetkapay'}</label>
            <input type="radio" name="ROZETKAPAY_VIEW_ICON" id="view_icon_status_off" 
                   {if $ROZETKAPAY_VIEW_ICON == 0 }checked="checked"{/if}value="0">
            <label for="view_icon_status_off">{l s='Disabled' mod='rozetkapay'}</label>                                
            <a class="slide-button btn"></a>
        </span>
    </div>
</div>