<?php defined('C5_EXECUTE') or die(_("Access Denied."));

class DesignerGalleryBlockController extends BlockController {
/*    ^^^^^^^^^^^^^^^
      CHANGE THIS PORTION
      OF THE CLASS NAME TO
      CamelCase VERSION OF
      THE BLOCK'S NAME.
*/
	
	protected $btTable = 'btDesignerGallery'; //Must be the same as table name in db.xml.
	                                          //Should correspond to block's directory/class name.
	
	public function getBlockTypeName() {
		return t('Designer Gallery'); //Appears in "Add Block" list when adding blocks to a page
	}
	
	public function getBlockTypeDescription() {
		return t('Designer Gallery'); //Only appears in dashboard "Add Functionality" page
	}
	
	//Default values for new blocks...
	//Note that if you disable controls below, the defaults here serve as
	// "permanent" or "hard-coded" values that the user can never change.
	private $defaultLargeWidth = 0;
	private $defaultLargeHeight = 0;
	private $defaultCropLarge = false;
	private $defaultThumbWidth = 0;
	private $defaultThumbHeight = 0;
	private $defaultCropThumb = true;
	private $defaultRandomize = false;
	
	//Add/Edit interface configuration...
	private $showLargeControls = true;
	private $showThumbControls = false;
	
	//Caching is disabled while in development,
	// but you should change these to TRUE for production.
	protected $btCacheBlockRecord = false;
	protected $btCacheBlockOutput = false;
	protected $btCacheBlockOutputOnPost = false;
	protected $btCacheBlockOutputForRegisteredUsers = false;
	protected $btCacheBlockOutputLifetime = CACHE_LIFETIME;

/* DONE! You generally don't need to change anything below this line.
**************************************************************************************************/
	
	protected $btInterfaceWidth = "500";
	protected $btInterfaceHeight = "200";
	
	public function getJavaScriptStrings() {
		return array(
			'fileset-required' => t('You must choose a file set.'),
		);
	}
	
	public function add() {
		$this->set('fsID', 0);
		$this->setFileSets();
		$this->setInterfaceSettings();
		
		//Default values for new blocks...
		$this->randomize = $this->defaultRandomize;
		$this->largeWidth = $this->defaultLargeWidth;
		$this->largeHeight = $this->defaultLargeHeight;
		$this->cropLarge = $this->defaultCropLarge;
		$this->thumbWidth = $this->defaultThumbWidth;
		$this->thumbHeight = $this->defaultThumbHeight;
		$this->cropThumb = $this->defaultCropThumb;
		
		$this->setNormalizedValues();
	}
	
	public function edit() {
		$this->setFileSets();
		$this->setInterfaceSettings();
		$this->setNormalizedValues();
	}
	
	private function setNormalizedValues() {
		//Don't show 0 for empty widths/heights...
		$this->set('largeWidth', empty($this->largeWidth) ? '' : $this->largeWidth);
		$this->set('largeHeight', empty($this->largeHeight) ? '' : $this->largeHeight);
		$this->set('thumbWidth', empty($this->thumbWidth) ? '' : $this->thumbWidth);
		$this->set('thumbHeight', empty($this->thumbHeight) ? '' : $this->thumbHeight);		
		
		$this->set('cropLarge', (empty($this->largeWidth) && empty($this->largeHeight)) ? '-1' : ($this->cropLarge ? 1 : 0));
		$this->set('cropThumb', $this->cropThumb ? 1 : 0);
	}
	
	private function setInterfaceSettings() {
		$filesetsToolsURL = Loader::helper('concrete/urls')->getBlockTypeToolsURL(BlockType::getByHandle($this->btHandle)) . '/fileset_select_options';
		$this->set('filesetsToolURL', $filesetsToolsURL);
		$this->set('showLargeControls', $this->showLargeControls);
		$this->set('showThumbControls', $this->showThumbControls);
	}
	
	//Internal helper function (this isn't extending a block_type_controller method)
	private function getPkgHandle() {
		return BlockType::getByHandle($this->btHandle)->getPackageHandle();
	}
	
	private function setFileSets() {
		Loader::model('file_set');
		$fileSets = FileSet::getMySets();
		$this->set('fileSets', $fileSets);
	}
	
	public function save($data) {
		$data['largeWidth'] = intval($data['largeWidth']);
		$data['largeHeight'] = intval($data['largeHeight']);
		$data['thumbWidth'] = intval($data['thumbWidth']);
		$data['thumbHeight'] = intval($data['thumbHeight']);
		
		$data['cropLarge'] = (intval($data['cropLarge']) < 1) ? 0 : 1; //Watch out for the "-1" option
		
		parent::save($data);
	}
	
	public function view() {
		$files = $this->getFilesetImages($this->fsID, $this->randomize);
		$images = $this->processImageFiles($files, $this->largeWidth, $this->largeHeight, $this->cropLarge, $this->thumbWidth, $this->thumbHeight, $this->cropThumb);
		$this->set('images', $images);
	}
	
