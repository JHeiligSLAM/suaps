<!DOCTYPE html>
<html>
	<?php
		if ( session_status() === PHP_SESSION_ACTIVE )
		{
			$connected = true;
		}
		else
		{
			$connected = session_start();
		}
	?>
	<head>
		<meta charset="utf-8" />
		<link rel="stylesheet" type="text/css" href="css/theme.css">
		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.1/jquery.min.js"></script>
		<title>Réservations Gold - SUAPS</title>
	</head>
	<body>

		<!-- EN-TÊTE -->
		<div id="head">

			<div id="head-logo"></div>

		</div>

		<!-- MENU -->
		<div>
			<ul id="horizontal_navbar">

				<li><a href="index.php">		ACCUEIL		</a></li>
				<li><a href="reservation.php">	RÉSERVATION	</a></li>
				<!--<li><a href="aide.php">			AIDE		</a></li>-->
				<li><a href="contact.php">		CONTACT 	</a></li>
				<li>
					<?php
						if ( $connected )
						{
							if ( isset( $_SESSION['username'] ) )
							{
								echo '<a href="deconnexion.php">DÉCONNEXION</a>';
							}
							else
							{
					?>
								<a href="#?w=500" rel="popup_name" class="poplight">CONNEXION</a>';

								<div id="popup_name" class="popup_block">

									<?php
										include( '/connexion.php' );;
									?>

								</div>

								<script>
									//Lorsque vous cliquez sur un lien de la classe poplight et que le href commence par #
									$( 'a.poplight[href^=#]' ).click( function()
									{
										var popID 	= $( this ).attr( 'rel' ); 
										var popURL 	= $( this ).attr( 'href' );

										//Récupérer les variables depuis le lien
										var query		= popURL.split( '?' );
										var dim			= query[1].split( '&amp;' );
										var popWidth 	= dim[0].split( '=' )[1];

										//Faire apparaitre la pop-up et ajouter le bouton de fermeture
										$( '#' + popID ).fadeIn().css(
										{
											'width': Number( popWidth )
										})
										.prepend( '<a href="#" class="close"><img src="close_pop.png" class="btn_close" title="Fermer" alt="Fermer" /></a>' );

										//Récupération du margin, qui permettra de centrer la fenêtre - on ajuste de 80px en conformité avec le CSS
										var popMargTop = ( $( '#' + popID ) .height() + 80 ) / 2;
										var popMargLeft = ( $( '#' + popID ).width() + 80 ) / 2;

										//On affecte le margin
										$( '#' + popID ).css(
										{
											'margin-top': 	-popMargTop,
											'margin-left': 	-popMargLeft
										});

										//Effet fade-in du fond opaque
										$( 'body' ).append( '<div id="fade"></div>' );
										//Apparition du fond - .css({'filter' : 'alpha(opacity=80)'}) pour corriger les bogues de IE
										$( '#fade' ).css(
										{
											'filter': 'alpha(opacity=80)'
										}).fadeIn();

										return false;
									});

									//Fermeture de la pop-up et du fond
									$( 'a.close, #fade' ).live( 'click', function()
									{
										$( '#fade , .popup_block' ).fadeOut( function()
										{
											$( '#fade, a.close' ).remove();
										});
										return false;
									});
								</script>
								<?php
							}
						}
					?>
				</li>
				<li>
					<?php
						if ( $connected )
						{
							if ( !isset( $_SESSION['username'] ) )
							{
								echo '<a href="inscription.php">INSCRIPTION</a>';
							}
						}
					?>
				</li>
				<li>
					<?php
						if ( $connected )
						{
							if ( isset( $_SESSION['username'] ) )
							{
								echo '<a>Bienvenue ' . $_SESSION['fname'] . ' ' . $_SESSION['lname'] . ' !</a>';
							}
							else
							{
								echo '<a>Bienvenue visiteur !</a>';
							}
						}
					?>
				</li>
			</ul>
		</div>
	</body>
</html>