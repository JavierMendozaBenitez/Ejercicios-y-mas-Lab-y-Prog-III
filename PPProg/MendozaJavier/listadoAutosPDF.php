<?php
require_once('./clases/AutoBD.php');

require_once('tcpdf/tcpdf.php');

class PDF extends TCPDF {
    
    public function Header() {
        $this->SetFont('helvetica', 'B', 12);
        $this->Cell(0, 10, 'Apellido y Nombre del Alumno', 0, false, 'L', 0, '', 0, false, 'M', 'M');
        
        $this->Cell(0, 10, 'Página ' . $this->getAliasNumPage(), 0, false, 'R', 0, '', 0, false, 'M', 'M');
    }
    public function Footer() {
        $this->SetY(-15);
        $this->SetFont('helvetica', 'I', 8);
        $this->Cell(0, 10, 'Fecha: ' . date('d/m/Y'), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }
}

$pdf = new PDF();
$pdf->SetAutoPageBreak(true, 15);

$pdf->AddPage();

$pdf->SetFont('helvetica', 'B', 16);
$pdf->Cell(0, 10, 'Listado Completo de Autos', 0, 1, 'C');

$autos = MendozaJavier\AutoBD::traerTodos();

$pdf->SetFont('helvetica', '', 12);

foreach ($autos as $auto) {
    $pdf->Cell(0, 10, 'Patente: ' . $auto->getPatente(), 0, 1, 'L');
    $pdf->Cell(0, 10, 'Marca: ' . $auto->getMarca(), 0, 1, 'L');
    $pdf->Cell(0, 10, 'Color: ' . $auto->getColor(), 0, 1, 'L');
    $pdf->Cell(0, 10, 'Precio: ' . $auto->getPrecio(), 0, 1, 'L');
    
    $fotoPath = $auto->getPathFoto();
    if (!empty($fotoPath) && file_exists($fotoPath)) {
        $pdf->Image($fotoPath, 20, $pdf->GetY() + 10, 80, 60);
    }

    $pdf->Ln(10); 
}

$pdf->Output();