	static function getFilesetImages($fsID, $randomize = false) {
		Loader::model('file_set');
		Loader::model('file_list');
		
		$fs = FileSet::getByID($fsID);
		$fl = new FileList();		
		$fl->filterBySet($fs);
		$fl->filterByType(FileType::T_IMAGE);
		$fl->setPermissionLevel('canRead');
		if ($randomize) {
			$fl->sortBy('RAND()', 'asc');
		} else {
			$fl->sortByFileSetDisplayOrder(); //Requires 5.4.1 or higher: version_compare(APP_VERSION, '5.4.1', '>=');
		}
		$files = $fl->get();
		return $files;	
	}
	
	private function processImageFiles($imageFiles, $largeWidth, $largeHeight, $cropLarge, $thumbWidth, $thumbHeight, $cropThumb) {
		$ih = version_compare(APP_VERSION, '5.4.2', '>=') ? Loader::helper('image') : Loader::helper('cropping_image', $this->getPkgHandle());
		$nh = Loader::helper('navigation');
		
		$resizeLarge = ($largeWidth > 0 || $largeHeight > 0);
		$resizeLargeWidth = empty($largeWidth) ? 9999 : $largeWidth;
		$resizeLargeHeight = empty($largeHeight) ? 9999 : $largeHeight;
		$resizeThumb = ($thumbWidth > 0 || $thumbHeight > 0);
		$resizeThumbWidth = empty($thumbWidth) ? 9999 : $thumbWidth;
		$resizeThumbHeight = empty($thumbHeight) ? 9999 : $thumbHeight;
		
		$maxOrigWidth = 0;
		$maxOrigHeight = 0;
		$maxLargeWidth = 0;
		$maxLargeHeight = 0;
		$maxThumbWidth = 0;
		$maxThumbHeight = 0;
		
		$images = array();
		foreach ($imageFiles as $f) {
			$image = new StdClass;
			
			//Metadata...
			$image->fID = $f->fID;
			$image->titleRaw = $f->getTitle();
			$image->title = htmlspecialchars($image->titleRaw, ENT_QUOTES, APP_CHARSET);
			$image->descriptionRaw = $f->getDescription();
			$image->description = htmlspecialchars($image->descriptionRaw, ENT_QUOTES, APP_CHARSET);
			$linkToCID = $f->getAttribute('gallery_link_to_cid'); //To make this work: 1) Install http://www.concrete5.org/marketplace/addons/page-selector-attribute/ . 2) Go to Dashboard -> Sitewide Settings -> Attributes, check the box under the "File" column for the "Page Selector" attribute type. 3) Go to Dashboard -> File Manager -> Attributes, find the "Choose Attribute Type" dropdown at the bottom, select "Page Selector" from the dropdown, click "Go" button. Handle should be "gallery_link_to_cid" (no quotes), name can be whatever you want ("Link To Page" might be good). Ignore the "Searchable" checkboxes. When done, click the "Add Attribute" button. 4) Now users can choose the page an image will link to by setting this property (click on an image in the file manager, choose "Properties" from the popup menu, find this attribute at the bottom of the list, edit it there and save).
			$image->linkUrl = empty($linkToCID) ? '' : $nh->getLinkToCollection(Page::getByID($linkToCID));
			
			//Original Image (full size)...
			$image->orig = new StdClass;
			$image->orig->src = $f->getRelativePath();
			$image->orig->width = $f->getAttribute('width');
			$image->orig->height = $f->getAttribute('height');
			$maxOrigWidth = ($image->orig->width > $maxOrigWidth) ? $image->orig->width : $maxOrigWidth;
			$maxOrigHeight = ($image->orig->height > $maxOrigHeight) ? $image->orig->height : $maxOrigHeight;
			
			//"Large" Size...
			if (!$resizeLarge) {
				$image->large = $image->orig;
			} else {
				$image->large = $ih->getThumbnail($f, $resizeLargeWidth, $resizeLargeHeight, $cropLarge);
			}
			$maxLargeWidth = ($image->large->width > $maxLargeWidth) ? $image->large->width : $maxLargeWidth;
			$maxLargeHeight = ($image->large->height > $maxLargeHeight) ? $image->large->height : $maxLargeHeight;
			
			//Thumbnail...
			if (!$resizeThumb) {
				$image->thumb = $image->orig;
			} else {
				$image->thumb = $ih->getThumbnail($f, $resizeThumbWidth, $resizeThumbHeight, $cropThumb);
			}
			$maxThumbWidth = ($image->thumb->width > $maxThumbWidth) ? $image->thumb->width : $maxThumbWidth;
			$maxThumbHeight = ($image->thumb->height > $maxThumbHeight) ? $image->thumb->height : $maxThumbHeight;
			
			$images[] = $image;
		}
		
		//These may come in handy to the view...
		$this->set('maxOrigWidth', $maxOrigWidth);
		$this->set('maxOrigHeight', $maxOrigHeight);
		$this->set('maxLargeWidth', $maxLargeWidth);
		$this->set('maxLargeHeight', $maxLargeHeight);
		$this->set('maxThumbWidth', $maxThumbWidth);
		$this->set('maxThumbHeight', $maxThumbHeight);
		
		return $images;
	}
		
}
