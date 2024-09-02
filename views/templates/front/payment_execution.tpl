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