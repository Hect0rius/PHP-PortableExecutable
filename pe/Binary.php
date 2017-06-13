<?php

/*
 * This part of my research is directly understud from my program xenious,
 * I originally took most of the format from pe_image.h on windows.
 * Written by Hect0r Xorius <staticpi.net@gmail.com> - StaticPi.net
 */

// Main Structure.
include('ImageDosHeader.php');
include('ImageFileHeader.php');
include('ImageOptHeader.php');
include('ImageSectionHeader.php');

// Flags.
include('ImageFlags.php');
include('ImageLoaderFlags.php');
include('ImageSectionAlignFlags.php');
include('ImageSectionContainsFlags.php');
include('ImageSectionLinkerFlags.php');
include('ImageSectionType.php');

class PEImage {
    public $imgDosHeader; // Dos Header Struct.
    public $imgFileHeader; // PE File Header Struct.
    public $imgOptHeader; // Optional Header.
    public $imgSections; // Sections List.
    
    private $io;
    private $open;
    private $endian;
    
    public function getImgEndian() { return (int)$this->endian; }
    public function isOpen() { return (bool)$this->open; }
    
    private function readDosHeader() {
        $this->imgDosHeader = new ImageDosHeader();
        $this->io->setPosition(0);
        $this->imgDosHeader->e_magic = $this->io->readUInt16((int)$this->endian);
        $this->imgDosHeader->e_cblp = $this->io->readUInt16((int)$this->endian);
        $this->imgDosHeader->e_cp = $this->io->readUInt16((int)$this->endian);
        $this->imgDosHeader->e_crlc =$this->io->readUInt16((int)$this->endian);
        $this->imgDosHeader->e_cparhdr = $this->io->readUInt16((int)$this->endian);
        $this->imgDosHeader->e_minalloc = $this->io->readUInt16((int)$this->endian);
        $this->imgDosHeader->e_maxalloc = $this->io->readUInt16((int)$this->endian);
        $this->imgDosHeader->e_ss = $this->io->readUInt16((int)$this->endian);
        $this->imgDosHeader->e_sp = $this->io->readUInt16((int)$this->endian);
        $this->imgDosHeader->e_csub = $this->io->readUInt16((int)$this->endian);
        $this->imgDosHeader->e_ip = $this->io->readUInt16((int)$this->endian);
        $this->imgDosHeader->e_cs = $this->io->readUInt16((int)$this->endian);
        $this->imgDosHeader->e_lfarlc = $this->io->readUInt16((int)$this->endian);
        $this->imgDosHeader->e_ovno = $this->io->readUInt16((int)$this->endian);
        $this->imgDosHeader->e_res = array();
        
        for($i = 0; $i < 4; $i++) {
            $this->imgDosHeader->e_res[] = $this->io->readUInt16((int)$this->endian);
        }
        
        $this->imgDosHeader->e_oemid = $this->io->readUInt16((int)$this->endian);
        $this->imgDosHeader->e_oeminfo = $this->io->readUInt16((int)$this->endian);
        $this->imgDosHeader->e_res2 = array();
        
        for($x = 0; $x < 10; $x++) {
            $this->imgDosHeader->e_res2[] = $this->io->readUInt16((int)$this->endian);
        }
        
        $this->imgDosHeader->e_lfanew = $this->io->readUInt16((int)$this->endian);
        $this->imgDosHeader->e_rstub = $this->io->readUInt16((int)$this->endian);
    }
    private function readFileHeader() {
        $this->io->setPosition((int)$this->imgDosHeader->e_lfanew);
        
        $this->imgFileHeader = new ImageFileHeader();
        $this->imgFileHeader->magic = $this->io->readUInt32((int)$this->endian);
        $this->imgFileHeader->Machine = $this->io->readUInt16((int)$this->endian);
        $this->imgFileHeader->NumberOfSections = $this->io->readUInt16((int)$this->endian);
        $this->imgFileHeader->TimeDateStamp = $this->io->readUInt32((int)$this->endian);
        $this->imgFileHeader->PointerToSymbolTable = $this->io->readUInt32((int)$this->endian);
        $this->imgFileHeader->NumberOfSymbols = $this->io->readUInt32((int)$this->endian);
        $this->imgFileHeader->SizeOfOptionalHeader = $this->io->readUInt16((int)$this->endian);
        $this->imgFileHeader->Characteristics = $this->io->readUInt16((int)$this->endian);
    }
    private function readOptHeader() {
        $this->io->setPosition((int)$this->imgDosHeader->e_lfanew + 24);
        
        $this->imgOptHeader = new ImageOptHeader();
        $this->imgOptHeader->Magic = $this->io->readUInt16((int)$this->endian);
        $this->imgOptHeader->MajorLinkerVersion = $this->io->readBytes(1)[0];
        $this->imgOptHeader->MinorLinkerVersion = $this->io->readBytes(1)[0];
        $this->imgOptHeader->SizeOfCode = $this->io->readUInt32((int)$this->endian);
        $this->imgOptHeader->SizeOfInitializedData = $this->io->readUInt32((int)$this->endian);
        $this->imgOptHeader->SizeOfUninitializedData = $this->io->readUInt32((int)$this->endian);
        $this->imgOptHeader->AddressOfEntryPoint = $this->io->readUInt32((int)$this->endian);
        $this->imgOptHeader->BaseOfCode = $this->io->readUInt32((int)$this->endian);
        $this->imgOptHeader->BaseOfData = $this->io->readUInt32((int)$this->endian);
        $this->imgOptHeader->ImageBase = $this->io->readUInt32((int)$this->endian);
        $this->imgOptHeader->SectionAlignment = $this->io->readUInt32((int)$this->endian);
        $this->imgOptHeader->FileAlignment = $this->io->readUInt32((int)$this->endian);
        $this->imgOptHeader->MajorOperatingSystemVersion = $this->io->readUInt16((int)$this->endian);
        $this->imgOptHeader->MinerOperatingSystemVersion = $this->io->readUInt16((int)$this->endian);
        $this->imgOptHeader->MajorImageVersion = $this->io->readUInt16((int)$this->endian);
        $this->imgOptHeader->MinorImageVersion = $this->io->readUInt16((int)$this->endian);
        $this->imgOptHeader->MajorSubsystemVersion = $this->io->readUInt16((int)$this->endian);
        $this->imgOptHeader->MinorSubsystemVersion = $this->io->readUInt16((int)$this->endian);
        $this->imgOptHeader->Reserved1 = $this->io->readUInt32((int)$this->endian);
        $this->imgOptHeader->SizeOfImage = $this->io->readUInt32((int)$this->endian);
        $this->imgOptHeader->Checksum = $this->io->readUInt32((int)$this->endian);
        $this->imgOptHeader->Subsystem = $this->io->readUInt16((int)$this->endian);
        $this->imgOptHeader->DllCharacteristics = $this->io->readUInt16((int)$this->endian);
        $this->imgOptHeader->SizeOfStackReserve = $this->io->readUInt32((int)$this->endian);
        $this->imgOptHeader->SizeOfStackCommit = $this->io->readUInt32((int)$this->endian);
        $this->imgOptHeader->SizeOfHeapReserve = $this->io->readUInt32((int)$this->endian);
        $this->imgOptHeader->SizeOfHeadCommit = $this->io->readUInt32((int)$this->endian);
        $this->imgOptHeader->LoaderFlags = $this->io->readUInt32((int)$this->endian);
        $this->imgOptHeader->NumberOfRvaAndSizes = $this->io->readUInt32((int)$this->endian);
    }
    private function readImgSections() {
        $this->io->setPosition((int)$this->imgDosHeader->e_lfanew + 248);
        $this->imgSections = array();
        
        for($i = 0; $i < (int)$this->imgFileHeader->NumberOfSections; $i++) {
            $buf = new ImageSectionHeader();
            $buf->Name = $this->io->readAsciiString(8);
            $buf->Misc = $this->io->readUInt32((int)$this->endian);
            $buf->VirtualAddress = $this->io->readUInt32((int)$this->endian);
            $buf->SizeOfRawData = $this->io->readUInt32((int)$this->endian);
            $buf->RawDataPtr = $this->io->readUInt32((int)$this->endian);
            $buf->RelocationsPtr = $this->io->readUInt32((int)$this->endian);
            $buf->LineNumsPtr = $this->io->readUInt32((int)$this->endian);
            $buf->NumRelocations = $this->io->readUInt32((int)$this->endian);
            $buf->NumRelocations = $this->io->readUInt16((int)$this->endian);
            $buf->Characteristics = $this->io->readUInt16((int)$this->endian);
            $this->imgSections[] = $buf;
            unset($buf);
        }
    }
    public function writeDosHeader($io) {
        if(!is_object($io) || get_class($io) !== 'fileStream') { throw new Exception('writeDosHeader: Invalid IO/FileStream Input...'); }
        $io->setPosition(0);
        $io->writeUInt16($this->imgDosHeader->e_magic, (int)$this->endian);
        $io->writeUInt16($this->imgDosHeader->e_cblp, (int)$this->endian);
        $io->writeUInt16($this->imgDosHeader->e_cp, (int)$this->endian);
        $io->writeUInt16($this->imgDosHeader->e_crlc, (int)$this->endian);
        $io->writeUInt16($this->imgDosHeader->e_cparhdr, (int)$this->endian);
        $io->writeUInt16($this->imgDosHeader->e_minalloc, (int)$this->endian);
        $io->writeUInt16($this->imgDosHeader->e_maxalloc, (int)$this->endian);
        $io->writeUInt16($this->imgDosHeader->e_ss, (int)$this->endian);
        $io->writeUInt16($this->imgDosHeader->e_sp, (int)$this->endian);
        $io->writeUInt16($this->imgDosHeader->e_csub, (int)$this->endian);
        $io->writeUInt16($this->imgDosHeader->e_ip, (int)$this->endian);
        $io->writeUInt16($this->imgDosHeader->e_cs, (int)$this->endian);
        $io->writeUInt16($this->imgDosHeader->e_lfarlc, (int)$this->endian);
        $io->writeUInt16($this->imgDosHeader->e_ovno, (int)$this->endian);
        
        for($i = 0; $i < 4; $i++) {
            $io->writeUInt16($this->imgDosHeader->e_res[$i], (int)$this->endian);
        }
        
        $io->writeUInt16($this->imgDosHeader->e_oemid, (int)$this->endian);
        $io->writeUInt16($this->imgDosHeader->e_oeminfo, (int)$this->endian);
        
        for($x = 0; $x < 10; $x++) {
            $io->writeUInt16($this->imgDosHeader->e_res2[$i], (int)$this->endian);
        }
        
        $io->writeUInt16($this->imgDosHeader->e_lfanew, (int)$this->endian);
        $io->writeUInt16($this->imgDosHeader->e_rstub, (int)$this->endian);
    }
    public function writeFileHeader($io) {
        if(!is_object($io) || get_class($io) !== 'fileStream') { throw new Exception('writeFileHeader: Invalid IO/FileStream Input...'); }
        $io->writeUInt32($this->imgFileHeader->magic, (int)$this->endian);
        $io->writeUInt16($this->imgFileHeader->Machine, (int)$this->endian);
        $io->writeUInt16($this->imgFileHeader->NumberOfSections, (int)$this->endian);
        $io->writeUInt32($this->imgFileHeader->TimeDateStamp, (int)$this->endian);
        $io->writeUInt32($this->imgFileHeader->PointerToSymbolTable, (int)$this->endian);
        $io->writeUInt32($this->imgFileHeader->NumberOfSymbols, (int)$this->endian);
        $io->writeUInt16($this->imgFileHeader->SizeOfOptionalHeader, (int)$this->endian);
        $io->writeUInt16($this->imgFileHeader->Characteristics, (int)$this->endian);
    }
    public function writeOptHeader($io) {
        if(!is_object($io) || get_class($io) !== 'fileStream') { throw new Exception('writeOptHeader: Invalid IO/FileStream Input...'); }
        $io->writeUInt16($this->imgOptHeader->Magic, (int)$this->endian);
        $io->writeByte(hexdec($this->imgOptHeader->MajorLinkerVersion));;
        $io->writeByte(hexdec($this->imgOptHeader->MinorLinkerVersion));
        $io->writeUInt32($this->imgOptHeader->SizeOfCode, (int)$this->endian);
        $io->writeUInt32($this->imgOptHeader->SizeOfInitializedData, (int)$this->endian);
        $io->writeUInt32($this->imgOptHeader->SizeOfUninitializedData, (int)$this->endian);
        $io->writeUInt32($this->imgOptHeader->AddressOfEntryPoint, (int)$this->endian);
        $io->writeUInt32($this->imgOptHeader->BaseOfCode, (int)$this->endian);
        $io->writeUInt32($this->imgOptHeader->BaseOfData, (int)$this->endian);
        $io->writeUInt32($this->imgOptHeader->ImageBase, (int)$this->endian);
        $io->writeUInt32($this->imgOptHeader->SectionAlignment, (int)$this->endian);
        $io->writeUInt32($this->imgOptHeader->FileAlignment, (int)$this->endian);
        $io->writeUInt32($this->imgOptHeader->MajorOperatingSystemVersion, (int)$this->endian);
        $io->writeUInt32($this->imgOptHeader->MinerOperatingSystemVersion, (int)$this->endian);
        $io->writeUInt16($this->imgOptHeader->MajorImageVersion, (int)$this->endian);
        $io->writeUInt16($this->imgOptHeader->MinorImageVersion, (int)$this->endian);
        $io->writeUInt16($this->imgOptHeader->MajorSubsystemVersion, (int)$this->endian);
        $io->writeUInt16($this->imgOptHeader->MinorSubsystemVersion, (int)$this->endian);
        $io->writeUInt32($this->imgOptHeader->Reserved1, (int)$this->endian);
        $io->writeUInt32($this->imgOptHeader->SizeOfImage, (int)$this->endian);
        $io->writeUInt32($this->imgOptHeader->Checksum, (int)$this->endian);
        $io->writeUInt16($this->imgOptHeader->Subsystem, (int)$this->endian);
        $io->writeUInt16($this->imgOptHeader->DllCharacteristics, (int)$this->endian);
        $io->writeUInt32($this->imgOptHeader->SizeOfStackReserve, (int)$this->endian);
        $io->writeUInt32($this->imgOptHeader->SizeOfStackCommit, (int)$this->endian);
        $io->writeUInt32($this->imgOptHeader->SizeOfHeapReserve, (int)$this->endian);
        $io->writeUInt32($this->imgOptHeader->SizeOfHeadCommit, (int)$this->endian);
        $io->writeUInt32($this->imgOptHeader->LoaderFlags, (int)$this->endian);
        $io->writeUInt32($this->imgOptHeader->NumberOfRvaAndSizes, (int)$this->endian);
    }
    public function writeImgSections($io) {
        if(!is_object($io) || get_class($io) !== 'fileStream') { throw new Exception('writeImgSections: Invalid IO/FileStream Input...'); }
        
        for($i = 0; $i < (int)sizeof($this->imgSections); $i++) {
            // Introduce padding to the name to make sure it is of length.
            if(strlen($this->imgSections[$i]->Name) !== 8) {
                do {
                    $this->imgSections[$i]->Name .= hex2bin('00');
                }
                while(strlen($this->imgSections[$i]->Name) !== 8);
            }
            $io->writeAsciiString($this->imgSections[$i]->Name);
            $io->writeUInt32($this->imgSections[$i]->Misc, (int)$this->endian);
            $io->writeUInt32($this->imgSections[$i]->VirtualAddress, (int)$this->endian);
            $io->writeUInt32($this->imgSections[$i]->SizeOfRawData, (int)$this->endian);
            $io->writeUInt32($this->imgSections[$i]->RawDataPtr, (int)$this->endian);
            $io->writeUInt32($this->imgSections[$i]->RelocationsPtr, (int)$this->endian);
            $io->writeUInt32($this->imgSections[$i]->LineNumsPtr, (int)$this->endian);
            $io->writeUInt32($this->imgSections[$i]->NumRelocations, (int)$this->endian);
            $io->writeUInt16($this->imgSections[$i]->NumRelocations, (int)$this->endian);
            $io->writeUInt16($this->imgSections[$i]->Characteristics, (int)$this->endian);
        }
    }
    
    
    public function __construct($peFile) {
        $this->io = new fileStream($peFile, 'r');
        $magic = (int)$this->io->readUInt16();
        
        $this->endian = ($magic === 23117 ? Endian::LOW : Endian::HIGH);
        
        if($magic === 23117 || $magic === 19802) {
            $this->readDosHeader();
            $this->readFileHeader();
            $this->readOptHeader();
            $this->readImgSections();
            $this->open = true;
            return;
        }
        else {
            throw new Exception('PE File does not have MS DOS Header :/');
        }
        
        $this->open = false;
    }
    
}

?>
