<?php

class ImageFlags {
    const RELOCS_STRIPPED = 0x0001;
    const EXECUTABLE_IMAGE = 0x0002;
    const LINE_NUMS_STRIPPED = 0x0004;
    const LOCAL_SYMS_STRIPPED = 0x0008;
    const MINIMAL_OBJECT = 0x0010;
    const UPDATE_OBJECT = 0x0020;
    const SIXTEENBIT_MACHINE = 0x0040;
    const BYTES_REVERSED_LO = 0x0080;
    const THIRTYTWOBIT_MACHINE = 0x0100;
    const DEBUG_STRIPPED = 0x0200;
    const PATCH = 0x0400;
    const SYSTEM = 0x1000;
    const DLL = 0x2000;
    const BYTES_REVERSED_HI = 0x8000;
}

?>
