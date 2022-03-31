<?php
/**
 * @link        http://www.phpcorner.net
 * @author      qingbing<780042175@qq.com>
 * @copyright   Chengdu Qb Technology Co., Ltd.
 */

namespace YiiArticleRepository\interfaces\backend;


use YiiHelper\services\interfaces\ICurdService;

/**
 * 接口: 文章分类管理
 *
 * Interface ICategoryService
 * @package YiiArticleRepository\interfaces\backend
 */
interface ICategoryService extends ICurdService
{
    /**
     * 获取文章分类选项
     *
     * @return mixed
     */
    public function options(): array;
}