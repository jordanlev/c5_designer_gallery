<?php defined('C5_EXECUTE') or die(_("Access Denied."));

class DesignerGalleryBlockController extends BlockController {
/*    ^^^^^^^^^^^^^^^
      CHANGE THIS PORTION
      OF THE CLASS NAME TO
      CamelCase VERSION OF
      THE BLOCK'S NAME.
*/
	
	//Size/Cropping Notes:
	// * Images will never be scaled up in size (i.e. an image smaller than the given size settings will not be enlarged).
	// * Cropping only works in Concrete version 5.4.2 and up.
	//   (If using an earlier version of C5, the crop setting will be ignored and images will always be scaled.)
	// * If cropping, the width and height determine the exact size of the resized image.
	// * If not cropping, the image is resized proportionally, so width and height determine the maximum possible size.
	// * Setting a width or height to 0 means "ignore this size in our calculations" (as opposed to "make this invisible"):
	//    ~if cropping, setting one dimension to 0 means that only the *other* dimension will be cropped.
	//    ~if not cropping, setting one dimension to 0 means that the image will be scaled down proportionally according to the *other* dimension.
	//    ~if both width and height are set to 0, resizing/cropping will be disabled for that size.
	//     (Do this if you're not using a particular size of image in your template, for example if you are not
	//     displaying thumbnails in your gallery, disable them by setting $thumbWidth and $thumbHeight to 0.)
	
	public $largeWidth = 300;
	public $largeHeight = 200;
	public $cropLarge = false;
	
	public $thumbWidth = 50;
	public $thumbHeight = 50;
	public $cropThumbs = true;
	
	public $randomizeOrder = false;
			
	protected $btName = 'Designer Gallery'; //Appears in "Add Block" list when adding blocks to a page
	protected $btDescription = 'Designer Gallery'; //Only appears in dashboard "Add Functionality" page
	protected $btTable = 'btDesignerGallery'; //Must be the same as table name in db.xml. Should correspond to block's directory/class name (with a "bt" prefix)

/* DONE! You generally don't need to change anything below this line.
**************************************************************************************************/

	protected $btInterfaceWidth = "300";
	protected $btInterfaceHeight = "95";
	protected $btCacheBlockRecord = true;
	protected $btCacheBlockOutput = true;
	protected $btCacheBlockOutputOnPost = true;
	protected $btCacheBlockOutputForRegisteredUsers = true;
	protected $btCacheBlockOutputLifetime = 300;
	
	public function add() {
		$this->set('fsID', 0);
		$this->loadFileSets();
		$this->set('filesetsToolURL', REL_DIR_FILES_TOOLS_BLOCKS . '/designer_gallery/fileset_select_options');
	}
	
	public function edit() {
		$this->loadFileSets();
		$this->set('filesetsToolURL', REL_DIR_FILES_TOOLS_BLOCKS . '/designer_gallery/fileset_select_options');
	}
	
	public function view() {
		$files = $this->getFilesetImages($this->fsID, $this->randomizeOrder);
		$images = $this->processImageFiles($files, $this->largeWidth, $this->largeHeight, $this->cropLarge, $this->thumbWidth, $this->thumbHeight, $this->cropThumbs);
		$this->set('images', $images);
	}
	
	private function loadFileSets() {
		Loader::model('file_set');
		$fileSets = FileSet::getMySets();
		$this->set('fileSets', $fileSets);
	}
	
	static function getFilesetImages($fsID, $randomize = false) {
		Loader::model('file_set');
		Loader::model('file_list');
		$filesetDisplayOrderSupported = version_compare(APP_VERSION, '5.4.1', '>=');
		
		$fs = FileSet::getByID($fsID);
		$fl = new FileList();		
		$fl->filterBySet($fs);
		$fl->filterByType(FileType::T_IMAGE);
		$fl->setPermissionLevel('canRead');
		if ($randomize) {
			$fl->sortBy('RAND()', 'asc');
		} else if ($filesetDisplayOrderSupported) {
			$fl->sortByFileSetDisplayOrder();
		}
		$files = $fl->get();
		return $files;	
	}
	
	private function processImageFiles($imageFiles, $largeWidth, $largeHeight, $cropLarge, $thumbWidth, $thumbHeight, $cropThumbs) {
		$ih = Loader::helper('image');
		$nh = Loader::helper('navigation');
		$resizeLarge = ($largeWidth > 0 || $largeHeight > 0);
		$resizeLargeWidth = empty($largeWidth) ? 9999 : $largeWidth;
		$resizeLargeHeight = empty($largeHeight) ? 9999 : $largeHeight;
		$resizeThumb = ($thumbWidth > 0 || $thumbHeight > 0);
		$resizeThumbWidth = empty($thumbWidth) ? 9999 : $thumbWidth;
		$resizeThumbHeight = empty($thumbHeight) ? 9999 : $thumbHeight;
		$croppingSupported = version_compare(APP_VERSION, '5.4.2', '>=');
		
		$images = array();
		foreach ($imageFiles as $f) {
			$image = new StdClass;

			$image->fID = $f->fID;
			$image->titleRaw = $f->getTitle();
			$image->title = htmlspecialchars($image->titleRaw, ENT_QUOTES, APP_CHARSET);
			$image->descriptionRaw = $f->getDescription();
			$image->description = htmlspecialchars($image->descriptionRaw, ENT_QUOTES, APP_CHARSET);
			$linkToCID = $f->getAttribute('gallery_link_to_cid'); //To make this work: 1) Install http://www.concrete5.org/marketplace/addons/page-selector-attribute/ . 2) Go to Dashboard -> Sitewide Settings -> Attributes, check the box under the "File" column for the "Page Selector" attribute type. 3) Go to Dashboard -> File Manager -> Attributes, find the "Choose Attribute Type" dropdown at the bottom, select "Page Selector" from the dropdown, click "Go" button. Handle should be "gallery_link_to_cid" (no quotes), name can be whatever you want ("Link To Page" might be good). Ignore the "Searchable" checkboxes. When done, click the "Add Attribute" button. 4) Now users can choose the page an image will link to by setting this property (click on an image in the file manager, choose "Properties" from the popup menu, find this attribute at the bottom of the list, edit it there and save).
			$image->linkUrl = empty($linkToCID) ? '' : $nh->getLinkToCollection(Page::getByID($linkToCID));
			
			$image->orig = new StdClass;
			$image->orig->src = $f->getRelativePath();
			$size = getimagesize($f->getPath());
			$image->orig->width = $size[0];
			$image->orig->height = $size[1];
			
			if (!$resizeLarge) {
				$image->large = $image->orig;
			} else if ($croppingSupported) {
				$image->large = $ih->getThumbnail($f, $resizeLargeWidth, $resizeLargeHeight, $cropLarge);
			} else {
				$image->large = $ih->getThumbnail($f, $resizeLargeWidth, $resizeLargeHeight);
			}
			
			if (!$resizeThumb) {
				$image->thumb = $image->orig;
			} else if ($croppingSupported) {
				$image->thumb = $ih->getThumbnail($f, $resizeThumbWidth, $resizeThumbHeight, $cropThumbs);
			} else {
				$image->thumb = $ih->getThumbnail($f, $resizeThumbWidth, $resizeThumbHeight);
			}

			$images[] = $image;
		}
		
		return $images;
	}
		
}
