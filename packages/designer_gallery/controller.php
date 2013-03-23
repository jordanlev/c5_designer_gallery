<?php defined('C5_EXECUTE') or die(_("Access Denied."));

class DesignerGalleryPackage extends Package {
/*    ^^^^^^^^^^^^^^^
      CHANGE THIS PORTION
      OF THE CLASS NAME TO
      CamelCase VERSION OF
      THE PACKAGE'S NAME.
*/

	protected $pkgHandle = 'designer_gallery';
	protected $appVersionRequired = '5.4.1';
	protected $pkgVersion = '1.0';
	
	public function getPackageName() {
		return t('Designer Gallery');
	}
	
	public function getPackageDescription() {
		return t('Designer Gallery');
	}
	
/* DONE! You generally don't need to change anything below this line.
**************************************************************************************************/
	
	public function install() {
		$pkg = parent::install();
		BlockType::installBlockTypeFromPackage($this->pkgHandle, $pkg);
		$this->installPageLinkAttribute($pkg);
	}
	
	public function upgrade() {
		$pkg = Package::getByHandle($this->pkgHandle);
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
			$akHandle = 'gallery_link_to_cid';
			$akGalleryLinkToCID = FileAttributeKey::getByHandle($akHandle);
			if (!is_object($akGalleryLinkToCID) || !intval($akGalleryLinkToCID->getAttributeKeyID())) {
				$akGalleryLinkToCID = FileAttributeKey::add(
					$at,
					array(
						'akHandle' => $akHandle,
						'akName' => t('Gallery Link To Page'),
					),
					$pkg
				);
			}
		}
	}
	
}
