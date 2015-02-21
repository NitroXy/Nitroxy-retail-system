<?php

class SessionC extends Controller {

	protected $_default_site = 'login';

	public function nx_login($params) {
		// If user not logged in, force her to log in and come back here (sends header and exits)
		NXAuth::login();

		try {
			// Check if this user has permission
			$nxu = NXAuth::user();
			if(NXAPI::has_right(["user" => $nxu->id, "right" => "sell_in_the_kiosk"]) !== true) {
				throw new Exception("Du saknar behörighet att kioska");
			}

			// Sync local db with remote user object
			$u = User::selection(["remote_id" => $nxu->user_id]);
			if(empty($u)) {
				$u = new User();
				$u->first_name = $nxu->fullname;
				$u->username = $nxu->username;
				$u->remote_id = $nxu->user_id;
				$u->commit();
			} else {
				$u = $u[0];
			}
			$_SESSION['login'] = $u->user_id;
			$_SESSION['user'] = $u;

			// We're done, back to where you came from
			kick(ClientData::post('kickback'));
		} catch(Exception $e) {
			$this->_access_type('html');
			$this->error_message = $e->getMessage();
			self::_partial('Layout/html', $this);
		}
	}

	public function login($params) {
		$this->_access_type('html');
		$this->post = ClientData::session('loggin_form');
		self::_partial('Layout/html', $this);
	}

	public function authenticate($params) {
		$this->_access_type('script');
		$_SESSION['loggin_form'] = $_POST;
		try {
			$user = User::login(ClientData::post('username'),
					ClientData::post('password'));
			unset($_SESSION['loggin_form']);
			$_SESSION['login'] = $user->id;
			$_SESSION['user'] = $user;
			kick(ClientData::post('kickback'));
		} catch(Exception $e) {
			Message::add_error($e->getMessage());
			kick('/Session/login');
		}
	}

	public function logout($params) {
		$this->_access_type('script');
		if(isset($_SESSION['user'])) {
			$user = $_SESSION['user'];
		}
		unset($_SESSION['login']);
		unset($_SESSION['user']);
		if(isset($user)) {
			if($user->remote_id !== null) {
				NXAuth::logout();
			}
		}
		kick($_SERVER['HTTP_REFERER']);
	}
}
