<?php
class Iweb_Loginfix_Model_Customer_Session extends Mage_Customer_Model_Session
{
    public function login($username, $password)
    {
        /** @var $customer Mage_Customer_Model_Customer */
        $customer = Mage::getModel('customer/customer')
            ->setWebsiteId(Mage::app()->getStore()->getWebsiteId());

        if ($customer->authenticate($username, $password)) {
            $this->setCustomerAsLoggedIn($customer);
            // This breaks certain setups
//            $this->renewSession();
            return true;
        }
        return false;
    }

    public function setCustomerAsLoggedIn($customer)
    {
        $this->setCustomer($customer);
        // This breaks certain setups
//        $this->renewSession();
        Mage::getSingleton('core/session')->renewFormKey();
        Mage::dispatchEvent('customer_login', array('customer'=>$customer));
        return $this;
    }
}