<?php
/**
 * @link        http://www.phpcorner.net
 * @author      qingbing<780042175@qq.com>
 * @copyright   Chengdu Qb Technology Co., Ltd.
 */

namespace YiiArticleRepository\controllers\backend;


use Exception;
use YiiArticleRepository\interfaces\backend\IArticleService;
use YiiArticleRepository\models\ArticleCategory;
use YiiArticleRepository\models\ArticleRepository;
use YiiArticleRepository\services\backend\ArticleService;
use YiiHelper\abstracts\RestController;

/**
 * 控制器: 文章仓库(文章池)管理
 *
 * Class ArticleController
 * @package YiiArticleRepository\controllers\backend
 *
 * @property-read IArticleService $service
 */
class ArticleController extends RestController
{
    public $serviceInterface = IArticleService::class;
    public $serviceClass     = ArticleService::class;

    /**
     * 文章列表
     *
     * @return array
     * @throws Exception
     */
    public function actionList()
    {
        // 参数验证和获取
        $params = $this->validateParams([
            ['key', 'in', 'label' => '分类', 'range' => array_keys(ArticleCategory::getOptions())],
            ['unique_key', 'string', 'label' => '分类标识'],
            ['title', 'string', 'label' => '文章标题'],
            ['tag', 'string', 'label' => '标签'],
        ]);
        // 业务处理
        $res = $this->service->list($params);
        // 渲染结果
        return $this->success($res, '文章列表');
    }

    /**
     * 添加文章
     *
     * @return array
     * @throws Exception
     */
    public function actionAdd()
    {
        $key = $this->getParam('key');
        // 参数验证和获取
        $params = $this->validateParams([
            [['key', 'title', 'sort_order'], 'required'],
            ['key', 'in', 'label' => '分类', 'range' => array_keys(ArticleCategory::getOptions())],
            ['unique_key', 'unique', 'label' => '分类标识', 'targetClass' => ArticleRepository::class, 'targetAttribute' => 'unique_key'],
            ['title', 'unique', 'label' => '文章标题', 'targetClass' => ArticleRepository::class, 'targetAttribute' => 'title', 'filter' => ['key' => $key]],
            ['sort_order', 'integer', 'label' => '排序'],
            ['author', 'string', 'label' => '发布者'],
            ['description', 'string', 'label' => '描述'],
            ['tag', 'string', 'label' => '标签'],
            ['content', 'string', 'label' => '内容'],
        ]);
        // 业务处理
        $res = $this->service->add($params);
        // 渲染结果
        return $this->success($res, '添加文章成功');
    }

    /**
     * 编辑文章
     *
     * @return array
     * @throws Exception
     */
    public function actionEdit()
    {
        // 数据提前获取
        $id  = $this->getParam('id');
        $key = $this->getParam('key');
        // 参数验证和获取
        $params = $this->validateParams([
            [['id', 'key', 'title'], 'required'],
            ['key', 'string', 'label' => '分类'],
            [
                'id',
                'exist',
                'label'           => 'ID',
                'targetClass'     => ArticleRepository::class,
                'targetAttribute' => 'id',
                'filter'          => ['=', 'key', $key],
            ],
            [
                'title',
                'unique',
                'label'           => '文章标题',
                'targetClass'     => ArticleRepository::class,
                'targetAttribute' => 'title',
                'filter'          => [
                    'and',
                    ['key' => $key],
                    ['!=', 'id', $id],
                ]
            ],
            ['sort_order', 'integer', 'label' => '排序'],
            ['author', 'string', 'label' => '发布者'],
            ['description', 'string', 'label' => '描述'],
            ['tag', 'string', 'label' => '标签'],
            ['content', 'string', 'label' => '内容'],
        ]);
        // 业务处理
        $res = $this->service->edit($params);
        // 渲染结果
        return $this->success($res, '编辑文章成功');
    }

    /**
     * 删除文章
     *
     * @return array
     * @throws Exception
     */
    public function actionDel()
    {
        // 参数验证和获取
        $params = $this->validateParams([
            [['id'], 'required'],
            ['id', 'exist', 'label' => 'ID', 'targetClass' => ArticleRepository::class, 'targetAttribute' => 'id',],
        ]);
        // 业务处理
        $res = $this->service->del($params);
        // 渲染结果
        return $this->success($res, '删除文章成功');
    }

    /**
     * 查看文章详情
     *
     * @return array
     * @throws Exception
     */
    public function actionView()
    {
        // 参数验证和获取
        $params = $this->validateParams([
            [['id'], 'required'],
            ['id', 'exist', 'label' => 'ID', 'targetClass' => ArticleRepository::class, 'targetAttribute' => 'id',],
        ]);
        // 业务处理
        $res = $this->service->view($params);
        // 渲染结果
        return $this->success($res, '查看文章详情');
    }
}