Magehack_Autogrid
=================
Every time a developer has to display information from database tables on Magento admin, she has to write the same code again and again.
Magehack_Autogrid can do most of the work for you, as long as the grid is dealing with a flat table.

Facts
-----
- version: check the [config.xml](https://github.com/MagentoHackathonUK2014/Magehack_Autogrid.git/blob/master/app/code/community/Magehack/Autogrid/etc/config.xml)
- Magento Connect 1.0 extension key: - none -
- Magento Connect 2.0 extension key: - none - 
- [extension on GitHub](https://github.com/MagentoHackathonUK2014/Magehack_Autogrid)
- [direct download link](https://github.com/MagentoHackathonUK2014/Magehack_Autogrid/zipball/master)

Description
-----------
Every time a developer has to display information from database tables on Magento admin, she has to write the same code again and again.
Magehack_Autogrid does most of the work automatically, as long as the grid is dealing with a flat table.

It only requires a potentially very small config file (like, 5 lines of XML excluding the root node), and the rest is taken care of automatically.  
This code belongs in a file called *[etc/autogrid.xml](https://github.com/MagentoHackathonUK2014/Magehack_Autogrid/blob/master/src/app/code/community/Magehack/Autogrid/etc/autogrid.xml)* in a modules directory.  

```{.xml}
<config>
    <tables>
        <example_product_table>
            <table>catalog/product</table>
        </example_product_table>
    </tables>
</config>
```

Additional options can be specified of course, overriding the defaults:

```{.xml}
<config>
    <tables>
        <example_product_table>
            <table>catalog/product</table>
            <title>Autogrid Example Table</title>
            <grid>
                <entity_type_id>
                    <sortable>0</sortable>
                </entity_type_id>
                <has_options>
                    <source_model>magehack_autogrid/table_column_source_yesno</source_model>
                </has_options>
                <type_id>
                    <header>The Product Type</header>
                </type_id>
            </grid>
            <form>
                <type_id>
                    <disabled>1</disabled>
                </type_id>
            </form>
        </example_product_table>
    </tables>
</config>

```

Please have a look at the examples in the *[etc/autogrid.xml](https://github.com/MagentoHackathonUK2014/Magehack_Autogrid/blob/master/src/app/code/community/Magehack/Autogrid/etc/autogrid.xml)* file for more examples.
  
Also, global defaults based on column name can be specified in the usual *[config.xml](https://github.com/MagentoHackathonUK2014/Magehack_Autogrid/blob/master/src/app/code/community/Magehack/Autogrid/etc/config.xml#L61-L95)* files:

```{xml}
<config>
    <adminhtml>
        <autogrid>
            <column_defaults>
                <attribute_set_id>
                    <frontend_input>select</frontend_input>
                    <source_model>magehack_autogrid/table_column_source_attributeSetId</source_model>
                </attribute_set_id>
                <website_ids>
                    <frontend_input>multiselect</frontend_input>
                    <source_model>magehack_autogrid/table_column_source_websiteId</source_model>
                </website_ids>
                <created_at>
                    <disabled>1</disabled>
                </created_at>
            </column_defaults>
        </autogrid>
    </adminhtml>
</config>
```

There are a number of nice features, for example backend model support for flat table entities.

```{.xml}
<config>
    <tables>
        <example_product_table>
            <form>
                <updated_at>
                    <backend_model>magehack_autogrid/table_column_backend_updatedAt</backend_model>
                </updated_at>
            </form>
        </example_product_table>
    </tables>
</config>
```

Extension Stability
-------------------
The extensions source still shows its roots in a one-night hackathon, but work has continued and it is in a usable and rather maintainable state.  
Plenty of work can still be done

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

Todo
----
Here a list of the todo items that I've got in my mind at the moment:
 
- add per table acl resource support
- add more tests
- refactor column class further, column types should be individual classes based on an abstract column type
- complete "all-tables" grid support (view selected tables, create, edit and delete for table contents)
- remove the example autogrid.xml
- create examples

Support
-------
If you have any issues with this extension, open an issue on GitHub (see URL above)

Contribution
------------
Any contributions are highly appreciated. The best way to contribute code is to open a
[pull request on GitHub](https://help.github.com/articles/using-pull-requests).

Developers
---------
* [Sam Ban](https://github.com/ShoobyBan)
* [Vinai Kopp](https://github.com/vinai)
* [Nils Preuss](https://github.com/nils.preuss)
* [Jacques Bodin-Hullin](https://github.com/jacquesbh)
* [Malachy McConnell](https://malachy.mcconnnell)

Licence
-------
[OSL - Open Software Licence 3.0](http://opensource.org/licenses/osl-3.0.php)
