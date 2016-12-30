PURGE CPT :: Wordpress Plugin
by Riccardo Raneri - Raneri Web - www.raneri.it
------------------------------------------------

Version 1.0

DISCLAIMER:
------------------------------
THIS PLUGIN ACTUALLY DELETES THE DATA YOU CHOOSE FROM YOUR WORDPRESS WEBSITE, SO USE IT CAREFULLY AND DO A FULL BACKUP OF YOUR WP DATABASE BEFORE THE USE. THE AUTHOR CANNOT BE CONSIDERED RESPONSIBLE FOR ANY DATA LOSS.

1. What does this plugin do
2. Installation
3. Usage
4. Changelog


1. What does this plugin do
------------------------------
This plugins cleans out your Wordpress database from unwanted posts and their metadata. This is useful when you have some posts of a certain Custom Post Type that you want to purge: when you de-register a CPT, all the posts of that type remain into the database.

With this plugin you have simply to select the CPT and press the button: it will delete the posts (from the wp_posts table) and the connected meta data (from the wp_postmeta table), automatically.


2. Installation
------------------------------
Just copy the whole content of the ZIP file inside your wp-content/plugins/ directory.
Then go to Wordpress admin panel and enable the plugin. Otherwise, install the .zip file directly with the plugin installation procedure from the WP Admin Panel.


3. Usage
------------------------------
The new function will appear into the WP Admin Panel, in Settings -> Purge CPT. Just choose the post type you want to purge; you will see a summary of the current post count for this CPT. Then click on the button to clean it from the database.


3. Changelog
------------------------------
1.0	Initial release.