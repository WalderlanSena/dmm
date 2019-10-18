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

class Mapping
{
    private $file;
    private $server;

    public function __construct()
    {
        $this->file = new File();    
    }

    public function create($server)
    {
        $this->server = $server;

        $this->argumentsIsValid($this->server);
        
        $document = $this->file->openFile($this->server['argv'][2]);
        
        fwrite($document, "<?php\n");
        
        fwrite($document, "/**\n * @author DMM - https://github.com/WalderlanSena/dmm/  \n */\n\n");
        
        $namespace_file = "namespace " . $this->server['argv'][1] . ";" . "\n\n";
        fwrite($document, $namespace_file);

        $nameClass = "class ".str_replace('.php', '', $this->server['argv'][2])."\n{\n";
        fwrite($document, $nameClass);

        $idType =  "\tprivate $"."id;\n\n";
        fwrite($document, $idType);
        
        $this->addAtributes($document);

        $this->file->closeFile($document);
    }
    
    private function addAtributes($document)
    {
        $option = 'y';
        $data = [];
        do {
            fwrite(STDOUT, 'Enter the field name: ');
            $nameField = $this->readLine();
            fwrite(STDOUT, 'Enter the field type: ');
            $typeField = $this->readLine();

            if (!$this->verifyTypePermission($typeField)) {
                fwrite(STDOUT,'The type'.$typeField.' not permission !'.PHP_EOL);
                continue;
            }
            
            $data[$nameField][$typeField];

            $addType  =  "\tprivate $" . $nameField . ";" ."\n\n";
            fwrite($document, $addType);
            fwrite(STDOUT, 'Do you want to enter another field ? [Y/n] ');
            $option = $this->readLine();
        } while (strtolower($option) == 'y');
        fwrite($document, "\n}");
    }

    private function readLine()
    {
        if (PHP_OS === 'WINNT') {
            echo '$ ';
            return stream_get_line(STDIN, 1024, PHP_EOL);
        }

        return readline('$ ');
    }

    private function verifyTypePermission($type)
    {
        $typePermission = [
            'bin',
            'bin_bytearray',
            'bin_custom',
            'bin_func',
            'bin_md5',
            'bin_uuid',
            'boolean',
            'collection',
            'custom_id',
            'date',
            'file',
            'float',
            'hash',
            'id',
            'int',
            'key',
            'object_id',
            'raw',
            'string',
            'timestamp',
        ];
        return in_array($type, $typePermission);
    }

    private function argumentsIsValid($server)
    {
        if ($server['argc'] < 3) {
            Message::error('Usage: php dmm.phar NAMESPACE NAME_FILE');
        }
    }
}