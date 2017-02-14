# PHP-Socket-Emitter
Sends data over a TCP socket in an efficient manner

## Installation

    $ composer require danjohnson95/php-socket-emitter
    
## Usage

    $host = "192.168.0.3"; // Where the TCP socket server is hosted
    $port = 3010; // The port in which the TCP socket server is running on
    $Socket = new Danj\PHPSocketEmitter\Socket($host, $port);
    
    $Socket->send([
        'any'  => 'data',
        'you'  => 'want',
        'to'   => 'send',
        'goes' => 'here!'
    ]);
    
## Why do I need this?

Due to the asyncronous nature of PHP, you can't send lots of sockets over a short period of time.

This is because PHP can't tell if the last one has finished sending, and you can't send another TCP socket until the last one was received.

*PHP-Socket-Emitter* stores when the last socket was sent, and will delay execution of sending more until we can reasonably assume the last one was sent.

Therefore, you can do stuff like this:

    foreach($BigObject as $Bit){
        $Socket->send($Array);
    }
   
