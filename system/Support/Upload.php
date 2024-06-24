<?php

namespace system\Support;

use DirectoryIterator;

class Upload 
{
    public $file;
    public $name;
    public $directory;
    public $folder;
   

    public function __construct(string $directory = null)
    {
       $this->directory = $directory ?? 'uploads';

       if(!file_exists($this->directory) && !is_dir($this->directory)){
            mkdir($this->directory, 0755);
       }
    }

    public function file(array $file, string $name = null, string $folder = null)
    {
        $this->file = $file;
        $this->name = $name ?? pathinfo($this->file['name'], PATHINFO_FILENAME);
        $this->folder = $folder ?? 'files';

        $this->createFolder();
        $this->renameFile();
        $this->upload();
    }

    public function createFolder(): void
    {
        if(!file_exists($this->directory.DIRECTORY_SEPARATOR.$this->folder) && !is_dir($this->directory.DIRECTORY_SEPARATOR.$this->folder)){
            mkdir($this->directory.DIRECTORY_SEPARATOR.$this->folder, 0775);
        }
        
    }

    public function renameFile(): void
    {
        $file =$this->name.strrchr($this->file['name'], '.');

        if(file_exists($this->directory.DIRECTORY_SEPARATOR.$this->folder.DIRECTORY_SEPARATOR.$file)){
            $file = $this->name.'_'.uniqid().strrchr($this->file['name'], '.');
        }
        $this->name = $file;
    }

    public function upload(): void
    {
        if(move_uploaded_file($this->file['tmp_name'], $this->directory.DIRECTORY_SEPARATOR.$this->folder.DIRECTORY_SEPARATOR.$this->name)){
            echo $this->name.' foi movido com sucesso';
        }else {
            echo 'Erro ao enviar arquivo';
        }
    }
}
