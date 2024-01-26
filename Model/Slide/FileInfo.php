<?php

declare(strict_types=1);

namespace Magelearn\Slider\Model\Slide;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\File\Mime;
use Magento\Framework\Filesystem;
use Magento\Framework\Filesystem\Directory\WriteInterface;
use Magento\Framework\UrlInterface;

class FileInfo
{
    private const MEDIA_DIRECTORY_PATH = '/slider/images/upload/';

    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @var Mime
     */
    private $mime;

    /**
     * @var UrlInterface
     */
    private $url;

    /**
     * @var WriteInterface
     */
    private $mediaDirectory;

    /**
     * @param Filesystem $filesystem
     * @param Mime $mime
     * @param UrlInterface $url
     */
    public function __construct(
        Filesystem $filesystem,
        Mime $mime,
        UrlInterface $url
    ) {
        $this->filesystem = $filesystem;
        $this->mime = $mime;
        $this->url = $url;
    }

    /**
     * @param string $fileName
     * @return string
     */
    public function getImageUrl($fileName)
    {
        $filePath = $this->getFilePath($fileName);

        return $this->url->getBaseUrl(['_type' => UrlInterface::URL_TYPE_MEDIA]) . ltrim($filePath, '/');
    }

    /**
     * @param string $fileUrl
     * @return string
     */
    public function getImagePathFromUrl($fileUrl)
    {
        $mediaUrl = $this->url->getBaseUrl(['_type' => UrlInterface::URL_TYPE_MEDIA]) . ltrim(self::MEDIA_DIRECTORY_PATH, '/');

        return str_replace($mediaUrl, '', $fileUrl);
    }

    /**
     * @param string $fileName
     * @return string
     */
    public function getMimeType($fileName)
    {
        $filePath = $this->getFilePath($fileName);
        $absoluteFilePath = $this->getMediaDirectory()->getAbsolutePath($filePath);

        return $this->mime->getMimeType($absoluteFilePath);
    }

    /**
     * @param string $fileName
     * @return array
     */
    public function getStat($fileName)
    {
        $filePath = $this->getFilePath($fileName);

        return $this->getMediaDirectory()->stat($filePath);
    }

    /**
     * @param string $fileName
     * @return bool
     */
    public function isExist($fileName)
    {
        $filePath = $this->getFilePath($fileName);

        return $this->getMediaDirectory()->isExist($filePath);
    }

    /**
     * Construct and return file subpath based on filename relative to media directory
     *
     * @param string $fileName
     * @return string
     */
    private function getFilePath($fileName)
    {
        if (!$fileName) {
            return '';
        }

        return self::MEDIA_DIRECTORY_PATH . ltrim($fileName, '/');
    }

    /**
     * @return WriteInterface
     */
    private function getMediaDirectory()
    {
        if ($this->mediaDirectory === null) {
            $this->mediaDirectory = $this->filesystem->getDirectoryWrite(DirectoryList::MEDIA);
        }

        return $this->mediaDirectory;
    }
}
