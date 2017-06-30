#!/bin/bash

echo "clean old packages"
rm *.zip

echo "Component, Creating Zip..."
cd com_ukrgb
zip -qr ../com_ukrgb .

cd ..
echo "Sys Plugin, Creating Zip..."
cd plg_ukrgbsystem
zip -qr ../plg_ukrgbsystem .

cd ..
echo "User Plugin, Creating Zip..."
cd plg_ukrgbuser
zip -qr ../plg_ukrgbuser .

cd ..
echo "Content Plugin, Creating Zip..."
cd plg_ukrgbcontent
zip -qr ../plg_ukrgbcontent .

cd ..
echo "Custom Search Module, Creating Zip..."
cd mod_ukrgbcustomsearch
zip -qr ../mod_ukrgbcustomsearch .

cd ..
echo "Custom Nestcam Module, Creating Zip..."
cd mod_ukrgbnestcam
zip -qr ../mod_ukrgbnestcam .


cd ..
echo "AWS Library, Creating Zip..."
cd lib_ukrgbaws
zip -qr  ../lib_ukrgbaws . -x composer.* -x setup.sh*

echo "Done."
