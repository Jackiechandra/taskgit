<?php
/**
 * Copyright 2018 aheadWorks. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Aheadworks\Layerednav\Test\Unit\Block\Navigation;

use Aheadworks\Layerednav\Block\Navigation\FilterRenderer;
use Magento\Catalog\Model\Layer;
use Magento\Catalog\Model\Layer\Filter\FilterInterface;
use Magento\Catalog\Model\Layer\Filter\Item as FilterItem;
use Magento\Catalog\Model\Layer\Resolver as LayerResolver;
use Magento\Catalog\Model\Layer\State;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;

/**
 * Test for \Aheadworks\Layerednav\Block\Navigation\FilterRenderer
 */
class FilterRendererTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var FilterRenderer
     */
    private $renderer;

    /**
     * @var Layer|\PHPUnit_Framework_MockObject_MockObject
     */
    private $layerMock;

    public function setUp()
    {
        $objectManager = new ObjectManager($this);
        $this->layerMock = $this->getMockBuilder(Layer::class)
            ->setMethods(['getState'])
            ->disableOriginalConstructor()
            ->getMock();
        $layerResolverMock = $this->getMockBuilder(LayerResolver::class)
            ->setMethods(['get'])
            ->disableOriginalConstructor()
            ->getMock();
        $layerResolverMock->expects($this->once())
            ->method('get')
            ->willReturn($this->layerMock);
        $this->renderer = $objectManager->getObject(
            FilterRenderer::class,
            ['layerResolver' => $layerResolverMock]
        );
    }

    public function testRender()
    {
        $html = 'filter html';

        /** @var FilterRenderer|\PHPUnit_Framework_MockObject_MockObject $rendererMock */
        $rendererMock = $this->getMockBuilder(FilterRenderer::class)
            ->setMethods(['assign', '_toHtml'])
            ->disableOriginalConstructor()
            ->getMock();
        $filterItemMock = $this->getMockBuilder(FilterItem::class)
            ->setMethods([])
            ->disableOriginalConstructor()
            ->getMock();
        /** @var FilterInterface|\PHPUnit_Framework_MockObject_MockObject $filterMock */
        $filterMock = $this->getMockForAbstractClass(FilterInterface::class);

        $filterMock->expects($this->once())
            ->method('getItems')
            ->willReturn([$filterItemMock]);
        $rendererMock->expects($this->exactly(2))
            ->method('assign')
            ->withConsecutive(
                ['filterItems', [$filterItemMock]],
                ['filterItems', []]
            );
        $rendererMock->expects($this->once())
            ->method('_toHtml')
            ->willReturn($html);

        $this->assertEquals($html, $rendererMock->render($filterMock));
    }

    /**
     * @param FilterInterface[]|\PHPUnit_Framework_MockObject_MockObject[] $currentFilterItemMocks
     * @param FilterItem|\PHPUnit_Framework_MockObject_MockObject $filterItemMock
     * @param bool $isActive
     * @dataProvider isActiveItemDataProvider
     */
    public function testIsActiveItem($currentFilterItemMocks, $filterItemMock, $isActive)
    {
        $stateMock = $this->getMockBuilder(State::class)
            ->setMethods(['getFilters'])
            ->disableOriginalConstructor()
            ->getMock();
        $this->layerMock->expects($this->once())
            ->method('getState')
            ->willReturn($stateMock);
        $stateMock->expects($this->once())
            ->method('getFilters')
            ->willReturn($currentFilterItemMocks);
        $this->assertSame($isActive, $this->renderer->isActiveItem($filterItemMock));
    }

    /**
     * Create filter item mock
     *
     * @param string $value
     * @param string $requestVar
     * @return FilterItem|\PHPUnit_Framework_MockObject_MockObject
     */
    private function createFilterItemMock($value, $requestVar)
    {
        $filterItemMock = $this->getMockBuilder(FilterItem::class)
            ->setMethods(['getValue', 'getFilter'])
            ->disableOriginalConstructor()
            ->getMock();
        $filterItemMock->expects($this->atMost(1))
            ->method('getValue')
            ->willReturn($value);
        $filterMock = $this->getMockForAbstractClass(FilterInterface::class);
        $filterMock->expects($this->any())
            ->method('getRequestVar')
            ->willReturn($requestVar);
        $filterItemMock->expects($this->atMost(1))
            ->method('getFilter')
            ->willReturn($filterMock);
        return $filterItemMock;
    }

    /**
     * @return array
     */
    public function isActiveItemDataProvider()
    {
        return [
            'active' => [
                [$this->createFilterItemMock('filter_value', 'request_var')],
                $this->createFilterItemMock('filter_value', 'request_var'),
                true
            ],
            'inactive, different value' => [
                [$this->createFilterItemMock('filter_value1', 'request_var')],
                $this->createFilterItemMock('filter_value2', 'request_var'),
                false
            ],
            'inactive, different request var' => [
                [$this->createFilterItemMock('filter_value', 'request_var1')],
                $this->createFilterItemMock('filter_value', 'request_var2'),
                false
            ],
            'inactive, no active filter items' => [
                [],
                $this->createFilterItemMock('filter_value', 'request_var'),
                false
            ]
        ];
    }
}
