# WAPH - Web Application Programming and Hacking

## Instructor
**Dr. Phu Phung**

## Student
- **Name:** Vaishakh Vibudhendran Nair
- **Email:** vibudhvh@ucmail.uc.edu
- **Short Bio:** Vaishakh has keen interests in web and app development.

![Vaishakh's headshot](headshot.jpeg)

## Repository Link
[GitHub Repository](https://github.com/waph-team11-sm24/waph-team11-sm24.github.io)


## Team Link
[GitHub Repository](https://github.com/waph-team11-sm24/waph-team11-sm24.github.io)

## Overview
We created a mini-Facebook-like application that supports login, registration, password change, email update, posting etc. Numerous safety features were also implemented.


## Video Demonstration
[![Video Demonstration](https://www.youtube.com/watch?v=trfD3QQGV-I)](https://www.youtube.com/watch?v=trfD3QQGV-I)


## Deployment on https
The deployment on https can be clearly seen in this screenshot from when i accessed the website from my windows laptop outside the vm and the url begins with https.
![](1.jpeg)
## hashed passwords and no mysql root account for php

This screenshot shows that the database has passwords hashed
![](2.jpeg)

This snippet from database.sql shows how i am using md5 to hash all the passwords when the user registers.
![](3.jpeg)

In this snipped you can see that no mysql root account is being used by my php code. it is clearly using my vibudhvh account
![](4.jpeg)

## prepared sql statements

These are just a few examples of me preparing sql statements to make them less susceptible to scripting attacks, sql injections etc.

![](5.jpeg)

![](6.jpeg)

## validated every layer



### html
HTML validation ensures that user inputs meet specific criteria before form submission using attributes like required and pattern. 
![](7.jpeg)




### php
PHP validation provides server-side security by validating and sanitizing inputs to prevent harmful data processing
![](9.jpeg)
![](8.jpeg)

### sql
![](6.jpeg)
This layer we are doing validation using prepare.

### HTML outputs must be sanitized

In index I do <?php if (isset($error)) echo "<p class='error'>" . htmlentities($error) . "</p>"; ?>
and in profile.php I do <p>Name: <?php echo htmlentities($user['name']); ?></p>
<p>Email: <?php echo htmlentities($user['email']); ?></p>
<p>Phone: <?php echo htmlentities($user['phone']); ?></p>

This is typically done using functions that escape special HTML characters so they are not interpreted as code by the browser.




## Role-based access control for registered users and super users
### A regular user cannot log in as a superuser
![](2.jpeg)
v1 is the only supersuer.

![](10.jpeg)
I logged in as v2 which is shown by the name and email as in the previous screenshot and admin setting arent visible here which are visible when i log in as v1 in the screenshot below.
![](11.jpeg)

I set the session superuser as true if its a superuser and kick them out of the admin page if they come there without the session. the button doesn't show up on the profile if they arent a superuser.

### A regular user cannot edit/update posts of other users
![](12.jpeg)

As you can see in the screenshot where I am logged in as v2 i can only edit the post made by v2 and not the one made by v1.

In the backend i search if the post is made by the user and only if it is, then I show the edit button, or else i don't.
	
### Session Authentication and Hijacking Prevention

Im doing this by 
Ensure your site is served over HTTPS to encrypt the data between the client and server.

Regenerate the session ID after login to prevent session fixation attacks.

Set session cookies with the Secure and HttpOnly flags.
Implement a session timeout to log out inactive users.


I also make the pages require session_auth and database.php which does authentication as well.

### CSRF Protection

Session_auth does this for us and I use it on all pages to make sure there is no hijacking happening.



### Integrating an open-source front-end CSS template
![](6.jpeg)
I do this in admin_users as shown in this screenshot. I just pull it from the internet in style.


### Source code



