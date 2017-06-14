<?php
/*
 * This is a near enough clone of c#'s binary reader/write combined...
 * Currently Unsigned/signed numbers are supported, still needs Unicode support and float support.
 * Written by Hect0r Xorius <staicpi.net@gmail.com>
 */
// Get Machine Endian, Gets the current machines endian type.
function getMachineEndian() {
    $testint = 0x00FF;
    $p = pack('S', $testint);
    return $testint===current(unpack('v', $p)) ? Endian::LOW:Endian::HIGH;
}
// To Byte Array, Converts a hexadecimal string to a byte array (ints).
function toByteArray($str) {
    $output = array();
    
    for($i = 0; $i < strlen($str) / 2; $i+=2) {
        $output[] = hexdec(substr($str, $i, 2));
    }
    
    return $output;
}
// Hex To String, Converts a hexadecimal string to a ascii string.
function hexToStr($hex) {
    $str = '';
    for($i=0;$i<strlen($hex);$i+=2) { $str .= chr(hexdec(substr($hex,$i,2))); }
    return $str;
}
// Reverse String, Reverses a hexadecimal string.
function reverseStr($bytes_str) {
    $str = "";
    for($i = 0; $i < strlen($bytes_str) / 2; $i++) {
        $str = substr($bytes_str, ($i * 2), 2) . $str;
    }
    return $str;
}
// Reverse Unicode String, Reverses a unicode hexadecimal string.
function reverseUnicodeStr($bytes_str) {
    $str = "";
    for($i = 0; $i < strlen($bytes_str) / 2; $i++) {
        $str .= substr($bytes_str, ($i * 2), 1) . substr($bytes_str, ($i * 2) - 1, 1);
    }
    return $str;
}

function decHexUInt8($in) {
    if((int)$in > 0 && (int)$in < 255) {
        $val = dexhex((int)$in);
        if(strlen($val) !== 2) { return '0' . $val; }
        return $val;
    }
    throw new Exception('decHexUInt8: value < 0 || value > 255...');
}
function decHexInt8($in) {
    if((int)$in > -128 && (int)$in < 127) {
        $val = dechex((int)$in);
        return substr($val, 14, 2);
    }
    throw new Exception('decHexInt8: value < -128 || value > 127...');
}
// Decimal Hexadecimal UInt16, Converts a UInt16 value to its correct hexadecimal representation, along with selected endian.
function decHexUInt16($in, $endian) {
    if((int)$in >= 0 && (int)$in <= 65535) {
        
        $val = dechex((int)$in);
        while(strlen($val) !== 4) {
            if((int)$endian === (int)Endian::HIGH) { $val = $val . '0'; }
            else { $val = '0' . $val; }
        }
        return $val;
    }
    throw new Exception('decHexUInt16: value < 0 || value > 65535...');
}

function decHexInt16($in, $endian) {
    if((int)$in > -32768 && (int)$in < 32767) {
        $val = dechex((int)$in);
        return ((int)$endian === (int) Endian::HIGH) ? substr($val, 12, 4):reverseStr(substr($val, 12, 4));
    }
    throw new Exception('decHexInt16: value < -32768 || value > 32767...');
}
// Decimal Hexadecimal UInt32, Converts a UInt32 value to its correct hexadecimal representation, along with selected endian.
function decHexUInt32($in, $endian) {
    if((int)$in >= 0 && (int)$in <= 4294967295) {
        $val = dechex((int)$in);
        while(strlen($val) !== 8) {
            if((int)$endian === (int)Endian::HIGH) { $val = $val . '0'; }
            else { $val = '0' . $val; }
        }
        return $val;
    }
    throw new Exception('decHexUInt32: value < 0 || value > 4294967295...');
}

function decHexInt32($in, $endian) {
    if((int)$in > -2147483646 && (int)$in < 2147483647) {
        $val = dechex((int)$in);
        return ((int)$endian === (int) Endian::HIGH) ? substr($val, 8, 8):reverseStr(substr($val, 8, 8));
    }
    throw new Exception('decHexInt16: value < -2147483646 || value > 2147483647...');
}

