#!/bin/sh

if [ ! -d "build/metrics" ]; then
  mkdir build/metrics
fi
appDir="Application"
testsDir="Test"
buildDir="build"

echo "\n\n************ Source Dir **************\n"
echo $appDir;
rm $buildDir/metrics/*

echo "\n\n**************Executing pdepend ************\n"
pdepend --jdepend-chart=build/metrics/depend-chart.svg --overview-pyramid=build/metrics/overview-pyramid.svg $appDir

echo "\n\n***********Executing phpmd ******************\n"
phpmd $appDir text cleancode,design

echo "\n\n*********** Executing phpmd CI ******************\n"
phpmd $appDir xml cleancode,design --reportfile $buildDir/metrics/messdetection-ci.xml

echo "\n\n**********Executing phpcpd ******************\n"
phpcpd $appDir


echo "\n\n**********Executing phpcpd CI ******************\n"
phpcpd --log-pmd=build/metrics/pmd-cpd-ci.xml $appDir

echo "\n\n**********Executing phpunit ******************\n"
phpunit --configuration=$testsDir/phpunit.xml $testsDir
