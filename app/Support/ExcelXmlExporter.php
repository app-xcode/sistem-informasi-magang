<?php

namespace App\Support;

use Illuminate\Http\Response;
use Traversable;

final class ExcelXmlExporter
{
    public static function download(string $filename, array $headings, iterable $rows): Response
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>'
            .'<?mso-application progid="Excel.Sheet"?>'
            .'<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet" '
            .'xmlns:o="urn:schemas-microsoft-com:office:office" '
            .'xmlns:x="urn:schemas-microsoft-com:office:excel" '
            .'xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet">'
            .'<Styles><Style ss:ID="Header"><Font ss:Bold="1"/></Style></Styles>'
            .'<Worksheet ss:Name="Data"><Table>';

        $xml .= '<Row ss:StyleID="Header">';
        foreach ($headings as $heading) {
            $xml .= '<Cell><Data ss:Type="String">'.self::escape((string) $heading).'</Data></Cell>';
        }
        $xml .= '</Row>';

        foreach ($rows as $row) {
            $xml .= '<Row>';
            foreach ($row as $value) {
                $value = $value === null ? '' : (string) $value;
                $xml .= '<Cell><Data ss:Type="String">'.self::escape($value).'</Data></Cell>';
            }
            $xml .= '</Row>';
        }

        $xml .= '</Table></Worksheet></Workbook>';

        return response($xml)
            ->header('Content-Type', 'application/vnd.ms-excel; charset=UTF-8')
            ->header('Content-Disposition', 'attachment; filename="'.$filename.'.xls"');
    }

    private static function escape(string $value): string
    {
        return htmlspecialchars($value, ENT_XML1 | ENT_QUOTES, 'UTF-8');
    }
}
