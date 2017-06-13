# PHP-PortableExecutable

# [ Overview ]
This is a simple class (with external structs) containing the structure of the protable executable file format used on windows.

# [ Features ]
The features include full support for reading and writing PE images, both in higher endian and lower (Ive only seen lower endian so far)

# [ Examples ]
These scripts have only been tested on Linux/Ubuntu, I dont have a windows pc at hand, so any ammendments please push any changes for windows and mac !

Basic Usage :

You will need to include both files (io/FileStream.php && pe/Binary.php) then just run through the functions.

Checkout the examples in this git (folder Examples, copy them to the main folder...).

Run them via "php -f example.name.php"

# [ Linux ]
Make sure you have php-cli installed, on ubuntu you can do this by running a apt-get install command :
sudo apt-get install php-cli

# [ Author ]
Hect0r Xorius <staticpi.net@gmail.com> - StaticPi.net

# [ Copyrights ]
I was able to understand the format originally from the file "pe_image.h" on windows, you can view this in the lib/include folder of visual studio.

Copyrights belong to Microsoft.
