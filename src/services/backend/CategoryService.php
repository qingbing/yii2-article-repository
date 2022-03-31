<?php
/**
 * @link        http://www.phpcorner.net
 * @author      qingbing<780042175@qq.com>
 * @copyright   Chengdu Qb Technology Co., Ltd.
 */

namespace YiiArticleRepository\services\backend;


use YiiArticleRepository\interfaces\backend\ICategoryService;
use YiiArticleRepository\models\ArticleCategory;
use YiiHelper\abstracts\Service;
use YiiHelper\helpers\Pager;
use Zf\Helper\Exceptions\BusinessException;

/**
 * 服务: 文章分类分类管理
 *
 * Class CategoryService
 * @package YiiArticleRepository\services\backend
 */
class CategoryService extends Service implements ICategoryService
{
    /**
     * 获取文章分类选项
     *
     * @return array
     */
    public function options(): array
    {
        return ArticleCategory::getOptions();
    }

    /**
     * 文章分类列表
     *
     * @param array|null $params
     * @return array
     */
    public function list(array $params = []): array
    {
        $query = ArticleCategory::find()
            ->orderBy('sort_order ASC');
        // 等于查询
        $this->attributeWhere($query, $params, ['is_enable']);
        // like 查询
        $this->likeWhere($query, $params, ['key', 'name']);
        return Pager::getInstance()->pagination($query, $params['pageNo'], $params['pageSize']);
    }

    /**
     * 添加文章分类
     *
     * @param array $params
     * @return bool
     * @throws \yii\db\Exception
     */
    public function add(array $params): bool
    {
        $model = new ArticleCategory();
        $model->setFilterAttributes($params);
        return $model->saveOrException();
    }

    /**
     * 编辑文章分类
     *
     * @param array $params
     * @return bool
     * @throws BusinessException
     * @throws \yii\db\Exception
     */
    public function edit(array $params): bool
    {
        $model = $this->getModel($params);
        unset($params['key']);
        $model->setFilterAttributes($params);
        return $model->saveOrException();
    }

    /**
     * 删除文章分类
     *
     * @param array $params
     * @return bool
     * @throws BusinessException
     * @throws \yii\db\StaleObjectException
     */
    public function del(array $params): bool
    {
        return $this->getModel($params)->delete();
    }

    /**
     * 查看文章分类详情
     *
     * @param array $params
     * @return mixed|ArticleCategory
     * @throws BusinessException
     */
    public function view(array $params)
    {
        return $this->getModel($params);
    }

    /**
     * 获取当前操作模型
     *
     * @param array $params
     * @return ArticleCategory
     * @throws BusinessException
     */
    protected function getModel(array $params): ArticleCategory
    {
        $model = ArticleCategory::findOne([
            'key' => $params['key'] ?? null
        ]);
        if (null === $model) {
            throw new BusinessException("分类不存在");
        }
        return $model;
    }
}