<?php
/**
 * Copyright 2018 aheadWorks. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Aheadworks\Layerednav\Model\Layer\Filter;

use Aheadworks\Layerednav\Model\Layer\Filter\DataProvider\Decimal as DataProvider;
use Aheadworks\Layerednav\Model\Layer\Filter\DataProvider\DecimalFactory as DataProviderFactory;
use Aheadworks\Layerednav\Model\ResourceModel\Layer\ConditionRegistry;
use Magento\Catalog\Model\Layer;
use Magento\Catalog\Model\Layer\Filter\AbstractFilter;
use Magento\Catalog\Model\Layer\Filter\Dynamic\AlgorithmFactory;
use Magento\Catalog\Model\Layer\Filter\ItemFactory;
use Magento\Catalog\Model\Layer\Filter\Item\DataBuilder as ItemDataBuilder;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\App\RequestInterface;

/**
 * Decimal Filter
 *
 * @method int getStorefrontDisplayState()
 * @method AbstractFilter setStorefrontDisplayState(int $storefrontDisplayState)
 *
 * @package Aheadworks\Layerednav\Model\Layer\Filter
 */
class Decimal extends AbstractFilter
{
    /**
     * @var AlgorithmFactory
     */
    private $algorithmFactory;

    /**
     * @var DataProvider
     */
    private $dataProvider;

    /**
     * @var ConditionRegistry
     */
    private $conditionsRegistry;

    /**
     * @param ItemFactory $filterItemFactory
     * @param StoreManagerInterface $storeManager
     * @param Layer $layer
     * @param ItemDataBuilder $itemDataBuilder
     * @param AlgorithmFactory $algorithmFactory
     * @param DataProviderFactory $dataProviderFactory
     * @param ConditionRegistry $conditionsRegistry
     * @param array $data
     */
    public function __construct(
        ItemFactory $filterItemFactory,
        StoreManagerInterface $storeManager,
        Layer $layer,
        ItemDataBuilder $itemDataBuilder,
        AlgorithmFactory $algorithmFactory,
        DataProviderFactory $dataProviderFactory,
        ConditionRegistry $conditionsRegistry,
        array $data = []
    ) {
        parent::__construct(
            $filterItemFactory,
            $storeManager,
            $layer,
            $itemDataBuilder,
            $data
        );
        $this->algorithmFactory = $algorithmFactory;
        $this->dataProvider = $dataProviderFactory->create();
        $this->conditionsRegistry = $conditionsRegistry;
    }

    /**
     * {@inheritdoc}
     */
    public function apply(RequestInterface $request)
    {
        $filter = $request->getParam($this->getRequestVar());
        if (!$filter || is_array($filter)) {
            return $this;
        }

        $filterParams = explode(',', $filter);
        $intervals = $this->dataProvider->getIntervals($filterParams);
        if (!count($intervals)) {
            return $this;
        }

        $this->dataProvider->setInterval($intervals);
        $this->dataProvider->getResource()->joinFilterToCollection($this);
        $this->conditionsRegistry->addConditions(
            $this->getAttributeModel()->getAttributeCode(),
            $this->dataProvider->getResource()->getWhereConditions($this, $intervals)
        );

        $value = [];
        foreach ($intervals as $item) {
            $value[] = implode('-', $item);
        }
        $value = implode(',', $value);
        $this->getLayer()
            ->getState()
            ->addFilter(
                $this->_createItem($this->getRequestVar(), $value)
            );

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    protected function _getItemsData()
    {
        $algorithm = $this->algorithmFactory->create();
        $algorithm->setFilter($this);
        return $algorithm->getItemsData(
            (array)$this->dataProvider->getInterval(),
            $this->dataProvider->getAdditionalRequestData()
        );
    }
}
