<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Icube\PromoShipping\Model\Quote;


/**
 * Fields:
 * - orig:
 *   - country_id: UK
 *   - region_id: 1
 *   - postcode: 90034
 * - dest:
 *   - country_id: UK
 *   - region_id: 2
 *   - postcode: 01005
 * - package:
 *   - value: $100
 *   - weight: 1.5 lb
 *   - height: 10"
 *   - width: 10"
 *   - depth: 10"
 * - order:
 *   - total_qty: 10
 *   - subtotal: $100
 * - option
 *   - insurance: true
 *   - handling: $1
 * - table (shiptable)
 *   - condition_name: package_weight
 * - limit
 *   - carrier: carrier code
 *   - method: carrier method
 * - shipping carrier
 *   - specific carrier fields
 *
 * @method int getStoreId()
 * @method \Magento\Quote\Model\Quote\Address\RateRequest setStoreId(int $value)
 * @method int getWebsiteId()
 * @method \Magento\Quote\Model\Quote\Address\RateRequest setWebsiteId(int $value)
 * @method string getBaseCurrency()
 * @method \Magento\Quote\Model\Quote\Address\RateRequest setBaseCurrency(string $value)
 *
 * @method \Magento\Quote\Model\Quote\Address\RateRequest setAllItems(array $items)
 * @method array getAllItems()
 *
 * @method \Magento\Quote\Model\Quote\Address\RateRequest setOrigCountryId(string $value)
 * @method string getOrigCountryId()
 * @method \Magento\Quote\Model\Quote\Address\RateRequest setOrigRegionId(int $value)
 * @method int getOrigRegionId()
 * @method \Magento\Quote\Model\Quote\Address\RateRequest setOrigPostcode(string $value)
 * @method string getOrigPostcode()
 * @method \Magento\Quote\Model\Quote\Address\RateRequest setOrigCity(string $value)
 * @method string getOrigCity()
 *
 * @method \Magento\Quote\Model\Quote\Address\RateRequest setDestCountryId(string $value)
 * @method string getDestCountryId()
 * @method \Magento\Quote\Model\Quote\Address\RateRequest setDestRegionId(int $value)
 * @method int getDestRegionId()
 * @method \Magento\Quote\Model\Quote\Address\RateRequest setDestRegionCode(string $value)
 * @method string getDestRegionCode()
 * @method \Magento\Quote\Model\Quote\Address\RateRequest setDestPostcode(string $value)
 * @method string getDestPostcode()
 * @method \Magento\Quote\Model\Quote\Address\RateRequest setDestCity(string $value)
 * @method string getDestCity()
 * @method \Magento\Quote\Model\Quote\Address\RateRequest setDestStreet(string $value)
 * @method string getDestStreet()
 *
 * @method \Magento\Quote\Model\Quote\Address\RateRequest setPackageValue(float $value)
 * @method float getPackageValue()
 * @method \Magento\Quote\Model\Quote\Address\RateRequest setPackageValueWithDiscount(float $value)
 * @method float getPackageValueWithDiscount()
 * @method \Magento\Quote\Model\Quote\Address\RateRequest setPackagePhysicalValue(float $value)
 * @method float getPackagePhysicalValue()
 * @method \Magento\Quote\Model\Quote\Address\RateRequest setPackageQty(float $value)
 * @method float getPackageQty()
 * @method \Magento\Quote\Model\Quote\Address\RateRequest setPackageWeight(float $value)
 * @method float getPackageWeight()
 * @method \Magento\Quote\Model\Quote\Address\RateRequest setPackageHeight(int $value)
 * @method int getPackageHeight()
 * @method \Magento\Quote\Model\Quote\Address\RateRequest setPackageWidth(int $value)
 * @method int getPackageWidth()
 * @method \Magento\Quote\Model\Quote\Address\RateRequest setPackageDepth(int $value)
 * @method int getPackageDepth()
 * @method \Magento\Quote\Model\Quote\Address\RateRequest setPackageCurrency(string $value)
 * @method string getPackageCurrency()
 *
 * @method \Magento\Quote\Model\Quote\Address\RateRequest setOrderTotalQty(float $value)
 * @method float getOrderTotalQty()
 * @method \Magento\Quote\Model\Quote\Address\RateRequest setOrderSubtotal(float $value)
 * @method float getOrderSubtotal()
 *
 * @method boolean getFreeShipping()
 * @method \Magento\Quote\Model\Quote\Address\RateRequest setFreeShipping(boolean $flag)
 * @method float getFreeMethodWeight()
 * @method \Magento\Quote\Model\Quote\Address\RateRequest setFreeMethodWeight(float $value)
 *
 * @method \Magento\Quote\Model\Quote\Address\RateRequest setOptionInsurance(boolean $value)
 * @method boolean getOptionInsurance()
 * @method \Magento\Quote\Model\Quote\Address\RateRequest setOptionHandling(float $flag)
 * @method float getOptionHandling()
 *
 * @method \Magento\Quote\Model\Quote\Address\RateRequest setConditionName(array|string $value)
 * @method array|string getConditionName()
 *
 * @method \Magento\Quote\Model\Quote\Address\RateRequest setLimitCarrier(string $value)
 * @method string getLimitCarrier()
 * @method \Magento\Quote\Model\Quote\Address\RateRequest setLimitMethod(string $value)
 * @method string getLimitMethod()
 * @api
 * @since 100.0.2
 */

 class RateRequest extends \Magento\Quote\Model\Quote\Address\RateRequest
{
    public function customGetFreeShipping()
    {
        // $writer = new \Zend\Log\Writer\Stream(BP . '/var/log/sales-rule.log');
        // $logger = new \Zend\Log\Logger();
        // $logger->addWriter($writer);

        $om = \Magento\Framework\App\ObjectManager::getInstance();
        $cartObj = $om->get('\Magento\Checkout\Model\Cart'); 
        $quote = $om->get('\Magento\Quote\Model\Quote');   
        $salesRule = $om->get('\Magento\SalesRule\Model\Rule');  
        $resource = $om->create('Magento\Framework\App\ResourceConnection');
 
        $connection = $resource->getConnection(\Magento\Framework\App\ResourceConnection::DEFAULT_CONNECTION);
        $tableName = $resource->getTableName('quote');
        
        $applied_rule_ids = $quote->getData("applied_rule_ids");
        
        $applied_rule_ids = explode(",",$applied_rule_ids);
        foreach($applied_rule_ids as $applied_rule_id){
            $sales_rule_id = $salesRule->load($applied_rule_id);
            if($applied_rule_id == $sales_rule_id->getData("rule_id")){
                if($sales_rule_id->getData("is_active") == 0){
                    if(is_array($applied_rule_ids)){
                        $applied_rule_ids = array_diff($applied_rule_ids, [$applied_rule_id]);
                        $applied_rule_ids = implode(",", $applied_rule_ids);
                        if(!empty($applied_rule_ids)){
                            $sql = "UPDATE " . $tableName . " SET applied_rule_ids='".$applied_rule_ids."' WHERE entity_id = '".$quote->getData("entity_id")."'";$connection->query($sql);
                        }
                    }
                }
            }
        }
        
        if(!empty($quote->getData("entity_id"))){
            $sql = "UPDATE " . $tableName . " SET trigger_recollect=0 WHERE entity_id = '".$quote->getData("entity_id")."'";$connection->query($sql);
        }

        $quoteId = $cartObj->getQuote()->getId();
        foreach($this->getAllItems() as $item){
            $quoteId = $item->getQuoteId();
            break;
        }

        $quote = $quote->load($quoteId);

        $getAppliedRuleIds = $quote->getAppliedRuleIds();
        $appliedRuleIds = explode(",",$getAppliedRuleIds);

        $arrRule = [];
        foreach($appliedRuleIds as $ruleIds){
            $rules =  $salesRule->getCollection()->addFieldToFilter('rule_id' , array('eq' => $ruleIds));
            foreach ($rules as $rule) {
                array_push($arrRule,array(
                    "ruleAction" => $rule->getSimpleAction(),
                    "ruleName" => $rule->getName(),
                    "discAmount" => $rule->getDiscountAmount(),
                    "maxDiscAmount" => $rule->getMaxDiscountShippingAmount(),
                    "getFreeShipping" => $this->getFreeShipping()
                ));
            }
            
        }
        // $logger->info(print_r($arrRule,1));
        return json_encode($arrRule);
    }
}
