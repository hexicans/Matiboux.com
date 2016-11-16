
# Oli Tutorials: Get started

Once you have downloaded Oli and extrated it on your web server, you have to configure it.
Here, I will help you to understand what's in the **config.json** file and show you how to create a basic web page.

### .htaccess configuration

You must verify that `ErrorDocument` is correctly set up.
If your Oli website is hosted on `http://yourdomain.com/` or any sub domain, set it as `ErrorDocument 404 /index.php`.
Else if your Oli website is hosted on `http://yourdomain.com/something/` or any sub domain, set it as `ErrorDocument 404 /something/index.php`.


### Oli configuration

The Oli parameter contains all configuration of Oli. Change the values to whatever you want to use. Here's is the detailed list of all these parameters:

- **`mysql`** contains login information to the database
	- `database` name, `username`, `password` and `hostname`
- **`settings_tables`** contains your settings tables
	- Can be one table (e.g. `"settings"`)
	- Can be multiple tables (in priority order, e.g. `["higher_priority", "lower_priority"]`)
	- These table contains website `url` column, if not specified, base url will be use instead
- **`post_vars_cookie`** contains informations for post vars cookie creation
	- cookie `name`, `domain`, `secure` parameter and `http_only` parameter
- **`default_content_type`** contains default content type (default: `"HTML"`)
- **`default_charset`** contains default content type (default: `"HTML"`)
- **`cdn_url`** contains cdn url (for cdn html file load functions)
- **`default_user_language`** contains default content type (default: `"en"`)
- **`translations_table`** contains translations table name (for translations functions)
- **`time_zone`** contains default content type (default: `"HTML"`)
- **`login_management`** allows or not login management (default: `"false"`)
- **`accounts_tables`**:
	- main `accounts` table which contains all accounts
	- accounts `infos` table which  contains secondary infos
	- accounts `sessions` table which contains user login sessions
	- accounts `requests` table which contains user requests
	- accounts `permissions` table which contains user own permissions
	- accounts `rights` table contains rights and their permissions
- **`prohibited_username`** sets prohibited usernames (e.g. `["username", "all"]`)
- **`register`**:
	- `verification` enables or not email verification (default: `"false"`)
	- `request_expire_delay` sets request expire delay (default: `2 days`)
- **`hash`** contains informations for cookie creation
	- `algorithm` sets which algorithm will be used
	- `salt` sets hash salt (it is *recommended to leave this empty*)
	- `cost` sets hash cost (default: `10`)
- **`auth_key_cookie`** contains informations for auth key cookie creation
	- cookie `name`, `domain`, `secure` parameter and `http_only` parameter

These parameters are optional, the framework should work without them, but it will surely disable a few functionalities.
Want to use Oli functions as values? A build-in decoder is available through the config file, here's all the syntaxes supported:

- **`Setting:{$setting}`** for `$_Oli->getSetting($setting)`
- **`UrlParam:{$param}`** for `$_Oli->getUrlParam($param)`
- **`ShortcutLink:{$shortcut}`** for `$_Oli->getShortcutLink($shortcut)`
- **`Const:{$shortcut}`** for `$_Oli->getShortcutLink($shortcut)`
- **`Time:{$time}`** to convert any time in seconds. Supported times:
	- number followed by `year` or `years`
	- number followed by `month` or `months`
	- number followed by `week` or `weeks`
	- number followed by `day` or `days`
	- number followed by `hour` or `hours`
	- number followed by `minute` or `minutes`
	- if no pattern has been found, time will be directly returned (as seconds, without been converted)
- **`Size:{$size}`** to convert any file size in bytes. Supported sizes:
	- number followed by `TB` or `To` (1000:1 ratio)
	- number followed by `GB` or `Go` (1000:1 ratio)
	- number followed by `MB` or `Mo` (1000:1 ratio)
	- number followed by `KB` or `Ko` (1000:1 ratio)
	- number followed by `TiB` or `Tio` (1024:1 ratio)
	- number followed by `GiB` or `Gio` (1024:1 ratio)
	- number followed by `MiB` or `Mio` (1024:1 ratio)
	- number followed by `KiB` or `Kio` (1024:1 ratio)
	- if no pattern has been found, time will be directly returned (as bytes, without been converted)
- **`MediaUrl`** for `$_Oli->getMediaUrl()`
- **`DataUrl`** for `$_Oli->getDataUrl()`
- **`"{$value}"`** to return raw $value
	- this will be also used if no pattern has been found

Multiple parameter can be provided at the same time using the `|` separator.
Here's some examples:

- `"request_expire_delay": "Time:3 days"`
- `"algorithm": "Const:PASSWORD_DEFAULT"`
- `"name": "Setting:cookie_name"`
- `"domain": ".|UrlParam:domain"` (using separator)

### Including addons

This is something you would definitely use.
Remember that Oli automatically include all active addons but their classes have to be manually set. The config file allows you to set them easily. Just list all addons classes you want to set into the "**addons**" parameter as below:
	
	"addons": [
		{
			"name": "Addon Name",
			"var": "_VarWithoutSymbol",
			"namespace": "\\NameSpace\\To\\",
			"class": "ClassName"
		}, { ... }
	]

The config file also allows you to configure these addons (if supported)! See [next section](#addonsconfig) for this.

### Addons configuration

As Oli configuration, a parameter will be dedicated to an addon. This parameter have to be the addon "name" parameter used to set the addon class.

A **config.php** file may also be created by the user, along with **config.json**, but it's not recommended.

### My first page!

Yay, you've done the configuration, now comes to the fun part: creating your website!
Go to the “**content/theme/**” folder and start edit “**index.php**” which is the default page of your website. Put anything you'd like in it, save and live changes on your browser!

See the ["How to create my first page" tutorial](#) for more info on what you can do. Also don't forget to check the [Oli documentation](#) to learn how to use all these functions.

### Bonus: Moving framework files

Last thing you would want to do is moving “**includes/**” and “**addons/**” directories into another one. If you manage multiple Oli websites, you could be able to set the same framework files for all your websites.
So you could organize your files like this:

	.../Oli/
		addons/
		includes/
	.../website/
		content/
		files

And, once directories are where you want, you can set the absolute path to them into the "**source_path**" parameter:

	"source_path": "/.../Oli/"

If you don't know how to get the absolute path, here's two ways to get it:

- create a php file with `<?php echo dirname(__FILE__) . '/'; ?>` in it, and open it in your browser. It shows the absolute file to this file.
- create a oli page with `<?php echo ABSPATH; ?>` in it, and open it in your browser. It shows the absolute file to the directories of "**content/**" directory and other files.