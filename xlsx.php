<?php
// =====================================================================
// xlsx.php - Generador mínimo de archivos Excel (.xlsx) sin librerías externas.
// Un .xlsx es un ZIP con varios archivos XML; aquí se arman a mano con ZipArchive
// (la extensión "zip" de PHP viene activada en XAMPP).
//
// Uso:
//   $x = new XlsxWriter();
//   $x->agregarHoja('Pedidos', $columnas, $filas, ['total']);
//   $x->enviar('reporte.xlsx');
//
// $columnas = [ ['Título', 'campo_en_la_fila', 'texto|entero|dinero'], ... ]
// $filas    = lista de arreglos asociativos (por ejemplo, el resultado de un fetchAll)
// El 4.º parámetro de agregarHoja() lista los campos que llevan una fila final de TOTAL.
// =====================================================================

class XlsxWriter {
    private $hojas = [];

    // Índices de estilo (cellXfs en styles.xml)
    const ESTILO_ENCABEZADO = 1;
    const ESTILO_TEXTO      = 2;
    const ESTILO_ENTERO     = 3;
    const ESTILO_DINERO     = 4;
    const ESTILO_TOTAL_TXT  = 5;
    const ESTILO_TOTAL_ENT  = 6;
    const ESTILO_TOTAL_DIN  = 7;
    const ESTILO_NOTA       = 8;

    public function agregarHoja($nombre, $columnas, $filas, $sumar = []) {
        $this->hojas[] = [
            'nombre'   => $this->nombreHoja($nombre, count($this->hojas) + 1),
            'columnas' => $columnas,
            'filas'    => $filas,
            'sumar'    => $sumar,
        ];
    }

    // Excel: máximo 31 caracteres y sin  [ ] : * ? / \
    private function nombreHoja($nombre, $n) {
        $nombre = trim(preg_replace('/[\[\]:*?\/\\\\]/u', ' ', (string)$nombre));
        if ($nombre === '') $nombre = 'Hoja' . $n;
        return mb_substr($nombre, 0, 31);
    }

