<?php
/**
 * @link        http://www.phpcorner.net
 * @author      qingbing<780042175@qq.com>
 * @copyright   Chengdu Qb Technology Co., Ltd.
 */

namespace YiiArticleRepository\services\backend;


use YiiArticleRepository\interfaces\backend\IArticleService;
use YiiArticleRepository\models\ArticleRepository;
use YiiHelper\abstracts\Service;
use YiiHelper\helpers\Pager;
use Zf\Helper\Exceptions\BusinessException;

/**
 * 服务: 文章仓库(文章池)管理
 *
 * Class ArticleService
 * @package YiiArticleRepository\services\backend
 */
class ArticleService extends Service implements IArticleService
{
    /**
     * 文章列表
     *
     * @param array|null $params
     * @return array
     */
    public function list(array $params = []): array
    {
        $query = ArticleRepository::find()
            ->orderBy('sort_order ASC');
        // 等于查询
        $this->attributeWhere($query, $params, ['key', 'unique_key']);
        // like 查询
        $this->likeWhere($query, $params, ['title', 'tag']);
        return Pager::getInstance()->pagination($query, $params['pageNo'], $params['pageSize']);
    }

    /**
     * 添加文章
     *
     * @param array $params
     * @return bool
     * @throws \yii\db\Exception
     */
    public function add(array $params): bool
    {
        $model = new ArticleRepository();
        $model->setFilterAttributes($params);
        return $model->saveOrException();
    }

    /**
     * 编辑文章
     *
     * @param array $params
     * @return bool
     * @throws BusinessException
     * @throws \yii\db\Exception
     */
    public function edit(array $params): bool
    {
        $model = $this->getModel($params);
        unset($params['id'], $params['key']);
        $model->setFilterAttributes($params);
        return $model->saveOrException();
    }

    /**
     * 删除文章
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
     * 查看文章详情
     *
     * @param array $params
     * @return mixed|ArticleRepository
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
     * @return ArticleRepository
     * @throws BusinessException
     */
    protected function getModel(array $params): ArticleRepository
    {
        $model = ArticleRepository::findOne([
            'id' => $params['id'] ?? null,
        ]);
        if (null === $model) {
            throw new BusinessException("文章不存在");
        }
        return $model;
    }
}