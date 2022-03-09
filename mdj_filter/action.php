<?php
    require_once($_SERVER['DOCUMENT_ROOT'] . $folder . '/wp-config.php');
    require_once($_SERVER['DOCUMENT_ROOT'] .  $folder . '/wp-load.php');

    $year = '';
    $make = '';
    $model = '';
    $tire_size = '';

	if(isset($_POST['callSetYear'])) 
    {
        $year = $_POST['callSetYear'];
    }

    if(isset($_POST['callSetMake'])) 
    {
        $make = $_POST['callSetMake'];
    }

    if(isset($_POST['callSetModel'])) 
    {
        $model = $_POST['callSetModel'];
    }

    if(isset($_POST['callSetTire_Size'])) 
    {
        $tire_size = $_POST['callSetTire_Size'];
        
        $wpdb;
        
        $p2 = $wpdb->get_results("SELECT DISTINCT jxg_posts.ID, jxg_posts.post_title, jxg_wc_product_meta_lookup.product_id, jxg_wc_product_meta_lookup.sku, jxg_term_relationships.object_id, jxg_term_relationships.term_taxonomy_id
        FROM jxg_wc_product_meta_lookup
        JOIN jxg_posts ON jxg_wc_product_meta_lookup.product_id = jxg_posts.ID
        JOIN jxg_term_relationships ON jxg_posts.ID = jxg_term_relationships.object_id
        WHERE jxg_posts.post_status IN ('publish') AND jxg_term_relationships.term_taxonomy_id IN ('".$year."', '".$make."', '".$model."', '".$tire_size."')");

        $Arr = array();

        foreach ($p2 as $i) 
        {
            $id = $i->ID;
            array_push($Arr, $i->ID);
        }

        $vals = array_count_values($Arr);

        $value = max($vals);
        $key = array_search($value, $vals);

        $sku = '';

        foreach ($p2 as $i) 
        {
            if($i->ID == $key) 
            {
                $sku = $i->sku;
                break;
            }
        }

        print_r($sku);
    }
?>