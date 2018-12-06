# Medicalert Website

## Installation

```
git clone git@bitbucket.org:cmsemails/medicalert-web2016.git
cd medicalert-web2016
composer install
yarn install
```

## Assets

Compile styles using `yarn start`

## Cron jobs
Ad cron jobs to cron.php file. To run cron jobs add following entry to server's crontab:

`* * * * * path/to/phpbin path/to/cron.php 1>> /dev/null 2>&1`

## Tests

To run acceptance tests install following dependencies first:

- Selenium Server
- ChromeDriver

On Mac all can be installed using `brew` command:

```
brew install selenium-server-standalone
brew tap caskroom/cask
brew cask install chromedriver
```

Then run selenium standalone server 

```selenium-server -port 4444``` 

and in a different shell run tests using:

```./vendor/bin/codecept run --steps --html```
