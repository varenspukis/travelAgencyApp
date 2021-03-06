<?php // Routage et actions

// Page d'acceuil

$app->get ( '/',
	// Affiche tous les circuits organisés prochainement
	function () use ($app)
	{
		$nextprogs =  array();
		$allprogs = get_all_programmations ();
		$now = new DateTime("now");
		foreach ($allprogs as $prog) {
			$datediff=date_diff($now,$prog->getDateDepart());
			$datesign = $datediff->format('%R');
			$daydiff = $datediff->format('%d');
			$monthdiff = $datediff->format('%m');
			if ($monthdiff < 12 && $datesign === '+'){
				$nextprogs[] = $prog;
			}
		}		
		return $app ['twig']->render ( 'frontoffice/home.html.twig', [

    			'progslist' => $nextprogs
		] ) ;
	}
)->bind ( 'index' );

// Liste tous les circuits avec programmation, pour le frontend
$app->get ( '/circuit', 
    function () use ($app) 
    {
	$progslist = get_all_programmations ();
	foreach ($progslist as $prog) {
		$circuitslist[] = $prog->getCircuit();
	}
    	// print_r($circuitslist);
    	
    	return $app ['twig']->render ( 'frontoffice/circuitslist.html.twig', [
    			'circuitslist' => $circuitslist
    	] );
    }
)->bind ( 'circuitlist' );

// circuitshow : affiche les détails d'un circuit
$app->get ( '/circuit/{id}', 
	function ($id) use ($app) 
	{
		$circuit = get_circuit_by_id ( $id );
		// print_r($circuit);
		//$programmations = get_programmations_by_circuit_id ( $id );
		//$circuit ['programmations'] = $programmations;

		return $app ['twig']->render ( 'frontoffice/circuitshow.html.twig', [ 
				'id' => $id,
				'circuit' => $circuit 
			] );
	}
)->bind ( 'circuitshow' );

// programmationlist : liste tous les circuits programmés
$app->get ( '/programmation', 
	function () use ($app) 
	{
		$programmationslist = get_all_programmations ();
		// print_r($programmationslist);

		return $app ['twig']->render ( 'frontoffice/programmationslist.html.twig', [ 
				'programmationslist' => $programmationslist 
			] );
	}
)->bind ( 'programmationlist' );

$app->get ( '/programmation/{id}',
	function ($id) use ($app)
	{
		$prog = get_programmation_by_id($id);
		return $app ['twig']->render ( 'frontoffice/programmationshow.html.twig', [
			'programmation' => $prog
		]);
	}
)->bind ( 'programmationshow' );
