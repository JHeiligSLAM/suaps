<?php
	session_start();
	// https://openclassrooms.com/courses/creer-un-espace-membre-pour-son-site/tp-connexion-et-deconnexion
	session_destroy();

	$_SESSION 	= array();
	$infos 		= Array(
					false,
					'Déconnexion',
					'Vous êtes à présent déconnecté.',
					' - <a href="connexion.php">Se connecter</a>',
					'index.php',
					5
	);

	echo $infos;
	header( 'Location: ' . $_SERVER['HTTP_REFERER'] );
?>