<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<link rel="stylesheet" type="text/css" href="css/theme.css">
		<title>Réservations Gold - SUAPS</title>
	</head>
	<body>
		<!-- EN-TÊTE -->
		<!-- MENU -->
		<?php
			include( '/modules/menu.php' );
		?>

		<!-- MAIN -->
		<div id="main">

			<?php
				include( '/modules/menu_vert.php' );
			?>

<!--
			<script type="text/javascript">

				function handleEvent(oEvent)
				{
				    var oTextbox = document.getElementById( "table" );
				    oTextbox.value += "\n" + oEvent.type;

				    if ( oEvent.type == "click" )
				    {
					    var iScreenX = oEvent.screenX;
					    var iScreenY = oEvent.screenY;
					    var b = "Clicked at "+iScreenX+" , "+iScreenY;

					    alert(b);
				    }
				}
			</script>
-->
			<div id="table_holder">
				
				<!--<table id="table" onclick="handleEvent(event)">-->
				<table id="table">

					<caption><center>Réservations</center></caption>

					<?php

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

						/*
							ARRACHAGE MYSQL.
						*/

						// commande MySQL préparer.
						$query_fetch = $conn -> prepare( "SELECT date_res, user, pos FROM reservations ORDER BY `pos` ASC" );

						// obtient la réponse.
						$query_fetch -> bind_result( 
							$db_date_res_fetch,
							$db_player_res_fetch,
							$db_pos_res_fetch
						);
						$query_fetch -> execute();
						$result_fetch = $query_fetch -> get_result();

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
							
							if ( $i == 0 )
							{
								echo "<tr id='test'><th>" . $months_fr[$month_number-1] . "</th>
									  <th>joueur 1</th>
									  <th>joueur 2</th>
									  <th>joueur 3</th>
									  <th>joueur 4</th>
									  <th>Annuler</th></tr>";
							}
							// Créer le string.
							$tr = $day_number > 5 ? "<tr id='weekend'>" : "<tr>";
							
							// AFFICHE LE TABLEAU!!!
							echo $tr . "<td id='day'" . $i . "><b>" . $days_fr[$day_number-1] . " " . $day . "</b></td>";

							$day_and_month = $days_fr[$day_number-1] . " " . $day . " " . $months_fr[$month_number-1];

							for ( $o = 0 ; $o < 4 ; $o++ )
							{
								$td_cell 	= "<td>";
								$cell		= "";

								while ( $row = $result_fetch -> fetch_row() )
								{
									if ( $day_and_month == $row[0]
										&& ($o + 1) == $row[2] )
									{
										$cell = $row[1];
										break;
									}
								}
								mysqli_data_seek( $result_fetch, 0 );

								if ( isset( $_SESSION['username'] ) )
								{
									if ( $cell == $_SESSION['fname'] . " " . $_SESSION['lname'] )
									{
										if ( $day_number > 5 )
											$td_cell = "<td id='yourselfweekend'>";
										else
											$td_cell = "<td id='yourself'>";
									}
								}

								echo $td_cell;
								echo $cell;
								echo "</td>";
								$current_index = $i;
							}
					?>
								<td><img src='contents/images/interdit.png' height='64' width='64'></td>
							</tr>
					<?php
						}
					?>
				</table>
			</div>

			<div>
			<?php
/*
				// Définition du tableau de couleurs
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

				// Variable qui ajoutera l'attribut selected de la liste déroulante
				$selected = '';
				 
				// Parcours du tableau
				echo '<select name="joueurs">',"n";
				foreach ( $liste as $i => $name )
				{
					// Test de la couleur
					//if( $i === 'rouge' )
					//{
					//	$selected = ' selected="selected"';
					//}
					// Affichage de la ligne
					echo "\t",'<option value="', $i ,'"', "selected" ,'>', $name,'</option>',"\n";

					// Remise à zéro de $selected
					$selected = '';
				}
				echo '</select>',"\n";
*/
			?>
			</div>
		</div>
	</body>
</html>