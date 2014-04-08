# PhotoShow Revolution

This is a Friendly fork of the original as the maintener is currently not active.

=======

## Overview

**PhotoShow Revolution **, *your* web gallery. **PhotoShow Revolution** is a *free* and *open source* web gallery, that you can very easily install on your web server. It doesn't even require a database !
Multi format supported : images (library GD required) and videos (for encoding/streaming :binary ffmpeg or avconv required)

Propulsed with Jquery 1.10 , BootStrap 2.3.2 , Webservice JSON-RPC.

## Demonstration

http://photoshow-revolution.ipocus.net
user : demo
pwd : demo123

## Installation

### Copy the repository

First, you need to copy the repository into whatever you like (here, toto)

`git clone https://github.com/cyr-ius/PhotoShow.git gallery`

based/forked on 
`git clone https://github.com/thibaud-rohmer/PhotoShow.git toto`

### Create two directories

Note : you may create those directories wherever you want, and give them the names you want. It is safer to have the Photos and Thumbs directories outside of your web path (this way, access can be restricted using the authentication & authorization mechanisms provided by PhotoShow).

* **Photos** : Where your photos will be stored.
* **Generated** : Where the thumbnails of your photos will be stored. 
* **Server FQDN** : Your server FQDN, as if not this is not working behind a reverse proxy
* **Server Path** : The application path as seen on the outside world

***Important*** : Make sure that the web server has the rights to read in the Photos directory and read/write in the Generated one.

### Edit your settings

Edit the file `config.php` that is inside your PhotoShow folder. It is advised to put absolute paths for each of the entries, although relative paths should work fine.

### Go to your website

Now, use your favorite web browser to go to your PhotoShow website. You should be asked to create the main account. This account will be the admin of the website.

> Your website is now ready.


=======

Packages needed:

* php5
* php5-gd
* php5-geoip
* php5-gmp

* ffmpeg or compatible

=======

Using:

* http://jquery.com/
* http://botstrap.com

=======

Thanks to:

* Thibault Rohmer

