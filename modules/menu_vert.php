<?php
	// Si l'utilisateur est valide,
	if ( isset( $_SESSION['username'] ) )
	{
		$db_fname 		= $_SESSION['fname'];
		$db_lname 		= $_SESSION['lname'];
		$db_tickets_sem	= $_SESSION['tickets_sem'];
		$db_tickets_we 	= $_SESSION['tickets_we'];
		$db_parcours 		= $_SESSION['parcours'];
		$db_reservations 	= $_SESSION['reservations'];
		$db_annulations 	= $_SESSION['annulations'];
		$db_invitations 	= $_SESSION['invitations'];
	}
	// sinon,
	else
	{
		$db_fname 		= "Visiteur";
		$db_lname 		= "";
		$db_tickets_sem	= 0;
		$db_tickets_we 	= 0;
		$db_parcours 		= 0;
		$db_reservations 	= 0;
		$db_annulations 	= 0;
		$db_invitations 	= 0;
	}
?>
<div id="vertical_navbar_holder">

	<ul id="vertical_navbar_userinfos">

		<?php
			echo '<li id="title">' . $db_fname . ' ' . $db_lname . '</li>
			<li>Golfeur</li>';
			if ( $_SESSION['admin'] == 0 )
			{
				echo '<li>Tickets SEM : '. $db_tickets_sem . '</li>
				<li>Tickets WE : ' . $db_tickets_we . '</li>';
			}
			echo '<li>Parcours : ' . $db_parcours . '</li>
			<li>Réservations : ' . $db_reservations . '</li>
			<li>Annulations : ' . $db_annulations . '</li>
			<li>Invitations : ' . $db_invitations . '</li>';
		?>

	</ul>
<!--
	<ul id="vertical_navbar">

		<li id="title">Golfeur</li>
		<li><a href="index.php">	ACCUEIL	</a></li>
		<li><a href="reservation.php">	RÉSERVATION	</a></li>
		<li><a href="aide.php">	AIDE		</a></li>
		<li><a href="contact.php">	CONTACT 	</a></li>
		<li><a href="connexion.php">	DÉCONNEXION	</a></li>

	</ul>
