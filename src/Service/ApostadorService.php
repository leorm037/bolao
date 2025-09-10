<?php

namespace App\Service;

use App\Entity\Apostador;
use ArrayIterator;
use DateTime;
use DateTimeZone;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ApostadorService {

    /**
     * 
     * @param ArrayIterator $apostadores
     * @return void
     */
    public function exportar(ArrayIterator $apostadores): void {

        $planilha = new Spreadsheet();

        $aba = $planilha->getActiveSheet()
                ->setTitle("Apostadores")
                ->setCellValue('A1', 'Nome')
                ->setCellValue('B1', 'E-mail')
                ->setCellValue('C1', 'Chave PIX')
                ->setCellValue('D1', 'Telefone')
                ->setCellValue('E1', 'Celular')
                ->setCellValue('F1', 'Atualização')
        ;

        $headerStyle = [
            'font' => [
                'bold' => true,
                'color' => ['argb' => 'FFFFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['argb' => '9BBB59'],
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN, // Estilo da borda (fina)
                    'color' => ['argb' => 'D6E3BA'], // Cor da borda (preta)
                ],
            ],
        ];

        $lineEvenStyle = [
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['argb' => 'EBF1DE'],
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => 'D6E3BA'],
                ],
            ],
        ];

        $lineOddStyle = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => 'D6E3BA'],
                ],
            ],
        ];

        $aba->getStyle('A1:F1')->applyFromArray($headerStyle);

        $highestColumn = $aba->getHighestColumn();

        foreach (range('A', $highestColumn) as $col) {
            $aba->getColumnDimension($col)->setAutoSize(true);
        }

        for ($i = 0; $i < count($apostadores); ++$i) {
            $linha = $i + 2;

            /** @var Apostador $apostador */
            $apostador = $apostadores[$i];

            $nome = $apostador->getNome();
            $email = $apostador->getEmail();
            $pix = $apostador->getPix();
            $telefone = $apostador->getTelefone();
            $celular = $apostador->getCelular();

            $timeZone = 'America/Sao_Paulo';
            $format = 'd/m/Y H:i';

            if ($apostador->getUpdatedAt()) {
                
                $atualizacao = $apostador->getUpdatedAt()->setTimezone(new DateTimeZone('America/Sao_Paulo'))->format($format);
            } else {
                $atualizacao = DateTime::createFromImmutable($apostador->getCreatedAt())->format($format);
            }

            $aba->setCellValue('A' . $linha, $nome);
            $aba->setCellValue('B' . $linha, $email);
            $aba->setCellValue('C' . $linha, $pix);
            $aba->setCellValue('D' . $linha, $telefone);
            $aba->setCellValue('E' . $linha, $celular);
            $aba->setCellValue('F' . $linha, $atualizacao);

            if ($linha % 2 == 0) {
                $aba->getStyle("A{$linha}:F{$linha}")->applyFromArray($lineOddStyle);
            } else {
                $aba->getStyle("A{$linha}:F{$linha}")->applyFromArray($lineEvenStyle);
            }            
        }
        
        $highestRow = $aba->getHighestRow();
        
        $aba->getStyle("E2:E{$highestRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        $aba->getStyle("F2:F{$highestRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        
        $aba->getStyle('A1');

        $write = new Xlsx($planilha);
        $write->save('php://output');
    }
}
