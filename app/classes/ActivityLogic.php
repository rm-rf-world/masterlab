<?php

/**
 * Created by PhpStorm.
 * User: sven
 * Date: 2017/7/7 0007
 * Time: 下午 3:56
 */

namespace main\app\classes;

use main\app\model\ActivityModel;

/**
 * 活动动态逻辑类
 * Class ActivityLogic
 * @package main\app\classes
 */
class ActivityLogic
{

    /**
     * 格式化活动动态
     * @param $row
     */
    public static function formatActivity(&$row)
    {
        if (empty($row)) {
            return;
        }
        if (!isset($row['time'])) {
            return;
        }
        $row['time_text'] = '';
        $row['time_full'] = '';
        $row['time'] = intval($row['time']);
        if (empty($row['time'])) {
            return;
        }
        $row['time_text'] = format_unix_time($row['time']);
        $row['time_full'] = date('Y-m-d H:i:s', $row['time']);
    }

    /**
     * @param $userId
     * @return array
     */
    public static function getCalendarHeatmap($userId)
    {
        $model = new ActivityModel();
        $sql = " select `date`, count(*) as count from  main_activity where user_id=:user_id AND `date`>=(curdate()-365)  GROUP BY date  ";
        $rows = $model->db->getRows($sql, ['user_id'=>$userId]);
        return $rows;
    }
    /**
     * 获取首页的活动动态
     * @param int $page
     * @param int $pageSize
     * @return array
     */
    public static function filterByIndex($page = 1, $pageSize = 50)
    {
        $conditions = [];
        $start = $pageSize * ($page - 1);
        $appendSql = " 1 Order by id desc  limit $start, " . $pageSize;

        $model = new ActivityModel();
        $fields = " * ";
        $rows = $model->getRows($fields, $conditions, $appendSql);
        foreach ($rows as &$row) {
            self::formatActivity($row);
        }
        $count = $model->getOne('count(*) as cc', $conditions);
        return [$rows, $count];
    }

    /**
     * 用户的活动动态
     * @param int $userId
     * @param int $page
     * @param int $pageSize
     * @return array
     */
    public static function filterByUser($userId = 0, $page = 1, $pageSize = 50)
    {
        $conditions = [];
        if (!empty($userId)) {
            $conditions['user_id'] = $userId;
        }
        $start = $pageSize * ($page - 1);
        $appendSql = " 1 Order by id desc  limit $start, " . $pageSize;

        $model = new ActivityModel();
        $fields = " * ";
        $rows = $model->getRows($fields, $conditions, $appendSql);
        foreach ($rows as &$row) {
            self::formatActivity($row);
        }
        $count = $model->getOne('count(*) as cc', $conditions);
        return [$rows, $count];
    }

    /**
     * 项目的活动动态
     * @param int $projectId
     * @param string $type
     * @param int $page
     * @param int $pageSize
     * @return array
     */
    public static function filterByProject($projectId = 0, $type = '', $page = 1, $pageSize = 50)
    {
        $conditions = [];
        if (!empty($projectId)) {
            $conditions['project_id'] = $projectId;
        }
        if (!empty($type)) {
            $conditions['type'] = $type;
        }
        $start = $pageSize * ($page - 1);
        $appendSql = " Order by id desc  limit $start, " . $pageSize;

        $model = new ActivityModel();
        $fields = " * ";
        $rows = $model->getRows($fields, $conditions, $appendSql);
        foreach ($rows as &$row) {
            self::formatActivity($row);
        }
        $count = $model->getOne('count(*) as cc', $conditions);
        return [$rows, $count];
    }
}
