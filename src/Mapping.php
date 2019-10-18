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
    private $xml;
    private $namespaceMapping;
    private $nameFileMapping;

    public function __construct()
    {
        $this->file = new File();
        $this->loadPartFile();
    }

    public function create($server)
    {
        $this->server = $server;
        $this->namespaceMapping = $this->server['argv'][1];
        $this->nameFileMapping  = $this->server['argv'][2];

        $this->argumentsIsValid($this->server);
        
        $document = $this->file->openFile($this->server['argv'][2].'.php');
        
        fwrite($document, "<?php\n");
        
        fwrite($document, "/**\n * @author DMM - https://github.com/WalderlanSena/dmm/  \n */\n\n");
        
        $namespace_file = "namespace " . $this->server['argv'][1] . ";" . "\n\n";
        fwrite($document, $namespace_file);

        $nameClass = "class ".str_replace('.php', '', $this->server['argv'][2])."\n{\n";
        fwrite($document, $nameClass);

        $idType =  "\tprivate $"."id;\n\n";
        fwrite($document, $idType);
        
        $this->addAtributes($document);

    }
    
    private function addAtributes($document)
    {
        $option = 'y';
        $names = [];
        $types = [];

        do {
            fwrite(STDOUT, 'Enter the field name: ');
            $nameField = $this->readLine();
            fwrite(STDOUT, 'Enter the field type: ');
            $typeField = $this->readLine();

            if (!$this->verifyTypePermission($typeField)) {
                fwrite(STDOUT,'The type'.$typeField.' not permission !'.PHP_EOL);
                continue;
            }

            array_push($names, $nameField);
            array_push($types, $typeField);

            $addType  =  "\tprivate $" . $nameField . ";" ."\n\n";
            fwrite($document, $addType);
            fwrite(STDOUT, 'Do you want to enter another field ? [Y/n] ');
            $option = $this->readLine();
        } while (strtolower($option) == 'y');

        fwrite($document, "\n}");
        
        $this->file->closeFile($document);

        $this->generateXml(array_combine($names, $types));
    }

    private function generateXml(array $data)
    {
        $nameXml = $this->nameFileMapping.'.mongodb.xml';

        $fileXml = $this->file->openFile($nameXml);
        fwrite($fileXml, str_replace(['{namespace}', '{collection}'], [$this->namespaceMapping, $this->nameFileMapping], $this->xml['header']));
        
        foreach ($data as $key => $value) {
            fwrite($fileXml, "\t".str_replace(['{name}', '{type}'], [$key, $value], $this->xml['field']).PHP_EOL);
        }
        fwrite($fileXml, $this->xml['footer']);
        $this->file->closeFile($fileXml);
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

    private function loadPartFile()
    {
        $this->xml = [
            'header' => file_get_contents(__DIR__.'/layouts/xml/header.template'),
            'field'  => file_get_contents(__DIR__.'/layouts/xml/field.template'),
            'footer' => file_get_contents(__DIR__.'/layouts/xml/footer.template'),
        ];
    }
}