<?php

namespace Connections\ConnectionsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Followers
 *
 * @ORM\Table(name="Followers", indexes={@ORM\Index(name="fk_Followers_User1_idx", columns={"FollowerId"}), @ORM\Index(name="fk_Followers_User2_idx", columns={"FollowingId"})})
 * @ORM\Entity
 */
class Followers
{
    /**
     * @var integer
     *
     * @ORM\Column(name="Id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var boolean
     *
     * @ORM\Column(name="IsDirect", type="boolean", nullable=false)
     */
    private $isdirect;

    /**
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="FollowerId", referencedColumnName="Id")
     * })
     */
    private $followerid;

    /**
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="FollowingId", referencedColumnName="Id")
     * })
     */
    private $followingid;



    /**
     * Set isdirect
     *
     * @param boolean $isdirect
     * @return Followers
     */
    public function setIsdirect($isdirect)
    {
        $this->isdirect = $isdirect;

        return $this;
    }

    /**
     * Get isdirect
     *
     * @return boolean 
     */
    public function getIsdirect()
    {
        return $this->isdirect;
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set followingid
     *
     * @param \Connections\ConnectionsBundle\Entity\User $followingid
     * @return Followers
     */
    public function setFollowingid(\Connections\ConnectionsBundle\Entity\User $followingid = null)
    {
        $this->followingid = $followingid;

        return $this;
    }

    /**
     * Get followingid
     *
     * @return \Connections\ConnectionsBundle\Entity\User 
     */
    public function getFollowingid()
    {
        return $this->followingid;
    }

    /**
     * Set followerid
     *
     * @param \Connections\ConnectionsBundle\Entity\User $followerid
     * @return Followers
     */
    public function setFollowerid(\Connections\ConnectionsBundle\Entity\User $followerid = null)
    {
        $this->followerid = $followerid;

        return $this;
    }

    /**
     * Get followerid
     *
     * @return \Connections\ConnectionsBundle\Entity\User 
     */
    public function getFollowerid()
    {
        return $this->followerid;
    }
}
