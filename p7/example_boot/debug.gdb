target remote :1234
set architecture i8086
set tdesc filename target.xml
set mem inaccessible-by-default off
set disassembly-flavor intel
set remotetimeout 999
define hook-stop
    x/10ig $eip
end
b *0x7c00
c
