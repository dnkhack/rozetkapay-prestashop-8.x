<?php
/**
 * 2007-2015 PrestaShop
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
 * @author PrestaShop SA <contact@prestashop.com>
 * @copyright 2007-2015 PrestaShop SA
 * @license http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 *           International Registered Trademark & Property of PrestaShop SA
 */
if (!defined('_PS_VERSION_')) {
    exit;
}

class RozetkaPayRozetkaPayModuleFrontController extends ModuleFrontController
{
    public $version = '3.0.2';

    public $ssl = true;

    public $display_column_left = false;

    private $rpay;

    private $langCode;

    private $extlog = false;

    public function __construct()
    {
        parent::__construct();

        $this->bootstrap = true;
        $this->rpay = new \RozetkaPaySDK();
        $this->langCode = Context::getContext()->language->iso_code;

        if ($this->langCode == "ru") {
            $this->langCode = "uk";
        }

        if (Configuration::get('ROZETKAPAY_LOG') === "1") {

            //$this->extlog = new \Log('rozetkapay');

        }
    }

    public function initContent()
    {
        parent::initContent();
        $action = Tools::getValue('action');

        if ($action === "creatPay") {
            $this->creatPay();
            return;
        }

        if ($action === "result") {
            $this->result();
            return;
        }

        if ($action === "callback") {
            $this->callback();
            return;
        }

        if ($action === "genQrCode") {
            $this->genQrCode();
            return;
        }

        $cart = $this->context->cart;
        $this->context->smarty->assign([
            'nbProducts' => $cart->nbProducts(),
            'cust_currency' => $cart->id_currency,
            'currencies' => $this->module->getCurrency((int) $cart->id_currency),
            'total' => $cart->getOrderTotal(true, Cart::BOTH),
            'this_path' => $this->module->getPathUri(),
            'this_path_bw' => $this->module->getPathUri(),
            'this_path_ssl' => Tools::getShopDomainSsl(true, true) . __PS_BASE_URI__ . 'modules/' . $this->module->name . '/',
            'urlCreatPay' => Context::getContext()->link->getModuleLink(
                'rozetkapay',
                'rozetkapay',
                ['action' => 'creatPay', 'id_cart' => $cart->id,]
            ),
            'pageType' => 'comfire'
        ]);
        $this->context->smarty->assign('showIcon', Configuration::get('ROZETKAPAY_VIEW_ICON') == "1");
        $this->context->smarty->assign('text_title', $this->getTitle());
        $this->context->smarty->force_compile = true;
        $this->setTemplate('payment_execution.tpl');
    }

