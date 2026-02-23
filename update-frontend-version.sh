#!/bin/bash
# 更新前端版本号（commit hash）

cd "$(dirname "$0")"

# 获取最新commit hash
VERSION=$(git rev-parse --short HEAD)

echo "更新前端版本号为: $VERSION"

# 更新app.js
sed -i "s/VERSION: '[a-f0-9]*'/VERSION: '$VERSION'/g" /storage/emulated/0/ZeroTermux/开发/github-wallpaper-site/frontend/assets/js/app.js

# 更新category.html和about.html
sed -i "s|wallpapers@[a-f0-9]*/data|wallpapers@${VERSION}/data|g" /storage/emulated/0/ZeroTermux/开发/github-wallpaper-site/frontend/category.html
sed -i "s|wallpapers@[a-f0-9]*/data|wallpapers@${VERSION}/data|g" /storage/emulated/0/ZeroTermux/开发/github-wallpaper-site/frontend/about.html

echo "✅ 前端版本已更新"
echo "请刷新网页查看更新"
