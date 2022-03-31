<?php

namespace YiiArticleRepository\models;

use YiiHelper\abstracts\Model;
use Zf\Helper\Util;
use Zf\PhpAnalysis\PhpAnalysis;

/**
 * This is the model class for table "program_article_repository".
 *
 * @property int $id 自增ID
 * @property string $key 分类标识
 * @property string $unique_key 文件标识
 * @property int $sort_order 排序
 * @property string $author 显示的发布者
 * @property string $title 显示名称
 * @property string $description 文章简述
 * @property string $tag 文章标签
 * @property string|null $content 文章内容
 * @property string $created_at 创建时间
 * @property string $updated_at 更新时间
 */
class ArticleRepository extends Model
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'program_article_repository';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['key'], 'required'],
            [['sort_order'], 'integer'],
            [['content'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['key', 'unique_key', 'author', 'title'], 'string', 'max' => 100],
            [['description', 'tag'], 'string', 'max' => 255],
            [['key', 'title'], 'unique', 'targetAttribute' => ['key', 'title']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id'          => '自增ID',
            'key'         => '分类标识',
            'unique_key'  => '文件标识',
            'sort_order'  => '排序',
            'author'      => '显示的发布者',
            'title'       => '显示名称',
            'description' => '文章简述',
            'tag'         => '文章标签',
            'content'     => '文章内容',
            'created_at'  => '创建时间',
            'updated_at'  => '更新时间',
        ];
    }

    /**
     * 保存前检查数据
     *
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        if (empty($this->unique_key)) {
            $this->unique_key = Util::uniqid(); // 界面未传递标志key时，自动创建
        }
        if (empty($this->tag)) {
            $this->tag = PhpAnalysis::getInstance()
                ->start($this->content)
                ->GetFinallyKeywords(3, false);
        }
        return parent::beforeSave($insert);
    }
}
