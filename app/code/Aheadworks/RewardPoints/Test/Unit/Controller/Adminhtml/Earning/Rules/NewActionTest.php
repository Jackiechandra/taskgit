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
namespace Aheadworks\RewardPoints\Test\Unit\Controller\Adminhtml\Earning\Rules;

use Aheadworks\RewardPoints\Controller\Adminhtml\Earning\Rules\NewAction;
use PHPUnit\Framework\TestCase;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Forward;
use Magento\Backend\Model\View\Result\ForwardFactory;

/**
 * Test for \Aheadworks\RewardPoints\Controller\Adminhtml\Earning\Rules\NewAction
 */
class NewActionTest extends TestCase
{
    /**
     * @var NewAction
     */
    private $controller;

    /**
     * @var Context|\PHPUnit_Framework_MockObject_MockObject
     */
    private $contextMock;

    /**
     * @var ForwardFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    private $resultForwardFactoryMock;

    /**
     * Init mocks for tests
     *
     * @return void
     */
    protected function setUp(): void
    {
        $objectManager = new ObjectManager($this);

        $this->contextMock = $objectManager->getObject(Context::class, []);
        $this->resultForwardFactoryMock = $this->createMock(ForwardFactory::class);

        $this->controller = $objectManager->getObject(
            NewAction::class,
            [
                'context' => $this->contextMock,
                'resultForwardFactory' => $this->resultForwardFactoryMock,
            ]
        );
    }

    /**
     * Test execute
     */
    public function testExecute()
    {
        $resultForwardMock = $this->createMock(Forward::class);
        $resultForwardMock->expects($this->once())
            ->method('forward')
            ->with('edit')
            ->willReturnSelf();
        $this->resultForwardFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($resultForwardMock);

        $this->assertSame($resultForwardMock, $this->controller->execute());
    }
}
