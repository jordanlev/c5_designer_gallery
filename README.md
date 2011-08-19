# Concrete5 Designer Gallery

Boilerplate image gallery block that makes is easy(er) for designers to add a jquery / javascript slideshow, slider, gallery, etc. to a website. Includes many example front-ends (Galleria, Galleriffic, Fancybox, PlusSlider, Coin Slider, and more).

## Installation / Customization

1. Move the `designer_gallery` directory from this repo's `blocks` directory to your site's top-level `blocks` directory (note: this is just a block, *not* a package -- so don't put it in your `packages` directory).
2. Rename the `designer_gallery` directory to whatever you want the block to be named (lowercase letters and underscores only -- no spaces).
3. Edit the `controller.php` file. Change the class name, size settings, and block name/description/tablename variables as needed.
4. Edit `db.xml` file so the table name matches what you set in `controller.php`.
5. Add your own js, css, and image files to their respective directories within the block. Note that Concrete5 automatically loads .js files in a block's `/js/` directory and .css files in a block's `/css/` directory (but not files in sub-directories of those).
6. Tweak your .css file so that image paths point to the images directory -- e.g. `url(../image/example.gif)`
6. Customize the `view.php` file to work with your gallery plugin (see below for more details).
7. When you're done referring to sample code and have your own gallery working, remove all un-needed files from the `/js/`, `/css/`, `/images/`, and `/templates/` directories. (Especially the js and css files, otherwise C5 auto-loads them which will cause your pages to be really slow).

## Titles, Captions, Links
This block is intended to facilitate advanced javascript for highly customized slideshows and galleries, but the editing interface is very bare-bones -- users only choose a file set, so all meta-data about images must be managed through the dashboard File Manager.

Titles and Descriptions are set via file "Properties" in the File Manager (click on an image, choose "Properties" from the popup menu). Note that Concrete5 sets the Title property to the file name when files are first uploaded, so if you want to use titles in your template you might want to change these.

Reordering the images can be done by clicking the "Sets" tab above the file list in the File Manager, clicking on the appropriate File Set name, and dragging-and-dropping the icons around.

Links to other pages are a bit more challenging, because there is no built-in facility for handling this. If you want to allow users to choose a page that an image links to when clicked, there are a few additional things you need to set up first:

1. Install the free [Page Selector Attribute](http://www.concrete5.org/marketplace/addons/page-selector-attribute/) addon from the marketplace.
2. Allow the new attribute type to be associated with files by going to Dashboard -> Sitewide Settings -> Attributes, and checking the box under the "File" column for the "Page Selector" row.
3. Add a new "Page Selector" attribute: go to Dashboard -> File Manager -> Attributes, find the "Choose Attribute Type" dropdown at the bottom, choose "Page Selector" from the dropdown menu, click the "Go" button. Enter "gallery_link_to_cid" (no quotes) for the handle, "Link To Page" for the name, and leave the "Searchable" checkboxes unchecked. Then click the "Add Attribute" button.

Now that you have the "Link To Page" attribute installed, users can set it for each image via file "Properties" (just like Title and Description above).

##Customization Notes
Some things to watch out for when building your own slideshow:

* If you're not using Concrete 5.4.2 or higher, you won't be able to use cropping (although there is a workaround available if needed -- replace your site's concrete/helpers/image.php file with a recent version from C5's github repo).

* If you're not using Concrete 5.4.1 or higher, you won't be able to specify the display order of images in the file set. Time to upgrade!

* When building your own template, it's best to append the block ID to an element's id (for example, `<div id="gallery<?php echo $bID ?>">`) because otherwise your javascript will break if the user adds more than one of these blocks to the same page.

* Along similar lines, you should only use id's to refer to elements from javascript -- for CSS styles you should stick to classes instead, because there may be more than one of these blocks on a page.

* if the javascript animations for your gallery are getting in the way of editing the page, you can disable it while in edit mode by putting a php "if" statement around your initialization script, for example:

        <?php if (!Page::getCurrentPage()->isEditMode()): ?>
        <script type="text/javascript>
        ...
        </script>
        <?php endif; ?>

* Some javascript/jquery image galleries have an option to automatically crop or resize thumbnails -- if so, it's probably better to disable this and handle it with C5's built-in thumbnailing instead, as performance will be better that way.

* I believe Concrete5 auto-loads js and css files in alphabetical order, so if you have more than one js or css file and need to load them in a specific order, you'll have to rename them to force the proper order.

* IE "filters" in css won't work if they refer to images with paths (because unlike image url's, those paths are relative to the page, not to the css file). There's a workaround that involves adding code to the controller, but it's a bit messy (let me know if you need it).

* If site exists in a subdirectory (for example, http://example.com/myc5site/), your stylesheets won't be loaded the very first time the block is added to a page -- but all subsequent page views will be fine so this is not a major problem, just something to be aware of when editing your site.
