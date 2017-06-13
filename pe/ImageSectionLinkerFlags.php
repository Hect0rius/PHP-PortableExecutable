<?php

class ImageSectionLinkerFLags {
    const OTHER = 0x00000100;  /* Reserved. */
    const INFO = 0x00000200;  /* Section contains comments or some other type of information. */
    const OVERLAY = 0x00000400;  /* Section contains an overlay. */
    const REMOVE = 0x00000800;  /* Section contents will not become part of image. */
    const COMDAT = 0x00001000;  /* Section contents comdat. */
}

?>

