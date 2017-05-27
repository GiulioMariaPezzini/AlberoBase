<?php
$product_card = array();
		$title = "Lacci Sneakers Colore Blu";
		$subtitle = "Prezzo: 3,99€ con sconto del 20%\nPrezzo Finale: 3,19€";
		$urlImage = "https://encrypted-tbn1.gstatic.com/shopping?q=tbn:ANd9GcSnCjwpT9bFiB3TMyEa8Q69YZtyMrNtsBBbgkMl9UFfwpJVfns1PzM0STy7&usqp=CAE";
		$urlItem = "";
		$button = array("title" => "Compra", "next_vertex" => "N0" ,"type" => "url", "url" => "http://allindustries.it");

		$push = array("title" => $title , "subtitle" => $subtitle , "urlItem" => $urlItem , "urlImage" => $urlImage , "buttons" => array($button));
		array_push($product_card, $push);


		$title = "Lacci Sneakers Colore Grigio";
		$subtitle = "Prezzo: 3,99€ con sconto del 20%\nPrezzo Finale: 3,19€";
		$urlImage = "https://encrypted-tbn0.gstatic.com/shopping?q=tbn:ANd9GcQzaScmfGsunrPJkKkcqcOoAVNnv9Qy5mNNqVTS3RDXkcH8HFOdgYglpkmJzg&usqp=CAE";
		$urlItem = "";
		$button = array("title" => "Compra", "next_vertex" => "N0" ,"type" => "url", "url" => "http://allindustries.it");

		$push = array("title" => $title , "subtitle" => $subtitle , "urlItem" => $urlItem , "urlImage" => $urlImage , "buttons" => array($button));
		array_push($product_card, $push);

    

    $output = array("PRODUCTS" => json_encode($product_card, true));
    echo json_encode($output, true);
?>

