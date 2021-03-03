<?php
/**
 * Aheadworks Inc.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://ecommerce.aheadworks.com/end-user-license-agreement/
 *
 * @package    RewardPoints
 * @version    1.7.2
 * @copyright  Copyright (c) 2020 Aheadworks Inc. (http://www.aheadworks.com)
 * @license    https://ecommerce.aheadworks.com/end-user-license-agreement/
 */
namespace Aheadworks\RewardPoints\Model\Source\Transaction;

use Magento\Framework\Option\ArrayInterface;

/**
 * Class BalanceUpdateActions
 *
 * @package Aheadworks\RewardPoints\Model\Source
 */
class BalanceUpdateActions implements ArrayInterface
{
    /**
     * @var TransactionType
     */
    private $transactionType;

    /**
     * @param Type $transactionType
     */
    public function __construct(Type $transactionType)
    {
        $this->transactionType = $transactionType;
    }

    /**
     *  {@inheritDoc}
     */
    public function toOptionArray()
    {
        return $this->transactionType->getBalanceUpdateActions();
    }
}
