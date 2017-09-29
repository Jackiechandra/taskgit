<?php
namespace Aheadworks\Blog\Model\Sitemap;

use Aheadworks\Blog\Api\Data\CategoryInterface;
use Aheadworks\Blog\Api\Data\PostInterface;
use Aheadworks\Blog\Api\CategoryRepositoryInterface;
use Aheadworks\Blog\Api\PostRepositoryInterface;
use Aheadworks\Blog\Model\Category;
use Aheadworks\Blog\Model\Config;
use Aheadworks\Blog\Model\Source\Post\Status as PostStatus;
use Magento\Framework\Api\SearchCriteriaBuilder;

/**
 * Sitemap blog items provider
 * @package Aheadworks\Blog\Model\Sitemap
 * @codeCoverageIgnore
 */
class ItemsProvider
{
    /**
     * @var CategoryRepositoryInterface
     */
    protected $categoryRepository;

    /**
     * @var PostRepositoryInterface
     */
    protected $postRepository;

    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * @var \Aheadworks\Blog\Model\Url
     */
    protected $url;

    /**
     * @var Config
     */
    protected $config;

    /**
     * ItemsProvider constructor.
     *
     * @param CategoryRepositoryInterface $categoryRepository
     * @param PostRepositoryInterface $postRepository
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param \Aheadworks\Blog\Model\Url $url
     * @param Config $config
     */
    public function __construct(
        CategoryRepositoryInterface $categoryRepository,
        PostRepositoryInterface $postRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        \Aheadworks\Blog\Model\Url $url,
        Config $config
    ) {
        $this->categoryRepository = $categoryRepository;
        $this->postRepository = $postRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->url = $url;
        $this->config = $config;
    }

    /**
     * Retrieves blog home page sitemap item
     *
     * @param int $storeId
     * @return \Magento\Framework\DataObject
     */
    public function getBlogItem($storeId)
    {
        return new \Magento\Framework\DataObject(
            [
                'changefreq' => $this->getChangeFreq($storeId),
                'priority' => $this->getPriority($storeId),
                'collection' => [
                    new \Magento\Framework\DataObject(
                        [
                            'id' => 'blog_home',
                            'url' => $this->config->getValue(Config::XML_GENERAL_ROUTE_TO_BLOG, $storeId),
                            'updated_at' => $this->getCurrentDateTime()
                        ]
                    )
                ]
            ]
        );
    }

    /**
     * Retrieves category sitemap items
     *
     * @param int $storeId
     * @return \Magento\Framework\DataObject
     */
    public function getCategoryItems($storeId)
    {
        $categoryItems = [];
        foreach ($this->getCategories($storeId) as $category) {
            $categoryItems[$category->getId()] = new \Magento\Framework\DataObject(
                [
                    'id' => $category->getId(),
                    'url' => $this->url->getCategoryRoute($category),
                    'updated_at' => $this->getCurrentDateTime()
                ]
            );
        }
        return new \Magento\Framework\DataObject(
            [
                'changefreq' => $this->getChangeFreq($storeId),
                'priority' => $this->getPriority($storeId),
                'collection' => $categoryItems
            ]
        );
    }

    /**
     * Retrieves post sitemap items
     *
     * @param int $storeId
     * @return \Magento\Framework\DataObject
     */
    public function getPostItems($storeId)
    {
        $postItems = [];
        foreach ($this->getPosts($storeId) as $post) {
            $postItems[$post->getId()] = new \Magento\Framework\DataObject(
                [
                    'id' => $post->getId(),
                    'url' => $this->url->getPostRoute($post),
                    'updated_at' => $this->getCurrentDateTime()
                ]
            );
        }
        return new \Magento\Framework\DataObject(
            [
                'changefreq' => $this->getChangeFreq($storeId),
                'priority' => $this->getPriority($storeId),
                'collection' => $postItems
            ]
        );
    }

    /**
     * Retrieves list of categories
     *
     * @param int $storeId
     * @return CategoryInterface[]
     */
    private function getCategories($storeId)
    {
        $this->searchCriteriaBuilder
            ->addFilter('status', Category::STATUS_ENABLED)
            ->addFilter(CategoryInterface::STORE_IDS, $storeId);
        return $this->categoryRepository->getList($this->searchCriteriaBuilder->create())->getItems();
    }

    /**
     * Retrieves list of posts
     *
     * @param int $storeId
     * @return PostInterface[]
     */
    private function getPosts($storeId)
    {
        $this->searchCriteriaBuilder
            ->addFilter('publish_date', $this->getCurrentDateTime(), 'lteq')
            ->addFilter('status', PostStatus::PUBLICATION)
            ->addFilter(PostInterface::STORE_IDS, $storeId);
        return $this->postRepository->getList($this->searchCriteriaBuilder->create())->getItems();
    }

    /**
     * @param int $storeId
     * @return float
     */
    private function getChangeFreq($storeId)
    {
        return $this->config->getValue(Config::XML_SITEMAP_CHANGEFREQ, $storeId);
    }

    /**
     * @param int $storeId
     * @return string
     */
    private function getPriority($storeId)
    {
        return $this->config->getValue(Config::XML_SITEMAP_PRIORITY, $storeId);
    }

    /**
     * Current date/time
     *
     * @return string
     */
    private function getCurrentDateTime()
    {
        return (new \DateTime())->format(\Magento\Framework\Stdlib\DateTime::DATETIME_PHP_FORMAT);
    }
}
