<?php

abstract class Conexao {
#ybe7urape
	const USER = "root";
	const PASS = "";

	private static $instance = null;

	private static function conectar() {

		try {
			if (self::$instance == null):
				$dsn = "mysql:host=localhost;dbname=artemis";
				self::$instance = new PDO($dsn, self::USER, self::PASS);
				self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			
			endif;
		} catch (PDOException $e) {
			echo "Erro: " . $e->getMessage();
		}
		return self::$instance;
	}

	protected static function getDB() {
		return self::conectar();
	}
}

?>
