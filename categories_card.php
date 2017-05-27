<?php
	//INPUT: ID_ecommerce*, MORE
	$ecommerce = $_POST["ID_ecommerce"];
	$MORE = isset($_POST['MORE']) ? intval($_POST['MORE']) : 0;
$ecommerce=1;
	//scarico il JSON contenente i dati dellecommerce
	$url = "http://localhost/utility/ecommerce/".$ecommerce.".json";
	//N.B. l'url andrà modificato per inserire quello degli script che generano i dati sugli ecommerce
	$categories_list = json_decode(file_get_contents($url),true)["categories"];

	$categories_card = array();
	foreach ($categories_list as $category)
	{
		$title = $category["name"];
		$subtitle = $category["description"];
		$urlImage = $category["image"];
		$urlItem = "";
		$button = array("title" => "Seleziona", "next_vertex" => "V6" ,"type" => "payload", "payload" => (string)$category["id"]);

		$push = array("title" => $title , "subtitle" => $subtitle , "urlItem" => $urlItem , "urlImage" => $urlImage , "buttons" => array($button));
		array_push($categories_card, $push);
	}

    //imposto il limite massimo di card da mostrare
    $limit = 9;

    //se ho già mostrato alcune di card elimino quelle già mostrate grazie al parametro $MORE
    $offset = $MORE * $limit - $MORE;
    $categories_card =  array_slice($categories_card, $offset);

    if (count($categories_card) >= $limit)
    {
    //se le card dovessero essere in numero maggiore o uguale a $limit elimino quelle in eccesso
        $categories_card = array_slice($categories_card, 0, $limit-1);

        $title = "Per conoscere ulteriori prodotti clicca qui";
        $urlImage = "";//http://allindustries.it/talkforce/fastyle/fastyle-logo-black.png";
        $more_btn = array("title" => "More", "next_vertex" => "V4" ,"type" => "payload", "payload" => "More services");
        $none_btn = array("title" => "Termina selezione", "next_vertex" => "N0" ,"type" => "payload", "payload" => "Termina selezione");
        $more_card = array("title" => $title , "subtitle" => "" , "urlItem" => "" , "urlImage" => $urlImage , "buttons" => array($more_btn, $none_btn));
        array_push($categories_card, $more_card);
    }
    else if (empty($categories_card))
    {
    //se non dovessi avere card ritorno una card di default
        $title = "Non riusciamo a trovare prodotti disponibili per questo ecommerce";
        $urlImage = "";//"http://allindustries.it/talkforce/fastyle/fastyle-logo-black.png";
        $btn = array("title" => "RESTART", "next_vertex" => "V0" ,"type" => "payload", "payload" => "Continua");
        $card = array("title" => $title , "subtitle" => "" , "urlItem" => "" , "urlImage" => $urlImage , "buttons" => array($btn));
        array_push($categories_card, $card);
    }
    else
    {
        $title = "Annulla selezione";
        $urlImage = "";//"http://allindustries.it/talkforce/fastyle/fastyle-logo-black.png";
        $none_btn = array("title" => "Termina selezione", "next_vertex" => "N0" ,"type" => "payload", "payload" => "Termina selezione");
        $more_card = array("title" => $title , "subtitle" => "" , "urlItem" => "" , "urlImage" => $urlImage , "buttons" => array($none_btn));
        array_push($categories_card, $more_card);
    }

    $output = array("CATEGORIES" => json_encode($categories_card, true) , "MORE" => (string)($MORE+1));
    echo json_encode($output, true);
?>
