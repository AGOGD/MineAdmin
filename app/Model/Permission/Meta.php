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

namespace App\Model\Permission;

use Hyperf\DbConnection\Model\Model;

/**
 * @property string $title 标题
 * @property string $i18n 国际化
 * @property string $badge 徽章
 * @property string $icon 图标
 * @property bool $affix 是否固定
 * @property bool $hidden 是否隐藏
 * @property string $type 类型
 * @property bool $cache 是否缓存
 * @property bool $copyright 是否
 * @property string $link 链接
 */
final class Meta extends Model
{
    public bool $incrementing = false;

    protected array $fillable = ['title', 'i18n', 'badge', 'icon', 'affix', 'hidden', 'type', 'cache', 'copyright', 'breadcrumbEnable', 'componentPath', 'componentSuffix', 'link'];

    protected array $casts = [
        'affix' => 'boolean',
        'hidden' => 'boolean',
        'cache' => 'boolean',
        'copyright' => 'boolean',
        'breadcrumbEnable' => 'boolean',
        'title' => 'string',
        'componentPath' => 'string',
        'componentSuffix' => 'string',
        'i18n' => 'string',
        'badge' => 'string',
        'icon' => 'string',
        'type' => 'string',
        'link' => 'string',
    ];
}