<?php
/*********************************************************************************
 * Karbon Framework is a PHP5 Framework developed by Maxx Ng'ang'a
 * (C) 2016 Crysoft Dynamics Ltd
 * Karbon V 1.0
 * Maxx
 * 5/1/2017
 ********************************************************************************/

namespace AppBundle\Entity;


use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="profile")
 */
class Profile
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Column(type="string")
     */
    private $id;
    /**
     * @ORM\Column(type="string")
     */
    private $firstName;
    /**
     * @ORM\Column(type="string",nullable=true)
     */
    private $middleName;
    /**
     * @ORM\Column(type="string")
     */
    private $lastName;
    /**
     * @ORM\Column(type="string",nullable=true)
     */
    private $ipn;
    /**
     * @ORM\Column(type="string",nullable=true)
     */
    private $memberNumber;
    /**
     * @ORM\Column(type="string")
     */
    private $prefix;
    /**
     * @ORM\Column(type="date",nullable=true)
     */
    private $dateOfBirth;
    /**
     * @ORM\Column(type="string")
     */
    private $stageName;
    /**
     * @ORM\Column(type="string",nullable=true)
     */
    private $nationality;
    /**
     * @ORM\Column(type="string",nullable=true)
     */
    private $language;
    /**
     * @ORM\Column(type="string",nullable=true)
     */
    private $maritalStatus;
    /**
     * @ORM\Column(type="string",nullable=true)
     */
    private $spouseName;
    /**
     * @ORM\Column(type="string")
     */
    private $idNumber;
    /**
     * @ORM\Column(type="string")
     */
    private $itaxPin;
    /**
     * @ORM\Column(type="string",nullable=true)
     */
    private $countryOfResidence;
    /**
     * @ORM\Column(type="string")
     */
    private $memberType;
    /**
     * @ORM\Column(type="string",nullable=true)
     */
    private $gender;
    /**
     * @ORM\Column(type="string",nullable=true)
     */
    private $rights;
    /**
     * @ORM\Column(type="string",nullable=true)
     */
    private $registrationNumber;
    /**
     * @ORM\Column(type="string",nullable=true)
     */
    private $sourceOfData;
    /**
     * @ORM\Column(type="date",nullable=true)
     */
    private $registrationDate;
    /**
     * @ORM\Column(type="string",nullable=true)
     */
    private $status;
    /**
     * @ORM\Column(type="string")
     */
    private $physicalAddress;
    /**
     * @ORM\Column(type="string")
     */
    private $postalAddress;
    /**
     * @ORM\Column(type="string")
     */
    private $postalCode;
    /**
     * @ORM\Column(type="string")
     */
    private $city;
    /**
     * @ORM\Column(type="string")
     */
    private $country;
    /**
     * @ORM\Column(type="string")
     */
    private $phoneNumber;
    /**
     * @ORM\Column(type="string",nullable=true)
     */
    private $altPhoneNumber;
    /**
     * @ORM\Column(type="string",nullable=true)
     */
    private $faxNumber;
    /**
     * @ORM\Column(type="string")
     */
    private $emailAddress;
    /**
     * @ORM\Column(type="string",nullable=true)
     */
    private $website;
    /**
     * @ORM\Column(type="string",nullable=true)
     */
    private $nameOfPayee;
    /**
     * @ORM\Column(type="string")
     */
    private $accountName;
    /**
     * @ORM\Column(type="string",nullable=true)
     */
    private $bank;
    /**
     * @ORM\Column(type="string")
     */
    private $bankBranch;
    /**
     * @ORM\Column(type="string")
     */
    private $accountNumber;
    /**
     * @ORM\Column(type="string")
     */
    private $currency;
    /**
     * @ORM\Column(type="string")
     */
    private $bankAccountType;
    /**
     * @ORM\Column(type="string",nullable=true)
     */
    private $bankPostalAddress;
    /**
     * @ORM\Column(type="string",nullable=true)
     */
    private $iban;
    /**
     * @ORM\Column(type="string",nullable=true)
     */
    private $swiftBic;
    /**
     * @ORM\Column(type="string")
     */
    private $nextOfKinFirstName;
    /**
     * @ORM\Column(type="string")
     */
    private $nextOfKinLastName;
    /**
     * @ORM\Column(type="string")
     */
    private $nextOfKinRelationship;
    /**
     * @ORM\Column(type="string")
     */
    private $nextOfKinIdNumber;
    /**
     * @ORM\Column(type="string")
     */
    private $nextOfKinPercent;
    /**
     * @ORM\Column(type="string")
     */
    private $nextOfKinPhysicalAddress;
    /**
     * @ORM\Column(type="string",nullable=true)
     */
    private $nextOfKinPostalAddress;
    /**
     * @ORM\Column(type="string",nullable=true)
     */
    private $nextOfKinPostalCode;
    /**
     * @ORM\Column(type="string")
     */
    private $nextOfKinCity;
    /**
     * @ORM\Column(type="string")
     */
    private $nextOfKinCountry;
    /**
     * @ORM\Column(type="string")
     */
    private $nextOfKinPhoneNumber;
    /**
     * @ORM\Column(type="string",nullable=true)
     */
    private $nextOfKinEmail;
    /**
     * @ORM\Column(type="date",nullable=true)
     */
    private $nextOfKinAddedDate;
    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;
}