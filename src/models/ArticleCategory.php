<?php

namespace YiiArticleRepository\models;

use Yii;
use YiiHelper\abstracts\Model;
use Zf\Helper\Exceptions\BusinessException;

/**
 * This is the model class for table "program_article_category".
 *
 * @property string $key 分类标识
 * @property string $name 分类名称
 * @property int $is_enable 启用状态
 * @property string $description 分类描述
 * @property int $sort_order 排序
 * @property string $created_at 创建时间
 * @property string $updated_at 更新时间
 *
 * @property-read int $articleCount 分类下拥有文章数量
 */
class ArticleCategory extends Model
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'program_article_category';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['key', 'name'], 'required'],
            [['is_enable', 'sort_order'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['key', 'name'], 'string', 'max' => 100],
            [['description'], 'string', 'max' => 255],
            [['name'], 'unique'],
            [['key'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'key'         => '分类标识',
            'name'        => '分类名称',
            'is_enable'   => '启用状态',
            'description' => '分类描述',
            'sort_order'  => '排序',
            'created_at'  => '创建时间',
            'updated_at'  => '更新时间',
        ];
    }

    /**
     * 查询分类时将分类文章数量一起输出
     *
     * @return array|false
     */
    public function fields()
    {
        return array_merge(parent::fields(), [
            'articleCount',
        ]);
    }

    /**
     * 获取用户的文章数量
     *
     * @return int|string|null
     */
    public function getArticleCount()
    {
        return $this->hasMany(ArticleRepository::class, [
            'key' => 'key'
        ])->count();
    }

    /**
     * 保存模型前，删除缓存中的类型选项
     *
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        Yii::$app->cache->delete(self::CACHE_KEY_OPTION);
        return parent::beforeSave($insert);
    }

    /**
     * 删除文章分类前，需要先检查分类下拥有的文章是否清空
     *
     * @return bool
     * @throws BusinessException
     */
    public function beforeDelete()
    {
        if ($this->articleCount > 0) {
            throw new BusinessException("该分类下还有文章，不能删除");
        }
        return parent::beforeDelete(); // TODO: Change the autogenerated stub
    }

    // 选项缓存KEY
    const CACHE_KEY_OPTION = "article-repository:category:options";

    /**
     * 获取分类成为选项卡，做1h的缓存
     *
     * @return mixed
     */
    public static function getOptions()
    {
        return Yii::$app->cache->getOrSet(self::CACHE_KEY_OPTION, function () {
            $res = self::find()
                ->select([
                    'key',
                    'name',
                ])
                ->andWhere(['=', 'is_enable', IS_ENABLE_YES])
                ->orderBy('sort_order ASC')
                ->asArray()
                ->indexBy('key')
                ->all();
            return array_combine(array_keys($res), array_column($res, 'name'));
        }, 3600);
    }
}