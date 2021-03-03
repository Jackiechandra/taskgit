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
namespace Aheadworks\RewardPoints\Block\Adminhtml\Page\Menu;

/**
 * Page Menu Item
 *
 * @method string getPath()
 * @method string getLabel()
 * @method string getResource()
 * @method string getController()
 * @method array getLinkAttributes()
 *
 * @method Item setLinkAttributes(array $linkAttributes)
 *
 * @package Aheadworks\RewardPoints\Block\Adminhtml\Page\Menu
 * @codeCoverageIgnore
 */
class Item extends \Magento\Backend\Block\Template
{
    /**
     * @var string
     */
    protected $_template = 'Aheadworks_RewardPoints::page/menu/item.phtml';

    /**
     * Prepare html attributes of the link
     *
     * @return void
     */
    protected function prepareLinkAttributes()
    {
        $linkAttributes = is_array($this->getLinkAttributes()) ? $this->getLinkAttributes() : [];
        if (!isset($linkAttributes['href'])) {
            $linkAttributes['href'] = $this->getUrl($this->getPath());
        }
        $classes = [];
        if (isset($linkAttributes['class'])) {
            $classes = explode(' ', $linkAttributes['class']);
        }
        if ($this->isCurrent()) {
            $classes[] = 'current';
        }
        $linkAttributes['class'] = implode(' ', $classes);
        $this->setLinkAttributes($linkAttributes);
    }

    /**
     * Retrieves string presentation of link attributes
     *
     * @return string
     */
    public function serializeLinkAttributes()
    {
        $nameValuePairs = [];
        foreach ($this->getLinkAttributes() as $attrName => $attrValue) {
            $nameValuePairs[] = sprintf('%s="%s"', $attrName, $attrValue);
        }
        return implode(' ', $nameValuePairs);
    }

    /**
     * @inheritdoc
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        $this->prepareLinkAttributes();
        if ($this->isCurrent()) {
            /** @var \Aheadworks\RewardPoints\Block\Adminhtml\Page\Menu $menu */
            $menu = $this->getParentBlock();
            if ($menu) {
                $menu->setTitle($this->getLabel());
            }
        }
    }

    /**
     * @inheritdoc
     */
    protected function _toHtml()
    {
        if ($this->getResource() && !$this->_authorization->isAllowed($this->getResource())) {
            return '';
        }
        return parent::_toHtml();
    }

    /**
     * Checks whether the item is current
     *
     * @return bool
     */
    private function isCurrent()
    {
        return $this->getController() == $this->getRequest()->getControllerName();
    }
}
