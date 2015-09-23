<?php

class FbCategory {

    private $source;
    private $fb;

    function __construct($fb, $source) {
        $this->fb = $fb;
        $this->source = $source;
    }

    static function find($fb, $id) {
        $fb->setMethod('category.get');
        $fb->post(array('category_id' => $id));
        $fb->request();

        return new self($fb, $fb->getResponse());
    }

    static function findByName($fb, $name) {
        $fb->setMethod('category.list');
        $fb->post(array());
        $fb->request();
        $categories = $fb->getResponse();

        foreach ($categories['categories']['category'] as $category) {
            if ($category['name'] == $name) {
                return new self($fb, $category);
            }
        }

        return null;
    }

    function getId(){
        return $this->source['category_id'];
    }

    function getName() {
        return $this->source['name'];
    }

    function getParentId() {
        return $this->source['parent_id'];
    }

}