<?php

namespace app\backend\modules\filtering\models;

use app\common\models\SearchFiltering;

class Filtering extends SearchFiltering
{
    protected $appends = ['filter_num'];

    protected $hidden = [
        'deleted_at',
    ];

    protected $attributes = [
        'is_show' => 0,
        'parent_id' => 0,
    ];


    public function getList($parent_id = 0)
    {
        return self::where('parent_id', $parent_id)->orderBy('id');
    }

    public function getFilterNumAttribute()
    {

        if ($this->parent_id == 0) {
            return Filtering::where('parent_id', $this->id)->count();
        }

    }

      /**
     * @param $id
     * @return mixed
     */
    public static function del($id)
    {
        return self::where('id', $id)
            ->orWhere('parent_id', $id)
            ->delete();
    }


    /**
     * 定义字段名
     * @return [type] [description]
     */
    public function atributeNames() {
        return [
            'name' => '过滤名称',
        ];
    }

    /**
     * 字段规则
     * @return [type] [description]
     */
    public function rules()
    {
        return [
            'name' => 'required|max:50',
        ];
    }
}