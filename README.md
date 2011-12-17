# Concrete5 Designer Gallery

Boilerplate image gallery block that makes is easy(er) for designers to add a jquery / javascript slideshow, slider, gallery, etc. to a website.

## Installation
Prerequisite: To enable image links, first install Mnkras's free [Page Selector Attribute](http://www.concrete5.org/marketplace/addons/page-selector-attribute/) addon.

Download this code by clicking the "Zip" button above. Unzip the downloaded file and move the entire `designer_gallery` directory into your site's top-level `packages` directory. Then log into your site and go to Dashboard -> Add Functionality. Click the "Install" button next to "Designer Gallery".

## Customization Steps
This package provides a backend / editing interface, but it is up to you to provide the front-end. The general steps involved are:

***TODO: Make these instructions apply to the package (will have to rename more directories and things in code)***

1. Uninstall the block via Dashboard -> Add Functionality. We must do this because we are about to rename the package (and its block), which you never want to do while it is installed -- that would result in Concrete5 errors.
2. Rename the `designer_gallery` directory as desired (should be the lowercase_and_underscore version of your block's name -- for example, "My Awesome Gallery" would get a directory name of "my_awesome_gallery").
3. Edit the `controller.php` file:
    * Change the class name to be a TitleCaseWithNoSpaces version of the block name (otherwise known as CamelCase), followed by `BlockController` -- for example, "My Awesome Gallery" would get a class name of `MyAwesomeGalleryBlockController`.
    * Change the block name and description. It is recommended that the name correspond with the directory and class names, but this is not a technical requirement (just avoids confusion).
    * Change the table name to `bt` followed by the CamelCase version of the block name -- for example, "My Awesome Gallery" would get a table name of `btMyAwesomeGallery`.
4. Edit `db.xml` file so the table name matches what you set in `controller.php`.
5. Reinstall the block via Dashboard -> Add Functionality.
6. Customize the gallery/slideshow/slider/fader design by either copying all of the files from one of the existing "templates" to the top-level of the block directory structure, or by creating your own template:
    * Put all of your required javascript files into the block's top-level `js` directory.
        * *NOTE: Do **not** put the base jQuery library in here -- Concrete5 loads this automatically on every page already so if you add it here, it will get loaded twice and cause conflicts and errors on the page.*
    * Put all of your required css files into the block's top-level `css` directory.
    * If a stylesheet utilizes background images, place those in the block's top-level `images` directory, then tweak the css so image paths point to that `images` directory -- e.g. `url(../images/example.png)`
        * *NOTE: Do **not** put the images that will be displayed in your gallery/slideshow/slider/fader here -- those will be chosen by the user when they add this block to a page.*
    * Modify the block's top-level `view.php` file to generate the proper html needed for your gallery/slideshow/slider/fader, as well as the javascript initialization code (as per your jQuery library's instructions). This is the hard part! It can often require tweaking the CSS that came with your gallery/slideshow/slider/fader. See the next section for additional help.
    * Change the size settings in controller.php as needed for your design (see code comments for more details).
    * By default, the display order of images in the gallery will be determined by the File Set's display order (Dashboard -> File Manager -> Sets -> [File Set Name] -> Files). If you wish to display images in a random order instead, set the $randomizeOrder variable to true in controller.php (images to be "shuffled" every time the page is viewed).
    * After you've finished created your own customized gallery/slideshow/slider/fader, you should probably remove the `/templates/` directory so users don't accidentally choose one of them as a custom template for the block, and also because it contains a lot of files that will just waste space if not being used.


***TODO: Add a section about how to customize the back-end (turning on/off large and thumb, setting default values in their place)***

##Customization Notes, Tips, and Gotchas
Some things to watch out for when building your own slideshow:

* The package requires Concrete 5.4.1 or higher (primarily because you couldn't specify the order of files in a file set before that version).

* When building your own template, it's best to append the block ID to an element's id (for example, `<div id="gallery<?php echo $bID ?>">`) because otherwise your javascript will break if the user adds more than one of these blocks to the same page.

* Along similar lines, you should only use id's to refer to elements from javascript -- for CSS styles you should stick to classes instead, because there may be more than one of these blocks on a page.

* If the javascript animations for your gallery are getting in the way of editing the page, you can disable it while in edit mode by putting a php "if" statement around your initialization script, for example:

        <?php if (!Page::getCurrentPage()->isEditMode()): ?>
        <script type="text/javascript>
        ...
        </script>
        <?php endif; ?>

* Some javascript/jquery image galleries have an option to automatically crop or resize thumbnails -- if so, it's probably better to disable this and handle it with C5's built-in thumbnailing instead, as performance will be better that way.

* I believe Concrete5 auto-loads js and css files in alphabetical order, so if you have more than one js or css file and need to load them in a specific order, you'll have to rename them to force the proper order.

* IE "filters" in css won't work if they refer to images with paths (because unlike image url's, those paths are relative to the page, not to the css file). There's a workaround that involves adding code to the controller, but it's a bit messy (let me know if you need it).

* If site exists in a subdirectory (for example, http://example.com/myc5site/), your stylesheets won't be loaded the very first time the block is added to a page -- but all subsequent page views will be fine so this is not a major problem, just something to be aware of when editing your site.

## Titles, Captions, Links
All meta-data about images must be managed through the dashboard File Manager.

Titles and Descriptions are set via file "Properties" in the File Manager (click on an image, choose "Properties" from the popup menu). Note that Concrete5 sets the Title property to the file name when files are first uploaded, so if you want to use titles in your template you might want to change these.

Reordering the images can be done by clicking the "Sets" tab above the file list in the File Manager, clicking on the appropriate File Set name, and dragging-and-dropping the icons around.

When this package is installed, it will check to see if you have the "Page Selector Attribute" addon installed -- and if so, a new "Gallery Link To Page" file attribute (property) will be created that lets users choose a page that the image will link to when clicked from the gallery. If you didn't have the "Page Selector Attribute" installed when you installed this package, and want to install it now so you can use the image link feature, either uninstall and re-install the package after installing the "Page Selector Attribute", or follow these steps:

1. Install the free [Page Selector Attribute](http://www.concrete5.org/marketplace/addons/page-selector-attribute/) addon from the marketplace.
2. Allow the new attribute type to be associated with files by going to Dashboard -> Sitewide Settings -> Attributes, and checking the box under the "File" column for the "Page Selector" row.
3. Add a new "Page Selector" attribute: go to Dashboard -> File Manager -> Attributes, find the "Choose Attribute Type" dropdown at the bottom, choose "Page Selector" from the dropdown menu, click the "Go" button. Enter "gallery_link_to_cid" (no quotes) for the handle, "Gallery Link To Page" for the name, and leave the "Searchable" checkboxes unchecked. Then click the "Add Attribute" button.
