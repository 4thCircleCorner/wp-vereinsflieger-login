<?php 
class VereinsfliegerRestInterface
{
	private $InterfaceUrl = 'https://www.vereinsflieger.de/interface/rest/';
	private $AccessToken;
	private $HttpStatusCode = 0;
	private $aResponse = array();
	
	//=============================================================================================
	// Anmelden
	//=============================================================================================
	public function SignIn($UserName, $Password, $Cid=0, $AppKey='', $AuthSecret='')
	{
		// Accesstoken holen
		$this->SendRequest("GET", "auth/accesstoken", null);
		if ($this->HttpStatusCode != 200 || !$this->aResponse) {
			return false;
		}
		$this->AccessToken = $this->aResponse['accesstoken'];
		//$PassWordHash = md5(md5($Password).$this->AccessToken);
		$PassWordHash = md5($Password);
		// Anmelden
		$Data = array(
			'accesstoken' 	=> $this->AccessToken, 
			'username' 		=> $UserName, 
			'password' 		=> $PassWordHash,
			'cid'      		=> $Cid,
			'appkey'   		=> $AppKey,
			'auth_secret' 	=> $AuthSecret);
		$this->SendRequest("POST", "auth/signin", $Data);
		return (($this->HttpStatusCode) == 200);
	}

	//=============================================================================================
	// Abmelden
	//=============================================================================================
	public function SignOut()
	{
		$Data = array('accesstoken' => $this->AccessToken);
		$this->SendRequest("DELETE", "auth/signout/".$this->AccessToken, $Data);
		return (($this->HttpStatusCode) == 200);
	}
	
	//=============================================================================================
	// GetUser
	//=============================================================================================
	public function GetUser()
	{
		$Data = array('accesstoken' => $this->AccessToken);
		$this->SendRequest("POST", "auth/getuser", $Data);
		if ($this->HttpStatusCode == 200) {
			return $this->aResponse;
		}
		return array();
	}
	
	//=============================================================================================
	// InsertFlight
	//=============================================================================================
	public function InsertFlight($aFlighData)
	{
		$aFlighData['accesstoken'] = $this->AccessToken;
		$this->SendRequest("POST", "flight/add", $aFlighData);
		return (($this->HttpStatusCode) == 200);
	}
	
	//=============================================================================================
	// UpdateFlight
	//=============================================================================================
	public function UpdateFlight($Flid, $aFlighData)
	{
		$aFlighData['accesstoken'] = $this->AccessToken;
		$this->SendRequest("PUT", "flight/edit/".intval($Flid), $aFlighData);
		return (($this->HttpStatusCode) == 200);
	}
	
	//=============================================================================================
	// DeleteFlight
	//=============================================================================================
	public function DeleteFlight($Flid)
	{
		$aFlighData['accesstoken'] = $this->AccessToken;
		$this->SendRequest("DELETE", "flight/delete/".intval($Flid), $aFlighData);
		return (($this->HttpStatusCode) == 200);
	}
	
	//=============================================================================================
	// GetFlight
	//=============================================================================================
	public function GetFlight($Flid)
	{
		$aFlighData['accesstoken'] = $this->AccessToken;
		$this->SendRequest("POST", "flight/get/".intval($Flid), $aFlighData);
		return (($this->HttpStatusCode) == 200);
	}
	
	//=============================================================================================
	// GetFlights_Today
	//=============================================================================================
	public function GetFlights_today()
	{
		$aData = array('accesstoken' => $this->AccessToken);
		$this->SendRequest("POST", "flight/list/today",$aData);
		return (($this->HttpStatusCode) == 200);
	}
	
	//=============================================================================================
	// GetFlights_Date
	//=============================================================================================
	public function GetFlights_date($Date)
	{
		$aData = array(
			'accesstoken' => $this->AccessToken,
			'dateparam' => $Date
			);
		$this->SendRequest("POST", "flight/list/date",$aData);
		return (($this->HttpStatusCode) == 200);
	}
		
	//=============================================================================================
	// GetFlights_Modified
	//=============================================================================================
	public function GetFlights_Modified($Days)
	{
		$aData = array(
			'accesstoken' => $this->AccessToken,
			'days' => $Days
			);
		$this->SendRequest('POST', 'flight/list/modified', $aData);
		return (($this->HttpStatusCode) == 200);
	}
	
	//=============================================================================================
	// GetFlights_NoDate
	//=============================================================================================
	public function GetFlights_nodate()
	{
		$aData = array(
			'accesstoken' => $this->AccessToken);
		$this->SendRequest("POST", "flight/list/nodate",$aData);
		return (($this->HttpStatusCode) == 200);
	}
		
