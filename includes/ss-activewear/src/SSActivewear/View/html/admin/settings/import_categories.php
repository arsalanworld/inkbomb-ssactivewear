<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

use SSActivewear\Model\Config;

//$config = new Config();
?>
<div class="wrap woocommerce">
    <h1 class="screen-reader-text"><?php esc_html_e( 'Import Categories', INKBOMB_SS_DOMAIN ); ?></h1>
    <h2><?php esc_html_e( 'Import Categories', INKBOMB_SS_DOMAIN ); ?></h2>
    <div class="import-wrapper">
        <button type="button" id="import_btn" class="button-primary woocommerce-save-button inkbomb-importer-btn"
                style="vertical-align: middle;"><?php esc_html_e('Import', INKBOMB_SS_DOMAIN); ?></button>
    </div>
    <div class="output"></div>
</div>
<script type="text/javascript">
    jQuery(function ($) {
        var endpoint = '<?php echo Config::ENDPOINT ?>v2/categories?mediatype=json';
        $(document).on('click', '#import_btn', function () {
            $.ajax({
                url: endpoint,
                "method": "GET",
                data: JSON.stringify({
                    "customerNumber": "414244",
                    "APIKey": "c1a754d4-b42a-4c85-84bc-bd4c1cd75c70"
                }),

                "headers": {
                    "Content-Type": "application/json"
                },
            }).done( function (reponse) {
                console.log(reponse);
            });
        });
    });
</script>
<?php
$curl = curl_init();

curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://api-ca.ssactivewear.com/v2/categories?mediatype=json',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'GET',
    CURLOPT_POSTFIELDS =>'{
    "customerNumber": "414244",
    "APIKey": "c1a754d4-b42a-4c85-84bc-bd4c1cd75c70"
}',
    CURLOPT_HTTPHEADER => array(
        'Authorization: Basic ' . base64_encode( "414244:c1a754d4-b42a-4c85-84bc-bd4c1cd75c70" ),
        'Content-Type: application/json'
    ),
));

$response = curl_exec($curl);

curl_close($curl);
echo "<pre>";
print_r($response);
echo "</pre>";
