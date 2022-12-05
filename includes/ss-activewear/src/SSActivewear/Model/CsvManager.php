<?php
namespace SSActivewear\Model;

class CsvManager
{
    const CATEGORY_CSV = 'categories.csv';

    /**
     * @param string|null $filename
     * @return array
     */
    public function read( $filename )
    {
        $filepath = $this->getFilePath( $filename );
        if ( !file_exists( $filepath ) ) {
            return [];
        }

        $data = file_get_contents( $filepath );
        $rows = explode(PHP_EOL, $data);
        $output = [];
        foreach ($rows as $key => $row) {
            if ($key == 0) {
                continue;
            }

            $content = explode(",", $row);
            if ( empty($row) ) {
                continue;
            }
            $output[] = $content;
        }

        return $output;
    }

    public function writeCategoryCSV( $data, $filename )
    {
        $filepath = $this->getFilePath( $filename );
        $this->checkAndCreateFile( $filepath );
        // Status refers to the import. If imported it should be set to 1 otherwise 0 or empty
        $csv = "categoryID,name,image,status" . PHP_EOL;
        foreach ( $data as $key => $item) {
            $csv .= "{$item['categoryID']},\"{$item['name']}\",\"{$item['image']}\","
                . ( isset($item['status']) ? $item['status'] : "0" );
            if ( $key < (count($data) - 1) ) {
                $csv .= PHP_EOL;
            }
        }
        file_put_contents( $filepath, $csv );
    }

    /**
     * @param string $filepath
     */
    private function checkAndCreateFile( $filepath )
    {
        if ( !file_exists( $filepath ) ) {
            $filehandler = fopen( $filepath, "w" );
            fwrite($filehandler, "");
            fclose($filehandler);
        }
    }

    /**
     * @param string|null $filename
     * @return string
     */
    private function getFilePath( $filename )
    {
        return INKBOMB_SS_PATH . 'assets/' . $filename;
    }
}