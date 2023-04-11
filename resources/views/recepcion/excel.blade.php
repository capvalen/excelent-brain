@php
  require '../vendor/autoload.php';

  use PhpOffice\PhpSpreadsheet\SpreadSheet;
  use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
  use \PhpOffice\PhpSpreadsheet\IOFactory;

  $spreadsheet = new SpreadSheet();
  $spreadsheet->getProperties()->setCreator("Johann Meza")->setTitle("Excel");

  $spreadsheet->setActiveSheetIndex(0);
  $hojaActiva = $spreadsheet->getActiveSheet();

  // $spreadsheet->getDefaultStyle()->getFont()->setName('Tahoma');
  // $spreadsheet->getDefaultStyle()->getFont()->setSize(15);

  // $hojaActiva->getColumnDimension('A')->setWidth(40);
  // $hojaActiva->getColumnDimension('B')->setWidth(20);

  $hojaActiva->setCellValue('A1', 'N°');
  $hojaActiva->setCellValue('B1', 'Profesional');
  $hojaActiva->setCellValue('C1', 'Paciente');
  $hojaActiva->setCellValue('D1', 'Tipo de Paciente');
  $hojaActiva->setCellValue('E1', 'Fecha');
  $hojaActiva->setCellValue('F1', 'Comprobante Emitido');
  $hojaActiva->setCellValue('G1', 'Medio de Pago');
  $hojaActiva->setCellValue('H1', 'I. Bancaria');
  $hojaActiva->setCellValue('I1', 'Ingresos');
  $hojaActiva->setCellValue('J1', 'Observación');
  
  $fila = 1;
  $idFila = 1;
  foreach ($dataPatients as $data) {
    $fila += 1;
    $hojaActiva->setCellValue("A$fila", "$idFila");
    $hojaActiva->setCellValue("B$fila", $data->professional->name);
    $hojaActiva->setCellValue("C$fila", $data->patient->name);
    $hojaActiva->setCellValue("D$fila", $data->patient_condition == 1 ? "Nuevo" : "Continuante");
    $hojaActiva->setCellValue("E$fila", $data->date);
    $hojaActiva->setCellValue("F$fila", $data->payment->voucher_issued);
    $hojaActiva->setCellValue("G$fila", $data->payment->payment_method);
    $hojaActiva->setCellValue("H$fila", $data->payment->bank);
    $hojaActiva->setCellValue("I$fila", "S/. " . $data->payment->price);
    $hojaActiva->setCellValue("J$fila", $data->payment->observation);
    $idFila += 1;
  }

  header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
  header("Content-Disposition: attachment;filename=Reporte " . $dataPatients[0]->date . ".xlsx");
  header('Cache-Control: max-age=0');

  $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
  $writer->save('php://output');

  // $writer = new Xlsx($spreadsheet);
  // $writer->save('MI excel.xlsx')
@endphp