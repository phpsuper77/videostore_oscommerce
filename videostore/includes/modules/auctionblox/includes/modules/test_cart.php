<?php

class abxModule_test_cart {
	
    function abxModule_test_cart()
    {      
        
        $productId       = 16;
        $qty             = 1;
   
        try {
            $cart = $this->_getCart();
            $product = Mage::getModel('catalog/product')
                		   ->setStoreId(Mage::app()->getStore()->getId())
                		   ->load($productId)
					  ;
            $eventArgs = array(
                'product' => $product,
                'qty' => $qty,
                'additional_ids' => $additionalIds,
                'request' => $this->getRequest(),
                'response' => $this->getResponse(),
            );

            Mage::dispatchEvent('checkout_cart_before_add', $eventArgs);

            $cart->addProduct($product, $qty)
                 ->addProductsByIds($additionalIds);

            Mage::dispatchEvent('checkout_cart_after_add', $eventArgs);

            $cart->save();

            Mage::dispatchEvent('checkout_cart_add_product', array('product'=>$product));

            $message = $this->__('%s was successfully added to your shopping cart.', $product->getName());

            if (!Mage::getSingleton('checkout/session')->getNoCartRedirect(true)) {
                Mage::getSingleton('checkout/session')->addSuccess($message);
                $this->_goBack();
            }
        }
        catch (Mage_Core_Exception $e) {
            if (Mage::getSingleton('checkout/session')->getUseNotice(true)) {
                Mage::getSingleton('checkout/session')->addNotice($e->getMessage());
            }
            else {
                Mage::getSingleton('checkout/session')->addError($e->getMessage());
            }

            $url = Mage::getSingleton('checkout/session')->getRedirectUrl(true);
            if ($url) {
                $this->getResponse()->setRedirect($url);
            }
            else {
                $this->_redirectReferer(Mage::helper('checkout/cart')->getCartUrl());
            }
        }
        catch (Exception $e) {
            Mage::getSingleton('checkout/session')->addException($e, $this->__('Can not add item to shopping cart'));
            $this->_goBack();
        }
    	
    	   
    }
    
     protected function _getCart()
    {
        return Mage::getSingleton('checkout/cart');
    }
}
?>