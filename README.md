# Kritbit

Kritbit is a simplistic alternative to continuous integration tools like Jenkins. I've personally found Jenkins to be too cumbersome for a one man show. It's a great tool if you have the time to sit down and configure it and have it used by many people - but it was too complex for my needs. Also I felt some features were unnecessarily complex which as a result didn't work.

Kritbit was created to fill a gap of needing the ability to track and run jobs.

Kritbit has 2 purposes:

1. To run a job locally and collect stats about the run
2. To allow an external service to phone home with stats about a job that ran

1 - is suited towards running compile and test jobs

2 - is to have integration into current task scheduling systems

Note: Kritbit originally had 3 purposes - one of them being running commands remotely. But since you can run any arbitrary script you can run a command remotely via SSH or some other external service. Yes, Windows does not have SSH natively (yet?) - but you can download an implementation [here](http://www.freesshd.com/). 

# Security

Kritbit is designed to be simple and flexible. It makes no assumptions, or verifications, about your security and only provides minimal security procedures. I am not a crypto expert - but I make tools that work. So while I cannot guarantee that big brother won't be able to decrypt messages from external services - it should be good enough for most implementations. So please, if you find that the crypto security is less than perfect I accept patches of any size, creed, or color. The encryption technology used isn't meant to prevent a guy with a Beowulf cluster from cracking your message - but rather preventing some script kiddie with Firesheep from seeing what you are doing.

# Authentication/Authorization

Each user logs in using OAuth (see below for setup) and can only edit jobs that they have created (there are no groups or way of "granting" permission). A user MUST be pre-registered into the users table otherwise they will not be able to login and create jobs (this is done to prevent abuse - though you can easily change this behavior). A job history can have a flag to allow anonymous users to view the history. However, kritbit does not censor the output so be careful allowing people to view history of jobs that may contain sensitive information.

# Install

1. Copy web/application/config.dist.php to web/application/config.php
2. Edit values for your environment[1]
3. Run `php migrations.php run` to setup your database
4. Run `php kritbit.php all-clear` to remove all sample data populated from the migrations
5. Run `php kritbit.php adduser you@gmail.com` to add yourself as an authorized user
6. Navigate to http://example.com/kritbit and you should be prompted to login with Google

[1] - Kritbit is designed to authenticate through Google OAuth. Since Kritbit uses an OAuth library you can really use any OAuth provider such as Facebook (which is included).

You must remember to change the REDIRECT_URI in config.php. If you don't want to use OAuth for login but want local users or Apache basic auth - all you have to do is modify login.php to read the user from those sources (which should be pretty simple as the login code there is very simple).

To get OAuth keys needed for Google Auth you need to create a project on [Google's Developers Dashboard](https://console.developers.google.com/), which is free.

kritbit can run on SQLite - however if you are going to deal with any volume you should use MySQL/MaraiaDB (other databases can be used - but you will need to modify some code).

To use MySQL/MaraiaDB specify in config.php (MariaDB is a drop-in replacement for MySQL so it doesn't matter if you specify MySQL):

    $config["DATABASE_TYPE"] = "MySQL";
    $config['MYSQL_DBNAME'] = "dbname";
    $config['MYSQL_HOST'] = "localhost";
    $config['MYSQL_USER'] = "user";
    $config['MYSQL_PASS'] = "pass";
    
# Running automated service

In your config.php you need to add/modify ACCEPTED_IPS :

    $config["ACCEPTED_IPS"] = ["127.0.0.1", "::1"];
    
Then write a cron that runs every minute to curl/wget this url:

    http://<ip or domain>/service/run/
    
For example:

    http://127.0.0.1/kritbit/service/run/
    
And kritbit will run through the jobs that are marked to be ran by kritbit and analyse the cron script to determine if it needs to be ran and run it.

# Sending script runs to kritbit

There is a provided runcommand Python script in the scripts directory. This should be modified to fill in data for your script. This will wrap the encryption and data structure in an easy to use library.

To use it just invoke it like so:

    /usr/bin/python runcommand.py '/bin/bash script.sh'
    
If you want to make it more generic you may replace SHARED_KEY and HASH variables with the following:

    SHARED_KEY = sys.argv[1]
    HASH = sys.argv[2]
    URL = "https://<your IP or domain>/kritbit/service/upload/" + sys.argv[3] + "/"

Then the command would be similar to the following:

    /usr/share/kritbit/runcommand.py '<shared key>' <hash> <id_of_project> '/bin/bash /root/downloadrouterconfig.sh'

ie:

    /usr/share/kritbit/runcommand.py '#$FDSFA#$' eeeffff3434343 7 '/bin/bash /root/downloadrouterconfig.sh'
    
# Long-term TODO

- Provide a way to offer more customization for viewing job information. Right now it's very generic - but it might be useful to be able to parse output and present custom columns or other data.
- Permission matrix allowing people to grant fine permissions to jobs and job history

# Patches

Patches are welcome of any kind. But please do note that your code will be integrated into the project under the MIT license. Mention to your contribution may not appear in the specific code or file that it applies to. But we can certainly make mention on the README describing your contribution.

If you do provide a patch changing backend crypto - then you should also provide a patch updating the crypto for the provided scripts.

# Screenshots

[![pic1](https://srchub.org/cdn/kritbit.png)](https://srchub.org/cdn/kritbit.png)

[![pic1](https://srchub.org/cdn/kritbit2.PNG)](https://srchub.org/cdn/kritbit2.PNG)
    
# Attributions

Kritbit is licensed under the MIT uses the following projects

- [Haplous Framework](https://srchub.org/p/haplousframework/) - MIT
- [h2o template engine](https://github.com/speedmax/h2o-php) - MIT
- [DByte](https://github.com/Xeoncross/DByte) - MIT
- [AES PHP support](http://stackoverflow.com/a/8232171/195722)
- [CRON expression](https://github.com/mtdowling/cron-expression) - MIT
- [phpoauthlib2](https://srchub.org/p/phpoauthlib2/) - MIT
- [stacktraceprint](http://stackoverflow.com/a/4282133/195722)
- [Twitter Bootstrap](http://getbootstrap.com/2.3.2/)
- [jQuery](https://jquery.com/) - MIT
- [jQuery confirm](http://craftpip.github.io/jquery-confirm/) - MIT
- [bootstrap fullscreen](http://craftpip.github.io/bootstrap-fullscreen-select/) - MIT
- [dynatable](http://www.dynatable.com/) - AGPL
- [is_cli](http://stackoverflow.com/a/25967493/195722)
- [dygraphs](http://dygraphs.com/) - MIT

# Donations

Donations can be accepted through [Paypal](https://www.paypal.me/NateAdams) or through BTC: [1F3NzZXUm4sATgCs3sgTqXHwrAqM4JnGVS](bitcoin:1F3NzZXUm4sATgCs3sgTqXHwrAqM4JnGVS)

Made with <3 by Nathan Adams