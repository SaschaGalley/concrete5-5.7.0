<?php
namespace Concrete\Core\File\Image\Thumbnail\Type;

use Database;
use Core;

/**
 * @Entity
 * @Table(name="FileImageThumbnailTypes")
 */
class Type
{

    /**
     * @Column(type="string")
     */
    protected $ftTypeHandle;

    /**
     * @Column(type="string")
     */
    protected $ftTypeName;

    /**
     * @Column(type="integer")
     */
    protected $ftTypeWidth = 0;

    /**
     * @Column(type="integer")
     */
    protected $ftTypeHeight;

    /**
     * @Column(type="boolean")
     */
    protected $ftTypeIsRequired = false;

    /**
     * @Id @Column(type="integer")
     * @GeneratedValue
     */
    protected $ftTypeID;

    /**
     * @param mixed $ftTypeHandle
     */
    public function setHandle($ftTypeHandle)
    {
        $this->ftTypeHandle = $ftTypeHandle;
    }

    /**
     * @return mixed
     */
    public function getHandle()
    {
        return $this->ftTypeHandle;
    }

    /**
     * @return mixed
     */
    public function getID()
    {
        return $this->ftTypeID;
    }

    /**
     * @param mixed $ftTypeIsRequired
     */
    public function requireType()
    {
        $this->ftTypeIsRequired = true;
    }

    /**
     * @return mixed
     */
    public function isRequired()
    {
        return $this->ftTypeIsRequired;
    }

    /**
     * @param mixed $ftTypeName
     */
    public function setName($ftTypeName)
    {
        $this->ftTypeName = $ftTypeName;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->ftTypeName;
    }

    /** Returns the display name for this thumbnail type (localized and escaped accordingly to $format)
     * @param string $format = 'html'
     *    Escape the result in html format (if $format is 'html').
     *    If $format is 'text' or any other value, the display name won't be escaped.
     * @return string
     */
    public function getDisplayName($format = 'html')
    {
        $value = tc('ThumbnailTypeName', $this->getName());
        switch ($format) {
            case 'html':
                return h($value);
            case 'text':
            default:
                return $value;
        }
    }

    /**
     * @param mixed $ftTypeWidth
     */
    public function setWidth($ftTypeWidth)
    {
        $this->ftTypeWidth = $ftTypeWidth;
    }

    /**
     * @param mixed $ftTypeHeight
     */
    public function setHeight($ftTypeHeight)
    {
        $this->ftTypeHeight = $ftTypeHeight;
    }

    /**
     * @return mixed
     */
    public function getWidth()
    {
        return $this->ftTypeWidth;
    }

    /**
     * @return mixed
     */
    public function getHeight()
    {
        return $this->ftTypeHeight;
    }


    /**
     * @return \Concrete\Core\File\Image\Thumbnail\Type\Type[]
     */
    public static function getList()
    {
        $db = Database::get();
        $em = $db->getEntityManager();
        return $em->getRepository('\Concrete\Core\File\Image\Thumbnail\Type\Type')->findBy(array(), array('ftTypeWidth' => 'asc'));
    }

    /**
     * @return \Concrete\Core\File\Image\Thumbnail\Type\Version[]
     */
    public static function getVersionList()
    {
        $types = static::getList();
        $versions = array();
        foreach($types as $type) {
            $versions[] = $type->getBaseVersion();
            $versions[] = $type->getDoubledVersion();
        }
        return $versions;
    }

    public function save()
    {
        $db = Database::get();
        $em = $db->getEntityManager();
        $em->persist($this);
        $em->flush();
    }

    public static function exportList($node)
    {
        $child = $node->addChild('thumbnailtypes');
        $list = static::getList();
        foreach($list as $link) {
            $linkNode = $child->addChild('thumbnailtype');
            $linkNode->addAttribute('name', $link->getName());
            $linkNode->addAttribute('handle', $link->getHandle());
            $linkNode->addAttribute('width', $link->getWidth());
            if ($link->getHeight()) {
                $linkNode->addAttribute('height', $link->getHeight());
            }
            if ($link->isRequired()) {
                $linkNode->addAttribute('required', $link->isRequired());
            }
        }
    }

    public function delete()
    {
        $em = Database::get()->getEntityManager();
        $em->remove($this);
        $em->flush();
    }

    public static function getByID($id)
    {
        $db = Database::get();
        $em = $db->getEntityManager();
        $r = $em->find('\Concrete\Core\File\Image\Thumbnail\Type\Type', $id);
        return $r;
    }

    /**
     * @param $ftTypeHandle
     * @return \Concrete\Core\File\Image\Thumbnail\Type\Type
     */
    public static function getByHandle($ftTypeHandle)
    {
        $db = Database::get();
        $em = $db->getEntityManager();
        $r = $em->getRepository('\Concrete\Core\File\Image\Thumbnail\Type\Type')
            ->findOneBy(array('ftTypeHandle' => $ftTypeHandle));
        return $r;
    }

    public function getBaseVersion()
    {
        return new Version($this->getHandle(), $this->getHandle(), $this->getName(), $this->getWidth(), $this->getHeight());
    }

    public function getDoubledVersion()
    {
        $name = t('%s (Retina Version)', $this->getName());
        $height = null;
        if ($this->getHeight()) {
            $height = $this->getHeight() * 2;
        }
        return new Version($this->getHandle() . '_2x', $this->getHandle() . '_2x', $name, $this->getWidth() * 2, $height);
    }

}