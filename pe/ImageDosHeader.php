<?php

class ImageDosHeader {
    public $magic;         // Magic number
    public $e_cblp;          // Bytes on last page of file
    public $e_cp;            // Pages in file
    public $e_crlc;          // Relocations
    public $e_cparhdr;       // Size of header in paragraphs
    public $e_minalloc;      // Minimum extra paragraphs needed
    public $e_maxalloc;      // Maximum extra paragraphs needed
    public $e_ss;            // Initial (relative) SS value
    public $e_sp;            // Initial SP value
    public $e_csum;          // Checksum
    public $e_ip;            // Initial IP value
    public $e_cs;            // Initial (relative) CS value
    public $e_lfarlc;        // File address of relocation table
    public $e_ovno;          // Overlay number
    public $e_res;        // Reserved words
    public $e_oemid;         // OEM identifier (for e_oeminfo)
    public $e_oeminfo;       // OEM information; e_oemid specific
    public $e_res2;      // Reserved words
    public $e_lfanew;        // File address of new exe header
    public $e_rstub; // Real Mode Stub Program.
}

?>

