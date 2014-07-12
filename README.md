Magehack_Autogrid
=================
Every time a developer has to display information from database tables on Magento admin, she has to write the same code again and again.
Using Magehack_Autogrid you don't have to do it again for flat tables.

Facts
-----
- version: check the [config.xml](https://github.com/VinaiMagentoHackathonUK2014/Magehack_Autogrid.git/blob/master/app/code/community/Magehack/Autogrid/etc/config.xml)
- Magento Connect 1.0 extension key: - none -
- Magento Connect 2.0 extension key: - none - 
- [extension on GitHub](https://github.com/MagentoHackathonUK2014/Magehack_Autogrid)
- [direct download link](https://github.com/MagentoHackathonUK2014/Magehack_Autogrid/zipball/master)

Description
-----------
Every time a developer hase to display information from database tables on Magento admin, she has to write the same code again and again.
Using Magehack_Autogrid you don't have to do it again for flat tables.

Usage
-----
1. Install the Magehack_Autogrid module
2. Create the table in your module as usual using a setup script
3. Create the autogrid.xml file in your modules *etc/* directory (syntax is coming soon)

The module will reading the database table and guess default behaviour for grid (and soon also the form.

Compatibility
-------------
- Magento >= 1.7 (probably also earlier)

Installation Instructions
-------------------------
If you are using the Magento compiler, disable compilation before the installation, and after the module is installed, you need to run the compiler again.

1. Install the extension from github (its not on Magento Connect).
2. Clear the cache, logout from the admin panel and then login again.

Support
-------
If you have any issues with this extension, open an issue on GitHub (see URL above)

Contribution
------------
Any contributions are highly appreciated. The best way to contribute code is to open a
[pull request on GitHub](https://help.github.com/articles/using-pull-requests).

Developers
---------
* Sam
* [Vinai Kopp](https://github.com/vinai)
* Niels Preuss
* Jaque
* Malachy

Licence
-------
[OSL - Open Software Licence 3.0](http://opensource.org/licenses/osl-3.0.php)