    public function creatPay()
    {

        $idCart = Tools::getValue('id_cart');

        if (!empty($idCart)) {
            $this->module->validateOrder((int)$this->context->cart->id, _PS_OS_PREPARATION_, $this->context->cart->getOrderTotal(), 'RozetkaPay');
            Tools::redirect(Context::getContext()->link->getModuleLink(
                'rozetkapay',
                'rozetkapay',
                ['action' => 'creatPay', 'id_order' => $this->module->currentOrder]
            ));
            return;
        }
        $idOrder = Tools::getValue('id_order');

        if(empty($idOrder)) {
            return 'fatal';
        }
        $orderInfo = new OrderCore($idOrder);

        if (Configuration::get('ROZETKAPAY_LIVE_MODE') === "1") {
            $this->rpay->setBasicAuthTest();
            $orderInfo->id = $orderInfo->id . "_" . sha1($_SERVER['HTTP_HOST']);
        } else {
            $this->rpay->setBasicAuth(Configuration::get('ROZETKAPAY_LOGIN'), Configuration::get('ROZETKAPAY_PASSWORD'));
        }
        $this->rpay->setResultURL(Context::getContext()->link->getModuleLink(
            'rozetkapay',
            'rozetkapay',
            ['action' => 'result', 'id_order' => $idOrder]
        ));
        $this->rpay->setCallbackURL(Context::getContext()->link->getModuleLink(
            'rozetkapay',
            'rozetkapay',
            ['action' => 'callback', 'id_order' => $idOrder]
        ));

        $total = $orderInfo->total_paid;

        $currency = new CurrencyCore($orderInfo->id_currency);
        $currencyCode = $currency->iso_code;

        $dataCheckout = new \RPayCheckoutCreatRequest();

        if ($currencyCode != "UAH") {
            $total = Tools::convertPrice($total, $currencyCode, "UAH");
            $currencyCode = "UAH";
        }
        $dataCheckout->amount = $total;
        $dataCheckout->external_id = $orderInfo->id;
        $dataCheckout->currency = $currencyCode;
        $language = Language::getIsoById((int)$orderInfo->id_lang);
        $language = (!in_array($language, ['uk', 'en'])) ? 'uk' : $language;
        $language = strtoupper($language);

        if (Configuration::get('ROZETKAPAY_SEND_DATA_CUSTOMER') == "1") {
            $address = new AddressCore($orderInfo->id_address_invoice);

            if ($address) {
                $customerNew = new \RPayCustomer();
                $customer = new CustomerCore($address->id_customer);
                $countrys = Country::getCountries($orderInfo->id_lang);

                if (isset($countrys[$address->id_country])) {
                    $customerNew->country = $countrys[$address->id_country]['iso_code'];
                }
                $customerNew->first_name = $address->firstname;
                $customerNew->last_name = $address->lastname;
                $customerNew->phone = $address->phone_mobile;
                $customerNew->email = $customer->email;
                $customerNew->city = $address->city;
                $customerNew->postal_code = $address->postcode;
                $customerNew->address = $address->address1 . ' ' . $address->address2;
                $dataCheckout->customer = $customer;
            }
        }

        if (Configuration::get('ROZETKAPAY_SEND_DATA_PRODUCT') == "1") {

            foreach ($orderInfo->getProducts() as $product) {
                $productNew = new \RPayProduct();
                $productPrices[] = $product['total_wt'];
                $productQty[] = $product['quantity'];
                $productNew->id = $product['product_id'];
                $productNew->name = $product['product_name'];
                $productNew->quantity = $product['product_quantity'];
                $productNew->net_amount = $product['product_price_wt'];
                $productNew->vat_amount = $product['total_wt'];
                $productNew->url = Context::getContext()->link->getProductLink($product);
                $dataCheckout->products[] = $productNew;
            }
        }

        list($result, $error) = $this->rpay->checkoutCreat($dataCheckout);
        
        $isPay = false;
        $message = "";
        $urlPay = '';
        $payQRcode = "";

        if ($error === false && isset($result->is_success)) {
            if (isset($result->action) && $result->action->type == "url") {
                $urlPay = $result->action->value;
                $isPay = true;
            }
        } else {
            $message = $error->message;
        }
        $isPayQRcode = false;

        if ($isPay) {
            if (Configuration::get('ROZETKAPAY_QRCODE') === "1") {
                $isPayQRcode = true;                
            } else {
                Tools::redirect($urlPay);
            }
        } else {
            return $this->displayError($message);
        }

        if (isset($result->data)) {
            $message = $result->data['message'];
        } elseif (isset($result->message)) {
            $message = $result->message;
        }

        if($isPayQRcode) {
            
            $idCheckout = $this->searchAll("checkout/", "/form", $urlPay);
            
            if(!empty($idCheckout)){
                $this->context->smarty->assign('urlGenQrCode', Context::getContext()->link->getModuleLink(
                    'rozetkapay',
                    'rozetkapay',
                    ['action' => 'genQrCode', 'text' => $idCheckout]
                ));
            }else{
                $isPayQRcode = false;
            }

        }

        $this->context->smarty->assign('isPay', $isPay);
        $this->context->smarty->assign('isPayQRcode', $isPayQRcode);
        $this->context->smarty->assign('urlPay', $urlPay);
        $this->context->smarty->assign('payQRcode', $payQRcode);
        $this->context->smarty->assign('message', $message);
        $this->context->smarty->assign('urlCancel', Context::getContext()->link->getPageLink('order', true, null, "step=3"));
        $this->context->smarty->assign('text_title', $this->module->getTitle());
        $this->context->smarty->force_compile = true;
        $this->setTemplate('module:rozetkapay/views/templates/front/payment_execution.tpl');
    }

