<?php
/**
 * Created by PhpStorm.
 * Author: 芸众商城 www.yunzshop.com
 * Date: 28/02/2017
 * Time: 17:00
 */

namespace app\common\models;


use Carbon\Carbon;
use Cache;

class Setting extends BaseModel
{
    public $table = 'yz_setting';

    public $timestamps = false;

    public $guarded = [''];

    public $defaultGroup = 'shop';

    /**
     * 获取统一账号配置与值
     *
     * @param $uniqueAccountId
     * @param $key
     * @param null $default 默认值
     * @return mixed
     */
    public function getValue($uniqueAccountId, $key, $default = null)
    {
        $cacheKey = 'setting.' . $uniqueAccountId . '.' . $key;
        $value = Cache::get($cacheKey);

        if ($value == null) {
            list($group, $item) = $this->parseKey($key);

            $value = array_get($this->getItems($uniqueAccountId, $group), $item, $default);

            Cache::put($cacheKey, $value,Carbon::now()->addSeconds(3600));
        }
        return $value;

    }

    /**
     * 设置配置值.
     *
     * @param $uniqueAccountId
     * @param  string $key 键 使用.隔开 第一位为group
     * @param  mixed $value 值
     *
     * @return void
     */
    public function setValue($uniqueAccountId, $key, $value = null)
    {
        list($group, $item) = $this->parseKey($key);

        $type = $this->getTypeOfValue($value);

        $result = $this->setToDatabase($value, $uniqueAccountId, $group, $item, $type);

        $cacheKey = 'setting.' . $uniqueAccountId . '.' . $key;
        if($type == 'array'){
            $value = unserialize($value);
        }

        Cache::put($cacheKey, $value,Carbon::now()->addSeconds(3600));
        return $result;
    }


    /**
     * 获取账号内当前组的所有配置信息
     *
     * @param $uniqueAccountId
     * @param $group
     * @return array
     */
    public function getItems($uniqueAccountId, $group)
    {
        $items = array();
        foreach (self::fetchSettings($uniqueAccountId, $group) as $item) {
            switch (strtolower($item->type)) {
                case 'string':
                    $items[$item->key] = (string)$item->value;
                    break;
                case 'integer':
                    $items[$item->key] = (integer)$item->value;
                    break;
                case 'double':
                    $items[$item->key] = (double)$item->value;
                    break;
                case 'boolean':
                    $items[$item->key] = (boolean)$item->value;
                    break;
                case 'array':
                    $items[$item->key] = unserialize($item->value);
                    break;
                case 'null':
                    $items[$item->key] = null;
                    break;
                default:
                    $items[$item->key] = $item->value;
            }
        }
        return $items;
    }

    /**
     * 检测是否存在相应配置组
     *
     * @param $uniqueAccountId
     * @param $group
     * @return bool
     */
    public function exists($uniqueAccountId, $group)
    {
        return !$this->fetchSettings($uniqueAccountId, $group)->isEmpty();
    }

    /**
     * 获取配置组数据
     *
     * @param $uniqueAccountId
     * @param $group
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function fetchSettings($uniqueAccountId, $group)
    {
        $cacheKey = 'setting.' . $uniqueAccountId . '.' . $group;
        $value = Cache::get($cacheKey);
        if ($value == null) {
        $value = self::where('group', $group)->where('uniacid', $uniqueAccountId)->get();
          Cache::put($cacheKey, $value,Carbon::now()->addSeconds(3600));
        }
        return $value;
    }


    /**
     * 解析key
     * 分离出 group 与真正的key,其中.分隔第一个为group
     *
     * @param $key
     * @return array
     */
    protected function parseKey($key)
    {
        $explodedOnGroup = explode('.', $key);
        if (count($explodedOnGroup) > 1) {
            $group = array_shift($explodedOnGroup);
            $item = implode('.', $explodedOnGroup);
        } else {
            $group = $this->defaultGroup;
            $item = $explodedOnGroup[0];
        }

        return [$group, $item];
    }

    /**
     * 获取值的类型 并设置值为序列化
     *
     * @param $value
     * @return null|string
     */
    protected function getTypeOfValue(&$value)
    {
        $type = null;
        $givenType = strtolower(gettype($value));

        switch ($givenType) {
            case 'string':
            case 'integer':
            case 'double':
            case 'boolean':
            case 'null':
                $type = $givenType;
                break;
            case 'array':
                $value = serialize($value);
                $type = 'array';
                break;
            default:
                $type = null;
        }
        return $type;
    }

    /**
     * 格式化并保存配置到数据库
     * @param $value
     * @param $uniqueAccountId
     * @param $group
     * @param $key
     * @param $type
     * @return static
     */
    protected function setToDatabase($value, $uniqueAccountId, $group, $key, $type)
    {

        //检测数组是否需要特殊操作
        $arrayHandling = false;
        $keyExploded = explode('.', $key);
        if (count($keyExploded) > 1) {
            $arrayHandling = true;
            $key = array_shift($keyExploded);
            if ($type == 'array') {
                $value = unserialize($value);
            }
        }

        //如果存在记录则更新
        $model = static::where('key', $key)
            ->where('group', $group)
            ->where('uniacid', $uniqueAccountId);
        $model = $model->first();


        if (is_null($model)) {

            //如果数组需要特殊操作
            if ($arrayHandling) {
                $array = array();
                self::buildArrayPath($keyExploded, $value, $array);
                $value = serialize($array);
                $type = 'array';
            }

            $data = [
                'uniacid' => $uniqueAccountId,
                'group' => $group,
                'key' => $key,
                'value' => $value,
                'type' => $type,
            ];

            return self::create($data);

        } else {

            //Check if we need to do special array handling
            if ($arrayHandling) { // we are setting a subset of an array
                $array = array();
                self::buildArrayPath($keyExploded, $value, $array);

                //如果是数组则合并
                if ($model->type == 'array') {
                    $array = array_replace_recursive(unserialize($model->value), $array);
                }
                $value = serialize($array);

                $type = 'array';
            }

            $model->value = $value;
            $model->type = $type;
            return $model->save();
        }
    }

    /**
     * 组合数组
     *
     * @param $map
     * @param $value
     * @param $array
     */
    protected static function buildArrayPath($map, $value, &$array)
    {
        $key = array_shift($map);
        if (count($map) !== 0) {
            $array[$key] = array();
            self::buildArrayPath($map, $value, $array[$key]);
        } else {
            $array[$key] = $value;
        }
    }

}