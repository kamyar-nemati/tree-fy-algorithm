<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once dirname(__FILE__) . '/Node.php';

class Tree {
    private $nodes;
    
    public function __construct(&$nodes) {
        $this->nodes = $nodes;
    }
    
    public function generateTree(&$count = 0) {
        $this->taskNormaliseToMakeHierarchy($this->nodes);
        $this->taskMakeHierarchy($this->nodes, $count);
        return $this->nodes;
    }
    
    private function taskNormaliseToMakeHierarchy(&$nodes) {
        foreach($nodes as &$node) {
            if($node->pid == 0) {
                continue;
            }
            $ok = FALSE;
            foreach($nodes as $ptr) {
                if($node->pid == $ptr->id) {
                    $ok = TRUE;
                    break;
                }
            }
            if(!$ok) {
                $node->pid = 0;
            }
        }
    }
    
    private function taskMakeHierarchy(&$nodes, &$total_count) {
        $dataRoot = array();
        $dataSub = array();
        for ($i = 0; $i < count($nodes); ++$i) {
            if ($nodes[$i]->pid == 0) {
                array_push($dataRoot, $nodes[$i]);
            } else {
                array_push($dataSub, $nodes[$i]);
            }
            ++$total_count;
        }
        $i = 0;
        while (count($dataSub) > 0) {
            $this->taskMakeHierarchyRecursive($dataRoot, $dataSub[$i]);
            if($this->cleanArray($dataSub)) {
                $i = 0;
            } else {
                ++$i;
            }
        }
        $nodes = $dataRoot;
    }
    
    private function cleanArray(&$array) {
        foreach ($array as $key => &$val) {
            if($val == NULL) {
                unset($array[$key]);
                $array = array_values($array);
                return TRUE;
            }
        }
        return FALSE;
    }
    
    private function taskMakeHierarchyRecursive(&$dataRoot, &$subItem) {
        for ($i = 0; $i < count($dataRoot); ++$i) {
            if (count($dataRoot[$i]->children) != 0) {
                $dataRoot[$i]->children = $this->taskMakeHierarchyRecursive($dataRoot[$i]->children, $subItem);
            }
            if (!is_null($subItem)) {
                if ($subItem->pid == $dataRoot[$i]->id) {
                    array_push($dataRoot[$i]->children, $subItem);
                    $subItem = NULL;
                    return $dataRoot;
                }
            }
        }
        return $dataRoot;
    }
}