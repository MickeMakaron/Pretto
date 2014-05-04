<?php if(isset($initTable)) include($initTable); ?>

<h1>Installation</h1>
<p>To install Pretto, follow the instructions below. The instructions are accompanied by notification boxes that pop up when you do things terribly wrong.</p>

<h2>Permissions, databases and PHP</h2>
<p>Before all else, you need to get rid of all the <strong>red notifications</strong>. They indicate fatal errors that must be corrected for Pretto to function properly.</p>

<h2>Setting up database</h2>
<p>Pretto needs a database, and a user to connect it with (if such exists). You have three choices:<p>
<ul>
	<li>If you want Pretto to edit the variables for you , follow 'Automatically' below. This will require writing access.</li>
	<li>If you want to edit <code>dp.php</code> yourself, follow 'Manually' below. This won't require any writing access.</li>
	<li>If you're feeling <strong>really</strong> lazy, follow 'Magically' below and Pretto will use a default database. All you need to do is press a magic button. This will require writing access.</li>
</ul>

<h3>Automatically</h3>
<p>Allow Pretto writing access (666) to <code>db.php</code> in Pretto's root directory. Then enter your database information in the form below and press 'Save'.</p>
<?=$form?>
<p>When you are done, move on to "Initializing modules to database".</p>

<h3>Manually</h3>
<p>Edit <code>db.php</code> in the root manually and edit the following variables yourself.</p>

<h5>Driver</h5>
<p><code>$driver = null;</code></p>
<p>Change 'null' to your SQL driver. Note that Pretto only supports MySQL. </p><p>Example: <code>$driver = "mysql";</code></p>

<h5>Host</h5>
<p><code>$host = null;</code></p>
<p>Change 'null' to your host. </p><p>Example: <code>$host = "localhost";</code></p>

<h5>Database name</h5>
<p><code>$db = null;</code></p>
<p>Change 'null' to the name of the database you want Pretto to use. </p><p>Example: <code>$db = "prettodb";</code></p>

<h5>Username</h5>
<p><code>$user = null;</code></p>
<p>If you want Pretto to connect with a user, you must set up a user for Pretto and change 'null' to that user's username. </p><p>Example: <code>$user = "Oskar_Onkelsnurra";</code></p>

<h5>Password</h5>
<p><code>$password = null;</code></p>
<p>If you want Pretto to connect with a user, you must set up a user for Pretto and change 'null' to that user's password. </p><p>Example: <code>$password = "!33asexyX##1";</code></p>
<p>When you are done, move on to "Initializing modules to database".</p>

<h3>Magically</h3>
<p>Make sure that Pretto has writing access (666) to <code>db.php</code> in the root.</p>
<p>Press this button to set the database to use the Pretto default database. Keep in mind that this database is shared with everyone who presses the magic button.</p>
<?=$simpleform?>

<h2>Initializing modules to database</h2>
<p>Some core modules of Pretto require database initialization. Press the button to initialize them!</p>
<?=$initForm?>

<p>That's it! If everything looks green, Pretto should be up and running.</p>

<h2>Don't forget!</h2>
<p>Go to <a href='<?=create_url('acp')?>'>the ACP</a> to edit your new Pretto-powered website! Also, don't forget to change the login information of the admin by logging in to <strong>root</strong> as username and password.</p>
