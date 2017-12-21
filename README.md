# Ratburger.org Code Base

[Ratburger.org](https://www.ratburger.org/) is an online community where a wide variety
of topics are discussed in a civil manner among an international membership whose
only common denominator is their distaste for the sewer that so many Internet
fora and comment sections have become.

Anyone can read all of the content at the site.  By joining, which is free, users can
write and publish their own posts and comment on the posts of others, and participate
in discussion groups on various topics and create their own new groups.  A chat room
is available for real-time conversations.  There are no advertisements on the site.
Members whose behaviour is deemed not in keeping with the goals of the site may be
banned at the sole discretion of the administrators.

Ratburger.org is built upon the foundation of [WordPress](https://wordpress.org/)
and [BuddyPress](https://buddypress.org/) extended by a number of
freely available plug-ins to those packages and site-specific customisations.

This repository represents the code on which the production Ratburger.org site
runs.  The site also uses a few proprietary plug-ins (for example, the
[CometChat](https://www.cometchat.com/) package for the chat room and
the [BuddyDev Editable Activity](https://buddydev.com/plugins/bp-editable-activity/)
plug-in to allow users to edit posts and comments in groups.  These packages,
whose source code cannot be redistributed under the terms of their licenses,
are excluded from this repository.

To bring up a site similar to Ratburger, install this repository in the Web
document home of a HTTP/HTTPS Web server.  The MySQL database used by
WordPress and BuddyPress is not included in the repository; you'll have to
create your own, adding users and passwords for administrators.  Also,
since Git does not preserve file permissions, there are several directories
into which the server needs to write for such fuctions as image uploads
on which you'll have to manually set the appropriate ownership and
permissions.  See a WordPress installation reference for details of these
matters.

## About the Name

The name _Ratburger_ was dreamed up in November 1984 by a bunch of
[Autodesk](http://www.fourmilab.ch/autofile/)
old-timers having dinner in a Las Vegas casino buffet after a long day working
our booth at
[Fall COMDEX 1984](http://www.fourmilab.ch/autofile/images/tradeshows/comdex_1984/).
We envisioned it as the name for a screen-oriented text editor we'd developed
and briefly sold under the name AutoScreen and then set aside when AutoCAD
took off, leaving us no time to pursue other products.  We continued to use
the editor in house, and thought it might still attract users if made available
at an affordable price with a catchy name.  We further thought about bundling
the editor with a number of other in-house tools, such as a **diff**
utility that ran on MS-DOS, as _RatPack_.  Nothing ever came of this.

In 2004, remembering this, I registered the domain name **ratburger.org**, which
I subsequently used as my own equivalent of **example.com** in software and
documentation, but the domain was otherwise unused.  While setting up the
prototype of the new discussion site in December 2017, I used the
domain as a placeholder while testing the prototype of the site with
the co-founder and a few bleeding edge early adopters.  Although it was
originally a joke, we found the name to curiously grow upon us, lending
itself to numerous humorous spin-offs.  So, when the time went to go
live, Ratburger it was.

The name, and the site, has nothing whatsoever to do with the eponymous
children's book, which was published thirty years after the we coined
the name and a decade after I registered the domain **ratburger.org**.

## About the Repository

The main purpose of this repository is to implement the principle of
[radical transparency](http://www.ratburger.org/index.php/2017/12/18/radical-transparency/)
which I adopted for the site's implementation, to serve as a worked
example and "code mine" for those wishing to set up similar discussion
sites, and a means for code-savvy members of the Ratburger community to
chip in and help improve the site's user experience.

**[Visit Ratburger.org](https://www.ratburger.org/)**

