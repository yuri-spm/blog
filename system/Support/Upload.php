<?php

namespace system\Support;

use DirectoryIterator;

class Upload 
{
    public $file;
    public $name;
    public $directory;
    public $folder;
   
    
    /**
     * __construct
     *
     * @param  mixed $directory
     * @return void
     */
    public function __construct(string $directory = null)
    {
       $this->directory = $directory ?? 'uploads';

       if(!file_exists($this->directory) && !is_dir($this->directory)){
            mkdir($this->directory, 0755);
       }
    }
    
    /**
     * file
     *
     * @param  mixed $file
     * @param  mixed $name
     * @param  mixed $folder
     * @return void
     */
    public function file(array $file, string $name = null, string $folder = null)
    {
        $this->file = $file;
        $this->name = $name ?? pathinfo($this->file['name'], PATHINFO_FILENAME);
        $this->folder = $folder ?? 'files';

        $this->createFolder();
        $this->renameFile();
        $this->upload();
    }
    
    /**
     * createFolder
     *
     * @return void
     */
    public function createFolder(): void
    {
        if(!file_exists($this->directory.DIRECTORY_SEPARATOR.$this->folder) && !is_dir($this->directory.DIRECTORY_SEPARATOR.$this->folder)){
            mkdir($this->directory.DIRECTORY_SEPARATOR.$this->folder, 0775);
        }
        
    }
    
    /**
     * renameFile
     *
     * @return void
     */
    public function renameFile(): void
    {
        $file =$this->name.strrchr($this->file['name'], '.');

        if(file_exists($this->directory.DIRECTORY_SEPARATOR.$this->folder.DIRECTORY_SEPARATOR.$file)){
            $file = $this->name.'_'.uniqid().strrchr($this->file['name'], '.');
        }
        $this->name = $file;
    }
    
    /**
     * upload
     *
     * @return void
     */
    public function upload(): void
    {
        if(move_uploaded_file($this->file['tmp_name'], $this->directory.DIRECTORY_SEPARATOR.$this->folder.DIRECTORY_SEPARATOR.$this->name)){
            echo $this->name.' foi movido com sucesso';
        }else {
            echo 'Erro ao enviar arquivo';
        }
    }
}
