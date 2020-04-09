<?php
namespace S3images;

use Aws\Result;
use Aws\S3\S3Client;

final class S3images
{
    private $s3Client;
    private $bucketName;
    private $listCache = [];

    public function __construct()
    {
        $this->s3Client = new S3Client([
            'version' => 'latest',
            'region' => AWS_REGION,
        ]);

        $this->bucketName = UPLOADED_IMAGE_BUCKET;
    }

    public function listImages(string $directory = ''): array
    {
        return array_filter(
            $this->listObjectsInPath($directory),
            static function ($object) {
                return $object['type'] === 'image';
            }
        );
    }

    public function listDirectories(string $directory = ''): array
    {
        return array_filter(
            $this->listObjectsInPath($directory),
            static function ($object) {
                return $object['type'] === 'directory';
            }
        );
    }

    public function uploadImage($filePath, $tmpFilePath): void
    {
        $this->s3Client->putObject([
            'Bucket' => $this->bucketName,
            'Key' => $this->openCartPathToBucketPath($filePath),
            'SourceFile' => $tmpFilePath,
        ]);
    }

    public function copyImage($from, $to): void
    {
        $this->s3Client->copyObject([
            'Bucket' => $this->bucketName,
            'CopySource' => $this->bucketName . '/' . $this->openCartPathToBucketPath($from),
            'Key' => $this->openCartPathToBucketPath($to),
        ]);
    }

    public function downloadImageToTmpDir($imagePath): string
    {
        $imagePath = $this->openCartPathToBucketPath($imagePath);

        $extension = pathinfo($imagePath, PATHINFO_EXTENSION);

        $tempFilename = '/tmp/' . uniqid(basename($imagePath) . '_', true) . '.' . $extension;

        $this->s3Client->getObject([
            'Bucket' => $this->bucketName,
            'Key' => $imagePath,
            'SaveAs' => $tempFilename,
        ]);

        return $tempFilename;
    }

    public function hasObject($imagePath): bool
    {
        return $this->s3Client->doesObjectExist($this->bucketName, $this->openCartPathToBucketPath($imagePath));
    }

    public function createFolder(string $path): void
    {
        $path = $this->openCartPathToBucketPath($path);

        $path = substr($path, -1) === '/' ? $path : $path . '/';

        $this->s3Client->putObject([
            'Bucket' => $this->bucketName,
            'Key' => $path,
        ]);
    }

    public function deleteObject($path): void
    {
        $this->s3Client->deleteMatchingObjects($this->bucketName, $this->openCartPathToBucketPath($path));
    }

    public function generateSignedUploadUrl($path): string
    {
        $command = $this->s3Client->getCommand('putObject', [
            'Bucket' => $this->bucketName,
            'Key' => $this->openCartPathToBucketPath($path),
            'ACL' => 'public-read',
        ]);

        return (string)$this->s3Client->createPresignedRequest($command, '+1 minutes')->getUri();
    }

    private function listObjectsInPath($prefix)
    {
        $prefix = $this->openCartPathToBucketPath($prefix ?: '/');

        if (array_key_exists($prefix, $this->listCache)) {
            return $this->listCache[$prefix];
        }

        $this->listCache[$prefix] = [];

        $results = $this->s3Client->getPaginator('ListObjectsV2', [
            'Bucket' => $this->bucketName,
            'Prefix' => $prefix,
            'Delimiter' => '/',
        ]);

        $results->each($this->processResults($prefix))->wait();

        return $this->listCache[$prefix];
    }

    /**
     * Bit of a quick fix to help with caching
     * @param $prefix
     * @return array
     */
    public function listObjectsInPathRecursive($prefix)
    {

        $prefix = $this->openCartPathToBucketPath($prefix ?: '/');

        $results = $this->s3Client->getPaginator('ListObjectsV2', [
            'Bucket' => $this->bucketName,
            'Prefix' => $prefix,
        ]);

        $items = [];

        $results->each(function (Result $result) use (&$items) {
            foreach ($result['Contents'] as $image) {
                $items[$this->bucketPathToOpenCartPath($image['Key'])] = HTTPS_CATALOG . $image['Key'];
            }
        })->wait();

        return $items;
    }

    private function openCartPathToBucketPath($imagePath): string
    {
        if (strpos($imagePath, DIR_IMAGE) !== 0) {
            $imagePath = DIR_IMAGE . $imagePath;
        }

        return $imagePath;
    }

    private function bucketPathToOpenCartPath($bucketPath): string
    {
        if (strpos($bucketPath, DIR_IMAGE) === 0) {
            $bucketPath = substr_replace($bucketPath, '', 0, strlen(DIR_IMAGE));
        }

        return rtrim($bucketPath, '/');
    }

    /**
     * @param $prefix
     * @return \Closure
     */
    private function processResults($prefix): \Closure
    {
        return function (Result $result) use ($prefix) {
            if (!empty($result['Contents'])) {
                foreach ($result['Contents'] as $image) {
                    if (!in_array(
                        strtolower(pathinfo($image['Key'], PATHINFO_EXTENSION)),
                        ['jpg', 'jpeg', 'png', 'gif']
                    )) {
                        continue;
                    }
                    $this->listCache[$prefix][] = [
                        'name' => basename($image['Key']),
                        'type' => 'image',
                        'path' => $this->bucketPathToOpenCartPath($image['Key']),
                        'href' => HTTPS_CATALOG . $image['Key']
                    ];
                }
            }

            if (!empty($result['CommonPrefixes'])) {
                foreach ($result['CommonPrefixes'] as $remoteDirectory) {
                    $this->listCache[$prefix][] = [
                        'name' => basename($remoteDirectory['Prefix']),
                        'type' => 'directory',
                        'path' => $this->bucketPathToOpenCartPath($remoteDirectory['Prefix']),
                        'href' => null,
                    ];
                }
            }

            return true;
        };
    }
}
