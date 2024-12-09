<?php
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
if (!defined('_PS_VERSION_')) {
    exit;
}
class RozetkapayApiSdkCustomerClass
{
    /**
     * @var string
     */
    public $color_mode = 'light';

    /**
     * @var string
     */
    public $locale = '';

    /**
     * @var string
     */
    public $account_number = '';

    /**
     * @var string
     */
    public $ip_address = '';
    /**
     * @var string
     */
    public $address = '';

    /**
     * @var string
     */
    public $city = '';

    /**
     * @var string
     */
    public $country = '';

    /**
     * @var string
     */
    public $email = '';

    /**
     * @var string
     */
    public $external_id = '';

    /**
     * @var string
     */
    public $first_name = '';

    /**
     * @var string
     */
    public $last_name = '';

    /**
     * @var string
     */
    public $patronym = '';

    /**
     * @var
     */
    public $payment_method;

    /**
     * @var string
     */
    public $phone = '';

    /**
     * @var string
     */
    public $postal_code = '';
}
