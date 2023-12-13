{extends file='page.tpl'}

{block name="page_content"}
    <div class="row">

        <div class="col-sm-6">
            <a href="{$urlPay}" class="button_large">{$text_catalog_button_pay}</a>
        </div>
    </div>
    <div class="col-sm-6">
        {if $isPay && $isPayQRcode }
            <div id="rozetkapay_pay">
                <img rpay_qrcode src="" style="display: none" height="150">
                <svg class="" rpay version="1.1" id="L9" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                    viewBox="0 0 100 100" enable-background="new 0 0 0 0" xml:space="preserve">
                      <rect x="20" y="50" width="4" height="10" fill="#000">
                        <animateTransform attributeType="xml"
                          attributeName="transform" type="translate"
                          values="0 0; 0 20; 0 0"
                          begin="0" dur="0.6s" repeatCount="indefinite" />
                      </rect>
                      <rect x="30" y="50" width="4" height="10" fill="#000">
                        <animateTransform attributeType="xml"
                          attributeName="transform" type="translate"
                          values="0 0; 0 20; 0 0"
                          begin="0.2s" dur="0.6s" repeatCount="indefinite" />
                      </rect>
                      <rect x="40" y="50" width="4" height="10" fill="#000">
                        <animateTransform attributeType="xml"
                          attributeName="transform" type="translate"
                          values="0 0; 0 20; 0 0"
                          begin="0.4s" dur="0.6s" repeatCount="indefinite" />
                      </rect>
                  </svg>
                </div>
            <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
            <script>
                $(document).ready(function(){
                    $.ajax({
                        method:'POST',
                        url: '{$urlGenQrCode}',
                        data:{ 'text':'{$urlPay}'}
                    }).done(function (image) {
                        $('[rpay_qrcode]').attr('src',image).show()
                        $('svg[rpay]').hide()
                    })
                })
                
            </script>
        {/if}
    </div>

{if !$isPay }
    <div class="row" style="color: red">
        {$message}

    </div>
{/if}

{/block}