<?php
namespace SSActivewear\Model;

class CsvManager
{
    const CATEGORY_CSV = 'categories.csv';
    const STYLES_CSV = 'styles.csv';

    /**
     * @param string|null $filename
     * @return array
     */
    public function read( $filename, $readSingleImport = false )
    {
        $filepath = $this->getFilePath( $filename );
        if ( !file_exists( $filepath ) ) {
            return [];
        }

        $data = file_get_contents( $filepath );
        $rows = explode(PHP_EOL, $data);
        $output = [];
        $keys = [];
        $i = 0;
        foreach ($rows as $key => $row) {
            if ($key == 0) {
                $keys = explode(",", $row);
                continue;
            }

            $content = explode(",", $row);
            $rContent = array_reverse($content);
            if ( empty($row) || $rContent[0]) {
                continue;
            }

            array_walk( $content, function ($v, $k) use ( &$output, $keys, $readSingleImport, $i ) {
                if ( !isset($keys[$k]) ) {
                    return;
                }

                if ( !$readSingleImport ) {
                    $output[$i][$keys[$k]] = urldecode($v);
                    return;
                }

                $output[$keys[$k]] = urldecode($v);
            });

            if ( !$readSingleImport ) {
                $i++;
                continue;
            }

            break;
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
     * Write data to the CSV file.
     *
     * @param array $data
     * @param string $filename
     */
    public function writeCSV( $data, $filename )
    {
        $filepath = $this->getFilePath( $filename );
        $this->checkAndCreateFile( $filepath );
        // Status refers to the import. If imported it should be set to 1 otherwise 0 or empty
        $csvKeys = array_keys($data[0]);
        $csv = implode(",", $csvKeys) . ',import_status' . PHP_EOL;
        foreach ( $data as $key => $item) {
            array_walk( $csvKeys, function ($v, $k) use ( &$csv, $item ) {
                if ( isset( $item[$v] ) ) {
                    $csv .= urlencode($item[$v]) . ",";
                }
            } );
            $csv .= ( isset($item['import_status']) ? $item['import_status'] : "0" );
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