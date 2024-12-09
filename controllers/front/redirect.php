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
/**
 * @property Rozetkapay $module
 */
class RozetkapayRedirectModuleFrontController extends ModuleFrontController
{
    /**
     * @throws PrestaShopException
     */
    public function getCartDescription($id_cart)
    {
        $cart = new Cart($id_cart);
        $products = $cart->getProducts();
        $cart_products = [];
        foreach ($products as $product) {
            $pay_product = new RozetkapayApiSdkProductClass();
            $pay_product->id = (string) $product['id_product'];
            $pay_product->name = $product['name'] . ' _ ' . ($product['attributes_small'] ?? '');
            $pay_product->description = $product['description_short'];
            $pay_product->category = $product['category'];
            $pay_product->image = $product['id_image'];
            $pay_product->quantity = (string) $product['quantity'];
            $pay_product->net_amount = (string) $product['price_with_reduction_without_tax'];
            $pay_product->vat_amount = (string) $product['price'];
            $pay_product->currency = $this->context->currency->iso_code;
            $pay_product->url = $this->context->link->getProductLink(new Product($product['id_product']));
            $cart_products[] = $pay_product;
        }

        return $cart_products;
    }

    /**
     * Do whatever you have to before redirecting the customer on the website of your payment processor.
     *
     * @throws PrestaShopException
     * @throws Exception
     */
    public function postProcess()
    {
        $cart = $this->context->cart;
        $customer = new Customer($cart->id_customer);
        if (!Validate::isLoadedObject($customer)) {
            Tools::redirectAdmin($this->context->link->getPageLink('order', null, null, 'step=1'));
        }

        if (
            !$this->context->cart->isVirtualCart()
            && (!$this->context->cart->id_address_delivery || !$this->context->cart->delivery_option)
        ) {
            Tools::redirectAdmin($this->context->link->getPageLink('order', null, null, 'step=3'));
        }

        $api_login = Configuration::get('ROZETKAPAY_API_LOGIN');
        $api_password = Configuration::get('ROZETKAPAY_API_PASSWORD');
        if (Configuration::get('ROZETKAPAY_API_TEST_MODE') && (($api_login == '') || ($api_password == ''))) {
            $this->displayError($this->l('Empty LOGIN and PASSWORD keys'));
        }

        $result = $this->module->validateOrder(
            $cart->id,
            Configuration::get('PS_OS_ROZETKAPAY_WAITING'),
            $cart->getOrderTotal(),
            $this->module->displayName,
            null,
            [
                'transaction_id' => '',
                'card_number' => '',
                'card_brand' => 'RozetkaPay',
                'card_expiration' => '',
                'card_holder' => '',
            ],
            $cart->id_currency,
            false,
            $customer->secure_key
        );

        if ($result) {
            $order = new Order(Order::getIdByCartId($cart->id));
            $callback_url = $this->context->link->getModuleLink($this->module->name, 'validation', [
                'order_id' => $order->id,
            ], true);
            $result_url = $this->context->link->getModuleLink($this->module->name, 'confirmation', [
                'order_id' => $order->id,
            ], true);

            $rozetka_pay = new RozetkapayApiSdkClass();
            if (Configuration::get('ROZETKAPAY_API_TEST_MODE')) {
                $rozetka_pay->setBasicAuthTest();
            } else {
                $rozetka_pay->setBasicAuth($api_login, $api_password);
            }

            $rozetka_pay->setCallbackUrl($callback_url);
            $rozetka_pay->setResultUrl($result_url);

            $dataRequest = new RozetkapayApiSdkCheckoutCreatRequestClass();
            $dataRequest->currency = $this->context->currency->iso_code;
            $dataRequest->amount = $this->context->cart->getOrderTotal();
            $dataRequest->external_id = $order->id . '_' . mt_rand(1, 1000);
            $language = $this->context->language->iso_code;
            $language = (!in_array($language, ['es', 'pl', 'fr', 'sk', 'de', 'uk', 'en'])) ?: 'uk';
            $language = strtoupper($language);

            $address = new Address($order->id_address_invoice);
            $pay_customer = new RozetkapayApiSdkCustomerClass();
            $pay_customer->first_name = $address->firstname;
            $pay_customer->last_name = $address->lastname;
            $pay_customer->patronym = $address->lastname;
            $pay_customer->external_id = (string) $customer->id;
            $pay_customer->account_number = (string) $customer->id;
            $pay_customer->email = $customer->email;
            $pay_customer->phone = (string) $address->phone;
            $pay_customer->city = $address->city;
            $pay_customer->country = $address->country;
            $pay_customer->address = mb_substr($address->address1 . ' ' . $address->address2, 0, 50);
            $pay_customer->postal_code = (string) $address->postcode;
            $pay_customer->ip_address = '127.0.0.1';
            $pay_customer->locale = $language;
            $dataRequest->customer = $pay_customer;
            $dataRequest->products = $this->getCartDescription($cart->id);
            [$result, $errors] = $rozetka_pay->checkoutCreat($dataRequest);
            if ($errors === false && isset($result->is_success)) {
                if (isset($result->action) && $result->action->type == 'url') {
                    Tools::redirect($result->action->value);
                }
            } else {
                foreach ($errors as $key => $error) {
                    $this->errors[] = $key . ' - ' . $error;
                    $this->module->logAdd($key . ' - ' . $error, 'RozetkaPayValidation');
                }
            }
            $this->module->logAdd('Can not to create an payment', 'RozetkaPayValidation');
            $this->errors[] = $this->l('Can not to create an payment');
        }
        $this->module->logAdd('Can not creat an order', 'RozetkaPayValidation');
        $this->errors[] = $this->l('An error occurred. Please contact with our managers to get support');

        return $this->displayError($this->l('Can not creat an order'));
    }

    protected function displayError($msg = '')
    {
        $this->context->smarty->assign([
            'error' => $msg,
            'error_link' => $this->context->link->getPageLink('order', null, null, 'step=3'),
            'contact_link' => $this->context->link->getPageLink('contact', null, null, null),
        ]);

        return $this->setTemplate('module:rozetkapay/views/templates/front/module_error.tpl');
    }
}
