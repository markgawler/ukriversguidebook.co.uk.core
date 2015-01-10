#!/bin/bash

echo "clean old packages"
rm *.zip

echo "Component, Creating Zip..."
cd com_ukrgb
zip -qr ../com_ukrgb .

cd ..
echo "Plugin, Creating Zip..."
cd plg_ukrgbdonate
zip -qr ../plg_ukrgbdonate .

echo "Done."
