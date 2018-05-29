<?php
if (php_sapi_name() !== "cli") {
    die("You may only run this inside of the PHP Command Line! If you did run this in the command line, please report: \"".php_sapi_name()."\" to the ghostFollowers-PHP Repo!");
}

logM("Loading ghostFollowers-PHP v0.1...");
set_time_limit(0);
date_default_timezone_set('America/New_York');

//Load Depends from Composer...
require __DIR__ . '/vendor/autoload.php';
use InstagramAPI\Instagram;
use InstagramAPI\Signatures;
use InstagramAPI\Response\Model\User;

require_once 'config.php';
/////// (Sorta) Config (Still Don't Touch It) ///////
$debug = false;
$truncatedDebug = false;
/////////////////////////////////////////////////////

if (IG_USERNAME == "USERNAME" || IG_PASS == "PASSWORD") {
    logM("Default Username and Passwords have not been changed! Exiting...");
    exit();
}

//Login to Instagram
logM("Logging into Instagram...");
$ig = new Instagram($debug, $truncatedDebug);
try {
    $ig->login(IG_USERNAME, IG_PASS);
} catch (\Exception $e) {
    echo 'Error While Logging in to Instagram: '.$e->getMessage()."\n";
    exit(0);
}

try {
    if (!$ig->isMaybeLoggedIn) {
        logM("Couldn't Login! Exiting!");
        exit();
    }
    logM("Logged In! Preparing Data...");
    logM("This may take a while...");
    $rankTokenFollowers = Signatures::generateUUID();
    $followersMaxId = null;
    $followers = [];
    logM("Collecting Followers...");
    do {
        $followersResponse = $ig->people->getSelfFollowers($rankTokenFollowers);
        foreach ($followersResponse->getUsers() as $user) {
            $followers[$user->getId()] = $user;
        }

        $followersMaxId = $followersResponse->getNextMaxId();
        logM("Sleeping for 5 seconds!");
        sleep(5);
    } while ($followersMaxId !== null);

    $rankTokenFollowing = Signatures::generateUUID();
    $followingsMaxId = null;
    $following = [];
    logM("Collecting Following...");
    do {
        $followingResponse = $ig->people->getSelfFollowing($rankTokenFollowing);
        foreach ($followingResponse->getUsers() as $user) {
            $following[$user->getId()] = $user;
        }

        $followingsMaxId = $followingResponse->getNextMaxId();
        logM("Sleeping for 5 seconds!");
        sleep(5);
    } while ($followingsMaxId !== null);
    logM("Data Collected! Parsing data now!");

    $nonMutual = [];

    foreach ($following as $user) {
        if ($user instanceof User) {
            if ($user->getFollowerCount() < FILTER_COUNT && !array_key_exists($user->getId(), $followers)) {
                $nonMutual[] = $user;
            }
        } else {
            logM("Null Follower!");
        }
    }

    logM("Here are your non mutual followers:");
    foreach ($nonMutual as $user) {
        if ($user instanceof User) {
            logM("@".$user->getUsername()." - ".$user->getFullName());
        } else {
            logM("Null Follower!");
        }
    }

} catch (\Exception $e) {
    echo 'Error While Creating Livestream: '.$e->getMessage()."\n";
}
/**
 * Logs a message in console but it actually uses new lines.
 */
function logM($message) {
    print $message."\n";
}