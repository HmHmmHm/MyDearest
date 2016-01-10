@echo off
TITLE PocketMine-MP server software for Minecraft: Pocket Edition
cd /d %~dp0
if exist bin\php\php.exe (
	set PHP_BINARY=bin\php\php.exe
) else (
	set PHP_BINARY=php
)

if exist PocketMine-MP.phar (
	set POCKETMINE_FILE=PocketMine-MP.phar
) else (
	if exist src\pocketmine\PocketMine.php (
		set POCKETMINE_FILE=src\pocketmine\PocketMine.php
	) else (
		echo "Couldn't find a valid PocketMine-MP installation"
		pause
		exit 1
	)
)
cls
#if exist bin\php\php_wxwidgets.dll (
#	%PHP_BINARY% %POCKETMINE_FILE% --enable-gui %*
#) else (
	if exist bin\mintty.exe (
		cls
		bin\mintty.exe -o Columns=130 -o Rows=32 -o AllowBlinking=0 -o FontQuality=3 -o Font="DejaVu Sans Mono" -o FontHeight=10 -o CursorType=0 -o CursorBlinks=1 -h error -t "PocketMine-MP" -i bin/pocketmine.ico %PHP_BINARY% %POCKETMINE_FILE% --enable-ansi %*
		start loop.cmd
		exit 1
	) else (
		%PHP_BINARY% -c bin\php %POCKETMINE_FILE% %*
	)
#)
