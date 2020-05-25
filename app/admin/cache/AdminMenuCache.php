<?php
/*
 * @Description  : 菜单缓存
 * @Author       : skyselang 215817969@qq.com
 * @Date         : 2020-05-06
 */

namespace app\admin\cache;

use think\facade\Db;
use think\facade\Cache;

class AdminMenuCache
{
    /**
     * 缓存key
     *
     * @param integer $admin_menu_id 菜单id
     * @return string
     */
    public static function key($admin_menu_id = 0)
    {
        $key = 'adminMenu:' . $admin_menu_id;

        return $key;
    }

    /**
     * 缓存有效时间
     *
     * @param integer $expire 有效时间
     * @return integer
     */
    public static function exp($expire = 0)
    {
        if (empty($expire)) {
            $expire = 30 * 24 * 60 * 60;
        }

        return $expire;
    }

    /**
     * 缓存设置
     *
     * @param integer $admin_menu_id 菜单id
     * @return array
     */
    public static function set($admin_menu_id = 0)
    {
        if (empty($admin_menu_id)) {
            $admin_menu = Db::name('admin_menu')
                ->where('is_delete', 0)
                ->select()
                ->toArray();
        } else {
            $admin_menu = Db::name('admin_menu')
                ->where('admin_menu_id', $admin_menu_id)
                ->where('is_delete', 0)
                ->find();
        }

        $key = self::key($admin_menu_id);
        $val = $admin_menu;
        $exp = self::exp();
        Cache::set($key, $val, $exp);

        return $admin_menu;
    }

    /**
     * 缓存获取
     *
     * @param integer $admin_menu_id 菜单id
     * @return array
     */
    public static function get($admin_menu_id = 0)
    {
        $key = self::key($admin_menu_id);
        $admin_menu =  Cache::get($key);
        if (empty($admin_menu)) {
            $admin_menu = self::set($admin_menu_id);
        }

        return $admin_menu;
    }

    /**
     * 缓存删除
     *
     * @param integer $admin_menu_id 菜单id
     * @return bool
     */
    public static function del($admin_menu_id = 0)
    {
        $key = self::key($admin_menu_id);
        $res = Cache::delete($key);

        return $res;
    }
}