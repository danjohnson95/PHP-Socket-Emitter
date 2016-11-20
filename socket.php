<?php namespace Danj\PHPSocketEmitter

class Socket{

    private $socket;
    private $wait_time = 150000;
    private $last_sent = 0;

    /**
     * Connects to the socket and stores the instance in this object.
     * @param $host string The host name of the node server
     * @param $port integer The port number of the node server
     * @throws Exception
     */
    public function __construct($host, $port){
        if(!$host) throw new \Exception('Please specify the socket host');
        if(!$port) throw new \Exception('Please specify the socket port');
        $this->socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        if($this->socket === false) throw new \Exception(socket_strerror(socket_last_error()));
        
        $result = socket_connect($this->socket, $host, $port);

        if($result === false) throw new \Exception(socket_strerror(socket_last_error($this->socket)));
    }

    /**
     * Closes the socket when we're finished with it to avoid redundant connections.
     */
    public function __destruct(){
        socket_close($this->socket);
    }

    /**
     * Sends the payload to the node server. This function will wait for the wait_time to ensure that
     * it's been sent successfully before we send another one. The last time a socket was sent is
     * stored within the object, so the time needed to wait will be calculated from that. This
     * means that we don't wait any longer than we need to.
     * @param $payload array The data you want to send to the node server
     * @return bool Returns true if payload was successfully sent.
     */
    public function send(array $payload){
        $json = json_encode($payload);
        $write = socket_write($this->socket, $json, strlen($json));
        $sleep_time = $this->wait_time - (microtime(true) - $this->last_sent);
        $sleep_time = $sleep_time > 0 ? $sleep_time : $this->wait_time;
        usleep($sleep_time);
        $this->last_sent = microtime(true);
        return $write !== false ? true : false;
    }

}
