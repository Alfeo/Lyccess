<?php

namespace FrontendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Friend
 *
 * @ORM\Table(name="friend")
 * @ORM\Entity(repositoryClass="FrontendBundle\Repository\FriendRepository")
 */
class Friend
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="iduser", type="integer", nullable=true)
     */
    private $iduser;

    /**
     * @var int
     *
     * @ORM\Column(name="idtarget", type="integer", nullable=true)
     */
    private $idtarget;


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
     * Set iduser
     *
     * @param integer $iduser
     * @return Friend
     */
    public function setIduser($iduser)
    {
        $this->iduser = $iduser;

        return $this;
    }

    /**
     * Get iduser
     *
     * @return integer 
     */
    public function getIduser()
    {
        return $this->iduser;
    }

    /**
     * Set idtarget
     *
     * @param integer $idtarget
     * @return Friend
     */
    public function setIdtarget($idtarget)
    {
        $this->idtarget = $idtarget;

        return $this;
    }

    /**
     * Get idtarget
     *
     * @return integer 
     */
    public function getIdtarget()
    {
        return $this->idtarget;
    }
}
