<?php

namespace App\Service;

use App\Entity\Product;

class CsvExportService
{
    /**
     * @param Product[] $products
     */
    public function exportProductsToCsv(array $products): string
    {
        $fp = fopen('php://temp', 'w');

        fputs($fp, chr(0xEF) . chr(0xBB) . chr(0xBF));

        fputcsv($fp, ['Nom', 'Description', 'Prix'], ';');

        foreach ($products as $product) {
            fputcsv($fp, [
                $product->getName(),
                $product->getDescription(),
                $product->getPrice()
            ], ';');
        }

        rewind($fp);
        $csvContent = stream_get_contents($fp);
        fclose($fp);

        return $csvContent;
    }
}