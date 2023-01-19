<?php

require('Where.php');

class Table
{
    public $collection;
    public $table;
    public $mapped;
    public $limit;
    public $sort;
    public $only;

    public function __construct($table)
    {
        $this->table = $table;
        $this->limit = Session::get("limit");
        $this->sort  = Session::get("sort");
        $this->only  = Session::get("only");

        ob_start();
        require_once "database/collections/".$table.'.php';
        $raw = ob_get_clean();
        $this->collection = json_decode($raw)->$table;
    }

    /**
     * Where selector
     * @param key
     * @param value
     * @return mixed
     */
    public function where($key, $val)
    {
        return new Where($this->collection, $key, $val);
    }

    /**
     * Get all collection
     * @return mixed
     */
    public function all()
    {
        if (empty($this->collection)) {
            return [];
        }

        $filterCollection = [];

        // If only (?only=title,category)
        $filterCollection = !empty($filterCollection)?$filterCollection:$this->collection;
        if (!empty($this->only)) {
            $keys = $this->only;
            $filterCollection = $this->getKeys($keys, $filterCollection);
        }

        // If no "sort" query, return array
        $filterCollection = !empty($filterCollection)?$filterCollection:$this->collection;
        if (empty($this->sort)){
            return $filterCollection;
        }

        // If sort by ASC  (?sort=ASC)
        if ($this->sort == 'ASC') {

            function sortByASC($a, $b)
            {
                return $b->_updated - $a->_updated;
            }
            usort($filterCollection, "sortByASC");
        }

        if ($this->sort == 'DESC') {

            // Default sort by DESC
            function sortByDESC($a, $b)
            {
                return $a->_updated - $b->_updated;
            }
            usort($filterCollection, "sortByDESC");
        }

        // If limit collection (?limit=)
        if (!empty($this->limit)) {
            $filterCollection = array_slice($filterCollection, 0, $this->limit);
        }

        return $filterCollection;

    }

    /**
     * Get collection
     * @return mixed
     */
    public function get()
    {
        return $this->collection;
    }

    /**
     * Save collection
     * @param data
     * @return mixed
     */
    public function save($data)
    {
        if (empty($data)) {
            return Helper::response(400, "Please provide data.", null);
        }
        $id = $data["_id"];
        if (!empty($id)) {
            return $this->update($id, $data);
        } else {
            return $this->update($this->uuid(), $data);
        }
    }

    /**
     * Update collection
     * @param id
     * @param data
     * @return mixed
     */
    public function update($id, $data)
    {
        if ($this->isIdExit($id, $this->collection) == true) {
            $this->mapped = array_map(function ($item) use ($id, $data) {
                if ($item->_id == $id) {
                    $data["_updated"] = time();
                    return $data;
                }
                return $item;
            }, $this->collection);
        }


        if ($this->isIdExit($id, $this->collection) == false) {
            $data["_id"] = $id;
            $data["_created"] = time();
            $data["_updated"] = time();
            array_push($this->collection, $data);
        }

        $content = !empty($this->mapped)?$this->mapped:$this->collection;

        $this->appendInCollection($content);

        return $data;
    }

    /**
     * Append in database collection
     * @param array
     * @return void
     */
    public function appendInCollection($data)
    {
        $collname = $this->table;
        $newData->$collname = $data;

        // Append array
        $head = "<?php
        if(!defined('sim-rest')){ exit;}
        header('Content-Type: application/json');
?>";

        $append = $head."\n".json_encode($newData, JSON_PRETTY_PRINT);

        file_put_contents("database/collections/$this->table.php", $append);
    }

    /**
     * Generate unique id
     */
    public function uuid()
    {
        return strtoupper(bin2hex(openssl_random_pseudo_bytes(16)));
    }

    /**
     * Filter by array object keys
     */
    public function getKeys($keys, $collections)
    {
        $keysArray = strpos($keys, ",") !== false?explode(",", $keys):[$keys];
        $filtered  = [];
        foreach ($collections as $col) {
            $newObj = new stdClass();
            foreach ($col as $key => $val) {
                if (in_array($key, $keysArray) || $key === "_id" || $key === "_updated" || $key === "_created") {
                    $newObj->$key = $val;
                }
            }

            array_push($filtered, $newObj);
        }
        return $filtered;
    }

    /**
     * If id Exit in array
     * @param id
     * @param content
     * @return Boolean
     */
    public function isIdExit($id, $content)
    {
        $data = array_filter($content, function ($item) use ($id) {
            if ($item->_id == $id) {
                return true;
            }
            return false;
        });

        if (!empty($data)) {
            return true;
        }

        return false;
    }

    /**
     * @param id
     * @return mixed
     */
    public function delete($id)
    {
        $filtered = [];
        foreach ($this->collection as $item) {
            if ($item->_id !== $id) {
                array_push($filtered, $item);
            }
        }

        $this->appendInCollection($filtered);

        if (count($this->collection) !== count($filtered)) {
            return Helper::response(200, "deleted.", null);
        } else {
            return Helper::response(400, "failed to delete.", null);
        }
    }
}
