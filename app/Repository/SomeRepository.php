<?php
/**
 * Ssome Repository
 * @author Vlad Ionov <vlad@f5.com.ru>
 */
namespace App\Repository;

abstract class SomeRepository
{
    /**
     * Get some items
	 * @param array $limit or [$count, $offset]
	 * @param string $sort_by
	 * @param string $sort_type
	 * @return array
     */
	public static function getSomeData($limit = null, $sort_by = null, $sort_type = null)
	{
		$items = [];
		$selected = db()->rawQuery('SELECT t1.id, t1.title, t1.data, t1.created_at
				FROM table1 t1
				WHERE t1.cat != \'test\' AND t1.state IN(\'a\',\'b\',\'c\',\'d\')
				ORDER BY t1.id DESC
				LIMIT '.intval($limit));
        if ($selected) {
			foreach ($selected as $raw) {
				$items[]= (object)$raw;
			}
        }
        return $items;
	}
}
