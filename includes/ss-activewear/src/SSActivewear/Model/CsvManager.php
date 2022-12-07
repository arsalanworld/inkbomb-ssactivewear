<?php
namespace SSActivewear\Model;

class CsvManager
{
    const CATEGORY_CSV = 'categories.csv';
    const STYLES_CSV = 'styles.csv';

    /**
     * @param string|null $filename
     * @param bool $readSingleImport
     * @param bool $readAllRows
     * @return array|array[]
     */
    public function read( $filename, $readSingleImport = false, $readAllRows = false )
    {
        $filepath = $this->getFilePath( $filename );
        $output = [
            "lines" => [],
            "data" => [],
        ];
        if ( !file_exists( $filepath ) ) {
            return $output;
        }

        $data = file_get_contents( $filepath );
        $rows = explode(PHP_EOL, $data);
        $keys = [];
        $i = 0;
        foreach ($rows as $key => $row) {
            if ($key == 0) {
                $keys = explode(",", $row);
                continue;
            }

            $content = explode(",", $row);
            if ( !$readSingleImport && $readAllRows ) {
                $output["data"][] = $content;
                continue;
            }

            $rContent = array_reverse($content);
            if ( (empty($row) || $rContent[0]) && ( $readSingleImport && !$readAllRows ) ) {
                continue;
            }

            array_walk( $content, function ($v, $k) use ( &$output, $keys, $readSingleImport, $i, $key ) {
                if ( !isset($keys[$k]) ) {
                    return;
                }

                // Mark the line numbers read.
                $output["lines"][$i] = $key - 1;
                if ( !$readSingleImport ) {
                    $output["data"][$i][$keys[$k]] = urldecode($v);
                    return;
                }

                $output["data"][$keys[$k]] = urldecode($v);
            });

            if ( !$readSingleImport ) {
                $i++;
                continue;
            }

            break;
        }

        return $output;
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
     * Delete the file.
     *
     * @param string $filename
     */
    public function delete( $filename )
    {
        $filepath = $this->getFilePath( $filename );
        if ( file_exists( $filepath ) ) {
            unlink( $filepath );
        }
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