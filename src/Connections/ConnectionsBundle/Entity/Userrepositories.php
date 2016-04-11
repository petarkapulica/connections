<?php

namespace Connections\ConnectionsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Userrepositories
 *
 * @ORM\Table(name="UserRepositories", indexes={@ORM\Index(name="fk_UserRepositories_User1_idx", columns={"UserId"}), @ORM\Index(name="fk_UserRepositories_Repository1_idx", columns={"RepositoryId"})})
 * @ORM\Entity
 */
class Userrepositories
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
     * @var \Repository
     *
     * @ORM\ManyToOne(targetEntity="Repository")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="RepositoryId", referencedColumnName="Id")
     * })
     */
    private $repositoryid;

    /**
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="UserId", referencedColumnName="Id")
     * })
     */
    private $userid;



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
     * Set userid
     *
     * @param \Connections\ConnectionsBundle\Entity\User $userid
     * @return Userrepositories
     */
    public function setUserid(\Connections\ConnectionsBundle\Entity\User $userid = null)
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

    /**
     * Set repositoryid
     *
     * @param \Connections\ConnectionsBundle\Entity\Repository $repositoryid
     * @return Userrepositories
     */
    public function setRepositoryid(\Connections\ConnectionsBundle\Entity\Repository $repositoryid = null)
    {
        $this->repositoryid = $repositoryid;

        return $this;
    }

    /**
     * Get repositoryid
     *
     * @return \Connections\ConnectionsBundle\Entity\Repository 
     */
    public function getRepositoryid()
    {
        return $this->repositoryid;
    }
}
