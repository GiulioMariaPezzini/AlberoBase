<?php
	//INPUT: ID_ecommerce*, CATEGORIES, MORE
	$ecommerce = $_POST["ID_ecommerce"];
	$categories = isset ($_POST['V5_payload'])? array(intval($_POST['V5_payload'])) : array();
	$MORE = isset($_POST['MORE']) ? intval($_POST['MORE']) : 0;

	
//$chat = "122545322";
//file_get_contents("https://api.telegram.org/bot285359951:AAE6MbFxWWu6jQKD7ubkzZ8Yq3zF9XPqlFQ/sendMessage?chat_id=".$chat."&text=".urlencode(json_encode($_POST,true)));

	//scarico il JSON contenente i dati dellecommerce
	$url = "http://localhost/utility/ecommerce/".$ecommerce.".json";
	//N.B. l'url andrà modificato per inserire quello degli script che generano i dati sugli ecommerce
	$DATA = json_decode(file_get_contents($url),true)["subcategories"];

//file_get_contents("https://api.telegram.org/bot285359951:AAE6MbFxWWu6jQKD7ubkzZ8Yq3zF9XPqlFQ/sendMessage?chat_id=".$chat."&text=".urlencode(json_encode($DATA,true)));

	$subcategories_list = array();
	//seleziono tutti i prodotti la cui categoria è contenuta nella lista di categorie o sottocategorie selezionate
	//N.B. se non ho selezionato categorie o sottocategorie selezionerò tutti i prodotti.
	foreach($DATA as $subcategory)
	{
//file_get_contents("https://api.telegram.org/bot285359951:AAE6MbFxWWu6jQKD7ubkzZ8Yq3zF9XPqlFQ/sendMessage?chat_id=".$chat."&text=".urlencode(json_encode($subcategory,true)));
		if (in_array($subcategory["parent"], $categories) or empty($categories)) 
			$subcategories_list[]=$subcategory;
	}

	//trasformo i dati ottenuti in card
	$subcategories_card = array();
	foreach ($subcategories_list as $subcategory)
	{
		$title = $subcategory["name"];
		$subtitle = $subcategory["description"];
		$urlImage = $subcategory["image"];
		$urlItem = "";
		$button = array("title" => "Seleziona", "next_vertex" => "V9" ,"type" => "payload", "payload" => (string)$subcategory["id"]);

		$push = array("title" => $title , "subtitle" => $subtitle , "urlItem" => $urlItem , "urlImage" => $urlImage , "buttons" => array($button));
		array_push($subcategories_card, $push);
	}

    //imposto il limite massimo di card da mostrare
    $limit = 9;

    //se ho già mostrato alcune di card elimino quelle già mostrate grazie al parametro $MORE
    $offset = $MORE * $limit - $MORE;
    $subcategories_card =  array_slice($subcategories_card, $offset);

    if (count($subcategories_card) >= $limit)
    {
    //se le card dovessero essere in numero maggiore o uguale a $limit elimino quelle in eccesso
        $subcategories_card = array_slice($subcategories_card, 0, $limit-1);

        $title = "Per conoscere ulteriori prodotti clicca qui";
        $urlImage = "";//http://allindustries.it/talkforce/fastyle/fastyle-logo-black.png";
        $more_btn = array("title" => "More", "next_vertex" => "V7" ,"type" => "payload", "payload" => "More services");
        $none_btn = array("title" => "Termina selezione", "next_vertex" => "N0" ,"type" => "payload", "payload" => "Termina selezione");
        $more_card = array("title" => $title , "subtitle" => "" , "urlItem" => "" , "urlImage" => $urlImage , "buttons" => array($more_btn, $none_btn));
        array_push($subcategories_card, $more_card);
    }
    else if (empty($subcategories_card))
    {
    //se non dovessi avere card ritorno una card di default
        $title = "Non riusciamo a trovare prodotti disponibili per questo ecommerce";
        $urlImage = "";//"http://allindustries.it/talkforce/fastyle/fastyle-logo-black.png";
        $btn = array("title" => "RESTART", "next_vertex" => "V0" ,"type" => "payload", "payload" => "Continua");
        $card = array("title" => $title , "subtitle" => "" , "urlItem" => "" , "urlImage" => $urlImage , "buttons" => array($btn));
        array_push($subcategories_card, $card);
    }
    else
    {
        $title = "Annulla selezione";
        $urlImage = "";//"http://allindustries.it/talkforce/fastyle/fastyle-logo-black.png";
        $none_btn = array("title" => "Termina selezione", "next_vertex" => "N0" ,"type" => "payload", "payload" => "Termina selezione");
        $more_card = array("title" => $title , "subtitle" => "" , "urlItem" => "" , "urlImage" => $urlImage , "buttons" => array($none_btn));
        array_push($subcategories_card, $more_card);
    }

    $output = array("SUBCATEGORIES" => json_encode($subcategories_card, true) , "MORE" => (string)($MORE+1));

//file_get_contents("https://api.telegram.org/bot285359951:AAE6MbFxWWu6jQKD7ubkzZ8Yq3zF9XPqlFQ/sendMessage?chat_id=".$chat."&text=".urlencode(json_encode($output,true)));

    echo json_encode($output, true);
?>
