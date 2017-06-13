<?php

// With thanks to https://en.wikibooks.org/wiki/X86_Disassembly/Windows_Executable_Files#COFF_Header for machine id's.

class MachineID {
    const INTEL386 = 0x14c;
    const X64 = 0x8664;
    const MIPS_R3000 = 0x162;
    const MIPS_R10000 = 0x168;
    const MIPS_LE_WCI_V2 = 0x169;
    const OLD_ALPHA_AXP = 0x183;
    const ALPHA_AXP = 0x184;
    const HITACHI_SH3 = 0x1a2;
    const HITACHI_SH3_DSP = 0x1a3;
    const HITACHI_SH4 = 0x1a6;
    const HITACHI_SH5 = 0x1a8;
    const HITACHI_ARM_LE = 0x1c0;
    const THUMB = 0x1c2;
    const MATSUSHITA_AM33 = 0x1d3;
    const POWERPC_LITTLE_ENDIAN = 0x1f0;
    const POWERPC_FPS = 0x1f1;
    const XBOX_360 = 0x1f2;
    const INTEL_IA64 = 0x200;
    const MIPS16 = 0x266;
    const ALPHA_AXP_64 = 0x284;
    const MIPS_FPU = 0x366;
    const EFI_BYTE_CODE = 0x466;
    const AMD64 = 0x8664;
    const MITSUBISHI_M32R_LE = 0x9041;
    const CLR_PURE_MSIL = 0xc0ee;
}

?>

