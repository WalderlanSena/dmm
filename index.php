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
require_once __DIR__ .'/vendor/autoload.php';

if (php_sapi_name() !== "cli") {
    die("Sorry, This Project was make for command line.".PHP_EOL);
}

$app = new \App\Bootstrap($_SERVER);
$app->init();