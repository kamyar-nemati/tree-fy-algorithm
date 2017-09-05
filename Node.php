<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * @name Node
 * 
 * @description The class <b>Node</b> is the necessary data structure required 
 * by the class <b>Tree</b>.
 * 
 * @property int $id To store a unique ID for each node in a tree.
 * @property int $pid To store the parent's ID of the node. No parent: $pid = 0;
 * @property string $name To assign the node a name or a title.
 * @property object $data To store any necessary data.
 * @property array $children To hold sub-nodes as children.
 * 
 * @package tree-fy-algorithm
 * @author Kamyar Nemati <kamyarnemati@gmail.com>
 */
class Node {
    public $id;
    public $pid;
    public $name;
    public $data;
    public $children;

    /**
     * @description Initializes the <b>Node</b> object.
     * 
     * @param int $id
     * @param int $pid
     * @param string $name
     * @param object $data
     */
    public function __construct($id, $pid, $name, $data) {
        $this->id = $id;
        $this->pid = $pid;
        $this->name = $name;
        $this->data = $data;
        $this->children = [];
    }
}
