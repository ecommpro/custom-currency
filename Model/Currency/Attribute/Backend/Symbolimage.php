<?php
/**
 * Symbolimage
 *
 * @copyright Copyright © 2017 Ecomm.pro. All rights reserved.
 * @author    dev@ecomm.pro
 */

namespace EcommPro\CustomCurrency\Model\Currency\Attribute\Backend;

use Magento\Eav\Model\Entity\Attribute\Backend\AbstractBackend;
use Magento\Framework\DataObject;
use EcommPro\CustomCurrency\Helper\FileProcessor;

class Symbolimage extends AbstractBackend
{
    /**
     * @var string
     */
    const FILES_SUBDIR = 'symbolimage';

    /**
     * @var FileProcessor
     */
    protected $fileProcessor;

    /**
     * @param FileProcessor $fileProcessor
     */
    public function __construct(FileProcessor $fileProcessor)
    {
        $this->fileProcessor = $fileProcessor;
    }

    /**
     * Prepare File data before saving object
     *
     * @param \Magento\Framework\DataObject $object
     *
     * @return $this
     * @throws \Exception
     */
    public function beforeSave($object) //@codingStandardsIgnoreLine
    {
        parent::beforeSave($object);
        $file = $object->getSymbolimage();
        if (!is_array($file)) {
            $object->setSkipSaveSymbolimage(true);
            return $this;
        }

        if (isset($file['delete'])) {
            $object->setSymbolimage(null);
            return $this;
        }

        $file = reset($file) ?: [];
        if (!isset($file['file'])) {
            throw new LocalizedException(
                __('Symbolimage does not contain field \'file\'')
            );
        }
        // Add file related data to object
        $object->setSymbolimage($file['file']);
        $object->setFileExists(isset($file['exists']));

        return $this;
    }

    /**
     * Save uploaded file and remove temporary file after saving object
     *
     * @param \Magento\Framework\DataObject $object
     *
     * @return $this
     * @throws \Exception
     */
    public function afterSave($object) //@codingStandardsIgnoreLine
    {
        parent::afterSave($object);
        // if file already exists we do not need to save any new file
        if ($object->getFileExists() || $object->getSkipSaveSymbolimage()) {
            return $this;
        }

        // Delete old file if new one has changed
        if ($object->getOrigData('symbolimage') && $object->getSymbolimage() != $object->getOrigData('symbolimage')) {
            $this->fileProcessor->delete($this->getFileSubDir($object), $object->getOrigData('symbolimage'));
        }

        if ($object->getSymbolimage()) {
            if (!$this->fileProcessor->saveFileFromTmp($object->getSymbolimage(), $this->getFileSubDir($object))) {
                throw new \Exception('There was an error saving the file');
            }
        }
    }

    /**
     * Subdir where files are stored
     *
     * @param \Magento\Framework\DataObject $object
     * @return string
     */
    protected function getFileSubDir($object)
    {
        return self::FILES_SUBDIR . '/' . $object->getId();
    }

    /**
     * Delete media file before an symbolimage row in database is removed
     * @param \Magento\Framework\DataObject $object
     * @return $this
     */
    public function beforeDelete($object) //@codingStandardsIgnoreLine
    {
        parent::beforeDelete($object);
        // Delete file from disk before the object is deleted from database
        if ($object->getSymbolimage()) {
            $this->fileProcessor->delete($this->getFileSubDir($object), $object->getSymbolimage());
        }
        return $this;
    }

    /**
     * Get full info from file
     *
     * @param \Magento\Framework\DataObject $object
     * @return DataObject
     */
    public function getFileInfo($object)
    {
        if (!$object->getData('file_info') && $object->getSymbolimage()) {
            $fileInfoObject = new DataObject();
            $fileInfo = $this->fileProcessor->getFileInfo($object->getSymbolimage(), $this->getFileSubDir($object));
            if ($fileInfo) {
                $fileInfoObject->setData($fileInfo);
            }
            $object->setFileInfo($fileInfoObject);
        }

        return $object->getData('file_info');
    }

    /**
     * Return file info in a format valid for ui form fields
     *
     * @param \Magento\Framework\DataObject $object
     * @return array
     */
    public function getFileValueForForm($object)
    {
        if ($this->getFileInfo($object)->getFile()) {
            return [$this->getFileInfo($object)->getData()];
        }
        return [];
    }
}
