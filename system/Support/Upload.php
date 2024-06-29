<?php

namespace system\Support;

use DirectoryIterator;

class Upload 
{
    public  ?array    $file;
    public  ?string   $name;
    public  ?string   $directory;
    public  ?string   $folder;
    public  ?string   $result = null;
    private ?string   $error;
    public  ?int      $size;
    
    /**
     * getResult
     *
     * @return string
     */
    public function getResult(): ?string
    {
        return $this->result;
    }
    
    /**
     * getError
     *
     * @return string
     */
    public function getError(): ?string
    {
        return $this->error;
    }
   
    
    /**
     * __construct
     * created directory
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
    public function file(array $file, string $name = null, string $folder = null, int $size =null)
    {
        $this->file = $file;
        $this->name = $name ?? pathinfo($this->file['name'], PATHINFO_FILENAME);
        $this->folder = $folder ?? 'files';

        $this->size = $size ?? 5;

        $extension = pathinfo($this->file['name'], PATHINFO_EXTENSION);

        $validExtension = ['pdf', 'png', 'jpeg', 'gif', 'jpg'];

        $validType = ['application/pdf', 'image/png', 'image/jpeg'];

        if(!in_array($extension, $validExtension)){
            $this->error = "Extensão não permitida. Por favor enviar apenas arquivos com extensão: " . implode(' .',$validExtension);
        }else if(!in_array($this->file['type'], $validType)){
            $this->error = "Tipo de arquivo não permitido";
        }else if($this->file['size'] > $this->size * (1024*1024)){
            $this->error = "Arquivo muito grande, tamanho permitido {$this->size}MB seu arquivo tem {$this->file['size']}";
        }else{
            $this->createFolder();
            $this->renameFile();
            $this->upload();
        }

        
    }
    
    /**
     * createFolder
     *
     * @return void
     */
    private function createFolder(): void
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
    private function renameFile(): void
    {
        $file =$this->name.strrchr($this->file['name'], '.');

        if(file_exists($this->directory.DIRECTORY_SEPARATOR.$this->folder.DIRECTORY_SEPARATOR.$file)){
            $file = $this->name.'_'.uniqid().strrchr($this->file['name'], '.');
        }
        $this->name = $file;
    }
    
    /**
     * upload
     * add and move file
     * @return void
     */
    private function upload(): void
    {
        if(move_uploaded_file($this->file['tmp_name'], $this->directory.DIRECTORY_SEPARATOR.$this->folder.DIRECTORY_SEPARATOR.$this->name)){
            $this->result = $this->name;
        }else {
           $this->result = null;
           $this->error = "Erro ao enviar arquivo";
        }
    }
}
