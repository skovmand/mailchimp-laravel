# skovmand/mailchimp-laravel
A minimal service provider to set up and use the Mailchimp PHP library in Laravel 5. 


## How it works
This package contains a service provider, which binds an instance of an initialized Mailchimp client to the IoC-container. 

You recieve the Mailchimp client through depencency injection already set up with your own API key.


**Usage example**

```php
class NewsletterManager 
{
	protected $mailchimp;
	protected $listId = '1234567890';        // Id of newsletter list
	
	/**
	 * Pull the Mailchimp-instance (including API-key) from the IoC-container.
	 */
	public function __construct(Mailchimp $mailchimp) 
	{
		$this->mailchimp = $mailchimp;
	}

	/**
	 * Access the mailchimp lists API
	 */
	public function addEmailToList($email) 
	{
		try {
			$this->mailchimp
				->lists
				->subscribe(
					$this->listId, 
					['email' => $email]
				);
        } catch (\Mailchimp_List_AlreadySubscribed $e) {
        	// do something
        } catch (\Mailchimp_Error $e) {
        	// do something
        }
	}
}

```

You can also instantiate the Mailchimp client in Laravel 5 by using: 

```$mailchimp = app('Mailchimp');```

 
## Setup
**Step 1: Adding the dependency to composer.json**

Add this to your composer.json in your Laravel folder.
Note: Adding this dependency will automatically setup "mailchimp/mailchimp": "~2.0" too.

```json
"require": {
    ...
    "skovmand/mailchimp-laravel": "1.*",
    ...
}
```


**Step 2: Register the service provider**

Register the service provider in ```config/app.php``` by inserting into the ```providers``` array

```php
'providers' => [
	...
	Skovmand\Mailchimp\MailchimpServiceProvider::class,
	...
]
```


**Step 3: From the command-line run**
 
```
php artisan vendor:publish --provider="Skovmand\Mailchimp\MailchimpServiceProvider"
```

This will publish ```config/mailchimp.php``` to your config folder. In this file, insert your Mailchimp API key:

```php
'apikey' => 'your-api-key-here',
```

**Good to go!** 
