<?php

/**
 * Database Class
 * 
 * Main methods to manipulate data on database. 
 */
class Db {

    const ASC = 'ASC';
    const DESC = 'DESC';

    /**
     * Database Object
     * @var PDO 
     */
    private $_dbh;

    public function __construct($driver, $dbname, $host, $user, $pass) {
        $this->_dbh = new PDO(sprintf('%s:dbname=%s;host=%s', $driver, $dbname, $host), $user, $pass);
    }

    /**
     * Adds new row
     * 
     * @param array $params
     * @return boolean
     */
    public function join($client, $ip, $mount, $agent, $referrer, $server, $port) {
        $stmt = $this->_dbh->prepare("INSERT into icecast (icecast_id, ip, mount, agent, referrer, server, port, datetime_start) VALUES (?,'?','?','?','?','?',?, NOW())");
        return $stmt->execute(array($client, $ip, $mount, $agent, $referrer, $server, $port));
    }

    /**
     * Updates row
     * 
     * @param array $params
     * @return boolean 
     */
    public function leave($client, $duration, $sent) {
        $stmt = $this->_dbh->prepare("UPDATE `icecast` SET duration = '?', sent_bytes = '?', datetime_end = NOW() WHERE icecast_id = '?' ORDER BY id DESC LIMIT 1");
        return $stmt->execute(array($duration, $sent, $client));
    }

    /**
     * Returns first start
     * 
     * @return string 
     */
    public function first_start() {
        $first = $this->_dbh->query('SELECT datetime_start as start FROM `icecast` ORDER BY id LIMIT 1')->fetch();
        $start = ($first) ? $first['start'] : '00-00-0000 00:00:00';
        return $start;
    }

    /**
     * Returns last record
     * 
     * @return mixed 
     */
    public function last() {
        $last = $this->fetch(1, Db::DESC);
        return ($last) ? array_shift($last) : array();
    }

    /**
     * Returns last record
     * 
     * @return mixed 
     */
    public function first() {
        $first = $this->fetch(1);
        return ($first) ? array_shift($first) : array();
    }

    /**
     * Returns last n records
     * 
     * @param int $num
     * @return mixed 
     */
    public function fetchLast($num) {
        return $this->fetch($num, Db::DESC);
    }

    /**
     * Retuns n rows
     * 
     * @param int $num
     * @param string $sort
     * @return mixed 
     */
    public function fetch($num = 1, $sort = Db::ASC) {
        // validate params - avoid injection
        $order = ($sort == Db::ASC) ? $sort : Db::DESC;
        $limit = (is_numeric($num)) ? $num : 1;
        return $this->_dbh->query("SELECT * from `icecast` ORDER by id $order LIMIT $limit")->fetchAll();
    }

    public function getCurrent() {
        return $this->_dbh->query("SELECT datetime_start as start FROM `icecast` WHERE duration is null")->fetchAll();
    }

    public function getStatsByRange($start, $end) {
        $query = "SELECT sum( duration ) as duration, avg( duration ) as average, count( id ) as listeners
            FROM `icecast` WHERE datetime_start >= ':start' AND datetime_end <= ':end'";
        $stmt = $this->_dbh->prepare($query);
        $stmt->bindValue('start', $start);
        $stmt->bindValue('end', $end);
        return $stmt->fetch();
    }

}