    private function esc($t) {
        $t = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F]/', '', (string)$t); // caracteres que XML no admite
        return htmlspecialchars($t, ENT_XML1 | ENT_QUOTES, 'UTF-8');
    }

    private function letra($i) { // 0 => A, 25 => Z, 26 => AA
        $s = '';
        $i++;
        while ($i > 0) {
            $m = ($i - 1) % 26;
            $s = chr(65 + $m) . $s;
            $i = intdiv($i - 1, 26);
        }
        return $s;
    }

    private function numero($v) {
        $s = number_format((float)$v, 2, '.', '');
        return rtrim(rtrim($s, '0'), '.');
    }

    private function celdaTexto($ref, $estilo, $texto) {
        return '<c r="' . $ref . '" s="' . $estilo . '" t="inlineStr"><is><t xml:space="preserve">' . $this->esc($texto) . '</t></is></c>';
    }

    private function xmlHoja($h, $primera) {
        $cols = $h['columnas'];
        $filas = $h['filas'];
        $nCols = count($cols);
        $nFilas = count($filas);

        // Anchos de columna según el contenido
        $anchos = [];
        foreach ($cols as $i => $c) {
            $max = mb_strlen($c[0]);
            foreach ($filas as $f) {
                $v = isset($f[$c[1]]) ? $f[$c[1]] : '';
                $len = mb_strlen($c[2] === 'texto' ? (string)$v : number_format((float)$v, 0, '.', ','));
                if ($len > $max) $max = $len;
            }
            $anchos[$i] = max(11, min(60, $max + 3));
        }

        $xml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' . "\n";
        $xml .= '<worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">';
        $xml .= '<sheetViews><sheetView workbookViewId="0"' . ($primera ? ' tabSelected="1"' : '') . '>'
              . '<pane ySplit="1" topLeftCell="A2" activePane="bottomLeft" state="frozen"/>'
              . '<selection pane="bottomLeft" activeCell="A2" sqref="A2"/></sheetView></sheetViews>';
        $xml .= '<sheetFormatPr defaultRowHeight="15"/>';
        $xml .= '<cols>';
        foreach ($anchos as $i => $w) {
            $xml .= '<col min="' . ($i + 1) . '" max="' . ($i + 1) . '" width="' . $w . '" customWidth="1"/>';
        }
        $xml .= '</cols><sheetData>';

        // Fila 1: encabezados
        $xml .= '<row r="1" ht="24" customHeight="1">';
        foreach ($cols as $i => $c) {
            $xml .= $this->celdaTexto($this->letra($i) . '1', self::ESTILO_ENCABEZADO, $c[0]);
        }
        $xml .= '</row>';

        // Filas de datos
        $r = 2;
        foreach ($filas as $f) {
            $xml .= '<row r="' . $r . '">';
            foreach ($cols as $i => $c) {
                $ref = $this->letra($i) . $r;
                $v = isset($f[$c[1]]) ? $f[$c[1]] : null;
                if ($v === null || $v === '') {
                    $xml .= '<c r="' . $ref . '" s="' . self::ESTILO_TEXTO . '"/>';
                } elseif ($c[2] === 'entero' && is_numeric($v)) {
                    $xml .= '<c r="' . $ref . '" s="' . self::ESTILO_ENTERO . '"><v>' . $this->numero($v) . '</v></c>';
                } elseif ($c[2] === 'dinero' && is_numeric($v)) {
                    $xml .= '<c r="' . $ref . '" s="' . self::ESTILO_DINERO . '"><v>' . $this->numero($v) . '</v></c>';
                } else {
                    $xml .= $this->celdaTexto($ref, self::ESTILO_TEXTO, $v);
                }
            }
            $xml .= '</row>';
            $r++;
        }

        if ($nFilas === 0) {
            $xml .= '<row r="2">' . $this->celdaTexto('A2', self::ESTILO_NOTA, 'No hay registros para este reporte.') . '</row>';
            $r = 3;
        } elseif (!empty($h['sumar'])) {
            // Fila final de TOTAL con fórmula SUM (Excel la recalcula al abrir)
            $xml .= '<row r="' . $r . '">';
            foreach ($cols as $i => $c) {
                $ref = $this->letra($i) . $r;
                if ($i === 0) {
                    $xml .= $this->celdaTexto($ref, self::ESTILO_TOTAL_TXT, 'TOTAL');
                } elseif (in_array($c[1], $h['sumar'], true)) {
                    $suma = 0;
                    foreach ($filas as $f) $suma += (float)(isset($f[$c[1]]) ? $f[$c[1]] : 0);
                    $estilo = ($c[2] === 'dinero') ? self::ESTILO_TOTAL_DIN : self::ESTILO_TOTAL_ENT;
                    $col = $this->letra($i);
                    $xml .= '<c r="' . $ref . '" s="' . $estilo . '"><f>SUM(' . $col . '2:' . $col . ($nFilas + 1) . ')</f><v>' . $this->numero($suma) . '</v></c>';
                } else {
                    $xml .= '<c r="' . $ref . '" s="' . self::ESTILO_TOTAL_TXT . '"/>';
                }
            }
            $xml .= '</row>';
        }

        $xml .= '</sheetData>';
        if ($nFilas > 0) {
            $xml .= '<autoFilter ref="A1:' . $this->letra($nCols - 1) . ($nFilas + 1) . '"/>';
        }
        $xml .= '<pageMargins left="0.7" right="0.7" top="0.75" bottom="0.75" header="0.3" footer="0.3"/>';
        $xml .= '<ignoredErrors><ignoredError sqref="A1:' . $this->letra($nCols - 1) . ($r + 1) . '" numberStoredAsText="1"/></ignoredErrors>';
        $xml .= '</worksheet>';
        return $xml;
    }

    private function xmlEstilos() {
        $borde = '<border><left style="thin"><color rgb="FFBFBFBF"/></left><right style="thin"><color rgb="FFBFBFBF"/></right>'
               . '<top style="thin"><color rgb="FFBFBFBF"/></top><bottom style="thin"><color rgb="FFBFBFBF"/></bottom><diagonal/></border>';
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' . "\n"
            . '<styleSheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">'
            . '<numFmts count="1"><numFmt numFmtId="164" formatCode="&quot;$&quot;#,##0"/></numFmts>'
            . '<fonts count="4">'
            .   '<font><sz val="11"/><name val="Calibri"/><family val="2"/></font>'
            .   '<font><b/><sz val="11"/><color rgb="FFFFFFFF"/><name val="Calibri"/><family val="2"/></font>'
            .   '<font><b/><sz val="11"/><name val="Calibri"/><family val="2"/></font>'
            .   '<font><i/><sz val="11"/><color rgb="FF777777"/><name val="Calibri"/><family val="2"/></font>'
            . '</fonts>'
            . '<fills count="4">'
            .   '<fill><patternFill patternType="none"/></fill>'
            .   '<fill><patternFill patternType="gray125"/></fill>'
            .   '<fill><patternFill patternType="solid"><fgColor rgb="FF1F4E79"/><bgColor indexed="64"/></patternFill></fill>'
            .   '<fill><patternFill patternType="solid"><fgColor rgb="FFFFF2CC"/><bgColor indexed="64"/></patternFill></fill>'
            . '</fills>'
            . '<borders count="2"><border><left/><right/><top/><bottom/><diagonal/></border>' . $borde . '</borders>'
            . '<cellStyleXfs count="1"><xf numFmtId="0" fontId="0" fillId="0" borderId="0"/></cellStyleXfs>'
            . '<cellXfs count="9">'
            .   '<xf numFmtId="0" fontId="0" fillId="0" borderId="0" xfId="0"/>'                                                                            // 0 normal
            .   '<xf numFmtId="0" fontId="1" fillId="2" borderId="1" xfId="0" applyFont="1" applyFill="1" applyBorder="1" applyAlignment="1"><alignment horizontal="center" vertical="center" wrapText="1"/></xf>' // 1 encabezado
            .   '<xf numFmtId="0" fontId="0" fillId="0" borderId="1" xfId="0" applyBorder="1" applyAlignment="1"><alignment vertical="center"/></xf>'       // 2 texto
            .   '<xf numFmtId="3" fontId="0" fillId="0" borderId="1" xfId="0" applyNumberFormat="1" applyBorder="1"/>'                                      // 3 entero
            .   '<xf numFmtId="164" fontId="0" fillId="0" borderId="1" xfId="0" applyNumberFormat="1" applyBorder="1"/>'                                    // 4 dinero
            .   '<xf numFmtId="0" fontId="2" fillId="3" borderId="1" xfId="0" applyFont="1" applyFill="1" applyBorder="1"/>'                                // 5 total texto
            .   '<xf numFmtId="3" fontId="2" fillId="3" borderId="1" xfId="0" applyNumberFormat="1" applyFont="1" applyFill="1" applyBorder="1"/>'          // 6 total entero
            .   '<xf numFmtId="164" fontId="2" fillId="3" borderId="1" xfId="0" applyNumberFormat="1" applyFont="1" applyFill="1" applyBorder="1"/>'        // 7 total dinero
            .   '<xf numFmtId="0" fontId="3" fillId="0" borderId="0" xfId="0" applyFont="1"/>'                                                              // 8 nota
            . '</cellXfs>'
            . '<cellStyles count="1"><cellStyle name="Normal" xfId="0" builtinId="0"/></cellStyles>'
            . '</styleSheet>';
    }

    // Arma el .xlsx y devuelve la ruta de un archivo temporal (el que llama debe borrarlo)
    public function guardarTemporal() {
        if (!class_exists('ZipArchive')) {
            throw new RuntimeException('Falta la extensión zip de PHP. En XAMPP abre php.ini, quita el ; de la línea extension=zip y reinicia Apache.');
        }
        if (count($this->hojas) === 0) throw new RuntimeException('El reporte no tiene hojas.');

        $tmp = tempnam(sys_get_temp_dir(), 'xlsx');
        $zip = new ZipArchive();
        if ($zip->open($tmp, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            throw new RuntimeException('No se pudo crear el archivo temporal del reporte.');
        }

        $tipos = '<Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml"/>'
               . '<Override PartName="/xl/styles.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.styles+xml"/>';
        $hojasXml = '';
        $relsXml = '';
        foreach ($this->hojas as $i => $h) {
            $n = $i + 1;
            $tipos .= '<Override PartName="/xl/worksheets/sheet' . $n . '.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/>';
            $hojasXml .= '<sheet name="' . $this->esc($h['nombre']) . '" sheetId="' . $n . '" r:id="rId' . $n . '"/>';
            $relsXml .= '<Relationship Id="rId' . $n . '" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" Target="worksheets/sheet' . $n . '.xml"/>';
            $zip->addFromString('xl/worksheets/sheet' . $n . '.xml', $this->xmlHoja($h, $i === 0));
        }
        $relsXml .= '<Relationship Id="rId' . (count($this->hojas) + 1) . '" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/styles" Target="styles.xml"/>';

        $zip->addFromString('[Content_Types].xml',
            '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' . "\n"
            . '<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">'
            . '<Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>'
            . '<Default Extension="xml" ContentType="application/xml"/>' . $tipos . '</Types>');
        $zip->addFromString('_rels/.rels',
            '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' . "\n"
            . '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">'
            . '<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="xl/workbook.xml"/>'
            . '</Relationships>');
        $zip->addFromString('xl/workbook.xml',
            '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' . "\n"
            . '<workbook xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">'
            . '<bookViews><workbookView/></bookViews><sheets>' . $hojasXml . '</sheets></workbook>');
        $zip->addFromString('xl/_rels/workbook.xml.rels',
            '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' . "\n"
            . '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">' . $relsXml . '</Relationships>');
        $zip->addFromString('xl/styles.xml', $this->xmlEstilos());

        if ($zip->close() !== true) throw new RuntimeException('No se pudo terminar el archivo Excel.');
        return $tmp;
    }

    // Envía el archivo al navegador como descarga
    public function enviar($nombreArchivo) {
        $tmp = $this->guardarTemporal();
        $nombre = preg_replace('/[^A-Za-z0-9_.-]/', '_', $nombreArchivo);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $nombre . '"');
        header('Content-Length: ' . filesize($tmp));
        header('Cache-Control: no-store, no-cache, must-revalidate');
        header('X-Content-Type-Options: nosniff');
        readfile($tmp);
        unlink($tmp);
    }
}