-->
	<?php
		if ( isset( $_SESSION['username'] ) )
		{
	?>
			<ul id="vertical_navbar_admin">

				<li id="title">
					<?php 
					if ( $_SESSION['admin'] == 1 ) { echo( "Administrateur" ); } else { echo( "Utilisateur" ); }
					?>
				</li>

				<div>
					<?php
						if ( $_SERVER['PHP_SELF'] == '/site/reservation.php' )
						{
							// Définition du tableau de joueurs.
							$liste = array();

							// appel la config.
							require_once( "modules/config.php" );

							// créer le controlleur et lien entre MySQL.
							$db_handle 	= new DBController();
							$conn 		= $db_handle -> getConn();

							// si invalide,
							if ( $conn -> connect_error )
							{
								// stop.
								die ( "Connexion interrompue: " . $conn -> connect_error );
							}

							//$date = date( 'Y-m-d' );

							// commande MySQL préparer.
							$query = $conn -> prepare( 'SELECT id, fname, lname FROM users' );

							$query -> bind_result( $db_id, $db_fn, $db_ln );
							$query -> execute();
							$result = $query -> get_result();

							while ( $row = $result -> fetch_row() )
							{
								array_push( $liste, $row[1] . " " . $row[2] );
							}

							// ferme tout.
							$query -> close();
							$conn -> close();

							// Si admin,
							if ( $_SESSION['admin'] == 1 )
							{
								// Variable qui ajoutera l'attribut selected de la liste déroulante
								$selected = '';
								 
								// Parcours du tableau
								echo '<select name="joueurs" id="joueurs">',"n";
								foreach ( $liste as $i => $name )
								{
									// Affichage de la ligne
									echo "\t",'<option value="', $i ,'"', "selected" ,'>', $name,'</option>',"\n";

									// Remise à zéro de $selected
									$selected = '';
								}
								echo '</select>',"\n";
							}

							// Bonjour, je suis les jours.
							$choix_jours = array();

							// Liste des jours.
							$days_fr = array (
								 "Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi", "Dimanche"
							);
							// Liste des mois.
							$months_fr = array (
								"Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre"
							);
							
							// Prend la date d'aujourd'hui.
							$date = getdate();

							// Créer les lignes du tableau.
							for ( $i = 0 ; $i < 15 ; $i++ )
							{
								// Modifie la date en fonction de la boucle.
								$datemaker = mktime( 0, 0, 0, $date['mon'], $date['mday'] + $i, $date['year'] );
								
								// Prend le jour et n° du jour.
								$day_number		= date( 'N', $datemaker );
								$day			= date( 'j', $datemaker );
								$month_number 	= date( 'n', $datemaker );
								$month			= date( 'F', $datemaker );

								//if ( $day_number < 6 )
									array_push( $choix_jours, $days_fr[$day_number-1] . " " . $day . " " . $months_fr[$month_number-1] );
							}

							// Variable qui ajoutera l'attribut selected de la liste déroulante
							$selected_jour = '';
								 
							// Parcours du tableau
							echo '<select name="jours" id="jours">',"n";
							foreach ( $choix_jours as $i => $name )
							{
								// Affichage de la ligne
								echo "\t",'<option value="', $i ,'"', "selected" ,'>', $name,'</option>',"\n";

								// Remise à zéro de $selected
								$selected_jour = '';
							}
							echo '</select>',"\n";

							if ( $_SESSION['admin'] == 0
								&& $_SESSION['tickets_sem'] > 0
							||	$_SESSION['admin'] == 0
								&& $_SESSION['tickets_we'] > 0
							|| $_SESSION['admin'] == 1 )
							{
					?>
								<button onclick="handleEvent(event)">Réserver</button>

								<script type="text/javascript">

									function handleEvent(oEvent)
									{
										var JoueursList 	= document.getElementById( "joueurs" );
										var JoursList 	= document.getElementById( "jours" );
										var JoueurAct	= "";
										var JourAct 	= "";

										if ( oEvent.type == "click" )
										{
											if ( JoueursList != null )
												JoueurAct = JoueursList[JoueursList.value].text;
											else
												JoueurAct = <?php echo json_encode( $_SESSION['fname'] . " " . $_SESSION['lname'] ); ?>;

											var DateAct = JoursList[JoursList.value].text;

											window.location.href = "http://localhost:3308/site/reservation.php?player=" + JoueurAct + "&date=" + DateAct;
										}
									}
								</script>
					<?php
							}

							if ( isset( $_GET['player'] ) && isset( $_GET['date'] ) )
							{
								$check_name = in_array( $_GET['player'], $liste );
								$check_date	= in_array( $_GET['date'], $choix_jours );

								if ( $check_name && $check_date )
								{
									// appel la config.
									require_once( "modules/config.php" );

									// créer le controlleur et lien entre MySQL.
									$db_handle 	= new DBController();
									$conn 		= $db_handle -> getConn();

									// si invalide,
									if ( $conn -> connect_error )
									{
										// stop.
										die ( "Connexion interrompue: " . $conn -> connect_error );
									}

									// variables.
									$player_res = $_GET["player"];
									$date_res 	= $_GET["date"];
									$pos_res	= 1;

									/*
										ARRACHAGE MYSQL.
									*/
									// commande MySQL préparer.
									$query_fetch = $conn -> prepare( "SELECT date_res, user, pos FROM reservations ORDER BY `pos` ASC" );

									// obtient la réponse.
									$query_fetch -> execute();
									$result_fetch = $query_fetch -> get_result();

									$check = 1;
									while ( $row = $result_fetch -> fetch_row() )
									{
										if ( $date_res == $row[0] )
										{
											if ( $pos_res > 3 )
											{
												$check = 0;
												echo "<br><b>Toutes les places sont déjà réservées.</b>";
												break;
											}
											if ( in_array( $player_res, $row ) )
											{
												$check = 0;
												echo "<br><b>La place a déjà été réservée.</b>";
												break;
											}

											if ( $pos_res == $row[2] )
											{
												$pos_res++;
											}
										}
									}

									if ( $check )
									{
										/*
											INSERTION MYSQL.
										*/
										// commande MySQL préparer.
										$query = $conn -> prepare(
											'INSERT INTO reservations (
												date_res,
												user,
												pos
											)
											VALUES (?, ?, ?)'
										);

										if ( !$query )
										{
											// stop.
											die ( "Connexion interrompue: " . $query );
										}

										$query -> bind_param( 
											"ssi",
											$date_res,
											$player_res,
											$pos_res
										);

										// obtient la réponse.
										$result = $query -> execute();

										// si positive,
										if ( !empty( $result ) )
										{
											
										}
										// sinon,
										else
										{
										}

										// ferme tout.
										$query -> close();
									}

									// ferme tout.
									$conn -> close();
								}
							}
						}
					?>
				</div>
			</ul>
	<?php
		}
	?>
</div>