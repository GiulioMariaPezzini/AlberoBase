<?php
	//INPUT: ID_ecommerce*, CATEGORIE, SOTTOCATEGORIE, MORE
	$ecommerce = $_POST["ID_ecommerce"];
	$categories = isset ($_POST['V8_payload'])? array(intval($_POST['V8_payload'])) : array();
	$MORE = isset($_POST['MORE']) ? intval($_POST['MORE']) : 0;
	
	//scarico il JSON contenente i dati dellecommerce
	$url = "http://localhost/utility/ecommerce/".$ecommerce.".json";
	//N.B. l'url andrà modificato per inserire quello degli script che generano i dati sugli ecommerce
	$DATA = json_decode(file_get_contents($url),true)["products"];

	$product_list = array();

	//seleziono tutti i prodotti la cui categoria è contenuta nella lista di categorie o sottocategorie selezionate
	//N.B. se non ho selezionato categorie o sottocategorie selezionerò tutti i prodotti.
	foreach($DATA as $product)
	{
		$test = false;
		foreach($product["categories"] as $product_category)
		{
			if ( in_array( $product_category["id"], $categories)) $test = true;
		}
		if ($test or empty($categories)) $product_list[]=$product;
	}

	//trasformo i dati ottenuti in card
	$product_card = array();
	foreach ($product_list as $product)
	{
		$title = $product["short_description"];
		$subtitle = price($product);
		$urlImage = $product["images"];
		$urlItem = "";
		$button = array("title" => "Seleziona", "next_vertex" => "N0" ,"type" => "url", "url" => (string)$product["link"]);

		$push = array("title" => $title , "subtitle" => $subtitle , "urlItem" => $urlItem , "urlImage" => $urlImage , "buttons" => array($button));
		array_push($product_card, $push);
	}

    //imposto il limite massimo di card da mostrare
    $limit = 9;

    //se ho già mostrato alcune di card elimino quelle già mostrate grazie al parametro $MORE
    $offset = $MORE * $limit - $MORE;
    $product_card =  array_slice($product_card, $offset);

    if (count($product_card) >= $limit)
    {
    //se le card dovessero essere in numero maggiore o uguale a $limit elimino quelle in eccesso
        $product_card = array_slice($product_card, 0, $limit-1);

        $title = "Per conoscere ulteriori prodotti clicca qui";
        $urlImage = "";//http://allindustries.it/talkforce/fastyle/fastyle-logo-black.png";
        $more_btn = array("title" => "More", "next_vertex" => "V10" ,"type" => "payload", "payload" => "More services");
        $none_btn = array("title" => "Termina selezione", "next_vertex" => "N0" ,"type" => "payload", "payload" => "Termina selezione");
        $more_card = array("title" => $title , "subtitle" => "" , "urlItem" => "" , "urlImage" => $urlImage , "buttons" => array($more_btn, $none_btn));
        array_push($product_card, $more_card);
    }
    else if (empty($product_card))
    {
    //se non dovessi avere card ritorno una card di default
        $title = "Non riusciamo a trovare prodotti disponibili per questo ecommerce";
        $urlImage = "";//"http://allindustries.it/talkforce/fastyle/fastyle-logo-black.png";
        $btn = array("title" => "RESTART", "next_vertex" => "V0" ,"type" => "payload", "payload" => "Continua");
        $card = array("title" => $title , "subtitle" => "" , "urlItem" => "" , "urlImage" => $urlImage , "buttons" => array($btn));
        array_push($product_card, $card);
    }
    else
    {
        $title = "Annulla selezione";
        $urlImage = "";//"http://allindustries.it/talkforce/fastyle/fastyle-logo-black.png";
        $none_btn = array("title" => "Termina selezione", "next_vertex" => "N0" ,"type" => "payload", "payload" => "Termina selezione");
        $more_card = array("title" => $title , "subtitle" => "" , "urlItem" => "" , "urlImage" => $urlImage , "buttons" => array($none_btn));
        array_push($product_card, $more_card);
    }


//$debug = json_encode($_POST,true).json_encode($output,true);
//$chat = "122545322";
//file_get_contents("https://api.telegram.org/bot285359951:AAE6MbFxWWu6jQKD7ubkzZ8Yq3zF9XPqlFQ/sendMessage?chat_id=".$chat."&text=".urlencode($debug));




    $output = array("PRODUCTS" => json_encode($product_card, true) , "MORE" => (string)($MORE+1));
    echo json_encode($output, true);


function price($product)
{
	$price = $product["price"];
	foreach($product["reduction_price"] as $discount)
	{
		$from = strtotime($discount["from"]);
                $to = strtotime($discount["to"]);
		$timestamp = time();

		if ($timestamp > $from && $timestamp < $to)
		{
			return "Prezzo: ".$discount["sale_price"]."€ invece che ".$price."€";
		}
	}
	return "Prezzo: ".$price."€";
}
?>