	//=============================================================================================
	// GetFlights_plane
	//=============================================================================================
	public function GetFlights_plane($Callsign, $Count)
	{
		$aData = array(
			'accesstoken' => $this->AccessToken,
			'callsign' => $Callsign,
			'count' => $Count);
		$this->SendRequest("POST", "flight/list/plane",$aData);
		return (($this->HttpStatusCode) == 200);
	}
		
	//=============================================================================================
	// GetFlights_user
	//=============================================================================================
	public function GetFlights_user($Uid, $Count)
	{
		$aData = array(
			'accesstoken' => $this->AccessToken,
			'uid' => $Uid,
			'count' => $Count);
		$this->SendRequest("POST", "flight/list/user",$aData);
		return (($this->HttpStatusCode) == 200);
	}
		
	//=============================================================================================
	// GetFlights_myflights
	//=============================================================================================
	public function GetFlights_myflights($Count)
	{
		$aData = array(
			'accesstoken' => $this->AccessToken,
			'count' => $Count);
		$this->SendRequest("POST", "flight/list/myflights",$aData);
		return (($this->HttpStatusCode) == 200);
	}
	
	//=============================================================================================
	// GetPublicCalendar
	//=============================================================================================
	public function GetPublicCalendar($HpAccessCode="")
	{
		$aData = array(
			'hpaccesscode' => $HpAccessCode
			);
		$this->SendRequest("POST", "calendar/list/public",$aData);
		return (($this->HttpStatusCode) == 200);
	}

	//=============================================================================================
	// GetUsers
	//=============================================================================================
	public function GetUsers()
	{
		$aData = array('accesstoken' => $this->AccessToken);
		$this->SendRequest("POST", "user/list",$aData);
		return (($this->HttpStatusCode) == 200);
	}	
		
	//=============================================================================================
	// GetReservations
	//=============================================================================================
	public function GetReservations()
	{
		$aData = array(
			'accesstoken' => $this->AccessToken
			);
		$this->SendRequest("POST", "reservation/list/active",$aData);
		return (($this->HttpStatusCode) == 200);
	}
		
	//=============================================================================================
	// GetAirplaneMaintenanceData
	//=============================================================================================
	public function GetAirplaneMaintenanceData($Callsign)
	{
		$aData = array(
			'accesstoken' => $this->AccessToken
			);
		$this->SendRequest("POST", "maintenance/airplane/".$Callsign, $aData);
		return (($this->HttpStatusCode) == 200);
	}
	
	//=============================================================================================
	// InsertAccountTransaction
	//=============================================================================================
	public function InsertAccountTransaction($aData)
	{
		$aData['accesstoken'] = $this->AccessToken;
		$this->SendRequest("POST", "account/add", $aData);
		return (($this->HttpStatusCode) == 200);
	}
	
	//=============================================================================================
	// GetAccountTransaction
	//=============================================================================================
	public function GetAccountTransaction($Adid)
	{
		$aData = array(
			'accesstoken' => $this->AccessToken
			);
		$this->SendRequest("POST", "account/get/".intval($Adid), $aData);
		return (($this->HttpStatusCode) == 200);
	}
	
	//=============================================================================================
	// GetAccountTransactions_today
	//=============================================================================================
	public function GetAccountTransactions_today()
	{
		$aData = array(
			'accesstoken' => $this->AccessToken
			);
		$this->SendRequest("POST", "account/list/today", $aData);
		return (($this->HttpStatusCode) == 200);
	}
	
	//=============================================================================================
	// GetAccountTransactions_year
	//=============================================================================================
	public function GetAccountTransactions_year($Year)
	{
		$aData = array(
			'accesstoken' => $this->AccessToken,
			'year' => $Year
			);
		$this->SendRequest("POST", "account/list/year", $aData);
		return (($this->HttpStatusCode) == 200);
	}

	//=============================================================================================
	// GetAccountTransactions_daterange
	//=============================================================================================
	public function GetAccountTransactions_daterange($DateFrom, $DateTo)
	{
		$aData = array(
			'accesstoken' => $this->AccessToken,
			'datefrom' => $DateFrom,
			'dateto' => $DateTo
			);
		$this->SendRequest("POST", "account/list/daterange", $aData);
		return (($this->HttpStatusCode) == 200);
	}

