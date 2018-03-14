<?php
	class DBController
	{
		private $DB_HOST 		= "localhost";
		private $DB_USER 		= "root";
		private $DB_PASSWORD 	= "admin";			// GROSS = RIEN | ERNY = admin
		private $DB_DATABASE 	= "suaps";		// GROSS = suaps | ERNY = suaps2
		private $bdd;
		
		function __construct()
		{
			$this -> bdd = $this -> connectDB();
		}
		
		function connectDB()
		{
			$bdd = mysqli_connect( 
				$this -> DB_HOST,
				$this -> DB_USER,
				$this -> DB_PASSWORD,
				$this -> DB_DATABASE
			);

			// Check connection
			if ( mysqli_connect_errno() )
			{
				echo "Failed to connect to MySQL: " . mysqli_connect_error();
			}
			
			return $bdd;
		}

		function getConn()
		{
			return $this -> bdd;
		}
	}
?>