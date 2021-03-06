<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\UrlRewrite\Model\Storage;

use Magento\UrlRewrite\Model\StorageInterface;
use Magento\UrlRewrite\Service\V1\Data\UrlRewriteBuilder;

/**
 * Abstract db storage
 */
abstract class AbstractStorage implements StorageInterface
{
    /** @var UrlRewriteBuilder */
    protected $urlRewriteBuilder;

    /**
     * @param UrlRewriteBuilder $urlRewriteBuilder
     */
    public function __construct(UrlRewriteBuilder $urlRewriteBuilder)
    {
        $this->urlRewriteBuilder = $urlRewriteBuilder;
    }

    /**
     * {@inheritdoc}
     */
    public function findAllByData(array $data)
    {
        $rows = $this->doFindAllByData($data);

        $urlRewrites = [];
        foreach ($rows as $row) {
            $urlRewrites[] = $this->createUrlRewrite($row);
        }
        return $urlRewrites;
    }

    /**
     * Find all rows by specific filter. Template method
     *
     * @param array $data
     * @return array
     */
    abstract protected function doFindAllByData($data);

    /**
     * {@inheritdoc}
     */
    public function findOneByData(array $data)
    {
        $row = $this->doFindOneByData($data);

        return $row ? $this->createUrlRewrite($row) : null;
    }

    /**
     * Find row by specific filter. Template method
     *
     * @param array $data
     * @return array
     */
    abstract protected function doFindOneByData($data);

    /**
     * {@inheritdoc}
     */
    public function replace(array $urls)
    {
        if (!$urls) {
            return;
        }

        try {
            $this->doReplace($urls);
        } catch (DuplicateEntryException $e) {
            throw new DuplicateEntryException(__('URL key for specified store already exists.'));
        }
    }

    /**
     * Save new url rewrites and remove old if exist. Template method
     *
     * @param \Magento\UrlRewrite\Service\V1\Data\UrlRewrite[] $urls
     * @return int
     * @throws DuplicateEntryException
     */
    abstract protected function doReplace($urls);

    /**
     * Create url rewrite object
     *
     * @param array $data
     * @return \Magento\UrlRewrite\Service\V1\Data\UrlRewrite
     */
    protected function createUrlRewrite($data)
    {
        return $this->urlRewriteBuilder->populateWithArray($data)->create();
    }
}
