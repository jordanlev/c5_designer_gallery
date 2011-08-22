# Concrete5 Designer Gallery

Boilerplate image gallery block that makes is easy(er) for designers to add a jquery / javascript slideshow, slider, gallery, etc. to a website. Includes many example front-ends (Galleria, Galleriffic, Fancybox, Nivo Slider, Coin Slider, and PlusSlider).

## Installation
Download this code by clicking the "Downloads" button above and to the right, then choosing "Download .zip". Unzip the downloaded file and move the `designer_gallery` directory from within the `blocks` directory to your site's top-level `blocks` directory (note: this is just a block, *not* a package -- so don't put it in your `packages` directory). Then log into your site and go to Dashboard -> Add Functionality. Click the "Install" button next to "Designer Gallery". Add the Designer Gallery block to a page, and then click on the new block in the page and choose "Custom Template" from the popup menu to see the list of choices. You will probably need to tweak the size settings in the controller.php file to make things look right.

## Customization Steps
The templates that come with the block are intended to be examples or starting points only. To make your own custom theme for this block, do the following:

1. Rename the `designer_gallery` directory as desired (should be the lowercase_and_underscore version of your block's name -- for example, "My Awesome Gallery" would get a directory name of "my_awesome_gallery").
2. Edit the `controller.php` file:
    * Change the class name to be a TitleCaseWithNoSpaces version of the block name (otherwise known as CamelCase), followed by `BlockController` -- for example, "My Awesome Gallery" would get a class name of `MyAwesomeGalleryBlockController`.
    * Change the size settings as appropriate for your design (see code comments for more details).
    * Set $randomizeOrder to true if you want images to be "shuffled" every time the page is viewed.
      If $randomOrder stays false, the display order of images in the gallery will be determined by the File Set's display order (Dashboard -> File Manager -> Sets -> [File Set Name] -> Files).
    * Change the block name and description. It is recommended that the name correspond with the directory and class names, but this is not a technical requirement (just avoids confusion).
    * Change the table name to `bt` followed by the CamelCase version of the block name -- for example, "My Awesome Gallery" would get a table name of `btMyAwesomeGallery`.
3. Edit `db.xml` file so the table name matches what you set in `controller.php`.
4. Customize the gallery/slideshow/slider/fader design by either copying all of the files from one of the existing "templates" to the top-level of the block directory structure, or by creating your own template:
    * Put all of your required javascript files into the block's top-level `js` directory.
        * *NOTE: Do **not** put the base jQuery library in here -- Concrete5 loads this automatically on every page already so if you add it here, it will get loaded twice and cause conflicts and errors on the page.*
    * Put all of your required css files into the block's top-level `css` directory.
    * If a stylesheet utilizes background images, place those in the block's top-level `images` directory, then tweak the css so image paths point to that `images` directory -- e.g. `url(../images/example.png)`
        * *NOTE: Do **not** put the images that will be displayed in your gallery/slideshow/slider/fader here -- those will be chosen by the user when they add this block to a page.*
    * Modify the block's top-level `view.php` file to generate the proper html needed for your gallery/slideshow/slider/fader, as well as the javascript initialization code (as per your jQuery library's instructions). This is the hard part! It can often require tweaking the CSS that came with your gallery/slideshow/slider/fader. See the next section for additional help.
    * After you've finished created your own customized gallery/slideshow/slider/fader, you should probably remove the `/templates/` directory so users don't accidentally choose one of them as a custom template for the block, and also because it contains a lot of files that will just waste space if not being used.

##Customization Notes, Tips, and Gotchas
Some things to watch out for when building your own slideshow:

* Coin Slider, Galleria and Fancybox require the least amount of CSS tweaking to get looking right. Nivo Slider and Galleriffic require a bit more CSS tweaking to get looking right, but they offer more features so it might be worth the trouble. The PlusSlider is a great bare-bones slider/fader that contains almost no out-of-the-box styles so that customization is as easy as possible (because you don't have to undo a lot of styles before adding your own) -- see https://github.com/JamyGolden/PlusSlider for more details.

* If you're not using Concrete 5.4.2 or higher, you won't be able to use cropping (although there is a workaround available if needed -- replace your site's concrete/helpers/image.php file with a recent version from C5's github repo).

* If you're not using Concrete 5.4.1 or higher, you won't be able to specify the display order of images in the file set. Time to upgrade!

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
This block is intended to facilitate advanced javascript for highly customized slideshows and galleries, but the editing interface is very bare-bones -- users only choose a file set, so all meta-data about images must be managed through the dashboard File Manager.

Titles and Descriptions are set via file "Properties" in the File Manager (click on an image, choose "Properties" from the popup menu). Note that Concrete5 sets the Title property to the file name when files are first uploaded, so if you want to use titles in your template you might want to change these.

Reordering the images can be done by clicking the "Sets" tab above the file list in the File Manager, clicking on the appropriate File Set name, and dragging-and-dropping the icons around.

Links to other pages are a bit more challenging, because there is no built-in facility for handling this. If you want to allow users to choose a page that an image links to when clicked, there are a few additional things you need to set up first:

1. Install the free [Page Selector Attribute](http://www.concrete5.org/marketplace/addons/page-selector-attribute/) addon from the marketplace.
2. Allow the new attribute type to be associated with files by going to Dashboard -> Sitewide Settings -> Attributes, and checking the box under the "File" column for the "Page Selector" row.
3. Add a new "Page Selector" attribute: go to Dashboard -> File Manager -> Attributes, find the "Choose Attribute Type" dropdown at the bottom, choose "Page Selector" from the dropdown menu, click the "Go" button. Enter "gallery_link_to_cid" (no quotes) for the handle, "Link To Page" for the name, and leave the "Searchable" checkboxes unchecked. Then click the "Add Attribute" button.

Now that you have the "Link To Page" attribute installed, users can set it for each image via file "Properties" (just like Title and Description above).
