/**
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
 */
window.addEventListener("load", function (event) {
    if ($('#ROZETKAPAY_API_TEST_MODE_on').is(':checked')) {
        $('.form-group.rozetka-credentials').hide();
    } else {
        $('.form-group.rozetka-credentials').show();
    }
    $('input[name=ROZETKAPAY_API_TEST_MODE]').on('change',function(){
        if ($('#ROZETKAPAY_API_TEST_MODE_on').is(':checked')) {
            $('.form-group.rozetka-credentials').hide();
        } else {
            $('.form-group.rozetka-credentials').show();
        }
    });
});