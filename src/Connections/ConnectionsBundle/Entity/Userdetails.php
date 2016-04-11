<?php

namespace Connections\ConnectionsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Userdetails
 *
 * @ORM\Table(name="UserDetails")
 * @ORM\Entity
 */
class Userdetails
{
    /**
     * @var string
     *
     * @ORM\Column(name="Name", type="string", length=100, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="Address", type="string", length=45, nullable=true)
     */
    private $address;

    /**
     * @var string
     *
     * @ORM\Column(name="Company", type="string", length=45, nullable=true)
     */
    private $company;

    /**
     * @var string
     *
     * @ORM\Column(name="Blog", type="string", length=200, nullable=true)
     */
    private $blog;

    /**
     * @var string
     *
     * @ORM\Column(name="Bio", type="text", length=65535, nullable=true)
     */
    private $bio;

    /**
     * @var string
     *
     * @ORM\Column(name="Image", type="string", length=200, nullable=false)
     */
    private $image;

    /**
     * @var \User
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="UserId", referencedColumnName="Id")
     * })
     */
    private $userid;



    /**
     * Set name
     *
     * @param string $name
     * @return Userdetails
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set address
     *
     * @param string $address
     * @return Userdetails
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string 
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set company
     *
     * @param string $company
     * @return Userdetails
     */
    public function setCompany($company)
    {
        $this->company = $company;

        return $this;
    }

    /**
     * Get company
     *
     * @return string 
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * Set blog
     *
     * @param string $blog
     * @return Userdetails
     */
    public function setBlog($blog)
    {
        $this->blog = $blog;

        return $this;
    }

    /**
     * Get blog
     *
     * @return string 
     */
    public function getBlog()
    {
        return $this->blog;
    }

    /**
     * Set bio
     *
     * @param string $bio
     * @return Userdetails
     */
    public function setBio($bio)
    {
        $this->bio = $bio;

        return $this;
    }

    /**
     * Get bio
     *
     * @return string 
     */
    public function getBio()
    {
        return $this->bio;
    }

    /**
     * Set image
     *
     * @param string $image
     * @return Userdetails
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return string 
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set userid
     *
     * @param \Connections\ConnectionsBundle\Entity\User $userid
     * @return Userdetails
     */
    public function setUserid(\Connections\ConnectionsBundle\Entity\User $userid)
    {
        $this->userid = $userid;

        return $this;
    }

    /**
     * Get userid
     *
     * @return \Connections\ConnectionsBundle\Entity\User 
     */
    public function getUserid()
    {
        return $this->userid;
    }
}
