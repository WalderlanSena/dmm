<?php
/**
 *  DMM - Doctrine Mapping MongoDB
 *  @author     Walderlan Sena - <senawalderlan@gmail.com>
 *  @license    MIT <https://opensource.org/licenses/MIT>
 *  @warning    Redistributions of files must retain the above copyright notice.
 *  @version    v1.0.0 - <https://github.com/WalderlanSena/dmm>
 *  @link       <https://www.walderlan.com>
 *  @copyright  Mentes Virtuais Sena © <https://www.mentesvirtuaissena.com>
 */
namespace App;

use App\Mapping;

class Bootstrap
{
    private $server;
    private $mapping;

    public function __construct($server)
    {
        $this->server  = $server;    
        $this->mapping = new Mapping();
    }

    public function init()
    {
        try {
            $this->mapping->create($this->server);
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage());
        }
    }
}