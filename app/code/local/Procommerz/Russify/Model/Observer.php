<?php
/**
 * Russify Observer Model
 *
 * @package    Procommerz_Russify
 * @author     Denis Kolesnichenko <denis@mobiquest.ru>
 */
class Procommerz_Russify_Model_Observer
{
    public function orderPageRussify(Varien_Event_Observer $observer)
    {
        if (Mage::getStoreConfig('procommerz/general/russify_enable')) {
            $block = $observer->getEvent()->getData( 'block' );
            if($block->getId() == 'order_info' && $block->getRequest()->getControllerName() == 'sales_order') {
                $transport = $observer->getTransport();
                $html = $transport->getHtml();
                $block = Mage::app()->getLayout()->createBlock('procommerz_russify/russify');
                $childBlock = Mage::app()->getLayout()->createBlock('procommerz_russify/status');
                $block->setChild('status',$childBlock);
                $postHtml = $block->toHtml();
                $pattern = '/(<input type="hidden" name="order_id" value="[^"]*"\\/>)/';
                $replacement = $postHtml. '${1}';
                $html = preg_replace($pattern, $replacement, $html);
                $transport->setHtml($html);
            }
        }
    }
}

