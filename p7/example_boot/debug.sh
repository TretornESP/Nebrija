nasm -fbin $1.asm -o $1
qemu-system-i386 -s -S -hda $1