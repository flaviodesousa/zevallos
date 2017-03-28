<?php
class Merlim {
	public $connType;
	public $connMSSQL;
	public $connMySQL;
	
	public $dateDay;
	public $dateMonth;
	public $dateYear;

	public $hourHour;
	public $hourMinute;
	public $hourSecond;
	
	public $pageTitle;
	public $pageBody;	

  public $siteID;
  public $siteSign;
  public $siteName;
  public $siteFonte;

  public $urlBase;

  public $siteCacheFolder;
	
	function __construct( $class ) {
		switch($this->$connType) {
			case 1: // MySQL
	  		if (!$this->connMySQL) {
	  		}
	  		break;
	  		
			default: // MSSQL
	  		if (!$this->connMSSQL) {
	  		}
		}
		
	  $this->dateDay   = 0;
		$this->dateMonth = 0;
		$this->dateYear  = 0;

		$this->hourHour   = 0;
		$this->hourMinute = 0;
		$this->hourSecond = 0;

		$this->pageTitle = "";
		$this->pageBody  = "";

		$this->siteID    = 34; // OAB
		$this->siteSign  = "OAB";
  	$this->siteName  = "Ordem dos Advogados do Brasil";
		$this->siteFonte = 10;

		$this->urlBase = "http://www.oab.org.br";

		$this->siteCacheFolder = "C:\\";
		
	}	
	
	function readHTTP($urlLista) {
  	$this->edible = $edible;
    $this->color = $color;
  }	
}
?>