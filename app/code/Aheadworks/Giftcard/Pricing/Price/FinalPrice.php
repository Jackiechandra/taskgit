<?php
/**
 * Aheadworks Inc.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://aheadworks.com/end-user-license-agreement/
 *
 * @package    Giftcard
 * @version    1.4.6
 * @copyright  Copyright (c) 2021 Aheadworks Inc. (https://aheadworks.com/)
 * @license    https://aheadworks.com/end-user-license-agreement/
 */
namespace Aheadworks\Giftcard\Pricing\Price;

use Aheadworks\Giftcard\Pricing\Adjustment\Calculator;
use Magento\Framework\Pricing\Amount\AmountInterface;
use Magento\Catalog\Pricing\Price\FinalPrice as CatalogFinalPrice;
use Aheadworks\Giftcard\Model\Product\Type\Giftcard\Price as GiftcardProductPrice;
use Magento\Framework\Pricing\SaleableInterface;
use Magento\Framework\Pricing\PriceCurrencyInterface;

/**
 * Class FinalPrice
 *
 * @package Aheadworks\Giftcard\Pricing\Price
 */
class FinalPrice extends CatalogFinalPrice
{
    /**
     * @var AmountInterface
     */
    protected $maximalPrice;

    /**
     * @var AmountInterface
     */
    protected $minimalPrice;

    /**
     * @param SaleableInterface $saleableItem
     * @param float $quantity
     * @param Calculator $calculator
     * @param PriceCurrencyInterface $priceCurrency
     */
    public function __construct(
        SaleableInterface $saleableItem,
        $quantity,
        Calculator $calculator,
        PriceCurrencyInterface $priceCurrency
    ) {
        parent::__construct($saleableItem, $quantity, $calculator, $priceCurrency);
    }

    /**
     * {@inheritdoc}
     */
    public function getMaximalPrice()
    {
        if ($this->maximalPrice === null) {
            $openAmountMax = $this->getPriceModel()->getOpenAmountMax($this->getProduct());
            $price = false;
            if ($openAmountMax !== false) {
                $price = $openAmountMax;
            }
            $amounts = $this->getPriceModel()->getAmounts($this->getProduct());
            if (!empty($amounts)) {
                if ($price) {
                    $amounts[] = $price;
                }
                $price = max($amounts);
            }
            if ($price) {
                $this->maximalPrice = $this->calculator->getAmount(
                    $this->priceCurrency->convertAndRound($price),
                    $this->getProduct()
                );
            }
        }
        return $this->maximalPrice;
    }

    /**
     * {@inheritdoc}
     */
    public function getMinimalPrice()
    {
        if ($this->minimalPrice === null) {
            $openAmountMin = $this->getPriceModel()->getOpenAmountMin($this->getProduct());
            $price = false;
            if ($openAmountMin !== false) {
                $price = $openAmountMin;
            }
            $amounts = $this->getPriceModel()->getAmounts($this->getProduct());
            if (!empty($amounts)) {
                if ($price) {
                    $amounts[] = $price;
                }
                $price = min($amounts);
            }
            if ($price) {
                $this->minimalPrice = $this->calculator->getAmount(
                    $this->priceCurrency->convertAndRound($price),
                    $this->getProduct()
                );
            }
        }
        return $this->minimalPrice;
    }

    /**
     * Retrieve product price model
     *
     * @return GiftcardProductPrice
     */
    private function getPriceModel()
    {
        return $this->getProduct()->getPriceModel();
    }
}
