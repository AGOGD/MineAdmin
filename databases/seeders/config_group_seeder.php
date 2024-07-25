<?php
/**
 * MineAdmin is committed to providing solutions for quickly building web applications
 * Please view the LICENSE file that was distributed with this source code,
 * For the full copyright and license information.
 * Thank you very much for using MineAdmin.
 *
 * @Author X.Mo<root@imoi.cn>
 * @Link   https://gitee.com/xmo/MineAdmin
 */

declare(strict_types=1);
/**
 * This file is part of MineAdmin.
 *
 * @link     https://www.mineadmin.com
 * @document https://doc.mineadmin.com
 * @contact  root@imoi.cn
 * @license  https://github.com/mineadmin/MineAdmin/blob/master/LICENSE
 */
use App\Model\Settings\ConfigGroup;
use Hyperf\Database\Seeders\Seeder;

class ConfigGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        ConfigGroup::truncate();
        $data = $this->data();
        foreach ($data as $value) {
            ConfigGroup::create($value);
        }
    }

    /**
     * Database seeds data.
     */
    public function data(): array
    {
        return [
            [
                'name' => '站点配置',
                'code' => 'site_config',
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'name' => '上传配置',
                'code' => 'upload_config',
                'created_by' => 1,
                'updated_by' => 1,
            ],
        ];
    }
}
