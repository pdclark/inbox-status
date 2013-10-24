<?php

/**
 * @author Ben Lobaugh <http://ben.lobaugh.net>
 */
class BlobImap {

    private $mServer;

    private $mUser;

    private $mPass;

    private $mExtraConConf = '/novalidate-cert';

    private static $mInstance;

    private $mConnection;

    public function __construct() {
    }

    public function __set( $name, $value ) {
	switch( $name ) {
	    case 'server':
		$this->mServer = $value;
		break;
	    case 'pass':
	    case 'password':
		$this->mPass = $value;
		break;
	    case 'user':
	    case 'username':
		$this->mUser = $value;
	}
    }

    public function create_connection( $server = null, $user = null, $pass = null ) {
	if( !is_null( $server ) )
	    $this->mServer = $server;
	if( !is_null( $user ) )
	    $this->mUser = $user;
	if( !is_null( $pass ) )
	    $this->mPass = $pass;
	$this->mConnection = imap_open( '{' . $this->mServer . ':993/imap/ssl' . $this->mExtraConConf . '}INBOX', $this->mUser, $this->mPass, OP_READONLY );
    }

    public function count_unread() {
	return count( imap_search( $this->mConnection, 'UNSEEN' ) );
    }

    public function count_all() {
	return count( imap_search( $this->mConnection, 'ALL' ) );
    }
} // end class
