<?php

declare(strict_types=1);
/**
 * This file is part of MineAdmin.
 *
 * @link     https://www.mineadmin.com
 * @document https://doc.mineadmin.com
 * @contact  root@imoi.cn
 * @license  https://github.com/mineadmin/MineAdmin/blob/master/LICENSE
 */

namespace App\Service\DataCenter;

use App\Repository\DataCenter\AttachmentRepository;
use Hyperf\Collection\Collection;
use Hyperf\Contract\ConfigInterface;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpMessage\Upload\UploadedFile;
use League\Flysystem\FilesystemException;
use Mine\Abstracts\AbstractService;
use Mine\Exception\NormalStatusException;
use Mine\Helper\Str;
use Mine\MineResponse;
use Mine\MineUpload;
use Psr\Http\Message\ResponseInterface;

/**
 * 文件上传业务
 * Class LoginLogService.
 */
class AttachmentService extends AbstractService
{
    /**
     * @var AttachmentRepository
     */
    public $repository;

    #[Inject]
    protected ConfigInterface $config;

    protected MineUpload $mineUpload;

    public function __construct(AttachmentRepository $repository, MineUpload $mineUpload)
    {
        $this->repository = $repository;
        $this->mineUpload = $mineUpload;
    }

    /**
     * 上传文件.
     */
    public function upload(UploadedFile $uploadedFile, array $config = []): array
    {
        try {
            $hash = md5_file($uploadedFile->getPath() . '/' . $uploadedFile->getFilename());
            if ($model = $this->repository->getFileInfoByHash($hash)) {
                return $model->toArray();
            }
        } catch (\Exception $e) {
            throw new NormalStatusException('获取文件Hash失败', 500);
        }
        $data = $this->mineUpload->upload($uploadedFile, $config);
        if ($this->save($data)) {
            return $data;
        }
        return [];
    }

    public function chunkUpload(array $data): array
    {
        if ($model = $this->repository->getFileInfoByHash($data['hash'])) {
            return $model->toArray();
        }
        $result = $this->mineUpload->handleChunkUpload($data);
        if (isset($result['hash'])) {
            $this->save($result);
        }
        return $result;
    }

    /**
     * 获取当前目录下所有文件（包含目录）.
     */
    public function getAllFile(array $params = []): array
    {
        return $this->getArrayToPageList($params);
    }

    /**
     * 保存网络图片.
     * @param array $data ['url', 'path']
     */
    public function saveNetworkImage(array $data): array
    {
        $data = $this->mineUpload->handleSaveNetworkImage($data);
        if (! isset($data['id']) && $this->save($data)) {
            return $data;
        }
        return $data;
    }

    /**
     * 通过hash获取文件信息.
     */
    public function readByHash(string $hash, array $columns = ['*']): mixed
    {
        return $this->repository->getFileInfoByHash($hash, $columns);
    }

    /**
     * @throws FilesystemException
     */
    public function responseFile(string $hash): ResponseInterface
    {
        $model = $this->readByHash($hash, ['url', 'mime_type']);
        if (! $model) {
            throw new NormalStatusException('文件不存在', 500);
        }

        return container()->get(MineResponse::class)->responseImage(
            $this->mineUpload->getFile()->read(
                $this->mineUpload->getStorageMode() === '1'
                    ? str_replace(env('UPLOAD_PATH', 'uploadfile'), '', $model->url)
                    : $model->url
            ),
            $model->mime_type
        );
    }

    /**
     * 数组数据搜索器.
     */
    protected function handleArraySearch(Collection $collect, array $params): Collection
    {
        if ($params['name'] ?? false) {
            $collect = $collect->filter(function ($row) use ($params) {
                return Str::contains($row['name'], $params['name']);
            });
        }

        if ($params['label'] ?? false) {
            $collect = $collect->filter(function ($row) use ($params) {
                return Str::contains($row['label'], $params['label']);
            });
        }
        return $collect;
    }
}