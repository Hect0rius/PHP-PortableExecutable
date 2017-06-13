<?php

class ImageOptHeader {
    public $Magic;
    public $MajorLinkerVersion;
    public $MinorLinkerVersion;
    public $SizeOfCode;
    public $SizeOfInitializedData;
    public $SizeOfUninitializedData;
    public $AddressOfEntryPoint;
    public $BaseOfCode;
    public $BaseOfData;
    
    // NT additional fields.
    public $ImageBase;
    public $SectionAlignment;
    public $FileAlignment;
    public $MajorOperatingSystemVersion;
    public $MinorOperatingSystemVersion;
    public $MajorImageVersion;
    public $MinorImageVersion;
    public $MajorSubsystemVersion;
    public $MinorSubsystemVersion;
    public $Reserved1;
    public $SizeOfImage;
    public $SizeOfHeaders;
    public $CheckSum;
    public $Subsystem;
    public $DllCharacteristics;
    public $SizeOfStackReserve;
    public $SizeOfStackCommit;
    public $SizeOfHeapReserve;
    public $SizeOfHeapCommit;
    public $LoaderFlags;
    public $NumberOfRvaAndSizes;
}

?>
