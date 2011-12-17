<?php defined('C5_EXECUTE') or die(_("Access Denied."));

class DesignerGalleryPackage extends Package {

	protected $pkgHandle = 'designer_gallery';
	protected $appVersionRequired = '5.4.1';
	protected $pkgVersion = '1.0';
	
	public function getPackageName() {
		return t('Designer Gallery');
	}
	
	public function getPackageDescription() {
		return t('Designer Gallery');
	}
	
	public function install() {
		$pkg = parent::install();
		BlockType::installBlockTypeFromPackage('designer_gallery', $pkg);
		$this->installPageLinkAttribute($pkg);
	}
	
	public function upgrade() {
		$pkg = Package::getByHandle('designer_gallery');
		$this->installPageLinkAttribute($pkg);
		parent::upgrade();
	}
	
	private function installPageLinkAttribute(&$pkg) {
		$at = AttributeType::getByHandle('page_selector');
		if ($at && intval($at->getAttributeTypeID())) {
			//Associate with "file" category (if not done alrady)
			Loader::model('attribute/categories/collection');
			$akc = AttributeKeyCategory::getByHandle('file');
			$sql = 'SELECT COUNT(*) FROM AttributeTypeCategories WHERE atID = ? AND akCategoryID = ?';
			$vals = array($at->getAttributeTypeID(), $akc->akCategoryID);
			$existsInCategory = Loader::db()->GetOne($sql, $vals);
			if (!$existsInCategory) {
				$akc->associateAttributeKeyType($at);
			}
			
			//Install the link-to-page attribute (if not done already)
			Loader::model('file_attributes');
			$akGalleryLinkToCID = FileAttributeKey::getByHandle('gallery_link_to_cid');
			if (!$akGalleryLinkToCID || !intval($akGalleryLinkToCID->getAttributeKeyID())) {
				$akGalleryLinkToCID = FileAttributeKey::add(
					$at,
					array(
						'akHandle' => 'gallery_link_to_cid',
						'akName' => t('Gallery Link To Page'),
					),
					$pkg
				);
			}
		}
	}
	
}