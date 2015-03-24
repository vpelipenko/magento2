<?php
/**
 *
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

// @codingStandardsIgnoreFile

namespace Magento\Catalog\Test\Unit\Model\Product\Gallery;

class GalleryManagementTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Magento\Catalog\Model\Product\Gallery\GalleryManagement
     */
    protected $model;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $storeManagerMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $productRepositoryMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $mediaConfigMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $contentValidatorMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $filesystemMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $entryFactoryMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $mediaGalleryMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $attributeRepositoryMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $entryResolverMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $productMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|\Magento\Framework\Api\DataObjectHelper
     */
    protected $dataObjectHelperMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|\Magento\Framework\Api\AttributeValue
     */
    protected $attributeValueMock;

    protected function setUp()
    {
        $this->storeManagerMock = $this->getMock('\Magento\Store\Model\StoreManagerInterface');
        $this->productRepositoryMock = $this->getMock('\Magento\Catalog\Api\ProductRepositoryInterface');
        $this->attributeRepositoryMock = $this->getMock('\Magento\Catalog\Api\ProductAttributeRepositoryInterface');
        $this->mediaConfigMock = $this->getMock('\Magento\Catalog\Model\Product\Media\Config', [], [], '', false);
        $this->filesystemMock = $this->getMock('\Magento\Framework\Filesystem', [], [], '', false);
        $this->contentValidatorMock = $this->getMock(
            '\Magento\Catalog\Model\Product\Gallery\ContentValidator',
            [],
            [],
            '',
            false
        );
        $this->entryResolverMock = $this->getMock(
            '\Magento\Catalog\Model\Product\Gallery\EntryResolver',
            [],
            [],
            '',
            false
        );
        $this->entryFactoryMock = $this->getMock(
            '\Magento\Catalog\Api\Data\ProductAttributeMediaGalleryEntryInterfaceFactory',
            ['create'],
            [],
            '',
            false
        );
        $this->dataObjectHelperMock = $this->getMockBuilder('\Magento\Framework\Api\DataObjectHelper')
            ->disableOriginalConstructor()
            ->getMock();
        $this->mediaGalleryMock = $this->getMock(
            '\Magento\Catalog\Model\Resource\Product\Attribute\Backend\Media',
            [],
            [],
            '',
            false
        );
        $this->productMock = $this->getMock(
            '\Magento\Catalog\Model\Product',
            [
                'getTypeInstance',
                'getSetAttributes',
                'setStoreId',
                'getMediaAttributes',
                'getMediaGallery',
                'getData',
                'getStoreId',
                'getSku',
                'getCustomAttribute'
            ],
            [],
            '',
            false
        );
        $this->model = new \Magento\Catalog\Model\Product\Gallery\GalleryManagement(
            $this->storeManagerMock,
            $this->productRepositoryMock,
            $this->attributeRepositoryMock,
            $this->mediaConfigMock,
            $this->contentValidatorMock,
            $this->filesystemMock,
            $this->entryResolverMock,
            $this->entryFactoryMock,
            $this->mediaGalleryMock,
            $this->dataObjectHelperMock
        );
        $this->attributeValueMock = $this->getMockBuilder('\Magento\Framework\Api\AttributeValue')
            ->disableOriginalConstructor()
            ->getMock();
    }

    /**
     * @expectedException \Magento\Framework\Exception\NoSuchEntityException
     * @expectedExceptionMessage There is no store with provided ID.
     */
    public function testCreateWithNoStoreException()
    {
        $this->storeManagerMock->expects($this->once())->method('getStore')
            ->willThrowException(new \Exception());
        $this->model->create($this->productMock);
    }

    /**
     * @expectedException \Magento\Framework\Exception\InputException
     * @expectedExceptionMessage The image content is not valid.
     */
    public function testCreateWithInvalidImageException()
    {
        $entryMock = $this->getMock('\Magento\Catalog\Api\Data\ProductAttributeMediaGalleryEntryInterface');
        $entryContentMock = $this->getMock(
            '\Magento\Catalog\Api\Data\ProductAttributeMediaGalleryEntryContentInterface'
        );
        $entryMock->expects($this->any())->method('getContent')->willReturn($entryContentMock);
        $this->attributeValueMock->expects($this->any())->method('getValue')->willReturn($entryMock);

        $storeId = 0;
        $this->productMock->expects($this->any())->method('getStoreId')->willReturn($storeId);
        $this->productMock->expects($this->any())
            ->method('getCustomAttribute')
            ->with('media_gallery')
            ->willReturn($this->attributeValueMock);

        $this->storeManagerMock->expects($this->once())->method('getStore')->with($storeId);
        $this->contentValidatorMock->expects($this->once())->method('isValid')->with($entryContentMock)
            ->willReturn(false);
        $this->entryResolverMock->expects($this->never())->method('getEntryIdByFilePath');

        $this->model->create($this->productMock);
    }

    /**
     * @expectedException \Magento\Framework\Exception\StateException
     * @expectedExceptionMessage Requested product does not support images.
     */
    public function testCreateWithProductWithoutImagesSupport()
    {
        $productSku = 'mediaProduct';
        $entryMock = $this->getMock('\Magento\Catalog\Api\Data\ProductAttributeMediaGalleryEntryInterface');
        $entryContentMock = $this->getMock(
            '\Magento\Catalog\Api\Data\ProductAttributeMediaGalleryEntryContentInterface'
        );
        $entryMock->expects($this->any())->method('getContent')->willReturn($entryContentMock);
        $this->attributeValueMock->expects($this->any())->method('getValue')->willReturn($entryMock);

        $storeId = 0;
        $this->productMock->expects($this->any())->method('getStoreId')->willReturn($storeId);
        $this->productMock->expects($this->any())->method('getSku')->willReturn($productSku);
        $this->productMock->expects($this->any())
            ->method('getCustomAttribute')
            ->with('media_gallery')
            ->willReturn($this->attributeValueMock);

        $this->storeManagerMock->expects($this->once())->method('getStore')->with($storeId);
        $this->entryResolverMock->expects($this->never())->method('getEntryIdByFilePath');

        $writeInterfaceMock = $this->getMock('\Magento\Framework\Filesystem\Directory\WriteInterface');
        $entryData = 'entryData';
        $mediaTmpPath = '/media/tmp/path';
        $fileName = 'Image';
        $mimeType = 'image/jpg';
        $relativeFilePath = $mediaTmpPath . DIRECTORY_SEPARATOR . $fileName . '.jpg';
        $this->storeManagerMock->expects($this->once())->method('getStore')->with($storeId);
        $this->contentValidatorMock->expects($this->once())->method('isValid')->with($entryContentMock)
            ->willReturn(true);
        $this->productRepositoryMock->expects($this->once())->method('get')->with($productSku)
            ->willReturn($this->productMock);
        $entryContentMock->expects($this->once())->method('getEntryData')->willReturn(base64_encode($entryData));
        $this->mediaConfigMock->expects($this->once())->method('getBaseTmpMediaPath')->willReturn($mediaTmpPath);
        $this->filesystemMock->expects($this->once())->method('getDirectoryWrite')
            ->with(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA)->willReturn($writeInterfaceMock);
        $writeInterfaceMock->expects($this->once())->method('create')->with($mediaTmpPath);
        $entryContentMock->expects($this->once())->method('getName')->willReturn($fileName);
        $entryContentMock->expects($this->once())->method('getMimeType')->willReturn($mimeType);
        $writeInterfaceMock->expects($this->once())->method('getAbsolutePath')->with($relativeFilePath);
        $writeInterfaceMock->expects($this->once())->method('writeFile')->with($relativeFilePath, $entryData);
        $this->productMock->expects($this->once())->method('getTypeInstance')->willReturnSelf();
        $this->productMock->expects($this->once())->method('getSetAttributes')->with($this->productMock)
            ->willReturn([]);
        $this->entryResolverMock->expects($this->never())->method('getEntryIdByFilePath');
        $this->model->create($this->productMock);
    }

    /**
     * @expectedException \Magento\Framework\Exception\StateException
     * @expectedExceptionMessage Cannot save product.
     */
    public function testCreateWithCannotSaveException()
    {
        $productSku = 'mediaProduct';
        $entryMock = $this->getMock('\Magento\Catalog\Api\Data\ProductAttributeMediaGalleryEntryInterface');
        $entryContentMock = $this->getMock(
            '\Magento\Catalog\Api\Data\ProductAttributeMediaGalleryEntryContentInterface'
        );
        $entryMock->expects($this->any())->method('getContent')->willReturn($entryContentMock);
        $this->attributeValueMock->expects($this->any())->method('getValue')->willReturn($entryMock);

        $storeId = 0;
        $this->productMock->expects($this->any())->method('getStoreId')->willReturn($storeId);
        $this->productMock->expects($this->any())->method('getSku')->willReturn($productSku);
        $this->productMock->expects($this->any())
            ->method('getCustomAttribute')
            ->with('media_gallery')
            ->willReturn($this->attributeValueMock);

        $entryPosition = 'entryPosition';
        $absolutePath = 'absolute/path';
        $productMediaGalleryMock = $this->getMock(
            '\Magento\Eav\Model\Entity\Attribute\Backend\AbstractBackend',
            ['addImage', 'updateImage'],
            [],
            '',
            false
        );
        $attributeMock = $this->getMock('\Magento\Eav\Model\Entity\Attribute\AbstractAttribute', [], [], '', false);
        $writeInterfaceMock = $this->getMock('\Magento\Framework\Filesystem\Directory\WriteInterface');
        $entryData = 'entryData';
        $mediaTmpPath = '/media/tmp/path';
        $fileName = 'Image';
        $mimeType = 'image/jpg';
        $imageFileUri = 'http://magento.awesome/image.jpg';
        $relativeFilePath = $mediaTmpPath . DIRECTORY_SEPARATOR . $fileName . '.jpg';
        $this->storeManagerMock->expects($this->once())->method('getStore')->with($storeId);
        $this->contentValidatorMock->expects($this->once())->method('isValid')->with($entryContentMock)
            ->willReturn(true);
        $this->productRepositoryMock->expects($this->once())->method('get')->with($productSku)
            ->willReturn($this->productMock);
        $entryContentMock->expects($this->once())->method('getEntryData')->willReturn(base64_encode($entryData));
        $this->mediaConfigMock->expects($this->once())->method('getBaseTmpMediaPath')->willReturn($mediaTmpPath);
        $this->filesystemMock->expects($this->once())->method('getDirectoryWrite')
            ->with(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA)->willReturn($writeInterfaceMock);
        $writeInterfaceMock->expects($this->once())->method('create')->with($mediaTmpPath);
        $entryContentMock->expects($this->once())->method('getName')->willReturn($fileName);
        $entryContentMock->expects($this->once())->method('getMimeType')->willReturn($mimeType);
        $writeInterfaceMock->expects($this->once())->method('getAbsolutePath')->with($relativeFilePath)
            ->willReturn($absolutePath);
        $writeInterfaceMock->expects($this->once())->method('writeFile')->with($relativeFilePath, $entryData);
        $this->productMock->expects($this->once())->method('getTypeInstance')->willReturnSelf();
        $this->productMock->expects($this->once())->method('getSetAttributes')->with($this->productMock)
            ->willReturn(['media_gallery' => $attributeMock]);
        $attributeMock->expects($this->once())->method('getBackend')->willReturn($productMediaGalleryMock);
        $entryMock->expects($this->once())->method('getTypes')->willReturn(['jpg']);
        $entryMock->expects($this->exactly(2))->method('isDisabled')->willReturn(false);
        $entryMock->expects($this->once())->method('getPosition')->willReturn($entryPosition);
        $entryMock->expects($this->once())->method('getLabel')->willReturn('entryLabel');
        $productMediaGalleryMock->expects($this->once())->method('addImage')->with(
            $this->productMock,
            $absolutePath,
            ['jpg'],
            true,
            false
        )->willReturn($imageFileUri);
        $productMediaGalleryMock->expects($this->once())->method('updateImage')->with(
            $this->productMock,
            $imageFileUri,
            [
                'label' => 'entryLabel',
                'position' => $entryPosition,
                'disabled' => false
            ]
        );
        $this->productRepositoryMock->expects($this->once())->method('save')->with($this->productMock)
            ->willThrowException(new \Exception());
        $this->entryResolverMock->expects($this->never())->method('getEntryIdByFilePath');
        $this->model->create($this->productMock);
    }

    public function testCreate()
    {
        $productSku = 'mediaProduct';
        $entryMock = $this->getMock('\Magento\Catalog\Api\Data\ProductAttributeMediaGalleryEntryInterface');
        $entryContentMock = $this->getMock(
            '\Magento\Catalog\Api\Data\ProductAttributeMediaGalleryEntryContentInterface'
        );
        $entryMock->expects($this->any())->method('getContent')->willReturn($entryContentMock);
        $this->attributeValueMock->expects($this->any())->method('getValue')->willReturn($entryMock);

        $storeId = 0;
        $this->productMock->expects($this->any())->method('getStoreId')->willReturn($storeId);
        $this->productMock->expects($this->any())->method('getSku')->willReturn($productSku);
        $this->productMock->expects($this->any())
            ->method('getCustomAttribute')
            ->with('media_gallery')
            ->willReturn($this->attributeValueMock);

        $entryPosition = 'entryPosition';
        $absolutePath = 'absolute/path';

        $productMediaGalleryMock = $this->getMock(
            '\Magento\Eav\Model\Entity\Attribute\Backend\AbstractBackend',
            ['addImage', 'updateImage', 'getRenamedImage'],
            [],
            '',
            false
        );
        $attributeMock = $this->getMock('\Magento\Eav\Model\Entity\Attribute\AbstractAttribute', [], [], '', false);
        $writeInterfaceMock = $this->getMock('\Magento\Framework\Filesystem\Directory\WriteInterface');
        $entryData = 'entryData';
        $mediaTmpPath = '/media/tmp/path';
        $fileName = 'Image';
        $mimeType = 'image/jpg';
        $imageFileUri = 'http://magento.awesome/image.jpg';
        $relativeFilePath = $mediaTmpPath . DIRECTORY_SEPARATOR . $fileName . '.jpg';
        $this->storeManagerMock->expects($this->once())->method('getStore')->with($storeId);
        $this->contentValidatorMock->expects($this->once())->method('isValid')->with($entryContentMock)
            ->willReturn(true);
        $this->productRepositoryMock->expects($this->once())->method('get')->with($productSku)
            ->willReturn($this->productMock);
        $entryContentMock->expects($this->once())->method('getEntryData')->willReturn(base64_encode($entryData));
        $this->mediaConfigMock->expects($this->once())->method('getBaseTmpMediaPath')->willReturn($mediaTmpPath);
        $this->filesystemMock->expects($this->once())->method('getDirectoryWrite')
            ->with(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA)->willReturn($writeInterfaceMock);
        $writeInterfaceMock->expects($this->once())->method('create')->with($mediaTmpPath);
        $entryContentMock->expects($this->once())->method('getName')->willReturn($fileName);
        $entryContentMock->expects($this->once())->method('getMimeType')->willReturn($mimeType);
        $writeInterfaceMock->expects($this->once())->method('getAbsolutePath')->with($relativeFilePath)
            ->willReturn($absolutePath);
        $writeInterfaceMock->expects($this->once())->method('writeFile')->with($relativeFilePath, $entryData);
        $this->productMock->expects($this->once())->method('getTypeInstance')->willReturnSelf();
        $this->productMock->expects($this->once())->method('getSetAttributes')->with($this->productMock)
            ->willReturn(['media_gallery' => $attributeMock]);
        $attributeMock->expects($this->once())->method('getBackend')->willReturn($productMediaGalleryMock);
        $entryMock->expects($this->once())->method('getTypes')->willReturn(['jpg']);
        $entryMock->expects($this->exactly(2))->method('isDisabled')->willReturn(false);
        $entryMock->expects($this->once())->method('getPosition')->willReturn($entryPosition);
        $entryMock->expects($this->once())->method('getLabel')->willReturn('entryLabel');
        $productMediaGalleryMock->expects($this->once())->method('addImage')->with(
            $this->productMock,
            $absolutePath,
            ['jpg'],
            true,
            false
        )->willReturn($imageFileUri);
        $productMediaGalleryMock->expects($this->once())->method('updateImage')->with(
            $this->productMock,
            $imageFileUri,
            [
                'label' => 'entryLabel',
                'position' => $entryPosition,
                'disabled' => false
            ]
        );
        $this->productRepositoryMock->expects($this->once())->method('save')->with($this->productMock);
        $writeInterfaceMock->expects($this->once())->method('delete')->with($relativeFilePath);
        $productMediaGalleryMock->expects($this->once())->method('getRenamedImage')->with($imageFileUri)
            ->willReturn('renamed');
        $this->entryResolverMock->expects($this->once())->method('getEntryIdByFilePath')->with(
            $this->productMock,
            'renamed'
        )->willReturn(42);
        $this->assertEquals(42, $this->model->create($this->productMock));
    }

    /**
     * @expectedException \Magento\Framework\Exception\NoSuchEntityException
     * @expectedExceptionMessage There is no store with provided ID.
     */
    public function testUpdateWithNonExistingStore()
    {
        $productSku = 'testProduct';
        $entryMock = $this->getMock('\Magento\Catalog\Api\Data\ProductAttributeMediaGalleryEntryInterface');
        $storeId = 0;
        $this->storeManagerMock->expects($this->once())->method('getStore')->with($storeId)
            ->willThrowException(new \Exception());
        $this->model->update($productSku, $entryMock, $storeId);
    }

    /**
     * @expectedException \Magento\Framework\Exception\NoSuchEntityException
     * @expectedExceptionMessage There is no image with provided ID.
     */
    public function testUpdateWithNonExistingImage()
    {
        $productSku = 'testProduct';
        $entryMock = $this->getMock('\Magento\Catalog\Api\Data\ProductAttributeMediaGalleryEntryInterface');
        $storeId = 0;
        $entryId = 42;
        $productMediaGalleryMock = $this->getMock(
            '\Magento\Eav\Model\Entity\Attribute\Backend\AbstractBackend',
            ['addImage', 'updateImage', 'getRenamedImage'],
            [],
            '',
            false
        );
        $attributeMock = $this->getMock('\Magento\Eav\Model\Entity\Attribute\AbstractAttribute', [], [], '', false);
        $this->storeManagerMock->expects($this->once())->method('getStore')->with($storeId);
        $this->productRepositoryMock->expects($this->once())->method('get')->with($productSku)
            ->willReturn($this->productMock);
        $this->productMock->expects($this->once())->method('getTypeInstance')->willReturnSelf();
        $this->productMock->expects($this->once())->method('getSetAttributes')->with($this->productMock)
            ->willReturn(['media_gallery' => $attributeMock]);
        $attributeMock->expects($this->once())->method('getBackend')->willReturn($productMediaGalleryMock);
        $entryMock->expects($this->once())->method('getId')->willReturn($entryId);
        $this->entryResolverMock->expects($this->once())->method('getEntryFilePathById')
            ->with($this->productMock, $entryId)
            ->willReturn(null);
        $this->model->update($productSku, $entryMock, $storeId);
    }

    /**
     * @expectedException \Magento\Framework\Exception\StateException
     * @expectedExceptionMessage Cannot save product.
     */
    public function testUpdateWithCannotSaveException()
    {
        $productSku = 'testProduct';
        $entryMock = $this->getMock('\Magento\Catalog\Api\Data\ProductAttributeMediaGalleryEntryInterface');
        $storeId = 0;
        $entryId = 42;
        $filePath = '/path/to/the/file.jpg';
        $entryPosition = 'entryPosition';
        $productMediaGalleryMock = $this->getMock(
            '\Magento\Eav\Model\Entity\Attribute\Backend\AbstractBackend',
            ['addImage', 'updateImage', 'getRenamedImage', 'clearMediaAttribute', 'setMediaAttribute'],
            [],
            '',
            false
        );
        $attributeMock = $this->getMock('\Magento\Eav\Model\Entity\Attribute\AbstractAttribute', [], [], '', false);
        $this->storeManagerMock->expects($this->once())->method('getStore')->with($storeId);
        $this->productRepositoryMock->expects($this->once())->method('get')->with($productSku)
            ->willReturn($this->productMock);
        $this->productMock->expects($this->once())->method('getTypeInstance')->willReturnSelf();
        $this->productMock->expects($this->once())->method('getSetAttributes')->with($this->productMock)
            ->willReturn(['media_gallery' => $attributeMock]);
        $attributeMock->expects($this->once())->method('getBackend')->willReturn($productMediaGalleryMock);
        $entryMock->expects($this->once())->method('getId')->willReturn($entryId);
        $this->entryResolverMock->expects($this->once())->method('getEntryFilePathById')
            ->with($this->productMock, $entryId)->willReturn($filePath);
        $entryMock->expects($this->once())->method('isDisabled')->willReturn(false);
        $entryMock->expects($this->once())->method('getPosition')->willReturn($entryPosition);
        $entryMock->expects($this->once())->method('getLabel')->willReturn('entryLabel');
        $productMediaGalleryMock->expects($this->once())->method('updateImage')->with(
            $this->productMock,
            $filePath,
            [
                'label' => 'entryLabel',
                'position' => $entryPosition,
                'disabled' => false
            ]
        );
        $this->productMock->expects($this->once())->method('getMediaAttributes')->willReturn([]);
        $productMediaGalleryMock->expects($this->once())->method('clearMediaAttribute')->with(
            $this->productMock,
            []
        );
        $entryMock->expects($this->once())->method('getTypes')->willReturn(['jpg']);
        $productMediaGalleryMock->expects($this->once())->method('setMediaAttribute')->with(
            $this->productMock,
            ['jpg'],
            $filePath
        );
        $this->productMock->expects($this->once())->method('setStoreId')->with($storeId);
        $this->productRepositoryMock->expects($this->once())->method('save')->with($this->productMock)
            ->willThrowException(new \Exception());
        $this->model->update($productSku, $entryMock, $storeId);
    }

    public function testUpdate()
    {
        $productSku = 'testProduct';
        $entryMock = $this->getMock('\Magento\Catalog\Api\Data\ProductAttributeMediaGalleryEntryInterface');
        $storeId = 0;
        $entryId = 42;
        $filePath = '/path/to/the/file.jpg';
        $entryPosition = 'entryPosition';
        $productMediaGalleryMock = $this->getMock(
            '\Magento\Eav\Model\Entity\Attribute\Backend\AbstractBackend',
            ['addImage', 'updateImage', 'getRenamedImage', 'clearMediaAttribute', 'setMediaAttribute'],
            [],
            '',
            false
        );
        $attributeMock = $this->getMock('\Magento\Eav\Model\Entity\Attribute\AbstractAttribute', [], [], '', false);
        $this->storeManagerMock->expects($this->once())->method('getStore')->with($storeId);
        $this->productRepositoryMock->expects($this->once())->method('get')->with($productSku)
            ->willReturn($this->productMock);
        $this->productMock->expects($this->once())->method('getTypeInstance')->willReturnSelf();
        $this->productMock->expects($this->once())->method('getSetAttributes')->with($this->productMock)
            ->willReturn(['media_gallery' => $attributeMock]);
        $attributeMock->expects($this->once())->method('getBackend')->willReturn($productMediaGalleryMock);
        $entryMock->expects($this->once())->method('getId')->willReturn($entryId);
        $this->entryResolverMock->expects($this->once())->method('getEntryFilePathById')
            ->with($this->productMock, $entryId)->willReturn($filePath);
        $entryMock->expects($this->once())->method('isDisabled')->willReturn(false);
        $entryMock->expects($this->once())->method('getPosition')->willReturn($entryPosition);
        $entryMock->expects($this->once())->method('getLabel')->willReturn('entryLabel');
        $productMediaGalleryMock->expects($this->once())->method('updateImage')->with(
            $this->productMock,
            $filePath,
            [
                'label' => 'entryLabel',
                'position' => $entryPosition,
                'disabled' => false
            ]
        );
        $this->productMock->expects($this->once())->method('getMediaAttributes')->willReturn([]);
        $productMediaGalleryMock->expects($this->once())->method('clearMediaAttribute')->with(
            $this->productMock,
            []
        );
        $entryMock->expects($this->once())->method('getTypes')->willReturn(['jpg']);
        $productMediaGalleryMock->expects($this->once())->method('setMediaAttribute')->with(
            $this->productMock,
            ['jpg'],
            $filePath
        );
        $this->productMock->expects($this->once())->method('setStoreId')->with($storeId);
        $this->productRepositoryMock->expects($this->once())->method('save')->with($this->productMock);
        $this->assertTrue($this->model->update($productSku, $entryMock, $storeId));
    }

    /**
     * @expectedException \Magento\Framework\Exception\NoSuchEntityException
     * @expectedExceptionMessage There is no image with provided ID.
     */
    public function testRemoveWithNonExistingImage()
    {
        $productSku = 'testProduct';
        $entryId = 42;
        $productMediaGalleryMock = $this->getMock(
            '\Magento\Eav\Model\Entity\Attribute\Backend\AbstractBackend',
            ['addImage', 'updateImage', 'getRenamedImage', 'clearMediaAttribute', 'setMediaAttribute'],
            [],
            '',
            false
        );
        $attributeMock = $this->getMock('\Magento\Eav\Model\Entity\Attribute\AbstractAttribute', [], [], '', false);
        $this->productRepositoryMock->expects($this->once())->method('get')->with($productSku)
            ->willReturn($this->productMock);
        $this->productMock->expects($this->once())->method('getTypeInstance')->willReturnSelf();
        $this->productMock->expects($this->once())->method('getSetAttributes')->with($this->productMock)
            ->willReturn(['media_gallery' => $attributeMock]);
        $this->entryResolverMock->expects($this->once())->method('getEntryFilePathById')
            ->with($this->productMock, $entryId)->willReturn(null);
        $attributeMock->expects($this->once())->method('getBackend')->willReturn($productMediaGalleryMock);
        $this->model->remove($productSku, $entryId);
    }

    public function testRemove()
    {
        $productSku = 'testProduct';
        $entryId = 42;
        $productMediaGalleryMock = $this->getMock(
            '\Magento\Eav\Model\Entity\Attribute\Backend\AbstractBackend',
            ['removeImage'],
            [],
            '',
            false
        );
        $attributeMock = $this->getMock('\Magento\Eav\Model\Entity\Attribute\AbstractAttribute', [], [], '', false);
        $this->productRepositoryMock->expects($this->once())->method('get')->with($productSku)
            ->willReturn($this->productMock);
        $this->productMock->expects($this->once())->method('getTypeInstance')->willReturnSelf();
        $this->productMock->expects($this->once())->method('getSetAttributes')->with($this->productMock)
            ->willReturn(['media_gallery' => $attributeMock]);
        $this->entryResolverMock->expects($this->once())->method('getEntryFilePathById')
            ->with($this->productMock, $entryId)->willReturn('/path');
        $attributeMock->expects($this->once())->method('getBackend')->willReturn($productMediaGalleryMock);
        $productMediaGalleryMock->expects($this->once())->method('removeImage')->with($this->productMock, '/path');
        $this->productRepositoryMock->expects($this->once())->method('save')->with($this->productMock);
        $this->assertTrue($this->model->remove($productSku, $entryId));
    }

    /**
     * @expectedException \Magento\Framework\Exception\NoSuchEntityException
     * @expectedExceptionMessage Such product doesn't exist
     */
    public function testGetWithNonExistingProduct()
    {
        $productSku = 'testProduct';
        $imageId = 42;
        $this->productRepositoryMock->expects($this->once())->method('get')->with($productSku)
            ->willThrowException(new \Exception());
        $this->model->get($productSku, $imageId);
    }

    /**
     * @expectedException \Magento\Framework\Exception\NoSuchEntityException
     * @expectedExceptionText Such image doesn't exist
     */
    public function testGetWithNonExistingImage()
    {
        $productSku = 'testProduct';
        $imageId = 43;
        $images = [['value_id' => 42, 'types' => [], 'file' => 'file.jpg']];
        $this->productRepositoryMock->expects($this->once())->method('get')->with($productSku)
            ->willReturn($this->productMock);
        $this->productMock->expects($this->once())->method('getMediaAttributes')->willReturn(['code' => 0]);
        $this->productMock->expects($this->once())->method('getData')->with('code')->willReturn('codeValue');
        $this->productMock->expects($this->once())->method('getMediaGallery')->with('images')->willReturn($images);
        $this->model->get($productSku, $imageId);
    }

    public function testGet()
    {
        $productSku = 'testProduct';
        $imageId = 42;
        $images = [['value_id' => 42, 'types' => [], 'file' => 'file.jpg']];
        $this->productRepositoryMock->expects($this->once())->method('get')->with($productSku)
            ->willReturn($this->productMock);
        $this->productMock->expects($this->once())->method('getMediaAttributes')->willReturn(['code' => 0]);
        $this->productMock->expects($this->once())->method('getData')->with('code')->willReturn('codeValue');
        $this->productMock->expects($this->once())->method('getMediaGallery')->with('images')->willReturn($images);
        $entryMock = $this->getMock('\Magento\Catalog\Api\Data\ProductAttributeMediaGalleryEntryInterface');
        $this->dataObjectHelperMock->expects($this->once())->method('populateWithArray')
            ->with($entryMock, $images[0], '\Magento\Catalog\Api\Data\ProductAttributeMediaGalleryEntryInterface')
            ->willReturnSelf();
        $this->entryFactoryMock->expects($this->once())->method('create')->willReturn($entryMock);
        $this->assertEquals($entryMock, $this->model->get($productSku, $imageId));
    }

    public function testGetList()
    {
        $productSku = 'testProductSku';
        $attributeMock = $this->getMock('\Magento\Catalog\Api\Data\ProductAttributeInterface');
        $objectMock = new \Magento\Framework\Object(['attribute' => $attributeMock]);
        $gallery = [[
            'value_id' => 42,
            'label_default' => 'defaultLabel',
            'file' => 'code',
            'disabled_default' => false,
            'position_default' => 1,
        ]];
        $this->productRepositoryMock->expects($this->once())->method('get')->with($productSku)
            ->willReturn($this->productMock);
        $this->attributeRepositoryMock->expects($this->once())->method('get')->with('media_gallery')
            ->willReturn($attributeMock);
        $this->mediaGalleryMock->expects($this->once())->method('loadGallery')->with($this->productMock, $objectMock)
            ->willReturn($gallery);
        $this->productMock->expects($this->once())->method('getMediaAttributes')->willReturn(['code' => 0]);
        $this->productMock->expects($this->once())->method('getData')->with('code')->willReturn('codeValue');
        $entryMock = $this->getMock('\Magento\Catalog\Api\Data\ProductAttributeMediaGalleryEntryInterface');
        $entryMock->expects($this->once())->method('setId')
            ->with($gallery[0]['value_id'])->willReturnSelf();
        $entryMock->expects($this->once())->method('setLabel')
            ->with($gallery[0]['label_default'])->willReturnSelf();
        $entryMock->expects($this->once())->method('setTypes')
            ->with([])->willReturnSelf();
        $entryMock->expects($this->once())->method('setDisabled')
            ->with($gallery[0]['disabled_default'])->willReturnSelf();
        $entryMock->expects($this->once())->method('setPosition')
            ->with($gallery[0]['position_default'])->willReturnSelf();
        $entryMock->expects($this->once())->method('setFile')
            ->with($gallery[0]['file'])->willReturnSelf();
        $this->entryFactoryMock->expects($this->once())->method('create')->willReturn($entryMock);
        $this->assertEquals([$entryMock], $this->model->getList($productSku));
    }
}
