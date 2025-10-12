<?php

namespace Dynamic\SiteTools\Extension;

use SilverStripe\Core\Extension;
use SilverStripe\Forms\FieldList;
use Dynamic\SiteTools\Model\HeaderImage;
use SilverShop\HasOneField\HasOneButtonField;

/**
 * Class HeaderImageDataExtension.
 *
 * @property HeaderImageExtension $owner
 * @property int $HeaderImageID
 * @method HeaderImage HeaderImage()
 */
class HeaderImageExtension extends Extension
{
    /**
     * @var array
     */
    private static $has_one = [
        'HeaderImage' => HeaderImage::class,
    ];

    /**
     * @var string[]
     */
    private static $cascade_duplicates = [
        'HeaderImage',
    ];

    /**
     * @param FieldList $fields
     */
    public function updateCMSFields(FieldList $fields)
    {
        $fields->replace(
            'HeaderImageID',
            $header_image = HasOneButtonField::create(
                $this->owner,
                'HeaderImage',
                ''
            )
        );

        $fields->insertBefore(
            'Content',
            $header_image
        );
    }

    /**
     *
     */
    public function getPageHeaderImage()
    {
        if ($this->owner->HeaderImageID) {
            return $this->owner->HeaderImage();
        } else {
            return self::getParentHeaderImage($this->owner);
        }
    }

    /**
     * @param $page
     */
    private function getParentHeaderImage($page)
    {
        $parent = $page->Parent;
        if ($parent && $parent->HeaderImageID) {
            return $parent->HeaderImage();
        } elseif ($parent) {
            return self::getParentHeaderImage($parent);
        }

        return;
    }
}
