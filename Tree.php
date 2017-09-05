<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

// Include the Node class
require_once dirname(__FILE__) . '/Node.php';

/**
 * @name Tree
 * 
 * @description This class is responsible to form a hierarchical data structure 
 * of nodes. In order to do so, the class <b>Tree</b> expects an array of 
 * <b>Nodes</b> to be passed in through its constructor. Then, the array is 
 * processed and each node is placed under its parent according to the 
 * properties <b>$id</b> and <b>$pid</b>. The algorithm is more or less like 
 * <i>heapifying</i> in <i>heap sort</i>. Sub nodes are accessible through the 
 * property <b>$children</b>.
 * 
 * @package tree-fy-algorithm
 * @author Kamyar Nemati <kamyarnemati@gmail.com>
 */
class Tree {
    private $nodes;
    
    /**
     * @description Initializes the <b>Tree</b> object.
     * 
     * @param array $nodes An array of <b>Node</b> objects.
     */
    public function __construct(&$nodes) {
        $this->nodes = $nodes;
    }
    
    /**
     * @description This function processes the array of nodes and transforms 
     * it into a hierarchical structure.
     * 
     * @param int $count Returns the number of nodes in the array.
     * 
     * @return array An array that contains root nodes.
     */
    public function generateTree(&$count = 0) {
        // Checking for the integrity of nodes
        $this->normalizeNodes($this->nodes);
        
        // Process the nodes by putting nodes under their parents
        $this->processNodes($this->nodes, $count);
        
        // Return the final tree
        return $this->nodes;
    }
    
    /*
     * This function makes sure nodes' dependency is not broken.
     * The relation between ID & PID is ckecked.
     */
    private function normalizeNodes(&$nodes) {
        // Checking nodes one by one
        foreach($nodes as &$node) {
            // Nodes with no parent (root-nodes) are skipped
            if($node->pid == 0) {
                continue;
            }
            // Nodes with parent are yet to be verified
            $ok = FALSE;
            foreach($nodes as $ptr) {
                // Find the parent of the node
                if($node->pid == $ptr->id) {
                    // Parent verified!
                    $ok = TRUE;
                    break;
                }
            }
            // If parent could not be found, set it as root-node
            if(!$ok) {
                $node->pid = 0;
            }
        }
    }
    
    /*
     * This function processes the node array and set nodes under their 
     * corresponding parents.
     */
    private function processNodes(&$nodes, &$total_count) {
        // Dividing nodes into nodes with and without parent
        $dataRoot = array();
        $dataSub = array();
        
        // Setting nodes into the proper category
        for ($i = 0; $i < count($nodes); ++$i) {
            if ($nodes[$i]->pid == 0) {
                // Node without parent
                array_push($dataRoot, $nodes[$i]);
            } else {
                // Node with parent
                array_push($dataSub, $nodes[$i]);
            }
            // Track the number of nodes
            ++$total_count;
        }
        
        // The process of tree-fy-ing starts here
        $i = 0;
        while (count($dataSub) > 0) {
            // Put each node under its parent
            $this->treefy($dataRoot, $dataSub[$i]);
            // The sub-nodes list has to be cleaned on each successful placement
            // Successful node placement leaves a NULL index at sub-node list
            if($this->cleanArray($dataSub)) { // Cleaning the NULL index
                // Going through the sub-node list from the beginning
                $i = 0;
            } else {
                // Check the next node if there's not a successful placement
                ++$i;
            }
            /*
             * The statement above caters the scenario when the parent of the 
             * node can not be yet found in the tree that is being formed.
             */
        }
        
        // Return the final tree
        $nodes = $dataRoot;
    }
    
    private function cleanArray(&$array) {
        // Checking sub-node list
        foreach ($array as $key => &$val) {
            // NULL index found: implies successful node placement
            if($val == NULL) {
                // Remove the NULL index
                unset($array[$key]);
                // Rearrange the sub-node list
                $array = array_values($array);
                return TRUE;
            }
        }
        // No NULL index found: implies no successful node placement
        return FALSE;
    }
    
    /*
     * This function traverses through the tree and put the node under its 
     * parent according to the PID.
     * 
     * This is the tree-fy-ing algorithm
     */
    private function treefy(&$dataRoot, &$subItem) {
        // Do the travers for each root node
        for ($i = 0; $i < count($dataRoot); ++$i) {
            // Go through children first: More like the depth first search
            if (count($dataRoot[$i]->children) != 0) {
                $dataRoot[$i]->children = $this->treefy($dataRoot[$i]->children, $subItem);
            }
            
            // Set the node once its parent found
            if (!is_null($subItem)) {
                // Checking parent's ID against the node's ID
                if ($subItem->pid == $dataRoot[$i]->id) {
                    // Setting the node under its parent
                    array_push($dataRoot[$i]->children, $subItem);
                    
                    // Removing the child node from the sub-node list
                    $subItem = NULL;
                    
                    // Return the curent state of the tree
                    return $dataRoot;
                }
            }
        }
        return $dataRoot;
    }
}