    public function callback()
    {

        $this->log('fun: callback');
        $this->log(Tools::file_get_contents('php://input'));
        
        $result = $this->rpay->сallbacks();

        if (!isset($result->external_id)) {
            $this->log('Failure error return data:');
            return;
        }

        $this->log('    result:');
        $this->log($result);

        if (Configuration::get('ROZETKAPAY_LIVE_MODE') === "1") {
            $ids = explode("_", $result->external_id);
            $id_order = $ids[0];
        } else {
            $id_order = $result->external_id;
        }

        $status = $result->details->status;
        $this->log('    id_order: ' . $id_order);
        $this->log('    status: ' . $status);
        $orderStatus_id = $this->getRozetkaPayStatusToOrderStatus($status);
        $this->log('    orderStatus_id: ' . $orderStatus_id);
        $status_holding = isset($this->request->get['holding']);
        $this->log('    hasHolding: ' . $status_holding);
        $refund = isset($this->request->get['refund']);
        $this->log('    hasRefund: ' . $refund);
        if($orderStatus_id != "0") {
            $history = new OrderHistory();
            $history->id_order = $id_order;
            $history->changeIdOrderState((int)$orderStatus_id, $id_order);
        }
        $history->addWithemail(true);
        exit();
    }

    public function result()
    {

        $id_order = Tools::getValue('id_order');

        if (Configuration::get('ROZETKAPAY_LIVE_MODE') === "1") {
            $ids = explode("_", $id_order);
            $id_order = $ids[0];
        }

        $this->log('fun: result');
        $this->log('    id_order: ' . $id_order);
        $order = new OrderCore((int)$id_order);
        $status = $this->getOrderStatus($id_order);
        $complete = true;

        if($status == (int)Configuration::get('ROZETKAPAY_ORDER_STATUS_SUCCESS')) {
            $complete = true;
        }

        if($complete) {
            $customer = new CustomerCore($order->id_customer);
            Tools::redirect('index.php?controller=order-confirmation&id_cart='.(int)$order->id.
                    '&id_module='.(int)$this->module->id.'&id_order='.$order->id.'&key='.$customer->secure_key);
        } else {
            Tools::redirect('index.php?controller=order&step=1');
        }
    }

    public function log($var)
    {
        if ($this->extlog !== false) {
            $this->extlog->write($var);
        }
    }

    public function getRozetkaPayStatusToOrderStatus($status)
    {
        switch ($status) {
            case "init":
                return Configuration::get('ROZETKAPAY_ORDER_STATUS_INIT');
            case "pending":
                return Configuration::get('ROZETKAPAY_ORDER_STATUS_PENDING');
            case "success":
                return Configuration::get('ROZETKAPAY_ORDER_STATUS_SUCCESS');
            case "failure":
                return Configuration::get('ROZETKAPAY_ORDER_STATUS_FAILURE');
            default:
                return "0";
        }
    }

    public function getOrderStatus($id_order)
    {
        $order = new Order($id_order);

        if (Validate::isLoadedObject($order)) {
            return $order->getCurrentState();
        } else {
            return false;
        }
    }

    public function genQrCode()
    {
        header ('Content-Type: image/png');
        if(Tools::getIsset('text')) {            
            $text = (string)Tools::getValue('text');
            $url = 'https://checkout.rozetkapay.com/api/v1/checkout/'.$text.'/form';
            QRcode::png($url, null, QR_ECLEVEL_L, 10, 2);
        }
        exit();
    }

    protected function displayError($message, $description = false)
    {
        $this->context->smarty->assign('path', '
			<a href="' . $this->context->link->getPageLink('order', null, null, 'step=3') . '">' . $this->module->l('Payment') . '</a>
			<span class="navigation-pipe">&gt;</span>' . $this->module->l('Error'));
        array_push($this->errors, $this->module->l($message), $description);
        return $this->setTemplate('module:rozetkapay/views/templates/front/error.tpl');
    }
    
    private function searchAll($start, $stop, $content) {
        $reg = '#' . preg_quote($start, '#') . '(.*?)' . preg_quote($stop, '#') . '#su';
        preg_match_all($reg, $content, $values);
        if(count($values[1])>0){
            return $values[1][0];
        }
        return '';
    }

}
