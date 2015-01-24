#!/bin/bash

echo "clean old packages"
rm *.zip

echo "Component, Creating Zip..."
cd com_ukrgb
zip -qr ../com_ukrgb .

cd ..
echo "Plugin, Creating Zip..."
cd plg_ukrgbsystem
zip -qr ../plg_ukrgbsystem .

echo "Done."
