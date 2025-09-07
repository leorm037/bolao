<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace App\Service\Upload;

use DateTime;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class AbstractUploadService
{

    public function __construct(
            private string $targetDirectory,
            private SluggerInterface $slugger,
            private LoggerInterface $logger
    )
    {
        
    }

    public function getTargetDirectory(): ?string
    {
        $dateTime = new DateTime();

        $dateTimeDirectory = $dateTime->format('Y/m/d');

        return $this->targetDirectory . '/' . $dateTimeDirectory;
    }

    public function save(UploadedFile $file): ?string
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), \PATHINFO_FILENAME);

        $safeFilename = $this->slugger->slug($originalFilename)->lower();

        $filename = $safeFilename . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

        try {
            $file->move($this->getTargetDirectory(), $filename);
        } catch (FileException $e) {
            $this->logger->error(
                'Erro ao tentar mover o arquivo ' . $originalFilename, [
                    'fileOriginal' => $file->getFileInfo(),
                    'targetDirectory' => $this->getTargetDirectory(),
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'code' => $e->getCode(),
                ]
            );

            return null;
        }

        return $this->getTargetDirectory() . '/' . $filename;
    }
    
    public function delete(?string $filename): bool
    {
        try {
            return unlink($filename);
        } catch (Exception $e) {
            $mensagem = \sprintf('Erro ao tentar excluir o arquivo "%s"', $filename);

            $this->logger->error($mensagem, $e->getTrace());

            return true;
        }
    }
}
