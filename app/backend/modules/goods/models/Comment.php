<?php
namespace app\backend\modules\goods\models;

/**
 * Created by PhpStorm.
 * User: yanglei
 * Date: 2017/2/27
 * Time: 下午5:10
 */

class Comment extends \app\common\models\Comment
{
    public $timestamps = false;

    /**
     * @param $pageSize
     * @return mixed
     */
    public static function getComments($pageSize)
    {
        return self::uniacid()
            ->where('comment_id', '0')
            ->with(['goods'=>function($query){
                return $query->select(['id', 'title', 'thumb']);
            }])
            ->orderBy('created_at', 'desc')
            ->paginate($pageSize)
            ->toArray();
    }

    /**
     * @param $id
     * @return mixed
     */
    public static function getComment($id)
    {
        return self::find($id);
    }



    /**
     * @param $comment
     * @return bool
     */
    public static function saveComment($comment)
    {
        return self::insert($comment);
    }

    /**
     * @param $comment_id
     * @return mixed
     */
    public static function getReplysByCommentId($comment_id)
    {
        return self::where('comment_id',$comment_id)
        ->orderBy('created_at', 'asc')
        ->get();
    }

    /**
     * @param $id
     * @return mixed
     */
    public static function daletedComment($id)
    {
        return self::where('id', $id)
            ->delete();
    }

    /**
     *
     * @return mixed
     */
    public function goods()
    {
        return $this->belongsTo('app\backend\modules\goods\models\Goods');
    }

    /**
     *  定义字段名
     * 可使
     * @return array */
    public static function atributeNames() {
        return [
            'goods_id'=> '评论商品',
            'content'=> '评论内容',
        ];
    }

    /**
     * 字段规则
     * @return array */
    public static function rules() {
        return [
            'goods_id'=> 'required',
            'content'=> 'required'
        ];
    }
}