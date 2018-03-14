<?php
if ( isset( $_POST['mailform'] ) )
{
	if(!empty($_POST['nom']) AND !empty($_POST['mail']) AND !empty($_POST['message']))
	{
		$header="MIME-Version: 1.0\r\n";
		$header.='From:"New_site"'."\n";
		$header.='Content-Type:text/html; charset="uft-8"'."\n";
		$header.='Content-Transfer-Encoding: 8bit';

		$message='
		<html>
			<body>
				<div>
					<u>Nom de l\'expediteur :</u>'.$_POST['nom'].'<br />
					<u>Mail de l\'expediteur :</u>'.$_POST['mail'].'<br />
					<br />
					<u>Message de l\'expediteur :</u>'.nl2br($_POST['message']).'
					<br />
				</div>
			</body>
		</html>
		';

		$success = mail("laurent.schaeffer20@gmail.com", "CONTACT - site SUAPS", $message, $header);
		
		if ( $success )
		{
			$msg="<br /><b style=\"color : green\"> Votre message a bien été envoyé !</b>";
		}
		else
		{
			$msg="<br /><b style=\"color : green\"> NOPE MDR !</b>";
		}
	}
	else
	{
		$msg="<br /><b style=\"color : red\"> Tous les champs doivent être complétés !</b>";
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

			<?php
				include( '/modules/menu_vert.php' );
			?>

			<div id="table_holder">
				
				<section id="content">
					
					<div class="wrapper">

						<form method="POST" id="ContactForm" action="#">

							<div>
								
								<div class="wrapper">
									<input type="text" class="input" name="nom" placeholder="Votre nom" value="<?php if(isset($_POST['nom'])) { echo $_POST['nom']; } ?>" />
								</div>
								
								<div class="wrapper">
									<input type="email" class="input" name="mail" placeholder="Votre email" value="<?php if(isset($_POST['mail'])) { echo $_POST['mail']; } ?>" />
								</div>
							
								<div class="textarea_box">
									<textarea name="message" placeholder="Votre message" cols="1" rows="1"><?php if(isset($_POST['message'])) { echo $_POST['message']; } ?></textarea>
								</div>
							
								<input type="submit" value="Envoyer !" name="mailform" style="width:100px"/>

							</div>
						</form>
					</div>
				</section>
			</div>
		</div>
	</body>
</html>