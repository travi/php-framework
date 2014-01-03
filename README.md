Travi Framework
===============

This is a php web framework that I've built over the years as I've worked to understand web development better.

# Installation using [Composer] (http://getcomposer.org/)
## Define the dependency
Add the framework to the require block of your project

<pre>"require": {
     "Travi/framework": "default"
}</pre>

## Extra Configuration
Since this is not yet registered with [Packagist] (https://packagist.org/) there are
a few extra steps to get the process to work:

* Add a custom repository

<pre>"repositories": [
   {
       "type": "vcs",
       "url": "http://github.com/travi/php-framework.git"
   }
]</pre>

* Pulling from a custom repository seems to be slower, so you may need to bump up the timeout

<pre>"config": {
    "process-timeout": 500
}</pre>

## Install
`php ../path/to/composer.phar install` will make the framework available for your project

# Example
An example implementation has been built. The repo can be found [here] (https://github.com/travi/example-framework).