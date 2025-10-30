<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class NavigationTools
{
    public function buildNavigationTree($items, $parentId = null, $level = 0)
    {
        $tree = [];

        foreach ($items as $item) {
            $item = (object) $item;

            if ($item->parent == $parentId) {
                $children = $this->buildNavigationTree($items, $item->_id, $level + 1);

                $treeItem = [
                    'id' => $item->id,
                    '_id' => $item->_id,
                    'label' => $item->label,
                    'path' => $item->path,
                    'sort_order' => $item->sort_order,
                    'visible' => $item->visible
                ];

                if (!empty($children)) {
                    $treeItem['children'] = $children;
                }

                $tree[] = $treeItem;
            }
        }

        usort($tree, function ($a, $b) {
            return $a['sort_order'] <=> $b['sort_order'];
        });

        return $tree;
    }
}