// Decimal Hexadecimal UInt64, Converts a UInt64 value to its correct hexadecimal representation, along with selected endian.
function decHexUInt64($in, $endian) {
    if((int)$in >= 0 && (int)$in <= 9223372036854775807) {
        $val = dechex((int)$in);
        while(strlen($val) !== 16) {
            if((int)$endian === (int)Endian::HIGH) { $val = $val . '0'; }
            else { $val = '0' . $val; }
        }
        
        return $val;
    }
    throw new Exception('Invalid Unsigned Integer (Size 64 Bits)');
}

function decHexInt64($in, $endian) {
    if((int)$in > -9223372036854775806 && (int)$in < 9223372036854775807) {
        $val = dechex((int)$in);
        return ((int)$endian === (int) Endian::HIGH) ? $val:reverseStr($val);
    }
    throw new Exception('decHexInt16: value < -9223372036854775806 || value > 9223372036854775807...');
}

// Endian Class, either high or low.
class Endian {
    const HIGH = 1;
    const LOW = 0;
}
class fileStream {
    private $filename = ''; // Input/Output File Location.
    private $handle = null; // The resource/pointer we're writing to.
    private $pos = 0; // The current position in the stream.
    private $open = false; // private to set if open or closed.
    
    // Checks boolean "open" for true = open, false = closed.
    public function isOpen() { return (bool)$this->open; }
    
    // Construction of the class deals with opening the pointer/resource to the stream.
    public function __construct($file, $perms) {
        $this->filename = $file;
        $this->handle = fopen($file, $perms);
        if(is_resource($this->handle)) {
            $this->open = true;
        }
    }
    
    // Set Position, sets the position in the stream, this is a static value it will go to.
    public function setPosition($pos) {
        $this->pos = (int)$pos;
        fseek($this->handle, (int)$this->pos, SEEK_SET);
    }
    
    // Append Position, takes current stream location and adds whatever static value you give it.
    public function appendPosition($pos) {
        $this->pos += (int)$pos;
        fseek($this->handle, (int)$pos, SEEK_SET);
    }
    
    // Get Position, takes the current position of the stream.
    public function getPosition() { return (int)$this->pos; }
    
    // Read Bytes String, reads a chunk of data into a hexadecimal string.
    public function readBytesStr($len) {
        $str = bin2hex(fread($this->handle, (int)$len));
        $this->pos += (int)$len;
        return $str;
    }
    
    // Read Byte, Reads a Int/UInt8 variable from the stream.
    public function readByte() {
        $str = bin2hex(fread($this->handle, 1));
        return hexdec($str);
    }
    // Read Bytes, reads a chunk of data into a integer array (like byte[] / uint8_t[]).
    public function readBytes($len) {
        $str = bin2hex(fread($this->handle, (int)$len));
        $this->pos += (int)$len;
        return toByteArray($str);
    }
    
    // Read UInt16, reads a unsigned integer of 16 bits and switches for selected endian.
    public function readUInt16($endian = Endian::LOW) {
        $str = bin2hex(fread($this->handle, 2));
        $this->pos += 2;
        return hexdec(((int)getMachineEndian() !== (int)$endian) ? reverseStr($str):$str);
    }
    
    // Read Int16, reads a integer of 16 bits and switches for selected endian.
    public function readInt16($endian = Endian::LOW) {
        $buf = fread($this->handle, 2);
        $this->pos += 2;
        $buf = ((int)$endian !== (int)getMachineEndian() ? hex2bin(reverseStr(bin2hex($buf))) : $buf);
        return unpack('s', $buf)[1];
    }
    
    // Read UInt32, reads a unsigned integer of 32 bits and switches for selected endian.
    public function readUInt32($endian = Endian::LOW) {
        $str = bin2hex(fread($this->handle, 4));
        $this->pos += 4;
        return hexdec(((int)getMachineEndian() !== (int)$endian) ? reverseStr($str):$str);
    }
    