	//=============================================================================================
	// GetWorkHours_daterange
	//=============================================================================================
	public function GetWorkHours_daterange($DateFrom, $DateTo)
	{
		$aData = array(
			'accesstoken' => $this->AccessToken,
			'datefrom' => $DateFrom,
			'dateto' => $DateTo
			);
		$this->SendRequest('POST', 'workhours/list/daterange', $aData);
		return (($this->HttpStatusCode) == 200);
	}

	//=============================================================================================
	// GetWorkHourCategories
	//=============================================================================================
	public function GetWorkHourCategories()
	{
		$aData = array('accesstoken' => $this->AccessToken);
		$this->SendRequest('POST', 'workhourcategories/list', $aData);
		return (($this->HttpStatusCode) == 200);
	}

	//=============================================================================================
	// InsertWorkHours
	//=============================================================================================
	public function InsertWorkHours($aData)
	{
		$aData['accesstoken'] = $this->AccessToken;
		$this->SendRequest('POST', 'workhours/add', $aData);
		return (($this->HttpStatusCode) == 200);
	}

	//=============================================================================================
	// GetArticles
	//=============================================================================================
	public function GetArticles()
	{
		$aData = array('accesstoken' => $this->AccessToken);
		$this->SendRequest('POST', 'articles/list', $aData);
		return (($this->HttpStatusCode) == 200);
	}


	//=============================================================================================
	// InsertSale
	//=============================================================================================
	public function InsertSale($aData)
	{
		$aData['accesstoken'] = $this->AccessToken;
		$this->SendRequest('POST', 'sale/add', $aData);
		return (($this->HttpStatusCode) == 200);
	}

	//=============================================================================================
	// GetHttpStatusCode
	//=============================================================================================
	public function GetHttpStatusCode()
	{
		return $this->HttpStatusCode;
	}
	//=============================================================================================
	// GetResponse
	//=============================================================================================
	public function GetResponse()
	{
		return $this->aResponse;
	}
	
	//=============================================================================================
	// SendRequest
	//=============================================================================================
	private function SendRequest($Method, $Resource, $Data)
	{
		$InterfaceUrl = $this->InterfaceUrl.$Resource;
		$CurlHandle = curl_init();
		curl_setopt($CurlHandle, CURLOPT_URL, $InterfaceUrl);
		curl_setopt($CurlHandle, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
		switch($Method) {
			case 'GET':
				break;
			case 'POST':
				$Fields = http_build_query(is_array($Data) ? $Data : array(),'','&');
				curl_setopt($CurlHandle, CURLOPT_HTTPHEADER, array('Content-Length: ' . strlen($Fields)));
				curl_setopt($CurlHandle, CURLOPT_POST, 1);
				curl_setopt($CurlHandle, CURLOPT_POSTFIELDS, $Fields);	
				break;
			case 'PUT':
				//$Fields = http_build_query(is_array($Data) ? $Data : array());
				$Fields = http_build_query(is_array($Data) ? $Data : array(),'','&');
				curl_setopt($CurlHandle, CURLOPT_HTTPHEADER, array('Content-Length: ' . strlen($Fields)));
				curl_setopt($CurlHandle, CURLOPT_CUSTOMREQUEST, 'PUT'); 
				curl_setopt($CurlHandle, CURLOPT_POSTFIELDS, $Fields);	
				break;
			case 'DELETE':
				//$Fields = http_build_query(is_array($Data) ? $Data : array());
				$Fields = http_build_query(is_array($Data) ? $Data : array(),'','&');
				curl_setopt($CurlHandle, CURLOPT_HTTPHEADER, array('Content-Length: ' . strlen($Fields)));
				curl_setopt($CurlHandle, CURLOPT_CUSTOMREQUEST, 'DELETE'); 
				curl_setopt($CurlHandle, CURLOPT_POSTFIELDS, $Fields);	
				break;
		}
		curl_setopt($CurlHandle, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($CurlHandle, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($CurlHandle, CURLOPT_SSL_VERIFYPEER, false);
		$Html = curl_exec($CurlHandle);
		$this->HttpStatusCode = curl_getinfo($CurlHandle, CURLINFO_HTTP_CODE);
		curl_close($CurlHandle);
		$this->aResponse = json_decode($Html, true);
		if (!$this->aResponse) {
			return false;
		}
		return true;
	}	

	//=============================================================================================
	// SetInterfaceUrl
	//=============================================================================================
	public function SetInterfaceUrl($InterfaceUrl)
	{
		return $this->InterfaceUrl = $InterfaceUrl;
	}

}
?>