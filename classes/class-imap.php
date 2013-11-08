<?php

class IS_IMAP extends Net_IMAP {

	/**
	 * @var boolean Whether the IMAP host is Gmail or not.
	 */
	protected $is_gmail = false;

	function __construct( $host = 'localhost',
                        $port = 143, 
                        $enableSTARTTLS = true,
                        $encoding = 'ISO-8859-1' ) {

		parent::Net_IMAP( $host, $port, $enableSTARTTLS, $encoding );

		if ( false !== strpos( $host, 'gmail.com' ) || false !== strpos( $host, 'googlemail.com' ) ) {
			$this->is_gmail = true;
		}
	}

	public function gmailSearchCount( $query ) {
		if ( !$this->is_gmail ) {
			return __( 'Gmail not being used as email host', 'inbox-status' );
		}

		$ret = $this->gmailSearch( $query );

		if ($ret instanceOf PEAR_Error) {
        return $ret;
    }

		return count( $ret );
	}

	public function gmailSearch( $query ) {
		if ( !$this->is_gmail ) {
			return __( 'Gmail not being used as email host', 'inbox-status' );
		}
		
		$ret = $this->_genericCommand('SEARCH', 'X-GM-RAW "' . $query . '"' );
    if (isset($ret['PARSED'])) {
        $ret['PARSED'] = $ret['PARSED'][0]['EXT'];
    }

		if ($ret instanceOf PEAR_Error) {
        return $ret;
    }
    if (strtoupper($ret['RESPONSE']['CODE']) != 'OK') {
        return new PEAR_Error($ret['RESPONSE']['CODE'] 
                              . ', ' 
                              . $ret['RESPONSE']['STR_CODE']);
    }
    return $ret['PARSED']['SEARCH']['SEARCH_LIST'];
	}

	/**
	 * Convert cryptic PEAR IMAP errors to notices that might not scare people.
	 * Add messages to notices
	 * 
	 * @param  PEAR_Error $login Error object
	 * @return void
	 */
	public function normalize_imap_error( $login ) {
		switch ( $login->message ) {
			case 'NO, [AUTHENTICATIONFAILED] Invalid credentials (Failure)': // Gmail
			case 'NO, Invalid username or password.': // Outlook
			case 'NO, [AUTHORIZATIONFAILED] Incorrect username or password. (#MBR1212)': // Yahoo
			case 'NO, [AUTHENTICATIONFAILED] (#AUTH012) Incorrect username or password.': // Yahoo
			case 'NO, [AUTHENTICATIONFAILED] Authentication failed': // iCloud
			case 'NO, Invalid login or password': // AOL
				return __( 'Authentication failed. Please check your username and password.', 'inbox-status' );
				break;
			case 'not connected! (CMD:LOGIN)':
				return __( 'Could not connect to IMAP server. Please verify the server address and port are correct.', 'inbox-status' );
				break;
			default:
				return $login->message;
				break;
		}
	}

}