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
class RozetkapayValidationModuleFrontController extends ModuleFrontController
{
    public function __construct()
    {
        parent::__construct();
        $this->postProcess();
    }

    /**
     * @throws PrestaShopException
     */
    public function postProcess()
    {
        $this->module->logAdd('Callback post started', 'RozetkaPayValidation');
        if ($this->module->active == false) {
            exit;
        }
        $id_order = (int) Tools::getValue('order_id');
        if ($id_order) {
            $this->module->logAdd('Order id got ' . $id_order, 'RozetkaPayValidation');
            $api_login = Configuration::get('ROZETKAPAY_API_LOGIN');
            $api_password = Configuration::get('ROZETKAPAY_API_PASSWORD');
            $rozetka_pay = new RozetkapayApiSdkClass();
            if (Configuration::get('ROZETKAPAY_API_TEST_MODE')) {
                $rozetka_pay->setBasicAuthTest();
            } else {
                $rozetka_pay->setBasicAuth($api_login, $api_password);
            }

            $result = $rozetka_pay->callbacks();
            $this->module->logAdd(json_encode($result), 'RozetkaPayValidation');
            if (isset($result->details->status)) {
                $status = $result->details->status;
                $order = new Order($id_order);
                Context::getContext()->currency = new Currency($order->id_currency);
                if ($status === 'success') {
                    if ((bool) $order->getHistory($order->id_lang, _PS_OS_PAYMENT_) === false) {
                        $order->setCurrentState(_PS_OS_PAYMENT_);
                        $this->module->logAdd('Set status  _PS_OS_PAYMENT_ ' . $id_order, 'RozetkaPayValidation');
                    }
                } elseif ($status === 'failure') {
                    $order->setCurrentState(_PS_OS_ERROR_);
                    $this->module->logAdd('Set status  _PS_OS_ERROR_ ' . $id_order, 'RozetkaPayValidation');
                }
            }
        }

        exit;
    }
}
