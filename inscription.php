<?php
	if ( !empty( $_POST["register-user"] ) )
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
			Validation des tailles maximales.
			- Valide si oui ou non tous les champs ne dépassent pas leur taille limite (sauf pour les termes).
		*/
		// NOM D'UTILISATEUR.
		// s'il n'y a pas eu d'erreur auparavant.
		if ( !isset( $error_message ) )
		{
			// si trop long,
			if ( strlen( $_POST['userName'] ) > 16 )
			{
				// erreur.
				$error_message = 'Votre nom d\'utilisateur est trop long, utilisez-en un autre. (max 16)<br>'; 
			}
		}
		// PRÉNOM.
		// s'il n'y a pas eu d'erreur auparavant.
		if ( !isset( $error_message ) )
		{
			// si trop long,
			if ( strlen( $_POST['firstName'] ) > 16 )
			{
				// erreur.
				$error_message = 'Votre prénom est trop long. (max 16)<br>'; 
			}
		}
		// NOM.
		// s'il n'y a pas eu d'erreur auparavant.
		if ( !isset( $error_message ) )
		{
			// si trop long,
			if ( strlen( $_POST['lastName'] ) > 32 )
			{
				// erreur.
				$error_message = 'Votre nom est trop long. (max 32)<br>'; 
			}
		}
		// MDP.
		// s'il n'y a pas eu d'erreur auparavant.
		if ( !isset( $error_message ) )
		{
			// si trop long,
			if ( strlen( $_POST['password'] ) > 64 )
			{
				// erreur.
				$error_message = 'Votre mot de passe est trop long. La sécurité est importante, mais quand même. (max 64)<br>';
			}
		}
		// E-MAIL.
		// s'il n'y a pas eu d'erreur auparavant.
		if ( !isset( $error_message ) )
		{
			// si trop long,
			if ( strlen( $_POST['userEmail'] ) > 32 )
			{
				// erreur.
				$error_message = 'Votre E-Mail est trop long, utilisez-en un autre. (max 32)<br>';
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
				$error_message = 'Le mot de passe doit contenir un minimum de 8 lettres (la sécurité avant tout!).<br>'; 
			}
		}
		// IDENTIQUE.
		// s'il n'y a pas eu d'erreur auparavant.
		if ( !isset( $error_message ) )
		{
			// si non identique,
			if ( $_POST['password'] != $_POST['confirm_password'] )
			{
				// erreur.
				$error_message = 'Les mots de passe doivent être identiques<br>'; 
			}
		}

		/*
			Validation de l'E-mail.
			- Valide si oui ou non l'E-mail est correcte.
		*/
		// s'il n'y a pas eu d'erreur auparavant.
		if ( !isset( $error_message ) )
		{
			// si non valide,
			if ( !filter_var( $_POST["userEmail"], FILTER_VALIDATE_EMAIL ) )
			{
				// erreur.
				$error_message = "L'E-mail doit être valide";
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
			$username 	= $_POST["userName"];
			$fname 	= $_POST["firstName"];
			$lname 	= $_POST["lastName"];
			// crypt le mdp.
			$pw 		= password_hash( $_POST["password"], PASSWORD_DEFAULT );
			$email 		= $_POST["userEmail"];
			$date 		= date( 'Y-m-d' );
			$undefined_v	= 0;

			// commande MySQL préparer.
			$query = $conn -> prepare(
				'INSERT INTO users (
					username,
					fname,
					lname,
					password,
					email,
					date_reg,
					date_last,
					tickets_sem,
					tickets_we,
					parcours,
					reservations,
					annulations,
					invitations,
					admin
				)
				VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)' );

			if ( !$query )
			{
				// stop.
				die ( "Connexion interrompue: " . $query );
			}

			$query -> bind_param( 
				"sssssssiiiiiii",
				$username,
				$fname,
				$lname,
				$pw,
				$email,
				$date,
				$date,
				$undefined_v,
				$undefined_v,
				$undefined_v,
				$undefined_v,
				$undefined_v,
				$undefined_v,
				$undefined_v
			);

			// obtient la réponse.
			$result = $query -> execute();

			// si positive,
			if ( !empty( $result ) )
			{
				// aucune erreur et succès.
				$error_message 		= "";
				$success_message 	= "Inscription complète!";	
				unset( $_POST );
			}
			// sinon,
			else
			{
				// erreur.
				$error_message = "Un problème est survenue. Réessayer ultérieurement!";	
			}

			// ferme tout.
			$query -> close();
			$conn -> close();
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
		<?php
			include( '/modules/menu.php' );
		?>

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
						<td><input type="text" class="demoInputBox" name="userName" value="
<?php
							if ( isset( $_POST['userName'] ) )
							{
								echo $_POST['userName'];
							}
?>">
						</td>
					</tr>

					<tr>
						<td>Prénom</td>
						<td><input type="text" class="demoInputBox" name="firstName" value="
<?php
							if ( isset( $_POST['firstName'] ) )
							{
								echo $_POST['firstName'];
							}
?>">
						</td>
					</tr>

					<tr>
						<td>Nom</td>
						<td><input type="text" class="demoInputBox" name="lastName" value="
<?php
							if ( isset( $_POST['lastName'] ) )
							{
								echo $_POST['lastName'];
							}
?>">
						</td>
					</tr>

					<tr>
						<td>Mot de passe</td>
						<td><input type="password" class="demoInputBox" name="password" value=""></td>
					</tr>

					<tr>
						<td>Confirmez votre mot de passe</td>
						<td><input type="password" class="demoInputBox" name="confirm_password" value=""></td>
					</tr>

					<tr>
						<td>E-mail</td>
						<td><input type="text" class="demoInputBox" name="userEmail" value="
<?php
							if ( isset( $_POST['userEmail'] ) )
							{
								echo $_POST['userEmail'];
							}
?>">
						</td>
					</tr>

					<tr>
						<td colspan=2>
						<input type="submit" name="register-user" value="S'inscrire" class="btnRegister"></td>
					</tr>
				</table>
			</form>
		</div>
	</body>
</html>