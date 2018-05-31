# ghostFollowers-PHP
A PHP script that gives you a list of people who do not follow you back, a.k.a.: _a ghost follower_!

# Note
This has been only tested on Windows, I have no clue if this works on UNIX-Based Systems. Feel free to try though!
# Setup
If you are running this after the first setup, work from step six on...

1. Install PHP, of course...
2. [Install Composer](https://getcomposer.org/download/)
3. Clone the Repository
4. Run ```composer require mgp25/instagram-php react/child-process``` in the cloned folder
5. Edit the Username and Password inside of `config.php` to your instagram details
6. Run the `ghostFollowers.php` script. (`php -f ghostFollowers.php`)
7. Wait while it parses all your follower and following lists.
8. Console will print all of your "Ghost Followers" to you.

# FAQ
#### Why does the console print so many "Sleeping for 3 Seconds!" messages?
This is due to the script pausing every 3 seconds between collecting data in order to prevent spam on Instagram's servers. This *could* also prevent Instagram from flagging you as a bot.
#### Why does it take so long?
This is due to two different reasons; Firstly, your follower and following lists are split into metaphorical "pages". This script has to get one 'page' at a time and if the script get's all pages all at once: Instagram may shadow ban your account. Therefore if you have a lot of followers or you follow a lot of people: that's a lot more 'pages' and will take the script longer to get them all.

# Donate
If you would like to donate to me because you find what I do useful and would like to support me, you can do so through this methods:

Patreon: https://www.patreon.com/JRoy

PayPal.me: https://www.paypal.me/JoshuaRoy1

Bitcoin: `32J2AqJBDY1VLq6wfZcLrTYS8fCcHHVDKD`
