<?php
/** ------------------- */
/**  Oli Configuration  */
/** ------------------- */

/** MySQL Configuration */
$_Oli->setupMySQL('ďąţąβą$€',
	'µ$€яɲąʍ€',
	'ρą$$ώ๏яď',
	'h๏$ţ');

/** Tables Configuration */
$_Oli->setSettingsTables(['settings_manager', 'settings']); // Dans l'ordre de priorité : [1,2,3]
$_Oli->setShortcutLinksTable('shortcut_links');

/** Content Type Configuration */
$_Oli->setDefaultContentType('HTML');

/** Url Configuration */
$_Oli->setCdnUrl($_Oli->getShortcutLink('cdn'));

/** Translations & User Language Configuration */
$_Oli->setDefaultUserLanguage('en');
$_Oli->setTranslationsTable('translations');

/** Authentification Key Cookie Configuration */
$_Oli->setPostVarsCookieName('OliPostVars');
$_Oli->setPostVarsCookieDomain('.' . $_Oli->getOption('domain'));
$_Oli->setPostVarsCookieSecure(false);
$_Oli->setPostVarsCookieHttpOnly(false);

/** Miscellaneous */
$_Oli->setTimeZone('Europe/Paris');

/** ------------------------ */
/**  Accounts Configuration  */
/** ------------------------ */

/** Enable / Disable Accounts Management */
// $_Oli->enableAccountsManagement();
$_Oli->disableAccountsManagement();

/** Tables Configuration */
$_Oli->setAccountsTable('accounts');
$_Oli->setAccountsInfosTable('accounts_infos');
$_Oli->setAccountsSessionsTable('accounts_sessions');
$_Oli->setAccountsRequestsTable('accounts_requests');
$_Oli->setAccountsPermissionsTable('accounts_permissions');
$_Oli->setAccountsRightsTable('accounts_rights');

/** Hash Configuration */
$_Oli->setHashAlgorithm(PASSWORD_DEFAULT);
// $_Oli->setHashSalt('');
// $_Oli->setHashCost(10);

/** Authentification Key Cookie Configuration */
$_Oli->setAuthKeyCookieName($_Oli->getOption('auth_key_cookie_name'));
$_Oli->setAuthKeyCookieDomain('.' . $_Oli->getOption('domain'));
$_Oli->setAuthKeyCookieSecure(false);
$_Oli->setAuthKeyCookieHttpOnly(false);

/** Register Verification Configuration */
// $_Oli->enableRegisterVerification();
// $_Oli->setRequestsExpireDelay(604800);


/** *** *** *** */

/** ------------------------------ */
/**  Upload Manager Configuration  */
/** ------------------------------ */

use UploadManager\UploadManager;
$_Upload = new UploadManager;

/** Setup MySQL */
$_Upload->setupExistMySQL();
// $_Upload->setupManualMySQL(DATABASE,
	// USERNAME,
	// PASSWORD,
	// HOST);

/** Set Upload Table & Path */
$_Upload->setUploadTable('imgshot_uploads');
$_Upload->setUploadPath(MEDIAPATH);
$_Upload->setUploadUrl($_Oli->getMediaUrl());

/** Set Upload Table & Path */
$_Upload->setMaxSize(4194304); // 4194304 => 4 Mo
$_Upload->setNameLength(12);
$_Upload->setAllowedTypes(['png', 'bmp', 'jpg', 'jpeg', 'gif']);



/** *** *** */

/** ------------------ */
/**  ACCOUNTS MANAGER  */
/** ------------------ */

// use AccountsManager\AccountsManager;
// $_Accounts = new AccountsManager;

/** Setup MySQL */
// $_Accounts->setupExistMySQL();
// $_Accounts->setupManualMySQL(DATABASE,
	// USERNAME,
	// PASSWORD,
	// HOST);

/** Set Tables */
// $_Accounts->setAccountsTable('accounts');
// $_Accounts->setInfosTable('accounts_infos');
// $_Accounts->setRightsTable('accounts_rights');
// $_Accounts->setSessionsTable('accounts_sessions');
// $_Accounts->setRequestsTable('accounts_pending_requests');

/** Setup Hash */
// $_Accounts->setHashAlgorithm(PASSWORD_DEFAULT);
// $_Accounts->setHashSalt('');
// $_Accounts->setHashCost(10);

/** Setup Authentification Key Cookie */
// $_Accounts->setAuthKeyCookieName($_Oli->getOption('auth_key_cookie_name'));
// $_Accounts->setAuthKeyCookieDomain('.' . $_Oli->getOption('domain'));
// $_Accounts->setAuthKeyCookieSecure(false);
// $_Accounts->setAuthKeyCookieHttpOnly(false);

/** Set Register verification */
// $_Accounts->setRegisterVerification(true);


/** *** *** */

/** -------------- */
/**  APIS MANAGER  */
/** -------------- */

// use APIsManager\APIsManager;
// $_APIs = new APIsManager;

/** Set Default Charset */
// $_APIs->setDefaultCharset('utf-8');

/** Set Authorized Websites */
// $_APIs->setauthorizedWebsites('*');
// $_APIs->setauthorizedWebsites([
	// $_Oli->getOption('url')
// ]);

/** Set API Url */
// $_APIs->setAPIUrl('http://apis.domain.com/');
?>