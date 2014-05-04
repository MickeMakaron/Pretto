Pretto
===
Installation:
-------
1. Extract contents to your website's root. It should be where you keep the index file.
2. Access your website through a browser and follow the instructions there.

Keep in mind that you might need to edit the *.htaccess* file to properly rewrite your baseurl. This depends on your server setup and is not a Pretto problem. 

--------------------

Navigation:
---
Before Pretto is installed there will be no navigation menu. Simply follow the instructions. After the database has been initialized there will be three items in the navigation menu: 
* Pages
* Guest book
* Blog

If you want to return to index, simply press the logo or the site title.

----------------

User panel
---
To log in. Press *login* in the top right corner and enter your login information. By default there are three users:
* anonymous - you are classed as anonymous when not logged in
* root (admin) - username:**root**, password:**root**
* doe (user) - username:**doe**, password:**doe**

#### Change user info
When logged in, either press your username in the top right corner or go to *user/profile*. The fields are self-explanatory.

-----

Configuration:
---
Pretto's settings are stored in *"site/config.php"*. Here follows instructions on how to edit some content of your website:

### Themes:
**Variable name:** *$pr->config['theme']*

To change the current theme you must set its path, its parent's (if it has one) path, its stylesheet and its template file in the *$pr->config['theme']*. All themes are stored in *"themes/"*. Pretto uses the *mytheme* theme by default, which has the *Grid* theme as its parent. This means the settings are as follows:
* name = `mytheme`
* path = `themes/mytheme`
* parent = `themes/grid`
* stylesheet = `style.css`
* template_file = `index.tpl.php`

Note that the stylesheet's and the template file's paths are relative to *path*. 

-------------

###Other theme data
**Variable name:** *$pr->config['theme']['data']*

The following are the default values:
* header = `Pretto`
* slogan = `MVC: Mobile Vehicle Construction`
* favicon = `pretto.jpg`
* logo = `pretto.jpg`
* logo_width = `80`
* logo_height = `80`
* footer = `&copy; Pretto, self | <a href='http://validator.w3.org/unicorn/check?ucn_uri=referer&amp;ucn_task=conformance'>Unicorn</a>`

Note that the logo's and the favicon's paths are relative to the current theme's folder.

----------------


###Styling
For example, to edit the color of the header and change the font, go to the current theme's folder (*themes/mytheme/* by default) and edit the *style.css* file.

##### Changing the color of the header
`#inner-wrap-header
{
	background-color:#ffffcc;
}`

#####Changing the font
`body
{
	background-color:#cc6666;
	font-family: Georgia,serif;
}`


------

Configuration through the ACP:
-----
You can also configure Pretto through its Admin Control Panel (ACP) in the browser. When logged in as admin, you can configure the following:

##### Menus
Edit the navigation menu and add new items to it.
* label - what is shown in the nagivation menu
* url - the url it points to.

##### Controllers
Toggle controllers. When a controller is turned off it will be inaccessible.
* enabled - when checked the controller is on, else it's off.

##### allow_browser_access
If you do not want to allow configuration through the ACP, you can turn it off here. You can also turn it off by setting *$pr->config['allow_browser_access']* to false in the config. 

##### Theme
Edit header, slogan, favicon, logo and footer.

Note that the logo's and the favicon's paths are relative to the current theme's folder.

--------------------

Adding content:
----
There are three modules that handle content: CCGuestbook, CCBlog and CCPage.



### Guestbook entries:
To view the guestbook, click on *Guest book* in the navbar or go to  *baseurl/guestbook*.
##### Adding
Type in your message in the box and press *Add message* when done.

##### Clearing
Press *Clear all messages* to remove all messages from the guestbook.

##### Initializing
Press *Create database table* to reinitialize the guestbook's database table. If it already exists it will be left intact.

---------------------------

### Blog entries:
To view the blog posts, click on *Blog* in the navbar or go to *baseurl/blog*.

##### Adding
Press *Add post* and enter data in the form.
* Title - title of the post
* Key - set this to whatever.
* Content - content of the post
* Type - what kind of content it is. A blog post has the type *post*.
* Filter - what filter to run the content text through. Available filters are: *htmlpurify* and *bbcode*. Leave the field blank if you do not want a filter.

##### Editing
At *Blog*, press *edit* on the post you want to edit. The form fields are explained above in **Adding**. You can also press *edit* when **viewing** the post.

##### Deleting
Press *edit* on the post you want to delete, and then press *Delete*.

--------------

### Pages:
Press *Pages* to view all pages or go directly to *baseurl/content*. 

##### Viewing
At *Blog*, press the title of the post you want to view.

##### Adding
Press *Create new content* and enter data in the form.
* Title - title of the page
* Key - set this to whatever.
* Content - content of the page
* Type - what kind of content it is. A page has the type *page*.
* Filter - what filter to run the content text through. Available filters are: *htmlpurify* and *bbcode*. Leave the field blank if you do not want a filter.

##### Editing
At *Pages*, press *edit* near the page you want to edit. The form fields are explained above in **Adding**.

##### Deleting
When editing a page, press *Delete*.

##### Viewing
At *Pages*, press *view* near the page you want to view.