    // Read Int32, reads a integer of 32 bits and switches for selected endian.
    public function readInt32($endian = Endian::LOW) {
        $buf = fread($this->handle, 4);
        $this->pos += 4;
        $buf = ((int)$endian !== (int)getMachineEndian() ? hex2bin(reverseStr(bin2hex($buf))) : $buf);
        return unpack('l', $buf)[1];
    }
    
    // Read UInt64, reads a unsigned integer of 64 bits and switches for the selected endian.
    public function readUInt64($endian = Endian::LOW) {
        $str = bin2hex(fread($this->handle, 8));
        $this->pos += 8;
        return hexdec(((int)getMachineEndian() !== (int)$endian) ? reverseStr($str):$str);
    }
    
    // Read UInt64, reads a integer of 64 bits and switches for the selected endian.
    public function readInt64($endian = Endian::LOW) {
        $buf = fread($this->handle, 8);
        $this->pos += 8;
        return unpack((int)$endian === (int)Endian::HIGH ? 'J':'P', $buf)[1];
    }
    
    // Read Float, reads a floating point number.
    public function readFloat($endian = Endian::LOW) {
        $buf = fread($this->handle, 4);
        $this->pos += 4;
        $buf = ((int)$endian !== (int)getMachineEndian() ? hex2bin(reverseStr(bin2hex($buf))) : $buf);
        return (float)unpack('f', (float)$buf);
    }
    
    // Read Ascii String, reads a ascii string (unsigned 8) to a string value.
    public function readAsciiString($len) {
        $str = fread($this->handle, (int)$len);
        $this->pos += (int)$len;
        return $str;
    }
    
    // Write Byte, Writes a byte to the stream.
    public function writeByte($byte) {
        fwrite($this->handle, hex2bin(decHexUInt8((int)$byte)), 1);
        $this->pos++;
    }
    
    // Write Bytes, Writes a hexadecimal string to the stream.
    public function writeBytes($bytesStr) {
        fwrite($this->handle, hex2bin($bytesStr), 1);
        $this->pos += strlen($bytesStr) / 2;
    }
    
    // Write UInt16, Writes a UInt16 to the stream.
    public function writeUInt16($int, $endian) {
        fwrite($this->handle, hex2bin(decHexUInt16((int)$int, (int)$endian)), 2);
        $this->pos += 2;
    }
    
    // Write Int16, Writes a Int16 to the stream.
    public function writeInt16($int, $endian) {
        fwrite($this->handle, hex2bin(decHexInt16((int)$int, (int)$endian)), 2);
        $this->pos += 2;
    }
    
    // Write UInt32, Writes a UInt32 to the stream.
    public function writeUInt32($int, $endian) {
        fwrite($this->handle, hex2bin(decHexUInt32((int)$int, (int)$endian)), 4);
        $this->pos += 4;
    }
    
    // Write Int32, Writes a Int32 to the stream.
    public function writeInt32($int, $endian) {
        fwrite($this->handle, hex2bin(decHexInt32((int)$int, (int)$endian)), 4);
        $this->pos += 4;
    }
    
    // Write UInt64, Writes a UInt64 to the stream.
    public function writeUInt64($int, $endian) {
        fwrite($this->handle, hex2bin(decHexUInt64((int)$int, (int)$endian)), 8);
        $this->pos += 8;
    }
    
    // Write Int64, Writes a Int64 to the stream.
    public function writeInt64($int, $endian) {
        fwrite($this->handle, hex2bin(decHexInt64((int)$int, (int)$endian)), 8);
        $this->pos += 8;
    }
    public function writeFloat($float) {
        fwrite(pack('f', (float)$float));
        $this->pos += 4;
    }
    public function writeAsciiString($str) {
        fwrite($this->handle, $str, strlen($str));
        $this->pos += (int)strlen($str);
    }
    
    // Closes the resource/stream.
    public function close() {
        if(is_resource($this->handle)) {
            fclose($this->handle);
            $this->open = false;
        }
    }
}
?>
