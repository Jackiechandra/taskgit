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
 * @package    StoreCredit
 * @version    1.1.7
 * @copyright  Copyright (c) 2020 Aheadworks Inc. (http://www.aheadworks.com)
 * @license    https://ecommerce.aheadworks.com/end-user-license-agreement/
 */
namespace Aheadworks\StoreCredit\Model\Comment;

use Magento\Framework\ObjectManagerInterface;

/**
 * Class Aheadworks\StoreCredit\Model\Comment\CommentPool
 */
class CommentPool implements CommentPoolInterface
{
    /**
     * Default comment code
     */
    const DEFAULT_COMMENT = 'default';

    /**
     * @var ObjectManagerInterface
     */
    private $objectManager;

    /**
     * @var array
     */
    private $comments;

    /**
     * @var CommentInterface[]
     */
    private $commentInstances = [];

    /**
     * @param ObjectManagerInterface $objectManager
     * @param array $comments
     * @throws \LogicException
     */
    public function __construct(
        ObjectManagerInterface $objectManager,
        array $comments
    ) {
        if (!isset($comments[self::DEFAULT_COMMENT])) {
            throw new \LogicException('Default comment should be provided.');
        }

        $this->objectManager = $objectManager;
        $this->comments = $comments;
    }

    /**
     * Create comment instance
     *
     * @param int $type
     * @return CommentInterface
     */
    public function get($type)
    {
        foreach (array_keys($this->comments) as $commentKey) {
            $commentInstance = $this->getCommentInstanceByKey($commentKey);
            if ($type == $commentInstance->getType()) {
                return $commentInstance;
            }
        }
        return $this->getCommentInstanceByKey(self::DEFAULT_COMMENT);
    }

    /**
     * Retrieve all comment instances
     *
     * @return CommentInterface[]
     */
    public function getAllComments()
    {
        if (empty($this->commentInstances)
            || count($this->commentInstances) != count($this->comments)
        ) {
            foreach ($this->comments as $comment => $commentClass) {
                $this->commentInstances[$comment] = $this->getCommentInstance($commentClass);
            }
        }
        return $this->commentInstances;
    }

    /**
     * Retirieve comment instance by key from instance cache
     *
     * @param string $comment
     * @return CommentInterface
     */
    private function getCommentInstanceByKey($comment)
    {
        if (isset($this->commentInstances[$comment])) {
            return $this->commentInstances[$comment];
        }
        $this->commentInstances[$comment] = $this->getCommentInstance($this->comments[$comment]);
        return $this->commentInstances[$comment];
    }

    /**
     * Retirieve comment instance
     *
     * @param string $commentClassName
     * @throws \InvalidArgumentException
     * @return CommentInterface
     */
    private function getCommentInstance($commentClassName)
    {
        $commentInstance = $this->objectManager->get($commentClassName);

        if (!$commentInstance instanceof CommentInterface) {
            throw new \InvalidArgumentException(
                'Comment instance "' . $commentClassName . '" must implement '
                . '\Aheadworks\StoreCredit\Model\Comment\CommentInterface'
            );
        }
        return $commentInstance;
    }
}
