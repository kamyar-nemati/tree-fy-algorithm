<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Node {
    public $id;
    public $pid;
    public $name;
    public $data;
    public $children;

    public function __construct($id, $pid, $name, $data) {
        $this->id = $id;
        $this->pid = $pid;
        $this->name = $name;
        $this->data = $data;
        $this->children = [];
    }
}
