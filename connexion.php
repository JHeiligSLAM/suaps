<?php
	if ( !empty( $_POST["connect-user"] ) )
	{
		/* 
			Validation principale.
			- Valide si oui ou non tous les champs sont remplis.
		*/
		// pour chaque champs.
		foreach ( $_POST as $key => $value )
		{
			// si une seule d'entre-elles est vide,
			if ( empty( $_POST[$key] ) )
			{
				// stop tout.
				$error_message = "Tous les champs sont requis.";
				break;
			}
		}

		/*
			Validation du MDP.
			- Valide si oui ou non le mot de passe est assez long et correspond bien au second.
		*/
		// TAILE MIN.
		// s'il n'y a pas eu d'erreur auparavant.
		if ( !isset( $error_message ) )
		{
			// si trop court,
			if ( strlen( $_POST['password'] ) < 8 )
			{
				// erreur.
				$error_message = 'Le mot de passe doit contenir un minimum de 8 lettres (la s?urit?avant tout!).<br>'; 
			}
		}

		/*
			Envoie des informations vers la base MySQL.
		*/
		// s'il n'y a pas eu d'erreur auparavant.
		if ( !isset( $error_message ) )
		{
			// appel la config.
			require_once( "modules/config.php" );

			// cr?r le controlleur et lien entre MySQL.
			$db_handle 	= new DBController();
			$bdd 		= $db_handle -> getConn();

			// si invalide,
			if ( $bdd -> connect_error )
			{
				// stop.
				die ( "Connexion interrompue: " . $bdd -> connect_error );
			}

			// variables.
			$username = strtolower( $_POST["userName"] );
			// crypt le mdp.
			$pw 	= $_POST["password"];
			$date 	= date( 'Y-m-d' );

			// commande MySQL pr?arer.
			$query = $bdd -> prepare( 'SELECT id, password, fname, lname, email, tickets_sem, tickets_we, parcours, reservations, annulations, invitations, admin FROM users WHERE LOWER( username ) = ?' );

			$query -> bind_param( "s", $username );

			// obtient la r?onse.
			$result = $query -> execute();

			// si positive,
			if ( !empty( $result ) )
			{
				// obtient les r?ultats.
				$query -> store_result();
				$query -> bind_result( 
					$db_id,
					$db_pw,
					$db_fn,
					$db_ln,
					$db_mail,
					$db_sem,
					$db_we,
					$db_par,
					$db_res,
					$db_ann,
					$db_inv,
					$db_admin
				);

				// cherche les correspondances.
				$rep = $query -> fetch();

				// si trouv?
				if ( $rep )
				{
					// v?ifie les mdps.
					if ( password_verify( $pw, $db_pw ) )
					{
						// Session start!
						$connected = session_start();

						// Store TOUTES les infos.
						$_SESSION['id'] 			= $db_id;
						$_SESSION['username']		= $username;
						$_SESSION['fname']			= $db_fn;
						$_SESSION['lname']			= $db_ln;
						$_SESSION['mail']			= $db_mail;
						$_SESSION['tickets_sem']	= $db_sem;
						$_SESSION['tickets_we']		= $db_we;
						$_SESSION['parcours']		= $db_par;
						$_SESSION['reservations']	= $db_res;
						$_SESSION['annulations']	= $db_ann;
						$_SESSION['invitations']	= $db_inv;
						$_SESSION['admin']			= $db_admin;

						// oh.
						$error_message 	= "";
						$success_message 	= "Connexion réussis!";

						if ( isset( $_POST['cookies'] ) )
						{
							setcookie( 'username', $username, time() + 365 * 24 * 3600 );
						}

						// Update la date de la dernière connexion.
						$query2 = $bdd -> prepare( 'UPDATE users SET date_last = ? WHERE id = ?' );
						$query2 -> bind_param( "si", $date, $db_id );

						// execute.
						$query2 -> execute();

						unset( $_POST );

						if ( $_SERVER['PHP_SELF'] == '/site/deconnexion.php'
						|| $_SERVER['PHP_SELF'] == '/site/inscription.php' )
							header("Location: index.php");
						else
							header( 'Location: ' . $_SERVER['HTTP_REFERER'] );
					}
					else
					{
						$error_message = "Mauvais mot de passe !";
					}
				}

				$query -> free_result();
			}
			// sinon,
			else
			{
				// erreur.
				$error_message = "Un problème est survenue. Réessayer ultérieurement!";	
			}

			// ferme tout.
			$query -> close();
			$bdd -> close();
		}
	}
?>

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
		<!--
		<?php
		//	include( '/modules/menu.php' );
		?>
		-->

		<!-- MAIN -->
		<div id="main">

			<form name="frmRegistration" method="post" action="">

				<table border="0" width="500" align="center" class="demo-table">

<?php
					if ( !empty( $success_message ) )
					{
						echo '<div class="success-message">';

							if ( isset( $success_message ) )
							{
								echo $success_message;
							}

						echo '</div>';
					}

					if ( !empty( $error_message ) )
					{
						echo '<div class="error-message">';

							if ( isset( $error_message ) )
							{
								echo $error_message;
							}

						echo '</div>';
					}
?>
					<tr>
						<td>Nom d'utilisateur</td>
						<td>
							<input type="text" class="demoInputBox" name="userName" value="
<?php
								if ( isset( $_POST['userName'] ) )
								{
									echo $_POST['userName'];
								}
?>">
						</td>
					</tr>

					<tr>
						<td>Mot de passe</td>
						<td>
							<input type="password" class="demoInputBox" name="password" value="">
						</td>
					</tr>

					<tr>
						<td colspan=2>
							<!--
							<input type="checkbox" name="cookies"> COOKIES!!
							-->
							<input type="submit" name="connect-user" value="Se connecter" class="btnConnect">
						</td>
					</tr>
				</table>
			</form>
		</div>
	</body>
</html>